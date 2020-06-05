<?php
include("./classes/Config/Config.php");
include("./classes/Config/Database.php");
include("./classes/users/User.php");
include("./classes/users/Session.php");
include("./classes/users/Authenication.php");
include("./classes/Playlists/Playlist.php");
include("./classes/Playlists/File.php");
use organised\Users\session;
use organised\Users\Authenication;
$session = new session;
if(!empty($_COOKIE) && $session->checkUserSession($_COOKIE['us'])){
    header("location: /home");
}else{
    $auth = new Authenication;
}
?>

<style>
@import url('https://fonts.googleapis.com/css?family=Comfortaa:300,400&display=swap');
.heading{
    font-family: 'Comfortaa';
    font-weight: 300;
    }
nav{
    width: 100%;
    padding-top: 15px;
}
#nav-options{
    margin-left: 50%;
}
.selection{
    background-color: #39CCCC;
    width: 100%;
}

.loginForm{
    margin-top: 10px;
}
.message{
    font-family: 'Comfortaa';
    font-weight: 300;
    text-align: center;
    margin-top: 50%;
}

</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Organised</title>
</head>
<body class="bg-primary" style="overflow-x: hidden;">
    <header>
        <nav class="nav navbar-expand-lg navbar-dark bg-dark">
            <div class="navbar-nav">
                <a href="/"><li class="navbar-brand heading" id="logo">Media Organiser</li></a>
        </div>
        </nav>
    </header>

    <div class="loginForm">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4"> 
                <form>

                    <input type="hidden" id="loginToken" name="token" value="<?= $auth->createToken()?>">

                <div class="form-group">
                    <label for="regUsername">Email</label>
                    <input type="text" class="form-control" id="loginEmail" placeholder="Email">
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="loginPassword" placeholder="Password">
                </div>
  
                <div onclick="login()" id="register" class="btn btn-success">Login</div>
            </form>
        </div>
            <div class="col-md-2"></div>
        </div>
           
    </div>
                
    </body>
</html>

<script src="script/script.js"></script>

<script>

function login(){
    email = document.querySelector("#loginEmail").value;
    password = document.querySelector("#loginPassword").value;

    xml = new XMLHttpRequest();

    xml.open("POST", "./login/", true);

    xml.onreadystatechange = function(){

        if(xml.status === 200 && xml.readyState === 4){

            
            try{

            if(xml.responseText !== '0'){
                data = JSON.parse(xml.responseText);
                document.cookie = "ut = " + data.ut; 
                document.cookie = "us = " + data.st;
                window.location.href = "startUser/login.php?sessionToken=" + data.ut;
            }else{
                location.reload();
            }
            
            }catch(e){

            }

           
               
            
            
        }

    }

    xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xml.send("email=" + email  + "&password=" + password + "&_=" + document.querySelector("#loginToken").value);

}

</script>