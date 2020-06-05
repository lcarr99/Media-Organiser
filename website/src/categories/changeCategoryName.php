<?php
include("../includes/config.php");

use organised\categories\Category;

$category = new Category($_GET['c_id']);

$message = "";

if(isset($_POST['changeName'])){
    if($category->renameCategory($_POST['categoryName'])){
        header("location: ./");
    }else{
        $message = "Name change failed";
    }
}

include('../includes/userHeader.php') ?>

    <div class="container">
        <div class="row">
            <form action="" method="POST" style="margin-left: 45%; margin-top: 20px;" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="categoryName">Category Name</label>
                    <input type="text" class="form-control" id="categoryName" name="categoryName" value="<?= $category->c_name?>" placeholder="category Name">
                </div>
                <button type="submit" name="changeName" id="createCategory" class="btn btn-success">Edit</button>
            </form>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <h3 style="text-align: center;"><?= $message?></h3>
            </div>
        </div>
    </div>

<?php include('../includes/userFooter.php') ?>