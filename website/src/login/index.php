<?php 

include("../includes/config.php");

use organised\Users\session;
use organised\Users\Authenication;

$auth = new Authenication($_POST['_']);

if(!$auth->checkToken()){
    echo "Error: 404";
}else{
    $auth->deactivateToken();
    echo $session->loginUser($_POST);
}


?>