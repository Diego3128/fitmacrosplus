<?php

namespace Controller\Home;

//models
use Model\ActivityLevel;
use Model\Gender;
use Model\Goal;
use Model\UserProfile;
use MVC\Router;

class HomeController
{
    public static function index(Router $router)
    {
        isAuth();
        $userId = $_SESSION["id"] ?? '0';
        $userProfile = UserProfile::where("user_id", $userId);
        // debugAndFormat($userProfile);
        // redirect if there's no profile
        if (!$userProfile) {
            header("location: /home/set-profile");
            exit;
        }

        $data = [
            "userProfile" => $userProfile,

        ];

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

        $userProfile = new UserProfile();

        $alerts = [];

        // debugAndFormat($userProfile);

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
                    //save
                    if ($userProfile->save()["result"]) {
                        // //redirect
                        header("location: /home");
                    } else {
                        UserProfile::setAlert("error", "Algo salio mal, intenta mas tarde");
                    }
                }
            } catch (\Exception $e) {
                UserProfile::setAlert("error", "Algo salio mal, intenta mas tarde");
            }


            //save 
        }

        $alerts = UserProfile::getAlerts();
        // debugAndFormat($alerts);

        $data = [
            "userProfile" => $userProfile,
            "activityLevels" => $activityLevels,
            "goals" => $goals,
            "genders" => $genders,
            "alerts" => $alerts
        ];

        // debugAndFormat($data);


        $router->render("pages/home/setProfile", $data);
    }
}
