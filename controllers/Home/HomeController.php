<?php

namespace Controller\Home;

//models

use DateTime;
use Exception;
use Model\ActivityLevel;
use Model\Auth\User;
use Model\Formula;
use Model\Gender;
use Model\Goal;
use Model\Settings;
use Model\Units;
use Model\UserFoodBasic;
use Model\UserFoods;
use Model\UserMealDetail;
use Model\UserMeals;
use Model\UserProfile;
use Model\UserRecord;
use Model\UserRecordDetails;
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
        // debugAndFormat($userId);
        // if the user already has a profile, redirect
        $userProfile = UserProfile::where("user_id", $userId);
        if ($userProfile) redirectTo("/home");

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
            $data = $_POST["user"] ?? [];
            $userProfile->synchronize($data);
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

                    //save user profile
                    $result = $userProfile->save();

                    //if the user profile was saved, then save its requirements
                    if ($result["result"]) {
                        //save requirements
                        $userRequirement = new UserRequirement();
                        //set the insert id to the userRequirement->setProfileId
                        $insertUserProfileId = $result["information"]["insert_id"];
                        $userRequirement->setUserProfileId($insertUserProfileId);
                        //calculate requirements
                        $userRequirement->calculateRequirements($userProfile);
                        //save requirements
                        $userRequirement->save();
                        //lastly, save its default meals referencing the id of the created userProfile
                        $userMeals = new UserMeals;
                        $userMeals->setUserProfileId($insertUserProfileId);
                        //save meals 
                        $userMeals->saveDefaultMeals();
                        // //redirect
                        redirectTo("/home");
                    } else {
                        UserProfile::setAlert("error", "Algo salio mal, intenta mas tarde");
                    }
                } else {
                    // error during form validation
                    http_response_code(400);
                }
            } catch (\Exception $e) {
                UserProfile::setAlert("error", "Algo salio mal, intenta mas tarde");
                http_response_code(500);
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

        //validate ids
        if (!validateInteger($mealId) || !validateInteger($recordId) || !validateInteger($foodId)) redirectTo("/home");

        //find food
        $userFood = UserFoods::fetchUserFood($userProfile->id, $foodId);

        //redirect invalid food
        if (!$userFood) redirectTo("/home");

        // attribute translations 
        $translations = UserFoods::getTranslations();
        // nutrient unit formats
        $nutrientUnits = UserFoods::getNutrientUnits();

        // measurement units
        $units = Units::all();

        //possible alerts updating the food
        $alerts = [];

        $units = Units::all();

        $portion = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $portion = $_POST["portion"] ?? '';
            //  create a record_detail with the original food serving size
            if (isset($_POST["original_size"]))  $portion = $userFood->serving_size;
            // validate number
            $recordDetail = new UserRecordDetails();
            $recordDetail->setRecordQuantity($portion);
            $alerts = $recordDetail->validatePortion();


            if (empty($alerts)) {
                try {
                    //validate userRecord //validate userMeal
                    $userRecord = UserRecord::fetchUserRecord($recordId, $userProfile->id);
                    $userMeal = UserMeals::fetchUserMeal($mealId, $userProfile->id);
                    if (!$userRecord || !$userMeal) redirectTo("/home");
                    //save recordDetail
                    $recordDetail->setUserFoodId($userFood->id);
                    $recordDetail->setUserRecordId($userRecord->id);
                    $recordDetail->setMealId($userMeal->id);

                    $result = $recordDetail->save();

                    if ($result["result"]) {
                        redirectTo("/home" . "?date=" . $userRecord->date);
                    } else {
                        $alerts = UserRecordDetails::setAlert("error", "No se pudo crear el registro");
                    }
                } catch (Exception $e) {
                    $alerts = UserRecordDetails::setAlert("error", "No se pudo crear el registro");
                }
            }
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

        $router->render('pages/home/createRecordDetail', $data);
    }

    //create a new record_detail //endpoint: /home/edit-record-detail?recordid=id&recorddetailid=id
    public static function editRecordDetail(Router $router)
    {
        //authenticate user
        isAuth();

        $userId = $_SESSION["id"] ?? '';

        $userProfile = UserProfile::where("user_id", $userId);

        if (!$userProfile) redirectTo("/home/set-profile");

        $recordId = $_GET["record"] ?? '';
        $recordDetailId = $_GET["record_detail"] ?? '';

        //validate ids
        if (!validateInteger($recordId) || !validateInteger($recordDetailId)) redirectTo("/home");

        // validate existence of the record detail and that belongs to the user
        $recordDetail = UserRecordDetails::fetchRecordDetail($recordDetailId, $recordId, $userProfile->id);
        if (!$recordDetail) redirectTo("/home");

        // food related to the record detail
        $userFood = UserFoods::fetchUserFood($userProfile->id, $recordDetail->user_food_id);
        if (!$userFood) redirectTo("/home");

        // measurement units
        $units = Units::all();

        // attribute translations 
        $translations = UserFoods::getTranslations();
        // nutrient unit formats
        $nutrientUnits = UserFoods::getNutrientUnits();

        //possible alerts updating the record detail
        $alerts = [];

        $portion = $recordDetail->quantity;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            //synch record detail
            $portion = $_POST["portion"] ?? $recordDetail->quantity;
            $recordDetail->setRecordQuantity($portion);
            $alerts = $recordDetail->validatePortion();

            if (empty($alerts)) {
                try {
                    //update recordDetail
                    $userRecord = UserRecord::fetchUserRecord($recordId, $userProfile->id);
                    $result = $recordDetail->save();

                    if ($result["result"]) {
                        redirectTo("/home" . "?date=" . $userRecord->date);
                    } else {
                        $alerts = UserRecordDetails::setAlert("error", "No se pudo actualizar el registro");
                    }
                } catch (Exception $e) {
                    debugAndFormat($e);
                    $alerts = UserRecordDetails::setAlert("error", "No se pudo actualizar el registro");
                }
            }
        }

        $data = [
            "userFood" => $userFood,
            "translations" => $translations,
            "units" => $units,
            "alerts" => $alerts,
            "nutrientUnits" => $nutrientUnits,
            "recordId" => $recordId,
            "recordDetail" => $recordDetail
        ];

        // debugAndFormat($recordDetail);

        $router->render('pages/home/editRecordDetail', $data);
    }
    //delete a new record_detail //endpoint: /home/delete-record-detail
    public static function dropRecordDetail()
    {
        //authenticate user
        isAuth();
        $userId = $_SESSION["id"] ?? '';
        // validate user
        $userProfile = UserProfile::where("user_id", $userId);
        if (!$userProfile) redirectTo("/home/set-profile");

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $recordId = $_POST["record"] ?? '';
            $recordDetailId = $_POST["record_detail"] ?? '';

            //validate ids
            if (!validateInteger($recordId) || !validateInteger($recordDetailId)) redirectTo("/home");

            // validate existence of the record detail and that belongs to the user
            $recordDetail = UserRecordDetails::fetchRecordDetail($recordDetailId, $recordId, $userProfile->id);
            if (!$recordDetail) redirectTo("/home");

            try {
                //delete recordDetail
                $result = $recordDetail->delete();

                if ($result["result"]) {
                    $userRecord = UserRecord::fetchUserRecord($recordId, $userProfile->id);
                    redirectTo("/home" . "?date=" . $userRecord->date);
                } else {
                    redirectTo("/home");
                }
            } catch (Exception $e) {
                redirectTo("/home");
            }
        }
    }
    //settings// endpoint: /home/settings
    public static function settings(Router $router)
    {
        //authenticate user
        isAuth();
        $userId = $_SESSION["id"] ?? '';
        // validate user
        $userProfile = UserProfile::where("user_id", $userId);
        if (!$userProfile) redirectTo("/home/set-profile");

        $basicSettings = Settings::fetchBasicSettings($userProfile->id);
        $mealList = UserMeals::findAllByColumn("user_profile_id", $userProfile->id);

        if (!$basicSettings || !$mealList) redirectTo("/home");


        $data = [
            "basicSettings" => $basicSettings,
            "mealList" => $mealList,
        ];

        $router->render('pages/home/sections/settings/settings', $data);
    }

    //crud meal

    //create a new meal //endpoint: '/home/create-meal'
    public static function createMeal(Router $router)
    {
        //authenticate user
        isAuth();

        $userId = $_SESSION["id"] ?? '';

        $userProfile = UserProfile::where("user_id", $userId);

        if (!$userProfile) redirectTo("/home/set-profile");

        //empty userMeal instance
        $userMeal = new UserMeals();
        //possible alerts
        $alerts = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // sync object in memory with data from the form
            $args = $_POST["meal"] ?? [];
            $userMeal->synchronize($args);

            $alerts = $userMeal->validate();

            if (empty($alerts)) {
                //set delete to 1
                $userMeal->setDetelable();
                $userMeal->setUserProfileId($userProfile->id);

                try {
                    //save food
                    if ($userMeal->save()["result"]) {
                        redirectTo("/home/settings");
                    } else {
                        //error saving in db
                        UserMeals::setAlert("error", "No se pudo crear la comida");
                    }
                } catch (Exception $e) {
                    UserMeals::setAlert("error", "No se pudo crear la comida");
                }
            }
        }

        $alerts = UserMeals::getAlerts();

        $data = [
            "alerts" => $alerts,
            "userMeal" => $userMeal
        ];

        $router->render('pages/home/sections/settings/createMeal', $data);
    }

    //update meal //endpoint: '/home/create-meal'
    public static function editMeal(Router $router)
    {
        //authenticate user
        isAuth();

        $userId = $_SESSION["id"] ?? '';

        $userProfile = UserProfile::where("user_id", $userId);

        if (!$userProfile) redirectTo("/home/set-profile");

        $mealId = $_GET["meal"];

        if (!validateInteger($mealId)) redirectTo("/home/settings");

        //bring meal
        $userMeal = UserMeals::fetchUserMeal($mealId, $userProfile->id);
        //possible alerts
        $alerts = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // sync object in memory with data from the form
            $args = $_POST["meal"] ?? [];
            $userMeal->synchronize($args);

            $alerts = $userMeal->validate();

            if (empty($alerts)) {
                try {
                    //save food
                    if ($userMeal->save()["result"]) {
                        redirectTo("/home/settings");
                    } else {
                        //error saving in db
                        UserMeals::setAlert("error", "No se pudo actualizar la comida");
                    }
                } catch (Exception $e) {
                    UserMeals::setAlert("error", "No se pudo actualizar la comida");
                }
            }
        }

        $alerts = UserMeals::getAlerts();

        $data = [
            "alerts" => $alerts,
            "userMeal" => $userMeal,
            "previousUrl" => "/home/settings"
        ];

        $router->render('pages/home/sections/settings/createMeal', $data);
    }

    //delete meal //endpoint: '/home/create-meal'
    public static function dropMeal()
    {
        //authenticate user
        isAuth();

        $userId = $_SESSION["id"] ?? '';

        try {
            $userProfile = UserProfile::where("user_id", $userId);

            if (!$userProfile) redirectTo("/home/set-profile");

            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                // sync object in memory with data from the form
                $mealId = $_POST["meal"] ?? [];

                if (!validateInteger($mealId)) redirectTo("/home/settings");

                $userMeal = UserMeals::fetchUserMeal($mealId, $userProfile->id);

                if (!$userMeal) redirectTo("/home/settings");

                if ($userMeal->deletable === '1') {
                    try {
                        //save food
                        if ($userMeal->delete()["result"]) {
                            redirectTo("/home/settings");
                        } else {
                            //error saving in db
                            redirectTo("/home/settings");
                        }
                    } catch (Exception $e) {
                        redirectTo("/home/settings");
                    }
                }
                redirectTo("/home/settings");
            }
        } catch (Exception $e) {
            redirectTo("/home");
        }
    }

    // get all records
    public static function getRecords(Router $router)
    {
        //authenticate user
        isAuth();

        $userId = $_SESSION["id"] ?? '';

        $userProfile = UserProfile::where("user_id", $userId);

        if (!$userProfile) redirectTo("/home/set-profile");

        $month = $_GET["month"] ?? date("m");
        $year = $_GET["year"] ?? date("Y");

        if (!validateDate($year . "-" . $month . "-" . date('d'))) redirectTo("/home/settings");

        //bring all records (by month)
        $userRecords = UserRecord::fetchUserRecordByDate($userProfile->id, $month, $year) ?? [];

        $alerts = [];

        if (empty($userRecords)) $alerts = UserRecord::setAlert("warning", "No existen registros para esta fecha");


        $date = $year . "-" . $month;

        $data = [
            "alerts" => $alerts,
            "userRecords" => $userRecords,
            "previousUrl" => "/home/settings",
            "date" =>  $date,
            "year" => $year,
            "month" => $month
        ];

        $router->render('pages/home/sections/settings/userRecords', $data);
    }

    // delete a record by its id
    public static function dropRecord()
    {
        try {
            if ($_SERVER["REQUEST_METHOD"] !== "POST") redirectTo("/home");

            //authenticate user
            isAuth();

            $userId = $_SESSION["id"] ?? '';

            $userProfile = UserProfile::where("user_id", $userId);


            if (!$userProfile) redirectTo("/home/set-profile");
            // get record id
            $recordId = $_POST["record"] ?? 0;

            if (!validateInteger($recordId)) redirectTo("/home");
            // bring user record
            $userRecord = UserRecord::fetchUserRecord($recordId, $userProfile->id);

            // drop record
            if (!$userRecord) redirectTo("/home/settings");

            $date = explode("-", $userRecord->date);
            $date = "month={$date[1]}&year={$date[0]}";

            $result = $userRecord->delete()["result"] ?? null;

            if ($result) redirectTo("/home/records?{$date}");

            redirectTo("/home/settings");
        } catch (Exception $e) {
            redirectTo("/home");
        }
    }

    // delete user account
    public static function dropAccount()
    {
        try {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                //authenticate user
                if (!isset($_SESSION["loggedin"])) {
                    header('Content-Type: application/json');
                    http_response_code(401); // NOT AUTHORIZED
                    echo json_encode(["error" => "Usuario no autenticado"]);
                    exit;
                }

                $userId = $_SESSION["id"] ?? '';

                $userAccount = User::findById($userId);

                $result = $userAccount->delete()["result"];

                http_response_code(204); // removed but not response

                header('Content-Type: application/json');
                echo json_encode(["result" => $result]);
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(["error" => "error"]);
            exit;
        }
    }
}
