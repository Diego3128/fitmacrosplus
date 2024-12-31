<?php

namespace Controller\Home;

//models

use DateTime;
use Exception;
use Model\ActivityLevel;
use Model\Formula;
use Model\Gender;
use Model\Goal;
use Model\Units;
use Model\UserFoodBasic;
use Model\UserFoods;
use Model\UserMealDetail;
use Model\UserMeals;
use Model\UserProfile;
use Model\UserRecord;
use Model\UserRequirement;
use MVC\Router;

class HomeController
{
    //home (dashboard)
    public static function index(Router $router)
    {
        isAuth();
        $userId = $_SESSION["id"] ?? '0';
        $userProfile = UserProfile::where("user_id", $userId);
        // redirect if there's no profile
        if (!$userProfile) redirectTo("/home/set-profile");

        // init variables

        $alerts = []; //erros
        $emptyRecord = false; //$date !== $today and there's no $userRecord for that date

        $userRequirements = null; //user requirements
        $userMeals = []; //all user meals (breakfast, lunch, dinner, etc..)

        $processedMealDetails = []; //every record of each meal processed
        $userRecord = null; // a userRecord related with the date
        $userRecordId = null;

        $mealStats = []; //total kal, protein, carbs and fat of each meal
        $generalStats = []; //total kal, protein, carbs and fat of all the meals
        $statPercentages = []; //total %percentage of kal, protein, carbs and fat of all the meals

        //get the searched date
        $date = $_GET["date"] ?? date('Y-m-d');
        //validate date
        if (validateDate($date)) {
            // record with the date and userProfile
            $userRecord = UserRecord::getUserRecord($userProfile->id, $date);
            // user meals
            $userMeals = UserMeals::findAllByColumn('user_profile_id', $userProfile->id);
            // user requirements
            $userRequirements = UserRequirement::where('user_profile_id', $userProfile->id);

            if (!empty($userRecord)) {
                $userRecordId = $userRecord->id;
                // get all the possible userRecordDetails for THAT date
                $userMealDetails = UserMealDetail::getMealDetails($userProfile->id, $date, $userRecord->id);
                // debugAndFormat($userMealDetails);

                // userMealDetails can be empty. This is handle in all the functions below

                //calculated proportions of each food in every meal
                $processedMealDetails = UserMealDetail::processMealDetails($userMealDetails);

                //calc stadistics
                [$mealStats, $generalStats] = UserMealDetail::calcMealStats($userMeals, $processedMealDetails);

                //calc percentages
                $statPercentages = UserMealDetail::calcStatPercentage($userRequirements, $generalStats);
                //
            } else {
                // if there's no record, create a new record if the date is today
                if ($date === date("Y-m-d")) {
                    $userRecord = new UserRecord();
                    //set date and profile id
                    $userRecord->setUserProfileId($userProfile->id);
                    $userRecord->setRecordDate($date);
                    //save the new record
                    try {
                        $result = $userRecord->save();
                        if ($result["result"]) {
                            $userRecordId = $result["information"]["insert_id"];

                            $alerts = UserRecord::setAlert("success", "Se ha iniciado un nuevo registro");
                            //calc stadistics (empty)
                            [$mealStats, $generalStats] = UserMealDetail::calcMealStats($userMeals, $processedMealDetails);
                            //calc percentages (empty)
                            $statPercentages = UserMealDetail::calcStatPercentage($userRequirements, $generalStats);
                        } else {
                            $alerts = UserRecord::setAlert("error", "No se pudo iniciar el registro");
                        }
                    } catch (Exception $e) {
                        $alerts = UserRecord::setAlert("error", "No se pudo iniciar el registro");
                    }
                } else {
                    // when the date is not today and there's no userRecord
                    // the user can't create records
                    $emptyRecord = true;
                    $today = new DateTime();
                    $seekedDate = new DateTime($date);


                    if ($seekedDate > $today) {
                        $alerts = UserRecord::setAlert("warning", "No se pueden crear nuevos registros futuros");
                    } elseif ($seekedDate < $today) {
                        $alerts = UserRecord::setAlert("warning", "No se pueden crear nuevos registros pasados");
                        $alerts = UserRecord::setAlert("warning", "No se creo registro este día");
                    }
                }
            }
        } else {
            //incorrect date
            redirectTo("/home");
        }

        $data = [];

        $data = [
            "alerts" => $alerts,
            "userMeals" => $userMeals,
            "userRequirement" => $userRequirements,
            "processedMealDetails" => $processedMealDetails,
            "mealStats" => $mealStats,
            "generalStats" => $generalStats,
            "statPercentages" => $statPercentages,
            "date" => $date
        ];

        if ($emptyRecord) $data["emptyRecord"] = $emptyRecord;
        if ($userRecordId) $data["userRecordId"] = $userRecordId;

        // debugAndFormat($data);

        $router->render("pages/home/panel", $data);
    }
    //set profile
    public static function setProfile(Router $router)
    {
        //authenticate user
        isAuth();

        $userId = $_SESSION["id"] ?? '';
        // if the user already has a profile, redirect
        $userProfile = UserProfile::where("user_id", $userId);

        if ($userProfile) {
            header("location: /home");
            exit;
        }
        //create new instance of UserProfile
        $userProfile = new UserProfile();

        $alerts = [];

        //info to complete the form
        $activityLevels = ActivityLevel::all();
        $goals = Goal::all();
        $genders = Gender::all();

        //save profile
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            //init instance of UserProfile
            $userProfile = new UserProfile($_POST["user"] ?? []);
            try {
                //valiate instance
                $alerts = $userProfile->validate();
                //
                if (empty($alerts)) {
                    //set user id
                    $userProfile->setUserId($_SESSION["id"]);

                    //set formula to the userProdile (based on the gender)
                    $formula = Formula::where('gender_id', $userProfile->gender_id);
                    $userProfile->setFormulaId($formula->id);

                    // debugAndFormat($userProfile);

                    //save user profile
                    $result = $userProfile->save();

                    //if the user profile was saved, then save its requirements
                    if ($result["result"]) {
                        //save requirements
                        $userRequirement = new UserRequirement();
                        //set the insert id to the userRequirement->setProfileId
                        $insertId = $result["information"]["insert_id"];
                        $userRequirement->setUserProfileId($insertId);
                        //calculate requirements
                        $userRequirement->calculateRequirements($userProfile);
                        //save requirements
                        $userRequirement->save();
                        //lastly, save its default meals // also referencing the id of the created userProfile
                        $userMeals = new UserMeals;
                        $userMeals->setUserProfileId($insertId);
                        //save meals 
                        $userMeals->saveDefaultMeals();
                        // //redirect
                        header("location: /home");
                    } else {
                        UserProfile::setAlert("error", "Algo salio mal, intenta mas tarde");
                    }
                }
            } catch (\Exception $e) {
                UserProfile::setAlert("error", "Algo salio mal, intenta mas tarde");
            }
        }

