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
//crud user profile
$router->get(url: "/home/profile", fn: [HomeController::class, "profile"]);
$router->post(url: "/home/profile", fn: [HomeController::class, "profile"]);

//user foods CRUD
//create a new food
$router->get(url: "/home/newfood", fn: [HomeController::class, "createFood"]);
$router->post(url: "/home/newfood", fn: [HomeController::class, "createFood"]);
//edit and read  a food
$router->get(url: "/home/editfood", fn: [HomeController::class, "editFood"]);
$router->post(url: "/home/editfood", fn: [HomeController::class, "editFood"]);
//read all foods
$router->get(url: "/home/foods", fn: [HomeController::class, "getAllFoods"]);
// delete
$router->post(url: "/home/deletefood", fn: [HomeController::class, "deleteFood"]);

//user_record_detail CRUD
//food list
$router->get(url: "/home/new-record-detail", fn: [HomeController::class, "newRecordDetail"]);
//create
$router->get(url: "/home/new-record-detail/create", fn: [HomeController::class, "createRecordDetail"]);
$router->post(url: "/home/new-record-detail/create", fn: [HomeController::class, "createRecordDetail"]);
// edit
$router->get(url: "/home/edit-record-detail", fn: [HomeController::class, "editRecordDetail"]);
$router->post(url: "/home/edit-record-detail", fn: [HomeController::class, "editRecordDetail"]);
// delete
$router->post(url: "/home/delete-record-detail", fn: [HomeController::class, "dropRecordDetail"]);


$router->verifyRoutes();
