<?php

include("../includes/config.php");

use organised\Playlists\Playlist;
use organised\Playlists\File;
use organised\Playlists\playlistFiles;
$playlist = new Playlist($_GET['id']);
$PlaylistFile = new playlistFiles;

$playlist->deletePlaylist();

header("Location: ./");