<?php
include_once "database_connection.php";
include_once "database_functions.php";
include_once "website_functions.php";
session_start();
redirect_if_not_logged_in();

if (isset($_POST['new_comment'])){
    $video_id = $_POST['video_id'];
    $user_id = $_POST['user_id'];
    $comment = $_POST['comment'];
    $date = date("Y-m-d H:i:s");
    insert_comment($user_id,$video_id, $comment, $date);
    echo "
<script>
    alert('A komment írása sikeres volt!');
</script>
";
}

// Komment törlése ID alapján
if (isset($_POST['delete_comment'])) {
    $comment_id = $_POST['delete_comment'];
    delete_comment($comment_id);
}

// Komment módosítása ID alapján
if (isset($_POST['save_comment'])) {
    $comment_id = $_POST['save_comment'];
    $updated_comment = $_POST['updated_comment'];
    update_comment($comment_id, $updated_comment);
}

?>

<html>
<head>
    <link rel=stylesheet type="text/css" href="mystyle.css" />
    <meta charset="UTF-16" />
    <Title>Kommentek</Title>
</head>
<body>
<div class="topright">
    <a href="index.php">Vissza a főoldalra</a>
    <br>
    <a href="videok.php">Vissza a videókhoz</a>
</div>
<div>
    <div>
        <h1>Kommentek</h1>
    </div>
    <div>
        <h3>Új komment írása</h3>
        <form action="kommentek.php" method="post">
            <input type="hidden" name="video_id" value="<?php echo $_POST['video_id']; ?>">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            <textarea name="comment" rows="4" cols="50"></textarea>
            <br>
            <input type="submit" name="new_comment" value="Komment írása">
    </div>

    <div>
        <?php
        $video_id = $_POST['video_id'];
        $comments = get_comments_by_video_id($video_id);
        if ($comments == null){
            echo "<p> Nincsenek kommentek ehhez a videóhoz! </p>";
        }else{
            foreach ($comments as $comment){
                $commented_by = get_user_by_id($comment['USER_ID']);
                echo "<div class='comment'>";
                echo "<p> Felhasználó: " . $commented_by['USERNAME'] . "</p>";
                echo "<p> Komment: " . $comment['COMMENTS'] . "</p>";
                echo "<p> Dátum: " . $comment['CREATED_AT'] . "</p>";
                if(isset($_SESSION['user_id'])){
                    $user_id = $_SESSION['user_id'];
                    if($user_id == $comment['USER_ID']){
                        echo "<button type='submit' name='delete_comment' value='" . $comment['COMMENT_ID'] . "'>Komment törlése</button>";
                        if (isset($_POST['delete_comment'])) {
                            echo "<script>
                            alert('A komment törlése sikeres volt!');
                            </script>";
                        }
                        echo "<input type='submit' name='update_comment' value='Komment szerkesztése'>";
                        if (isset($_POST['update_comment'])) {
                            echo "<br>";
                            echo "<textarea name='updated_comment' rows='4' cols='50'></textarea>";
                            echo "<br>";
                            echo "<button type='submit' name='save_comment' value='" . $comment['COMMENT_ID'] . "'>Mentés</button>";
                            if (isset($_POST['save_comment'])) {
                                echo "<script>
                                alert('A komment szerkesztése sikeres volt!');
                                </script>";
                            }
                        }
                    }
                }
                echo "</div>";
                echo "<br>";
            }
        }
        ?>

    </div>
</div>
</body>
</html>