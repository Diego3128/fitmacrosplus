<?php

declare(strict_types=1);

require __DIR__ . "/../includes/app.php"; //composer autoload and helper functions

use Controller\Auth\LoginController;
use Controller\Home\HomeController;
use Controller\RootController;
use MVC\Router;
//router
$router = new Router;

//define get and post routes

//initial page
$router->get(url: "/", fn: [RootController::class, "index"]);
//login and logout
$router->get(url: "/login", fn: [LoginController::class, "login"]);
$router->post(url: "/login", fn: [LoginController::class, "login"]);

$router->get(url: "/logout", fn: [LoginController::class, "logout"]);
//create a new account
$router->get(url: "/register", fn: [LoginController::class, "create"]);
$router->post(url: "/register", fn: [LoginController::class, "create"]);

//validation message when creating an account
$router->get(url: "/message", fn: [LoginController::class, "message"]);
//validate an account with its token
$router->get(url: "/validate-account", fn: [LoginController::class, "validate"]);

//request create a new password
$router->get(url: "/password/request", fn: [LoginController::class, "fortgotPassword"]);
$router->post(url: "/password/request", fn: [LoginController::class, "fortgotPassword"]);
//reset password
$router->get(url: "/password/reset", fn: [LoginController::class, "resetPass"]);
$router->post(url: "/password/reset", fn: [LoginController::class, "resetPass"]);

//private routes
//show administration panel
$router->get(url: "/home", fn: [HomeController::class, "index"]);
//set profile info (only required once)
$router->get(url: "/home/set-profile", fn: [HomeController::class, "setProfile"]);
$router->post(url: "/home/set-profile", fn: [HomeController::class, "setProfile"]);






$router->verifyRoutes();
