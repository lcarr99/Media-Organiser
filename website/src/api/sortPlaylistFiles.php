<?php

include('../includes/config.php');

use organised\Playlists\playlistFiles;

$playlistFile = new playlistFiles;

echo $playlistFile->sortPlaylistFiles($_GET['search'], $_GET['sort'], $_GET['file'], $_GET['playlist']);
