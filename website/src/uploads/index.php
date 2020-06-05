<?php
include("../includes/config.php");

use organised\Playlists\File;
use organised\Playlists\Playlist;
use organised\images\image;
use organised\categories\fileCategory;

$file = new File;
$playlist = new Playlist;
$image = new image;
$fileCategory = new fileCategory;

$playlists = $playlist->findAllPlaylists();

if (isset($_POST['uploadFile'])) {

    if ($file->uploadFile($_POST['filename'], $_FILES['file'], $_POST['comment'], $_FILES['image'])) {
        header("location: uploadSuccess.php");
    } else {
        header("location: uploadError.php");
    }

}

include('../includes/userHeader.php');

?>

<h1 class="heading" style="text-align: center; margin-top: 10px; color: white;">Uploads</h1>

<div class="container">
    <div class="row">
        <form action="" method="POST" style="margin-left: 45%; margin-top: 20px;" enctype="multipart/form-data">
            <div class="form-group">
                <label for="filename">File Name</label>
                <input type="text" class="form-control" id="fileName" name="filename"
                       placeholder="Enter the name of your file">
            </div>

            <div class="form-group">
                <label for="file">Insert file you'd like to upload</label>
                <br>
                <input type="file" id="file" name="file">
            </div>

            <div class="form-group">
                <label for="image">Insert the image for the file</label>
                <br>
                <input type="file" name="image" id="image">
            </div>

            <div class="form-group">
                <label for="comment">Add a comment for this File</label>
                <br>
                <input type="text" class="form-control" name="comment" id="comment">
            </div>

            <button type="submit" name="uploadFile" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

<div class="container">

    <div class="row">
        <nav class="navbar navbar-expand-lg navbar-light bg-secondary">
            <div class="navbar" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item mr-2">Sort By:</li>
                    <li class="nav-item mr-2">
                        <select onchange="sortFiles()" class="form-control" name="sortDropdown" id="sortDropdown">
                            <option value="">Select</option>
                            <option value="1">Alphabetically A-Z</option>
                            <option value="2">Alphabetically Z-A</option>
                            <option value="3">Date Uploaded</option>
                        </select>
                    </li>
                    <li class="nav-item mr-2">File Type</li>

                    <li class="nav-item mr-2">
                        <select onchange="sortFiles()" name="fileType" id="fileDropdown" class="form-control">
                            <option value="">Select</option>
                            <option value="mp3">MP3</option>
                            <option value="wav">WAV</option>
                            <option value="flac">FLAC</option>
                            <option value="aac">AAC</option>
                            <option value="ogg">OGG</option>
                            <option value="amr">AMR</option>
                            <option value="wma">WMA</option>
                            <option value="mpeg">MPEG</option>
                            <option value="mp4">MP4</option>
                        </select>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input onkeyup="sortFiles()" class="form-control mr-sm-2" id="fileSearch" type="search" placeholder="Search" aria-label="Search">
                </form>
            </div>
        </nav>
    </div>

    <div class="row">
        <table class="table">
            <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">Filename</th>
                <th scope="col">Edit File</th>
                <th scope="col">Comment</th>
                <th scope="col">View</th>
                <th scope="col">Delete</th>
            </tr>
            </thead>
            <tbody id="fileResults">
            <?php

            $userFiles = $file->getAllFiles();

            foreach ($userFiles as $key => $value) {
                $playlist = new Playlist($userFiles[$key]['p_id']);
                $image = new image($userFiles[$key]["f_id"]);
                $file = new File($userFiles[$key]["f_id"]);
                ?>
                <tr>
                    <th scope="row">
                        <?php if ($image->directory !== null): ?>
                            <img style="height: 25px; width: 25px;"
                                 src="<?= $image->directory . "/" . $image->filename ?>">
                        <?php endif; ?>
                    </th>
                    <td><?= $file->filename ?></td>
                    <td><a href="editFile.php?f_id=<?= $file->f_id?>">Edit File</a></td>
                    <td><?= $file->comment ?></td>
                    <td>
                        <a href="<?= $file->directory . "/" . $file->filename?>">View</a>
                    </td>
                    <td>
                        <a href="deleteAudio.php?f_id=<?= $file->f_id ?>" class="delete">X</a>
                    </td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>

<script>

    function checkImage(item){
        if(item.i_filename !== null){
            return `<img style="height: 25px; width: 25px;" src="${item.i_directory + "/" + item.i_filename}">`;
        }else{
            return ``;
        }
    }

    function sortFiles(){

            var http = new XMLHttpRequest();
            http.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {

                    console.log(this.responseText);

                    var data = JSON.parse(this.responseText);

                    document.querySelector('#fileResults').innerHTML = ``;
                    data.forEach(appendData);

                    function appendData(item, index) {

                        document.querySelector('#fileResults').innerHTML += `<tr>
                        <th scope="row">${checkImage(item)}</th>
                    <td>${item.f_filename}</td>
                    <td><a href="editFile.php?f_id=<?= $file->f_id?>">Edit File</a></td>
                    <td>${item.f_comment}</td>
                    <td>
                        <div>
                            <a href="${item.f_directory}/${item.f_filename}">View</a>
                        </div>
                    </td>
                    <td>
                        <a href="deleteAudio.php?f_id=${item.f_f_id}" class="delete">X</a>
                    </td>
                </tr>`;
                    }
                }
    };
            http.open("GET", "../api/sortFiles.php?search=" + document.getElementById('fileSearch').value + "&sort=" + document.getElementById('sortDropdown').value + "&file=" + document.getElementById('fileDropdown').value, true);
            http.send();

        }




</script>

<?php include('../includes/userFooter.php') ?>

