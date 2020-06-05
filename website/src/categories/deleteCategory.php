<?php

include("../includes/config.php");

use organised\categories\Category;

$category = new Category($_GET['c_id']);

$category->deleteCategory();

header("Location: ./");
