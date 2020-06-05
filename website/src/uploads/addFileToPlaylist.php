<?php

include("../includes/config.php");

use organised\Playlists\playlistFiles;

$playlistFile = new playlistFiles;

$playlistFile->addFileToPlaylist($_POST['f_id'], $_POST['Playlists']);

header("location: filePlaylists.php?f_id=" . $_POST['f_id']);