<?php
include_once "database_connection.php";
include_once "database_functions.php";
include_once "website_functions.php";
session_start();
redirect_if_not_logged_in();

//add to playlist_video
if (isset($_POST['add_to_playlist'])) {
    $video_id = $_POST['video_id'];
    $playlist_id = $_POST['playlist_id'];
    add_video_to_playlist($video_id, $playlist_id);
}
?>
<html>
<head>
    <link rel=stylesheet type="text/css" href="mystyle.css" />
    <meta charset="UTF-16" />
    <Title>Lejátszási lista</Title>
</head>
<body>
<div class="topright">
    <a href="index.php">Vissza a főoldalra</a>
    <br>
    <a href="sajat_oldal.php">Vissza a saját oldalra</a>
</div>
<h2>Lejátszási listához adás</h2>

<form action="add_to_playlist.php" method="post">
    <select name="video_id">
        <?php
        $videos = get_videos();
        foreach ($videos as $video) {
            echo "<option value='" . $video['VIDEO_ID'] . "'>" . $video['TITLE'] . "</option>";
        }
        ?>
    </select>
    <select name="playlist_id">
        <?php
        $playlists = get_playlists($_SESSION['user_id']);
        foreach ($playlists as $playlist) {
            echo "<option value='" . $playlist['PLAYLIST_ID'] . "'>" . $playlist['NAME'] . "</option>";
        }
        ?>
    </select>
    <input type="submit" name="add_to_playlist" value="Lejátszási listához adás">
</form>

<?php
if (isset($_POST['add_to_playlist'])) {
    echo "<script>alert('Sikeresen hozzáadva a lejátszási listához!')</script>";
}
?>

</body>
</html>
