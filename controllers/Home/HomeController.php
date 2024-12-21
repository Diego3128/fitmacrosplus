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
    //home (dashboard)
    public static function index(Router $router)
    {
        isAuth();
        $userId = $_SESSION["id"] ?? '0';
        $userProfile = UserProfile::where("user_id", $userId);
        // redirect if there's no profile
        if (!$userProfile) {
            header("location: /home/set-profile");
            exit;
        }

        $data = [];

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

    //update profile
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

        //info to complete the form
        $activityLevels = ActivityLevel::all();
        $goals = Goal::all();
        $genders = Gender::all();

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
                        // //redirect
                        header("location: /home");
                        exit;
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

        $router->render("pages/home/profile", $data);
    }
}
