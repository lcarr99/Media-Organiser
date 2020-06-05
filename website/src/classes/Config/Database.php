<?php

namespace organised\Config;
use PDO;

class Database extends Config{

     private $db;
     private $table;

     function __construct($table)
     {
            // establish connection and the table to be used
           $this->db = new PDO("mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName .  ";", $this->dbUser, "");
           $this->table = $table;
        
     }

     public function insert($rowStatement,  $array){
         $valueStatement = "";
         $i = 0;

         // this will add to the values of the insert query everytime to goes through the array
         foreach($array as $key => $value){
            $i++;
            //when it reaches the last value it will not have a comma behind the question mark
            $valueStatement .= ($i === count($array)) ? " ?" : "?, ";
         }
        $query = $this->db->prepare("insert into " . $this->table . "(" . $rowStatement . ")" . "values(" . $valueStatement . ")");
        return ($query->execute($array)) ? true : false;

     }

     public function select($selectStatement, $joinStatement,$whereStatement, $array = null, $object = false){

         /* if either the array of values to put entered is empty or the where statement is equal to empty quotes then the where statement will
         be equal to empty string */
         $whereStatement = ($array !== null || $whereStatement !== "") ? " where " . $whereStatement : "";
         // if the join statement is empty then it wont be added to the query
         $joinStatement = ($joinStatement !== "") ?  " join " . $joinStatement : "";

        $query = $this->db->prepare("SELECT " . $selectStatement . " FROM " . $this->table . $joinStatement . $whereStatement);

        if($query->execute($array)){
            // if the user decides to return the data as an object or an array
            return ($object) ? $query->fetchObject() : $query->fetchAll();
        }

     }

      public function delete($whereStatement, $array){
        $query = $this->db->prepare("DELETE FROM " . $this->table . " where " . $whereStatement);
        return ($query->execute($array)) ? true : false;
     }

     public function update($setStatement, $whereStatement, $array){
         $query = $this->db->prepare("UPDATE " . $this->table . " SET " . $setStatement . " WHERE " . $whereStatement);
         return ($query->execute($array)) ? true : false;
     }
     
}