<?php

include("../includes/config.php");

use organised\Playlists\Playlist;

$playlist = new Playlist;

$playlist->createNewPlaylist($_POST["playlistName"]);

header("location: ./");