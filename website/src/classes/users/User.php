<?php 

namespace organised\Users;

use organised\Config\Database;

class User{

    public $u_id;
    public $username;
    public $firstname;
    public $lastname;
    public $dob;
    public $email;
    private $database;

    function __construct($token = null)
    {
        
        $this->database = new Database("users");
        if($token !== null){
            $userDetails = $this->getUserDetails($token);
            $this->u_id = $userDetails->id;
            $this->username = $userDetails->username;
            $this->firstname = $userDetails->firstName;
            $this->lastname = $userDetails->lastName;
            $this->email = $userDetails->email;
            $this->dob = $userDetails->dob;
            
        }

        
    }

    public function getUserDetails($token){
        return $this->database->select("*","", "userToken = ? limit 1", array($token), true);
    }

    public function registerUser($array){

       $hash = password_hash($array['password'], CRYPT_BLOWFISH);

       $insertArray = array($array['username'], $array['firstname'], $array["lastname"], $array["birth"], $array['email'], $hash, $this->generateToken());

       if($this->database->insert("username, firstName, lastName, dob, email, password, userToken", $insertArray)){
           $status = "success";
           $message = "Registered successfully, please go log in " . $array['username'];
       }else{
           $status = "error";
           $message = "Registered unsuccessfully";
       }

       $result = array("result" => $status, "message" => $message);

       if($result["result"] === "success"){
            $this->createUserDirectory($array['username']);
        }

        return json_encode($result);

    }

    private function createUserDirectory($userName){
        mkdir("../users/" . $userName);
        mkdir("../users/" . $userName . "/playlists");
    }

    public function getUserToken($email){
        return $this->database->select("userToken","", "email = ?", array($email), true);
    }

    public function checkPassword($email, $password){
        $result = $this->database->select("password","", "email = ?", array($email), true);
        return (password_verify($password, $result->password)) ? true : false;
    }

    public function generateToken(){
        $characters = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
        $stringArray = str_split($characters);
        $arrayLength = count($stringArray);
        $i = 0;
        $endString = "";

        while($i < $arrayLength){
            $endString .= $stringArray[rand(0, $arrayLength - 1)];
            $i++;
        }

        return $endString;
    }


}


?>