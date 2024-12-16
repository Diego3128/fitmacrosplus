<?php

namespace Controller\Auth;

use Classes\Email;
use Exception;
use Model\Auth\User;
use MVC\Router;

class LoginController
{
    //logic when logging in
    public static function login(Router $router)
    {
        $user = new User;

        $alerts = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            //create an instance only with the data the email and password
            $auth = new User($_POST["user"]);
            //validate received data
            $alerts = $auth->validateLogin();

            if (empty($alerts)) {
                //find the user in the database by their email
                $user = User::where("email", $auth->email);

                if ($user) {
                    //check if user is verified and if the password matches the hash
                    if ($user->checkVerifiedAndPassword($auth->password)) {
                        //log the user in and start a session
                        //session is already started in verifyRoutes()
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $user->id;
                        $_SESSION["name"] = $user->name;
                        //check if the user is an admin
                        if ($user->isadmin == "0") {
                            header("location: /home");
                        } else {
                            header("location: /admin");
                        }
                    } else {
                        //checkVerfiedAndPassword() creates the alerts, we need to get it
                        $alerts = User::getAlerts();
                    }
                } else {
                    $alerts = User::setAlert("error", "El usuario no existe");
                }
            }
        }


        $data = [
            "user" => $user,
            "alerts" => $alerts
        ];

        $router->render("auth/login", $data);
    }
    //logic when logging out
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
                        // throw new Exception("test error: fails to send the email.");
                        $email = new Email($user->email, $user->name, $user->token);

                        //send confirmation email
                        if ($email->sendConfirmationEmail()) {
                            //save user in db
                            try {
                                // throw new Exception("test error when saving in db.");
                                if ($user->save()["result"]) {
                                    //redirecto to /message?email=useremail
                                    header("location: /message?email=" . $user->email);
                                } else {
                                    //error saving in db
                                    $alerts = User::setAlert("error", "Error creando la cuenta");
                                }
                            } catch (Exception $e) {
                                //error saving in db
                                $alerts = User::setAlert("error", "Error creando la cuenta");
                            }
                        } else {
                            //error sending email
                            $alerts = User::setAlert("error", "Error enviando el email de confirmación");
                        }
                    } catch (Exception $e) {
                        //error sending email
                        $alerts = User::setAlert("error", "Error enviando el email de confirmación");
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

    //validate a new account by token

    public static function validate(Router $router)
    {
        $token = $_GET["token"] ?? null;

        if (!$token) {
            header("location: /");
            exit;
        }
        // Proceed with the database query

        $errorMessage = null;

        $email = null;

        $user = User::where("token", $token);

        if ($user) {
            // validate user
            $user->verified = "1";
            $user->token = null;
            $user->save();
            $email = $user->email;
        } else {
            $errorMessage = getErrorMessage(5);
        }

        $data = [
            "errorMessage" => $errorMessage,
            "email" => $email
        ];

        //send view
        $router->render("auth/validate", $data);
    }
    public static function fortgotPassword(Router $router)
    {
        $user = new User;

        $alerts = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            //create a object with the password
            $auth = new User($_POST["user"]);
            //validate received data    
            $auth->validateEmail();

            $alerts = User::getAlerts();

            if (empty($alerts)) {
                //find the user in the database by their email
                $user = User::where("email", $auth->email);

                if ($user and $user->verified == "1") {
                    //generate a token for email validation
                    try {
                        // throw new Exception("test error: fails to save the token.");
                        $user->generateToken();
                        $user->save();
                        //send email
                        try {
                            // throw new Exception("test error: fails to send the email.");
                            $email = new Email($user->email, $user->name, $user->token);
                            if ($email->sendInstructions()) {
                                User::setAlert("success", "Revisa tu correo electrónico " . $user->email);
                            } else {
                                User::setAlert("error", "Error enviando el correo de recuperación, inténtalo más tarde");
                            }
                        } catch (Exception $e) {
                            $alerts = User::setAlert("error", "Error enviando el email de confirmación, inténtalo más tarde");
                        }
                    } catch (Exception $e) {
                        User::setAlert("error", "Algo ha salido mal, inténtalo más tarde");
                    }
                } else {
                    User::setAlert("error", "El correo no existe o no está verificado");
                }
            }
        }

        $alerts = User::getAlerts();

        $data = [
            "user" => $user,
            "alerts" => $alerts
        ];


        $router->render("auth/password-request", $data);
    }
    //
    public static function resetPass(Router $router)
    {
        $token = $_GET["token"] ?? null;

        if (!$token) {
            header("location: /");
            exit;
        }

        $alerts = [];

        $tokenError = false;
        $redirect = false;

        //find the user in the database by their token
        $user = User::where("token", $token);

        if (!$user) {
            $tokenError = true;
            User::setAlert("error", "Token no valido");
        }

        if (!$tokenError) {

            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                //create a object with the password
                $newPassword = $_POST["user"]["password"];
                //validate password
                $user->password = $newPassword;
                $user->validatePassword();

                $alerts = User::getAlerts();

                if (empty($alerts)) {
                    //update password
                    $user->hashPassword();
                    $user->token = null;

                    try {
                        if ($user->save()) {
                            User::setAlert("success", "Contraseña actualizada");
                            $redirect = true;
                        } else {
                            User::setAlert("error", "Algo ha salido mal, inténtalo mas tarde");
                        }
                    } catch (Exception $e) {
                        User::setAlert("error", "Algo ha salido mal, inténtalo mas tarde");
                    }
                }
            }
        }


        $alerts = User::getAlerts();

        $data = [
            "alerts" => $alerts,
            "tokenError" => $tokenError,
            "redirect" => $redirect
        ];

        $router->render("auth/password-reset", $data);
    }
}
