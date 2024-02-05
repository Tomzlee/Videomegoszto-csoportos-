<?php
include_once "database_connection.php";
include_once "database_functions.php";
include_once "website_functions.php";
session_start();

?>

<html>
<head>
    <link rel=stylesheet type="text/css" href="mystyle.css" />
    <meta charset="UTF-16" />
    <Title>Legnépszerűbb kategóriák</Title>
</head>
<body>
<div class="topright"">
<a href="index.php">Vissza a főoldalra</a>
</div>
<div>
    <div>
        <h1>Legnépszerűbb kategóriák</h1>
    </div>
    <div>
        <?php
        $categs = get_categs();
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