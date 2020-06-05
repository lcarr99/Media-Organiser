<?php


namespace organised\Users;

use organised\Config\Database;


class Authenication extends User
{

    private $database;
    private $token;

    function __construct($token = null)

    {
        $this->database = new Database("auth_tokens");
        if($token !== null){
            $this->token = $token;
        }

    }

    public function createToken(){
        $token = $this->generateToken();
        if($this->database->insert("a_token", array($token))){
            return $token;
        }else{
            return false;
        }

    }

    public function checkToken(){
        $count = $this->database->select("count(*) as count", "", "a_token = ? and active = 1", array($this->token), true)->count;
        return ($count > 0) ? true : false;
    }

    public function deactivateToken(){
        return $this->database->update("active = 0", "a_token = ?", array($this->token));
    }


}