<?php

namespace organised\Playlists;

use organised\Config\Database;
use organised\Users\User;
use organised\images\image;

class File{

    private $database;
    private $user;
    private $playlist;
    private $image;
    public $f_id;
    public $u_id;
    public $p_id;
    public $filename;
    public $directory;
    public $file_type;
    public $comment;


    function __construct($id = null){
        $this->database = new Database("files");
        $this->user = new User($_COOKIE['ut']);
        $this->playlist = new Playlist;
        $this->image = new image;

        if($id !== null){
            $result = $this->getFileByID($id);
            $this->f_id = $result->f_id;
            $this->u_id = $result->u_id;
            $this->p_id = $result->p_id;
            $this->filename = $result->filename;
            $this->directory = $result->directory;
            $this->file_type = $result->file_type;
            $this->comment = $result->comment;
        }

    }

    private function getFileByID($id){
        return $this->database->select("*","", "f_id = ?", array($id), true);
    }

    public function uploadFile($filename, $file, $comment, $image){

            $audioArray = array("audio/mp3", "audio/wav", "audio/flac", "audio/aac", "audio/ogg", "audio/amr", "audio/wma", "audio/mpeg");
            $videoArray = array("video/mp4", "video/mpeg", "video/ogg");

            // check the name entered by the user doesn't already exist so then it doesn't break the application

            if(!$this->checkFileNameDoesntExist($filename)){

                // check that the file type uploaded is a media file
                if(in_array($file['type'], $audioArray)){
                    $location = "music";
                }elseif(in_array($file['type'], $videoArray)){
                    $location = "videos";
                }else{
                    return false;
                }

                // add the extension the file
                $finalFile = $this->generateFileName($file['type'], $filename);

                $targetDir = "../files/" . $location;
                // if the file is then successfully uploaded onto the system then it will save the file to the database
                if(move_uploaded_file($file['tmp_name'], $targetDir . "/" . $finalFile)){

                    if($this->saveTheFileToDB($filename, $targetDir, $file['type'], $comment)){

                        // if the image upload file isn't empty then it will upload it here
                        if(!empty($image)){
                            // This gets the most recently uploaded file by the user by searching for the highest id in the files data base
                            // it also narrows the search using the user id
                            $f_id = $this->getMostRecentlyUploadedFile()->f_id;
                            // then it uploads the image
                            return $this->image->uploadImage($image, $f_id);
                        }
                        return true;
                    }else{
                        return false;
                    }

                }else{
                    return false;
                }
            }else{
                return false;
            }

    }

    // checks there isnt already a file uploaded with the same name the user has entered
    private function checkFileNameDoesntExist($filename, $f_id = ''){
        // count all the files saved with that name entered by the user
        $result = $this->database->select("count(*) as count", "", "filename = ? and f_id != ?", array($filename, $f_id), true);
        return ($result->count > 0) ? true : false;
    }

    // this function is called when the file is first uploaded
    private function generateFileName($type = null, $name = null){
        //get the name of the file extension from the file type field in the database
        //it will do this to the properties of a file object or use the data inserted into the function depending on the circumstance
        if($type !== null && $name !== null){
            $array = explode("/", $type);
            return $name . "." . $array[1];
        }else{
            $array = explode("/", $this->file_type);
            // the splits the string of the file type to get extension to the file
            return $this->filename . "." . $array[1];
        }

    }

    public function sortFiles($search, $sort, $fileType){
        $sortStatement = " order by ";
        // adds or empties the sort statement depending on what the user has selected
        if($sort === "1"){
            $sortStatement .= "files.filename asc";
        }elseif($sort === "2"){
            $sortStatement .= "files.filename desc";
        }elseif ($sort === "3"){
            $sortStatement .= "f_date_uploaded";
        }else{
            $sortStatement = "";
        }

        $searchParam = "%" . $search . "%";
        $fileSearchParam = "%" . $fileType . "%";

        $results = $this->database->select("i.filename as i_filename, i.directory as i_directory, 
        files.filename as f_filename, 
        files.comment as f_comment, files.directory as f_directory, files.f_id as f_f_id",
            "images i on files.f_id = i.f_id",
            "files.filename like ? and files.file_type like ?" . $sortStatement, array($searchParam, $fileSearchParam), false);

        return json_encode($results);


    }

    public function editFile($filename, $playlistID, $imageFileArray, $comment){

        $playlist = new Playlist($playlistID);

        if(!$this->checkFileNameDoesntExist($filename, $this->f_id)){

            if(!empty($imageFileArray)) {

                $this->image->uploadImage($imageFileArray, $this->f_id);

            }
            //rename the file in directory
            // generate filename function returns a string of the file name of the file type combined
            if(rename($this->directory . "/" . $this->generateFileName(), $this->directory . "/" . $this->generateFileName($this->file_type, $filename))){

                // update the database
                return $this->database->update("filename = ?, comment = ?", "f_id = ?", array($filename, $comment, $this->f_id));

            }else{
                return false;
            }
        }else{
            return false;
        }

    }

    private function saveTheFileToDB($filename, $dir, $type, $comment){
        return ($this->database->insert("u_id, filename, directory, file_type, comment", array($this->user->u_id , $filename, $dir, $type, $comment)));
    }

    public function getAllFiles(){
        return $this->database->select("*","", "");
    }

    public function getFile($f_id){
        return $this->database->select("*", "", "f_id = ?", array($f_id), true);
    }

    public function deleteFile(){
    
        if(unlink($this->directory . "/" . $this->generateFileName())){
            return ($this->database->delete("f_id = ?", array($this->f_id))) ? 1 : "File doesn't exist";
        }else{
            return "File doesn't exist";
        }
        
    }

    private function getMostRecentlyUploadedFile(){
        return $this->database->select("f_id","", "u_id = ? order by f_id desc", array($this->user->u_id), true);
    }

}

?>