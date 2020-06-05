<?php
include("../includes/config.php");

use organised\Playlists\Playlist;

$playlist = new Playlist($_GET['p_id']);

$message = "";

if(isset($_POST['changeName'])){
    if($playlist->renamePlaylist($_POST['playlistName'])){
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
    <label for="playlistName">Playlist Name</label>
    <input type="text" class="form-control" id="playlistName" name="playlistName" value="<?= $playlist->p_name?>" placeholder="Playlist Name">
  </div>
  <button type="submit" name="changeName" id="createPlaylist" class="btn btn-success">Edit</button>
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