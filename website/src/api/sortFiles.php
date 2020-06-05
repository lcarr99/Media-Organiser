<?php

include('../includes/config.php');

use organised\Playlists\File;

$file = new File;

echo $file->sortFiles($_GET['search'], $_GET['sort'], $_GET['file']);