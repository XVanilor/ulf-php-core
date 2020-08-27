<?php

//Loads configuration
$config = (new \App\Core\KeyBuilder('../config'))->build();
//Base functions
require_once '../App/Core/Funcs.php';
//Loads routes
$routes = route();