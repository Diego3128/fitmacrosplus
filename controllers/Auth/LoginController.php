<?php

namespace Controller\Auth;

use Model\Auth\User;
use MVC\Router;

class LoginController
{

    public static function login()
    {
        echo "login";
    }
    public static function logout()
    {
        echo "logout";
    }
    public static function create(Router $router)
    {
        $user = new User;
        $alerts = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            //sync the object with the data from the form
            $user->synchronize($_POST["user"]);
            // debugAndFormat($user);

            ///validateInputs() returns possible errors
            $alerts = $user->validateInputs();

            // validate existing email if there's no errors
            if (empty($alerts)) {
                // userExists() validates if the email already exists
                $user->userExists();

                $alerts = $user->getAlerts();

                // create account if the email is available
                if (empty($alerts)) {
                    //create a hashed password
                    $user->hashPassword();
                    //generate a token for email validation
                    $user->generateToken();
                    //send email with the token
                    debugAndFormat($user);
                }
            }
        }

        $data = [
            "alerts" => $alerts,
            "user" => $user
        ];

        $router->render("auth/register", $data);
    }
    public static function fortgotPassword()
    {
        echo "forgot pass";
    }
    public static function resetPass()
    {
        echo "reset pass";
    }
}
