<?php

namespace organised\categories;

use organised\Config\Database;

class Category
{
    private $db;
    public $c_id;
    public $c_name;

    function __construct($id = null){

        $this->db = new Database("categories");

        if($id !== null){
            $result = $this->fetchCategoryByID($id);
            $this->c_id = $result->c_id;
            $this->c_name = $result->c_name;
        }

    }

    private function fetchCategoryByID($id){

        return $this->db->select("*","", "c_id = ?", array($id), true);

    }

    public function addCategory($categoryName){

        return $this->db->insert("c_name", array($categoryName));

    }

    public function renameCategory($name){
        return $this->db->update("c_name = ?", "c_id = ?", array($name, $this->c_id));
    }

    public function deleteCategory(){
        return $this->db->delete("c_id = ?", array($this->c_id));
    }

    public function fetchAllCategories(){
        return $this->db->select("*","", "", null);
    }

}