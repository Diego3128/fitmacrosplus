<?php
require __DIR__ . '/../vendor/autoload.php';
require 'functions.php';

//set timezone
date_default_timezone_set('America/Bogota');

//Main class active record
use Model\ActiveRecord;
// ent variables
use Dotenv\Dotenv;

//get .env file
$dotenv = Dotenv::createImmutable(__DIR__); //route to where the .env file is located.
$dotenv->safeLoad();

// debugAndFormat($_ENV);

//use environment variables inside this file to set the connection //it's required after setting the .env file
require 'database.php';

//set connection
ActiveRecord::setDB($db);
