<?php

include("../includes/config.php");

use organised\Playlists\playlistFiles;

$playlistFiles = new playlistFiles;

$playlistFiles->removeFileFromPlaylist($_GET['f_id'], $_GET['p_id']);

header("location: ./?n=" . $_GET['p_id']);
