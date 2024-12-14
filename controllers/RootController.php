<?php

namespace Controller;

use MVC\Router;

class RootController
{

    public static function index(Router $router)
    {
        $data = [
            "username" => "diego perez"
        ];
        //render view home
        $router->render("pages/home", $data);
    }
}
