<?php

include("../includes/config.php");

use organised\Playlists\File;
use organised\Users\User;
use organised\Playlists\Playlist;

$file = new File;
$user = new User($_COOKIE['ut']);
$playlist = new Playlist;

$result = $file->getFile($_GET['id']);
$playlistName = $playlist->getPlaylistById($result->p_id)->p_name;

echo "<audio controls> 
<source src='../users/" . $user->username . "/" . $result->directory . "/" . $playlistName . "'> </audio>";

?>