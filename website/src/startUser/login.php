<?php

include("../includes/config.php");

use organised\Users\session;

$session = new session;

$location = ($session->startUserSession($_COOKIE['us'], $_GET['sessionToken'])) ? "Location: ../home" : "Location: ../";

header($location);