<?php include("../includes/config.php"); ?>
<?php include('../includes/userHeader.php') ?>

<?php

use organised\Playlists\Playlist;
use organised\Playlists\File;
use organised\Playlists\playlistFiles;

$file = new File;
$playlist = new Playlist($_GET['n']);
$playlistFile = new PlaylistFiles;

$uploadedFiles = $file->getAllFiles();
$fileResults = $playlistFile->getPlaylistFiles($_GET['n']);

?>

<h1 class="heading" style="text-align: center; margin-top: 10px; color: white;"><?= $playlist->p_name?></h1>

<div class="container" style="margin-top: 20px;">

    <div class="row">
        <input type="hidden" value="<?= $_GET['n']?>" name="id" class="id">
        <nav class="navbar navbar-expand-lg navbar-light bg-secondary">
            <div class="navbar" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item mr-2">Sort By:</li>
                    <li class="nav-item mr-2">
                        <select onchange="sortPlaylistFiles()" class="form-control" name="sortDropdown" id="sortDropdown">
                            <option value="">Select</option>
                            <option value="1">Alphabetically A-Z</option>
                            <option value="2">Alphabetically Z-A</option>
                            <option value="3">Date Uploaded</option>
                        </select>
                    </li>
                    <li class="nav-item mr-2">File Type</li>

                    <li class="nav-item mr-2">
                        <select onchange="sortPlaylistFiles()" name="fileType" id="fileDropdown" class="form-control">
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
                    <input onkeyup="sortPlaylistFiles()" class="form-control mr-sm-2" id="fileSearch" type="search" placeholder="Search" aria-label="Search">
                </form>
            </div>
        </nav>
    </div>

  <div class="row">

  <table class="table">
  <thead>
    <tr>
      <th scope="col">Song Name</th>
      <th scope="col">View</th>
      <th scope="col">Remove</th>
    </tr>
  </thead>
  <tbody class="fileResults">
    <?php 

  if(!empty($fileResults)){

    foreach($fileResults as $key => $value){
        $file = new File($fileResults[$key]['f_id']);
        ?>
    <tr>
      <td><?=$file->filename?></td>
      <td><a href="<?= $file->directory . "/" . $file->filename?>" class="view">View</a></td>
      <td><a href="removeFile.php?p_id=<?= $playlist->p_id?>&f_id=<?= $file->f_id?>" class="delete">X</a></td>
    </tr>
   <?php }}?>
  </tbody>
  </table>
  </div>
</div>
<?php include('../includes/userFooter.php') ?>

<script>

    function sortPlaylistFiles(){

        var http = new XMLHttpRequest();
        http.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                var data = JSON.parse(this.responseText);

                document.querySelector('.fileResults').innerHTML = ``;
                data.forEach(appendData);

                function appendData(item, index) {

                    document.querySelector('.fileResults').innerHTML += `   <tr>
      <td>${item.filename}</td>
      <td><a href="${item.directory}/${item.filename}" class="view">View</a></td>
      <td><a href="removeFile.php?p_id=<?= $_GET['n'] ?>&f_id=${item.f_id}" class="delete">X</a></td>
    </tr>`;
                }
            }
        };
        http.open("GET", "../api/sortPlaylistFiles.php?search=" + document.getElementById('fileSearch').value + "&sort=" + document.getElementById('sortDropdown').value + "&file=" + document.getElementById('fileDropdown').value + "&playlist=" + document.querySelector('.id').value , true);
        http.send();

    }

</script>
