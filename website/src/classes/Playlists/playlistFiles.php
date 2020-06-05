<?php


namespace organised\Playlists;

use organised\Config\Database;

class playlistFiles
{
    private $database;

    function __construct()
    {
        $this->database = new Database("playlist_files");
    }

    public function addFileToPlaylist($f_id, $p_id){
        return $this->database->insert("pf_f_id, pf_p_id", array($f_id, $p_id));
    }

    public function getFilePlaylists($f_id){
        return $this->database->select("p_name, playlists.p_id as p_id", "playlists on pf_p_id = p_id join files on pf_f_id = f_id", "pf_f_id = ?", array($f_id));
    }

    public function getPlaylistFiles($p_id){
        return $this->database->select("files.*", "playlists on pf_p_id = p_id join files on pf_f_id = f_id", "pf_p_id = ?", array($p_id));
    }

    public function removeFileFromPlaylist($f_id, $p_id){
        return $this->database->delete("pf_f_id = ? and pf_p_id = ?", array($f_id, $p_id));
    }

    public function removeAllPlaylistFiles($p_id){
        // this will be done when the user deletes a playlist
        return $this->database->delete("pf_p_id = ?", array($p_id));
    }

    public function sortPlaylistFiles($search, $sort, $fileType, $playlistID){

        $sortStatement = " order by ";
        // if an option is selected add order by that option to the query
        // if not don't add the sort statement to the query at all
        if($sort === "1"){
            $sortStatement .= "files.filename asc";
        }elseif($sort === "2"){
            $sortStatement .= "files.filename desc";
        }elseif ($sort === "3"){
            $sortStatement .= "files.f_date_uploaded";
        }else{
            $sortStatement = "";
        }

        $searchParam = "%" . $search . "%";
        $fileSearchParam = "%" . $fileType . "%";

        $results = $this->database->select("files.*",
            "files on pf_f_id = f_id join playlists on pf_p_id = p_id",
            "filename like ? and file_type like ? and pf_p_id = ?" . $sortStatement, array($searchParam, $fileSearchParam, $playlistID), false);
        // return in a json format so that javascript can parse the data
        return json_encode($results);

    }

}