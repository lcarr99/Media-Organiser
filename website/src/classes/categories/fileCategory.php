<?php


namespace organised\categories;

use organised\Config\Database;


class fileCategory
{

    private $db;
    public $f_id;
    public $c_id;
    public $c_name;

    public function __construct($f_id = null, $c_id = null)
    {

        $this->db = new Database("file_categories");

        if($f_id !== null && $c_id !== null){
            $result = $this->getFileCategory($f_id, $c_id);
            $this->f_id = $f_id;
            $this->c_id = $c_id;
            $this->c_name = $result->c_name;
        }

    }

    private function getFileCategory($f_id, $c_id){
        return $this->db->select("c_name","files on file_categories.f_id = files.f_id join categories on categories.c_id = file_categories.c_id ", "file_categories.f_id = ? and file_categories.c_id = ?", array($f_id, $c_id), true);
    }

    public function addFileCategory($f_id, $c_id){
        return $this->db->insert("f_id, c_id", array($f_id, $c_id));
    }

    public function findFileCategories($f_id){
        return $this->db->select("*","files on file_categories.f_id = files.f_id join categories on categories.c_id = file_categories.c_id ", "file_categories.f_id = ?", array($f_id));
    }

    public function deleteFileCategory($f_id, $c_id){
        return $this->db->delete("f_id = ? and c_id = ?", array($f_id, $c_id));
    }

}