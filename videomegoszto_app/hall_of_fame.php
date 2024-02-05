<?php
include_once "database_connection.php";
include_once "database_functions.php";
include_once "website_functions.php";
include_once "osszetett_lekerdezesek.php";
session_start();
redirect_if_not_logged_in();

// Ez az az oldal, ahol az összetett lekérdezések többsége van: Később átnevezhető, ha kell.

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
</div>
<h1>Összetett lekérdezések</h1>

<h3>A legaktívabb felhasználó videó feltöltés szerint (Top 1)</h3>
<?php
get_most_active_user_by_video_upload();
?>

<h3>A legaktívabb felhasználók kommentek szerint (Top 3)</h3>
<?php
get_most_active_users_by_comment();
?>

<div>
    <div>
        <h1>Legnépszerűbb kategóriák</h1>
    </div>
    <div>
        <?php
        $categs = get_most_watched_categs();
        echo '<table border=1>';
        echo '</tr>';
        foreach ($categs as $categ){
            echo '<td>' . $categ['NAME'] . '</td>';

            echo '<td>' . $categ['VIDEO_COUNT'] . " videó" . '</td>';
            echo "</tr>";
        }

        echo "</table>";
        ?>
    </div>
</div>

</body>
</html>
