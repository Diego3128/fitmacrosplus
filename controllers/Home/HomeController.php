<?php

namespace Controller\Home;

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
        $userId = $_SESSION["id"];
        $userProfile = UserProfile::where("user_id", $userId);
        // debugAndFormat($userProfile);
        // redirect if there's no profile
        if (!$userProfile) {
            header("location: /home/set-profile");
            exit;
        }

        $data = [
            "userProfile" => $userProfile
        ];

        $router->render("pages/home/panel", $data);
    }
    //set profile
    public static function setProfile(Router $router)
    {
        //authenticate user
        isAuth();
        // debugAndFormat(ActivityLevel::all());

        // debugAndFormat($_SESSION);

        //receive info

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            //init instance of UserProfile

            //validate inputs

            //save 
        }


        $router->render("pages/home/setProfile");
    }
}
