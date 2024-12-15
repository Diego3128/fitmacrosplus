<?php

namespace Controller\Auth;

use Classes\Email;
use Exception;
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
    //logic when creating a new account
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

                    try {
                        // throw new Exception("test error when saving in db.");

                        //save user in db
                        if ($user->save()["result"]) {
                            //send email with the validation token
                            try {
                                // throw new Exception("test error: fails to send the email.");
                                $email = new Email($user->email, $user->name, $user->token);
                                if ($email->sendConfirmationEmail()) {
                                    //redirecto to /message?email=useremail
                                    header("location: /message?email=" . $user->email);
                                } else {
                                    //user saved but the email was not sent
                                    header("location: /message?email=" . $user->email . "&error=1");
                                }
                            } catch (Exception $e) {
                                //error sending the email
                                // error_log("Error al enviar email: " . $e->getMessage());
                                header("location: /message?email=" . $user->email . "&error=1");
                            }
                        }
                    } catch (Exception $e) {
                        //error saving the new account
                        $alerts = $user->setAlert("error", "Error al crear cuenta. Intenta más tarde");
                    }
                }
            }
        }

        $data = [
            "alerts" => $alerts,
            "user" => $user
        ];

        $router->render("auth/register", $data);
    }
    //message after creating a new account
    public static function message(Router $router)
    {
        $user = new User;

        $errorMessage = null;

        $errorCode = $_GET["error"] ?? null;

        $email = $_GET["email"] ?? null;

        //invalid email param will be redirected
        if (!$email) header("location: /");

        //validate error message
        if ($errorCode) $errorMessage = getErrorMessage($errorCode);

        $data = [
            "errorMessage" => $errorMessage,
            "email" => $email
        ];

        $router->render("auth/message", $data);
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
