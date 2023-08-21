<?php
session_start();
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'transport');

define('APP_NAME', 'DM Services');

function customname($name){
    return $name.' | '.APP_NAME;
}
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$commmunes=[
    "abobo"=>"Abobo",
    "anyama"=>"Anyama",
    "adjame"=>"Adjamé",
    "attecoube"=>"Attécoubé",
    "bingerville"=>"Bingerville",
    "cocody"=>"Cocody",
    "koumassi"=>"Koumassi",
    "marcory"=>"Marcory",
    "plateau"=>"Le Plateau",
    "portbouet"=>"Port-Bouët",
    "treichville"=>"Treichville",
    "yopougon"=>"Yopougon",
];

if ($conn->connect_error) {
    die("<script> alert(`Connection failed: ".$conn->connect_error."`);</script>");
}

// Set to true to allow error reporting, false otherwise
define('DEBUG', true);

// Set default timezone
date_default_timezone_set('Europe/Paris');

// Set error reporting based on DEBUG
if(DEBUG){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}else{
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', dirname(__FILE__) . '/erreurs.txt');
}
?>
