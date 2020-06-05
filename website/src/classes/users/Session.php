<?php

namespace organised\Users;

use organised\Config\Database;
use organised\Users\User;

class session {

    private $database;
    private $user;

    function __construct()
    {
        $this->database = new Database("user_sessions");
        $this->user = new User;
    }

    public function startUserSession($sess, $userToken){
       return $this->database->insert("user_token, active, session_token", array($userToken, 1, $sess));
    }

    public function checkUserSession($token){
        return $this->database->select("active","", "session_token = ?", array($token), true)->active;
    }

    public function loginUser($array){
        $details = $this->user->getUserToken($array['email']);
        $result = (!empty($details)) ? $this->user->checkPassword($array['email'], $array['password']) : 0;
        return ($result) ? json_encode(array("ut" => $details->userToken, "st" => $this->user->generateToken())) : 0;
    }

    public function logoutUser($token){
        return ($this->database->update("active = 0" , "session_token = ?", array($token))) ? true : false;
    }
}

?>