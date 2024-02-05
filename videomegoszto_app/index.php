<?php
// ADMIN felhasznaáló USERNAME: admin, PASSWORD: admin123

include_once "database_connection.php";
include_once "database_functions.php";
include_once "website_functions.php";
session_start();
?>

<html>
<head>
    <link rel=stylesheet type="text/css" href="mystyle.css" />
    <meta charset="UTF-16" />

</head>
<body>


<?php
if (isset($_SESSION['user_id'])){
    // Bejelntkezett felhasználó nevének kiírása
    $user_name = get_user_by_id($_SESSION['user_id'])["USERNAME"];
    echo "Bejelentkezve: " . $user_name;
    echo "<br>";
    echo "<a class='topright' href='logout.php'>Kijelentkezés</a>";
}
else{
    echo "
    <div class='topright'>
        <a href='register.php'>Regisztráció</a>
        <br>
        <a href='login.php'>Bejelentkezés</a>
    </div>";
}
if (isset($_SESSION['user_id']) && get_user_by_id($_SESSION['user_id'])["USERNAME"] == "admin"){
    echo "<a href='admin.php'>Admin felület</a>";
    echo "<br>";
}

?>

<?php
if (isset($_SESSION['user_id'])){
?>

<?php
}
if (isset($_POST['url'])){
    $url = $_POST['url'];
    insert_video_to_site($url);
}
?>

<?php
if (isset($_SESSION['user_id'])){
    echo "<a href='sajat_oldal.php'>Saját oldal</a>";
    echo "<br>";
    echo "<a href='videok_feltoltese.php'>Videók feltöltése</a>";
    echo "<br>";
    echo "<a href='videok.php'>Videók</a>";
    echo "<br>";
    echo "<a href='hall_of_fame.php'>Legek</a>";
    echo "<br>";
    echo "<a href='hot_stuff.php'>Hot stuff</a>";
    echo "<br>";
    echo "<a href='filtered_videos.php'>Videók szűrése</a>";
}
?>

<div class="content_center">
    <h1>Videó megosztó</h1>
    <p>Itt megoszthatod a kedvenc videóidat másokkal!</p>
    <?php
    if (isset($_SESSION['user_id'])){
        echo "<div>
        <h1>Ajánlott videó</h1>
        </div>
        <div>";
        $videos = get_rec_video();
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

            insert_video_to_site($video['URL']);
            echo "</div>";
            echo "<br>";
        }
    }
    ?>
</div>
</body>
</html>




