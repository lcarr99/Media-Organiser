<?php 

namespace organised\images;

use organised\Config\Database;
use organised\Users\User;

class image{

    private $db;
    private $user;
    public $file_type;
    public $directory;
    public $filename;

    function __construct($f_id = null){

        $this->db = new Database("images");
        $this->user = new User($_COOKIE['ut']);

        if($f_id !== null){
            $results = $this->getImageFromF_id($f_id);
            $this->file_type = $results->file_type;
            $this->directory = $results->directory;
            $this->filename = $results->filename;
        }
    }

    private function getImageFromF_id($f_id){
        return $this->db->select("*","", "f_id = ?", array($f_id), true);
    }

    public function uploadImage($imageFileArray, $f_id){

        $targetDir = "../images/";

        if(move_uploaded_file($imageFileArray['tmp_name'], $targetDir . $imageFileArray['name'])){

            if($this->countFileImages($f_id) > 0){
                return $this->db->update("file_type = ?, directory = ?, filename = ?", "f_id = ?", array($imageFileArray['type'], $targetDir, $imageFileArray['name'], $f_id));

            }else{
                return $this->db->insert("f_id, u_id, filename, file_type, directory", array($f_id, $this->user->u_id, $imageFileArray['name'],$imageFileArray['type'], $targetDir));
            }

        }else{
            return false;
        }

        
    }

    public function countFileImages($f_id){
        return $this->db->select("count(*) as count","", "f_id = ?", array($f_id), true)->count;
    }

}

?>