<?php
include_once "database_connection.php";
include_once "database_functions.php";
include_once "website_functions.php";
session_start();
redirect_if_not_logged_in();

// a user update
if (isset($_POST['update_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_id = $_SESSION['user_id'];
    update_user($user_id, $username, $email, $password);
}

// delete video
if (isset($_POST['delete_video'])) {
    $video_id = $_POST['video_id'];
    delete_video($video_id);
}

// új lejátszási lista
if (isset($_POST['new_playlist'])) {
    $playlist_name = $_POST['playlist_name'];
    $user_id = $_SESSION['user_id'];
    insert_playlist($playlist_name, $user_id);
}
// lejátszási lista törlése
if (isset($_POST['delete_playlist'])) {
    $playlist_id = $_POST['playlist_id'];
    delete_playlist($playlist_id);
}
?>

<html>
<head>
    <link rel=stylesheet type="text/css" href="mystyle.css" />
    <meta charset="UTF-16" />
    <Title>Sajat oldal</Title>
</head>
<body>
<div class="topright">
    <a href="index.php">Vissza a főoldalra</a>
    <br>
    <a href="sajat_oldal.php">Vissza a saját oldalra</a>
</div>
<h1>Saját oldal</h1>
<h3>Adatok módosítása</h3>
<form action="sajat_oldal.php" method="post">
    <input type="text" name="username" placeholder="Felhasználónév" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Jelszó" required>
    <input type="submit" name="update_user" value="Felhasználó módosítása" required>
</form>

<h2>Lejátszási lista hozzáadása</h2>
<form action="sajat_oldal.php" method="post">
    <input type="text" name="playlist_name" placeholder="Lejátszási lista neve">
    <input type="submit" name="new_playlist" value="Lejátszási lista hozzáadása">
</form>
</div>


    <h2>Video hozzáadása l.listákhoz</h2>
    <form action="add_to_playlist.php">
        <input type="submit" value="Az oldalra">
    </form>



<div>
    <h1>Lejátszási listáid</h1>
    <?php
    $playlist_count = 0;
    $playlists = get_playlists($_SESSION['user_id']);
    foreach ($playlists as $playlist){
        if ($playlist['USER_ID'] == $_SESSION['user_id']){
            echo "<div>";
            echo "<h2>" . $playlist['NAME'] . "</h2>";
            echo "</div>";
            echo "<div>";
            echo "<form action='sajat_oldal.php' method='post'>";
            echo "<input type='hidden' name='playlist_id' value='" . $playlist['PLAYLIST_ID'] . "'>";
            echo "<input type='submit' name='delete_playlist' value='Lejátszási lista törlése'>";
            echo "</form>";

            echo "<form action='playlist_items.php' method='post'>";
            echo "<input type='hidden' name='playlist_id' value='" . $playlist['PLAYLIST_ID'] . "'>";
            echo "<input type='submit' name='watch_elements' value='Lejátszási lista megtekintése'>";
            echo "</form>";
            echo "</div>";

            $playlist_count++;
        }
    }
    if ($playlist_count == 0){
        echo "<p>Nincs lejátszási listád!</p>";
    }
    ?>
</div>


    <div>
        <div>
            <h1>Videóid</h1>
        </div>
        <div>
            <?php
            $videos = get_videos();
            $video_count = 0;
            foreach ($videos as $video){
                if ($video['USER_ID'] == $_SESSION['user_id']){
                    $uploaded_by = get_user_by_id($video['USER_ID']);
                    echo "<div class='video'>";
                    echo "<h2>" . $video['TITLE'] . "</h2>";
                    echo "<p> Leírás: " . $video['DESCRIPTION'] . "</p>";
                    echo "<p> Megosztva: " . $video['CREATED_AT'] . "</p>";

                    // kommentek
                    echo "<form action='kommentek.php' method='post'>";
                    echo "<input type='hidden' name='video_id' value='" . $video['VIDEO_ID'] . "'>";
                    echo "<input type='submit' name='comment' value='Kommentek'>";
                    echo "</form>";

                    // hasonló videók

                    echo "<form action='hasonlo_videok.php' method='get'>";
                    echo "<input type='hidden' name='video_id' value='" . $video['VIDEO_ID'] . "'>";
                    echo "<input type='hidden' name='video_title' value='" . $video['TITLE'] . "'>";
                    echo "<input type='submit' name='similar_videos' value='Hasonló videók'>";
                    echo "</form>";

                    insert_video_to_site($video['URL']);
                    //delete video by id
                    echo "<form action='sajat_oldal.php' method='post'>";
                    echo "<input type='hidden' name='video_id' value='" . $video['VIDEO_ID'] . "'>";
                    echo "<input type='submit' name='delete_video' value='Videó törlése'>";
                    echo "</form>";
                    echo "</div>";
                    echo "<br>";
                    $video_count++;
                }
            }
            if ($video_count == 0){
                echo "<p>Nincs feltöltött videód!</p>";
            }
            ?>
        </div>
    </div>

</body>
</html>