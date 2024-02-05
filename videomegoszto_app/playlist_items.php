<?php
include_once "database_connection.php";
include_once "database_functions.php";
include_once "website_functions.php";
session_start();
redirect_if_not_logged_in();

//delet from playlist_video
if (isset($_POST['delete_from_playlist'])) {
    $video_id = $_POST['video_id'];
    $playlist_id = $_POST['playlist_id'];
    delete_from_playlist_video($playlist_id, $video_id);
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
<h1>Lejátszási lista</h1>
<div>
    <div>
        <?php
        $videos_id_array = get_videos_from_playlist_video($_POST['playlist_id']);
        $video_count = 0;
        foreach ($videos_id_array as $video_id){
            $video = get_video_by_id($video_id['VIDEO_ID']);
            echo "<br>";
            echo "<h2>" . $video['TITLE'] . "</h2>";
            insert_video_to_site($video['URL']);
            // delete video from playlist video
            echo "<form action='playlist_items.php' method='post'>";
            echo "<input type='hidden' name='video_id' value='" . $video['VIDEO_ID'] . "'>";
            echo "<input type='hidden' name='playlist_id' value='" . $_POST['playlist_id'] . "'>";
            echo "<input type='submit' name='delete_from_playlist' value='Lejátszási listából törlés'>";
            echo "</form>";
            $video_count++;
        }

        if ($video_count == 0){
            echo "<p>Nincs feltöltött videód!</p>";
        }
        ?>
    </div>
</div>
</body>
</html>

