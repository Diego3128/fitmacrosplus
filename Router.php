<?php

namespace MVC;

class Router
{
    public $getRoutes = [];
    public $postRoutes = [];
    //get routes and their controllers
    public function get(string $url, $fn)
    {
        $this->getRoutes[$url] = $fn;
    }
    //post routes and their controllers
    public function post(string $url, $fn)
    {
        $this->postRoutes[$url] = $fn;
    }
    // method executed when requesting the ./public/index.php file
    public function verifyRoutes()
    {
        //start session (avoids starting session in every controllers)
        session_start();
        //remove query params from the requested URL
        $currentUrl = strtok($_SERVER["REQUEST_URI"], "?") ?? "/";
        //request method
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        //the controller and tis method to manage the request
        $fn = null;
        //identify request method and its controller
        if ($requestMethod === "GET") {
            //read function
            $fn = $this->getRoutes[$currentUrl] ?? null;
        } elseif ($requestMethod === "POST") {
            //read function
            $fn = $this->postRoutes[$currentUrl] ?? null;
        }
        //If the controller and method exist, call it. Otherwise send 404
        if ($fn) {
            //the instace of Router is sent to the method of the controller
            call_user_func($fn, $this);
        } else {
            $this->render("errors/404");
        }
    }
    //Render a view from the controller
    public function render(String $view, array $data = [])
    {
        //$view: relative path to the view in ./views/
        // $data: information to be printed in the view

        //create a variable for each key of the array
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        ob_start(); //store output being printed and save it temporarily in memory

        include __DIR__ . "/views/$view.php"; //output being saved

        $content = ob_get_clean(); //clean buffer

        include __DIR__ . "/views/layout.php"; //include master layout (the variable $content is printed inside)
    }
}
