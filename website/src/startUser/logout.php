<?php
include("../includes/config.php");

use organised\Users\session;

$session = new session;

$location = ($session->logoutUser($_COOKIE['us'])) ? "http://localhost:8080" : "http://localhost:8080/home/";

header("location: " . $location);