<?php
include("../includes/config.php");

use organised\Playlists\playlistFiles;
use organised\Playlists\File;
use organised\Playlists\Playlist;

$playlistFile = new playlistFiles;
$playlist = new Playlist;

$playlists = $playlist->findAllPlaylists();
$filePlaylists = $playlistFile->getFilePlaylists($_GET['f_id']);

include('../includes/userHeader.php');
?>

<div class="container">
    <div class="row">
        <form action="addFileToPlaylist.php" method="POST" style="margin-left: 45%; margin-top: 20px;" enctype="multipart/form-data">
            <div class="form-group">
                <?php if(!empty($playlists)){ ?>
                    <label for="playlist">Add to Playlist</label>
                    <select id="Playlist" name="Playlists" class="form-control">
                        <option value = "">Select</option>
                        <?php foreach ($playlists as $key => $value) { ?>
                            <option value="<?= $playlists[$key]['p_id'] ?>" <?= ($file->p_id === $playlists[$key]['p_id']) ? "selected=selected" : "" ?>>
                                <?= $playlists[$key]['p_name'] ?></option>
                        <?php } ?>
                    </select>
                    <input type="hidden" value="<?= $_GET['f_id']?>" name="f_id">
                <?php }else{ ?>
                <?php }?>
            </div>
            <br>
            <button type="submit" name="playlist" class="btn btn-success">Add to Playlist</button>
        </form>
    </div>
    <div class="row">
        <br>
        <div class="Playlists" style="margin-left: 40%; margin-right: 40%;">
            <?php
            if(!empty($filePlaylists)): ?>
                <br>
                <table class="table" id="fileCategoriesTable" >
                    <thead>
                    <tr>
                        <th scope="col">Playlist Name</th>
                        <th scope="col">Remove</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($filePlaylists as $key => $value):
                        $playlist = new Playlist($filePlaylists[$key]['p_id']);
                        ?>
                    <tr>
                        <td><?= $playlist->p_name?></td>
                        <td><a href="removeFileFromPlaylist.php?f_id=<?= $_GET['f_id']?>&p_id=<?= $playlist->p_id?>">X</a></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("../includes/userFooter.php")?>
