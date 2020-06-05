<?php

include('../includes/config.php');

use organised\categories\Category;

$category = new Category;

if(isset($_POST['createCategory'])){
    $category->addCategory($_POST['categoryName']);
    header("./");
}

$categoryResults = $category->fetchAllCategories();



?>

<?php include('../includes/userHeader.php') ?>

    <h1 class="heading" style="text-align: center; margin-top: 10px; color: white;">Categories</h1>

    <div class="container">
        <div class="row">
            <form action="" method="POST" style="margin-left: 45%; margin-top: 20px;" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="playlistName">Category Name</label>
                    <input type="text" class="form-control" id="categoryName" name="categoryName"
                           placeholder="Category Name">
                </div>
                <button type="submit" name="createCategory" id="createCategory" class="btn btn-success">Create</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Category Name</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody class="categoryResults">
                <?php
                if (!empty($categoryResults)) {
                    foreach ($categoryResults as $key => $value) { ?>
                        <tr>
                            <td><?= $categoryResults[$key]['c_name'] ?></td>
                            <td><a href="changeCategoryName.php?c_id=<?= $categoryResults[$key]['c_id'] ?>">Change
                                    Name</a></td>
                            <td><a href="deleteCategory.php?c_id=<?= $categoryResults[$key]['c_id'] ?>">X</a></td>
                        </tr>
                    <?php }
                } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include('../includes/userFooter.php') ?>