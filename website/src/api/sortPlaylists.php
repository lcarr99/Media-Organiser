<?php

include('../includes/config.php');

use organised\Playlists\Playlist;

$playlist = new Playlist;

echo $playlist->sortPlaylists($_GET['search'], $_GET['sort']);