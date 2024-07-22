<?php
session_start();
//require class to create an instance
function autoloadFn(string $class) : void
{
    if (preg_match("/Controller$/", $class))    
        require ("Controllers/" .$class. ".php");    
    else 
        require ("Models/" .$class. ".php");
}
spl_autoload_register("autoloadFn");

Database::connect();

$router = new RouterController();
$router->process(array($_SERVER["REQUEST_URI"]));
$router->render();