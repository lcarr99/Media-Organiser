<?php 

include("../includes/config.php");

use organised\Users\User;

$user = new User;

if (!empty($_POST)) {

    echo $user->registerUser($_POST);

} else{
     echo "Error: 404";
}

?>