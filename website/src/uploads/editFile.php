<?php
include("../includes/config.php");

use organised\Playlists\File;
use organised\Playlists\Playlist;
use organised\images\image;
use organised\categories\Category;
use organised\categories\fileCategory;
use organised\Playlists\playlistFiles;

$file = new File($_GET['f_id']);
$playlist = new Playlist;
$image = new image($_GET['f_id']);
$category = new Category;
$fileCategory = new fileCategory;
$playlistFile = new playlistFiles;

$playlists = $playlist->findAllPlaylists();
$fileCategories = $fileCategory->findFileCategories($_GET['f_id']);
$categoriesList = $category->fetchAllCategories();

if(isset($_POST['editFile'])){

        if($file->editFile($_POST['filename'], $_POST['playlist'], $_FILES['image'], $_POST['comment'])){

            header("location: index.php");

        }

}

if(isset($_POST['addCategory'])){
    $fileCategory->addFileCategory($_GET['f_id'], $_POST['category']);
    header("location: editFile.php?f_id=" . $_GET['f_id']);
}

include('../includes/userHeader.php');

?>

<h1 class="heading" style="text-align: center; margin-top: 10px; color: white;">File</h1>

<div class="container">
    <div class="row">
        <?php if(!empty($image)): ?>
        <img style="height: 100px; width: 100px; margin-left: 50%; margin-right: 50%;" src="<?= $image->directory . "/" . $image->filename ?>" alt="">
        <?php endif;?>
    </div>
    <div class="row">
        <form action="" method="POST" style="margin-left: 45%; margin-top: 20px;" enctype="multipart/form-data">
            <div class="form-group">
                <label for="filename">File Name</label>
                <input type="text" value="<?= $file->filename?>" class="form-control" id="fileName" name="filename"
                       placeholder="Enter the name of your file">
            </div>
            <div class="form-group">
                <?php if(!empty($playlists)){ ?>
                    <a href="filePlaylists.php?f_id=<?= $_GET['f_id']?>">Edit Associated Playlists</a>
                </select>
                <?php } else{ ?>
                    <input type="hidden" name="playlist" value="">
                <?php }?>
            </div>

            <div class="form-group">
                <label for="image">Edit the image for the file</label>
                <br>
                <input type="file" name="image" id="image">
            </div>

            <div class="form-group">
                <label for="comment">Add a comment for this File</label>
                <br>
                <input type="text" value="<?= $file->comment ?>" class="form-control" name="comment" id="comment">
            </div>
            <?php if(!empty($categoriesList)): ?>

            <div class="form-group">
                <label for="comment">Add a category for this File</label>
                <br>

                <select name="category" id="category" class="form-control">

                    <option value="">Select</option>

                    <?php foreach($categoriesList as $key => $value):
                        $category = new Category($categoriesList[$key]['c_id']);
                        ?>

                        <option value="<?= $category->c_id?>"><?= $category->c_name?></option>

                    <?php endforeach;?>
                </select>
                <br>
                <button type="submit" name="addCategory" class="btn btn-success">Add Category</button>
                <br>
                <div class="fileCategories">
                    <?php
                    if(!empty($fileCategories)): ?>
                        <br>
                        <table class="table" id="fileCategoriesTable">
                            <thead>
                                <tr>
                                    <th scope="col">Category Name</th>
                                    <th scope="col">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($fileCategories as $key => $value):
                                $fileCategory = new fileCategory($fileCategories[$key]['f_id'], $fileCategories[$key]['c_id']);
                                ?>
                            <tr>
                                <td><?= $fileCategory->c_name?></td>
                                <td><a href="removeFileCategory.php?f_id=<?= $fileCategory->f_id?>&c_id=<?= $fileCategory->c_id?>">X</a></td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

            </div>

            <?php endif;?>
            <button type="submit" name="editFile" class="btn btn-primary">Edit</button>
        </form>
    </div>
</div>

<?php include("../includes/userFooter.php");?>
