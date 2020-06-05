<?php

include("../includes/config.php");

use organised\categories\fileCategory;

$fileCategory = new fileCategory;

$fileCategory->deleteFileCategory($_GET['f_id'], $_GET['c_id']);

header("location: editFile.php?f_id=" . $_GET['f_id']);