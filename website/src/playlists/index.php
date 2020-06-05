<?php include("../includes/config.php") ?>
<?php include('../includes/userHeader.php') ?>

<?php

use organised\Playlists\File;
use organised\Playlists\Playlist;

$message = "";
$file = new File;
$playlist = new Playlist;

$playlistResults = $playlist->findAllPlaylists();

?>

<h1 class="heading" style="text-align: center; margin-top: 10px; color: white;">Playlists</h1>

<div class="container">
    <div class="row">
    <form action="createPlaylist.php" method="POST" style="margin-left: 45%; margin-top: 20px;" enctype="multipart/form-data">
  <div class="form-group">
    <label for="playlistName">Playlist Name</label>
    <input type="text" class="form-control" id="playlistName" name="playlistName" placeholder="Playlist Name">
  </div>
  <button id="createPlaylist" name="createPlaylist" class="btn btn-success">Create</button>
</form>
    <?= $message ?>
    </div>
</div>

<div class="container">
    <div class="row">
        <nav class="navbar navbar-expand-lg navbar-light bg-secondary">
            <div class="navbar" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item mr-2">Sort By:</li>
                    <li class="nav-item mr-2">
                        <select onchange="sortPlaylists()" class="form-control" name="sortDropdown" id="sortDropdown">
                            <option value="">Select</option>
                            <option value="1">Alphabetically A-Z</option>
                            <option value="2">Alphabetically Z-A</option>
                            <option value="3">Date Created</option>
                        </select>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input onkeyup="sortPlaylists()" class="form-control mr-sm-2" id="playlistSearch" type="search" placeholder="Search" aria-label="Search">
                </form>
            </div>
        </nav>
    </div>
  <div class="row">
  <table class="table">
  <thead>
    <tr>
      <th scope="col">Playlist Name</th>
      <th scope="col">Date Created</th>
      <th scope="col"> </th>
      <th scope="col"> </th>
      <th scope="col"> </th>
    </tr>
  </thead>
  <tbody class="playlistResults">
    <?php 
    if(!empty($playlistResults)){
    foreach($playlistResults as $key => $value){
        $playlist = new Playlist($playlistResults[$key]['p_id']);
        ?>
    <tr>
      <td><?=$playlist->p_name?></td>
      <td><?=$playlist->date_created?></td>
      <td><a href="changePlaylistName.php?p_id=<?= $playlist->p_id?>">Change Name</a></td>
      <td><a href="../editPlaylist?n=<?= $playlist->p_id?>">View</a></td>
      <td><a href="deletePlaylist.php?id=<?= $playlist->p_id?>">X</a></td>
    </tr>
   <?php }}?>
  </tbody>
  </table>
  </div>
</div>

<?php include('../includes/userFooter.php') ?>

<script>

    function sortPlaylists () {
        var http = new XMLHttpRequest();
        http.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

             var data = JSON.parse(this.responseText);

             document.querySelector('.playlistResults').innerHTML = ``;
             data.forEach(appendData);

             function appendData(item, index){
                 
                    document.querySelector('.playlistResults').innerHTML +=
                        `<tr>
      <td>${item.p_name}</td>
      <td>${item.date_created}</td>
      <td><a href="changePlaylistName.php?p_id=${item.p_id}">Change Name</a></td>
      <td><a href="../editPlaylist?n=${item.p_id}">View</a></td>
      <td><a href="deletePlaylist.php?id=${item.p_id}">X</a></td>
    </tr>`;
             }
            }
        };
        http.open("GET", "../api/sortPlaylists.php?search=" + document.getElementById('playlistSearch').value + "&sort=" + document.getElementById('sortDropdown').value, true);
        http.send();

    }

</script>
