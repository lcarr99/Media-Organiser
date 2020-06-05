<?php 

namespace organised\Playlists;

use organised\Config\Database;
use organised\Users\User;
use organised\Playlists\playlistFiles;

class Playlist{

    private $database;
    private $user;
    private $u_id;
    private $playlistFile;
    public $p_id;
    public $p_name;
    public $date_created;


    
    function __construct($id = null){
        $this->database = new Database("playlists");
        $this->user = new User($_COOKIE['ut']);
        $this->playlistFile = new playlistFiles;
        if($id !== null){
            $result = $this->getPlaylistByID($id);
            $this->p_id = $result->p_id;
            $this->u_id = $result->u_id;
            $this->p_name = $result->p_name;
            $this->date_created = $result->date_created;
        }
    }

    public function getPlaylistByID($id){
        return $this->database->select("*", "","p_id = ?", array($id), true);
    }
    
    public function findAllPlaylists(){
        return $this->database->select("*","", "");
    }

    public function createNewPlaylist($playlistName){
        return $this->database->insert("u_id, p_name", array($this->user->u_id, $playlistName));
    }
    public function renamePlaylist($newPlaylistName){
        return ($this->database->update("p_name = ?", "p_id = ?", array($newPlaylistName, $this->p_id))) ? true : false;
    }
    
    private function getNewPlaylist(){
        return $this->database->select("*","", "u_id = ? order by p_id desc limit 1", array($this->user->u_id),true);
    }

    public function deletePlaylist(){
        $this->database->delete("p_id = ?", array($this->p_id));
        $this->playlistFile->removeAllPlaylistFiles($this->p_id);
    }

    public function sortPlaylists($search, $sort){
        $sortStatement = " order by ";

            if($sort === "1"){
                $sortStatement .= "p_name asc";
            }elseif($sort === "2"){
                $sortStatement .= "p_name desc";
            }elseif ($sort === "3"){
                $sortStatement .= "date_created";
            }else{
                $sortStatement = "";
            }


            $searchParam = "%" . $search . "%";

                $results = $this->database->select("*", "", "p_name like ?" . $sortStatement, array($searchParam), false);

                return json_encode($results);


        }

    
}


?>