        $alerts = UserProfile::getAlerts();

        $data = [
            "userProfile" => $userProfile,
            "activityLevels" => $activityLevels,
            "goals" => $goals,
            "genders" => $genders,
            "alerts" => $alerts
        ];

        $router->render("pages/home/setProfile", $data);
    }

    //update profile //endpoint: '/home/profile'
    public static function profile(Router $router)
    {
        //authenticate user
        isAuth();

        $userId = $_SESSION["id"] ?? '';

        $userProfile = UserProfile::where("user_id", $userId);

        if (!$userProfile) {
            header("location: /home/set-profile");
            exit;
        }

        // init alerts
        $alerts = [];

        //info to complete the form and show in the view
        $activityLevels = ActivityLevel::all();
        $goals = Goal::all();
        $genders = Gender::all();
        $userRequirement = UserRequirement::where("user_profile_id", $userProfile->id);

        //update profile
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            //data from the form
            $data = $_POST["user"] ?? [];
            //sychronize data with the object in memory
            $userProfile->synchronize($data);

            try {
                //valiate instance
                $alerts = $userProfile->validate();

                if (empty($alerts)) {
                    //update
                    if ($userProfile->save()["result"]) {
                        //update requirements based on the new information
                        $userRequirement->calculateRequirements($userProfile);
                        //update requirements
                        $userRequirement->save();
                        //sucess message
                        UserProfile::setAlert("success", "Información actualizada");
                    } else {
                        UserProfile::setAlert("error", "Algo salio mal, intenta mas tarde");
                    }
                }
            } catch (\Exception $e) {
                UserProfile::setAlert("error", "Algo salio mal, intenta mas tarde");
            }
        }

        $alerts = UserProfile::getAlerts();

        $data = [
            "userProfile" => $userProfile,
            "userRequirements" => $userRequirement,
            "activityLevels" => $activityLevels,
            "goals" => $goals,
            "genders" => $genders,
            "alerts" => $alerts
        ];

        $router->render("pages/home/profile", $data);
    }

    //create a new food //endpoint: '/home/newfood'
    public static function createFood(Router $router)
    {
        //authenticate user
        isAuth();

        $userId = $_SESSION["id"] ?? '';

        $userProfile = UserProfile::where("user_id", $userId);

        if (!$userProfile) {
            header("location: /home/set-profile");
            exit;
        }

        // measurement units
        $units = Units::all();
        // debugAndFormat($units);
        //empty userFood instance
        $userFood = new UserFoods;
        //possible alerts
        $alerts = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // sync object in memory with data from the form
            $args = $_POST["food"] ?? [];
            $userFood->synchronize($args);
            $alerts = $userFood->validate();

            if (empty($alerts)) {
                //set userProfileId
                $userFood->setUserProfileId($userProfile->id);
                try {
                    //save food
                    if ($userFood->save()["result"]) {
                        header("location: /home");
                        exit;
                    } else {
                        //error saving in db
                        UserFoods::setAlert("error", "No se pudo guardar el alimento");
                    }
                } catch (Exception $e) {
                    UserFoods::setAlert("error", "No se pudo guardar el alimento");
                }
            }
        }

        $alerts = UserFoods::getAlerts();

        $data = [
            "userFood" => $userFood,
            "units" => $units,
            "alerts" => $alerts
        ];

        $router->render('pages/home/createFood', $data);
    }

    //edit a food //endpoint: '/home/editfood?id=id'
    public static function editFood(Router $router)
    {
        //authenticate user
        isAuth();

        $userId = $_SESSION["id"] ?? '';

        $userProfile = UserProfile::where("user_id", $userId);

        if (!$userProfile) redirectTo("/home/set-profile");

        $foodId = $_GET["id"] ?? '';

        //validate valid id
        if (!validateInteger($foodId)) redirectTo("/home");

        //find food
        $userFood = UserFoods::fetchUserFood($userProfile->id, $foodId);
        //validate food
        if (!$userFood) redirectTo("/home");

        // measurement units
        $units = Units::all();

        //possible alerts updating the food
        $alerts = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // sync object in memory with data from the form
            $args = $_POST["food"] ?? [];
            //synchronize only updates attributes that are not empty or null
            $userFood->synchronize($args);

            $alerts = $userFood->validate();

            if (empty($alerts)) {
                try {
                    //update food
                    if ($userFood->save()["result"]) {
                        redirectTo("/home");
                    } else {
                        //error saving in db
                        UserFoods::setAlert("error", "No se pudo guardar el alimento");
                    }
                } catch (Exception $e) {
                    UserFoods::setAlert("error", "No se pudo guardar el alimento");
                }
            }
        }

        $alerts = UserFoods::getAlerts();

        $data = [
            "userFood" => $userFood,
            "units" => $units,
            "alerts" => $alerts
        ];

        $router->render('pages/home/editFood', $data);
    }

    //get all foods //endpoint: '/home/foods
    public static function getAllFoods(Router $router)
    {
        //authenticate user
        isAuth();

        $userId = $_SESSION["id"] ?? '';

        $userProfile = UserProfile::where("user_id", $userId);

        if (!$userProfile) redirectTo("/home/set-profile");

        //fetch user foods (basic data)
        $userFoods = UserFoodBasic::findAllByColumn("user_profile_id", $userProfile->id);

        $units = Units::all();


        $data = [
            "units" => $units,
            "userFoods" => $userFoods
        ];

        $router->render('pages/home/userFoods', $data);
    }

    //delete a food //endpoint: '/home/deletefood?id=id
    public static function deleteFood()
    {
        //authenticate user
        isAuth();

        $userId = $_SESSION["id"] ?? '';

        $userProfile = UserProfile::where("user_id", $userId);

        if (!$userProfile) redirectTo("/home/set-profile");


        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // read food id
            $foodId = $_POST["food"]["id"] ?? null;
            //validate valid id
            if (!validateInteger($foodId)) redirectTo("/home");
            //find food
            $userFood = UserFoods::fetchUserFood($userProfile->id, $foodId);
            //validate food
            if (!$userFood) redirectTo("/home");
            //delete food
            try {
                $userFood->delete();
                redirectTo($_SERVER['HTTP_REFERER'] ?? '/home');
            } catch (Exception $e) {
                redirectTo($_SERVER['HTTP_REFERER'] ?? '/home');
            }
        } else {
            redirectTo($_SERVER['HTTP_REFERER'] ?? '/home');
        }
    }

    //create a new record detail (food list) //endpoint: /home/new-record-detail?mealid=id&recordid=id
    public static function newRecordDetail(Router $router)
    {
        //authenticate user
        isAuth();

        $userId = $_SESSION["id"] ?? '';

        $userProfile = UserProfile::where("user_id", $userId);

        if (!$userProfile) redirectTo("/home/set-profile");

        $mealId = $_GET["mealid"] ?? '';
        $recordId = $_GET["recordid"] ?? '';

        //validate ids
        if (!validateInteger($mealId) || !validateInteger($recordId)) redirectTo("/home");

        //fetch user foods (basic data)
        $userFoods = UserFoodBasic::findAllByColumn("user_profile_id", $userProfile->id);

        $units = Units::all();

        $data = [
            "units" => $units,
            "userFoods" => $userFoods,
            "mealId" => $mealId,
            "recordId" => $recordId
        ];


        $router->render('pages/home/foodList', $data);
    }

    //create a new record_detail //endpoint: home/new-record-detail/create?mealid=id&foodid=id'&recordid=id
    public static function createRecordDetail(Router $router)
    {
        //authenticate user
        isAuth();

        $userId = $_SESSION["id"] ?? '';

        $userProfile = UserProfile::where("user_id", $userId);

        if (!$userProfile) redirectTo("/home/set-profile");

        $mealId = $_GET["mealid"] ?? '';
        $recordId = $_GET["recordid"] ?? '';
        $foodId = $_GET["foodid"] ?? '';

        // debugAndFormat($_GET);

        //validate ids
        if (!validateInteger($mealId) || !validateInteger($recordId) || !validateInteger($foodId)) redirectTo("/home");

        //find food
        $userFood = UserFoods::fetchUserFood($userProfile->id, $foodId);

        //redirect invalid food
        if (!$userFood) redirectTo("/home");

        // attributes translations 
        $translations = UserFoods::getTranslations();
        // nutrient unit formats
        $nutrientUnits = UserFoods::getNutrientUnits();

        // measurement units
        $units = Units::all();

        //possible alerts updating the food
        $alerts = [];

        $units = Units::all();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            debugAndFormat($_POST);

            debugAndFormat($_POST["original_size"]);

            //  A $POST["original_size"] create a record_detail with the original food serving size
            // other wize read the ["portion"] and calc the proportions
        }

        $data = [
            "userFood" => $userFood,
            "translations" => $translations,
            "units" => $units,
            "alerts" => $alerts,
            "nutrientUnits" => $nutrientUnits,
            "mealId" => $mealId,
            "recordId" => $recordId,
            "foodId" => $foodId
        ];

        // debugAndFormat($data);

        $router->render('pages/home/createRecordDetail', $data);
    }
}
