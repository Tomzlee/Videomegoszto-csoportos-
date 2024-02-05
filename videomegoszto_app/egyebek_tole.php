<?php
include_once "database_connection.php";
include_once "database_functions.php";
include_once "website_functions.php";
include_once "osszetett_lekerdezesek.php";
session_start();
redirect_if_not_logged_in();

?>

<html>
<head>
    <link rel=stylesheet type="text/css" href="mystyle.css" />
    <meta charset="UTF-16" />
    <Title>Videó ajánlása</Title>
</head>
<body>
<div class="topright">
    <a href="index.php">Vissza a főoldalra</a>
</div>
<h2>Egyéb videók tőle </h2>

<?php
$user_id = $_GET['user_id'];
$video_id = $_GET['video_id'];
$videos = get_other_videos($user_id,$video_id);
if (count($videos) == 0){
    echo "Nincs más videója";
}else{
    foreach ($videos as $video){
        $uploaded_by = get_user_by_id($video['USER_ID']);
        echo "<div class='video'>";
        echo "<h2>" . $video['TITLE'] . "</h2>";
        echo "<p> Leírás: " . $video['DESCRIPTION'] . "</p>";
        echo "<p> Feltöltő: " . $uploaded_by['USERNAME'] . "</p>";
        echo "<p> Megosztva: " . $video['CREATED_AT'] . "</p>";

        echo "<form action='kommentek.php' method='post'>";
        echo "<input type='hidden' name='video_id' value='" . $video['VIDEO_ID'] . "'>";
        echo "<input type='submit' name='comment' value='Kommentek'>";
        echo "</form>";

        echo "<form action='hasonlo_videok.php' method='get'>";
        echo "<input type='hidden' name='video_id' value='" . $video['VIDEO_ID'] . "'>";
        echo "<input type='hidden' name='video_title' value='" . $video['TITLE'] . "'>";
        echo "<input type='submit' name='similar_videos' value='Hasonló videók'>";
        echo "(".call_ennyi_hasonlo_van($video['VIDEO_ID']).")";
        echo "</form>";


        insert_video_to_site($video['URL']);
        echo "</div>";
        echo "<br>";
    }
}

?>

</body>
</html>

