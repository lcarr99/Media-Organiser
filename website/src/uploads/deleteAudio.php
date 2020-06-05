<?php

include("../includes/config.php");

use organised\Playlists\File;

$file = new File($_GET['f_id']);

$file->deleteFile();

header("location: ./");