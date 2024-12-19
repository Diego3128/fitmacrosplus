<?php

namespace Controller;

use MVC\Router;

class RootController
{

    public static function index(Router $router)
    {
        //block if the user is already logged in
        redirectToHomeIfLoggedIn();
        //render view home
        $router->render("pages/home");
    }
}
