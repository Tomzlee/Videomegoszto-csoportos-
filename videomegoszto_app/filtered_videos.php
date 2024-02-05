<?php
include_once "database_connection.php";
include_once "database_functions.php";
include_once "website_functions.php";
session_start();
redirect_if_not_logged_in();

$filtered = false;

$videos=null;
if(isset($_POST['category']) && isset($_POST['uname']) && $_POST['uname'] != '' && $_POST['category'] != ''){

    $uname = $_POST['uname'];

    $category = $_POST['category'];


    $videos = get_videos_by_uploader_and_category($uname,$category);

    $filtered = true;
}elseif (isset($_POST['category']) && $_POST['category'] != ''){
    $category = $_POST['category'];
    $videos=get_videos_by_category($category);//itt hiba

    $filtered = true;

}elseif (isset($_POST['uname']) && $_POST['uname'] != '' ){
    $uname = $_POST['uname'];
    $videos = get_videos_by_uploader($uname);//itt hiba
    echo "asdasdads";

    $filtered = true;

}else{
    $videos = get_videos();
}


?>

<html>
<head>
    <link rel=stylesheet type="text/css" href="mystyle.css" />
    <meta charset="UTF-16" />
    <Title>Videók Szűrt listázása</Title>
</head>

<div class="topright"">
<a href="index.php">Vissza a főoldalra</a>
</div>



<body>
<div>

    <div>
        <h1>Videók Szűrése</h1>
    </div>

    <div>
        <form name="filter_videos" action="filtered_videos.php" method="post">

            <label for="uname">Feltöltő:</label>
            <select name="uname" id="uname">

                <option value="" selected></option>
                <?php
                $uploaders = get_uploaders();
                foreach ($uploaders as $key => $value){
                    echo "<option value='" . $key . "'>" . $value . "</option>";
                }
                ?>
            </select>

            <label for="category">Kategória:</label>
            <select name="category" id="category">
                <option value="" selected></option>

                <?php
                $categories = get_categories();
                foreach ($categories as $key => $value){
                    echo "<option value='" . $key . "'>" . $value . "</option>";
                }
                ?>
            </select>

            <input type="submit" value="Listázás">

        </form>

<hr>
    </div>
</div>


<div>

</div>
<?php
foreach ($videos as $video) {
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
    echo "(" . call_ennyi_hasonlo_van($video['VIDEO_ID']) . ")";
    echo "</form>";

    echo "<form action='egyebek_tole.php' method='get'>";
    echo "<input type='hidden' name='video_id' value='" . $video['VIDEO_ID'] . "'>";
    echo "<input type='hidden' name='user_id' value='" . $video['USER_ID'] . "'>";
    echo "<input type='submit' name='other_videos' value='Egyéb videók tőle'>";
    echo "</form>";


    insert_video_to_site($video['URL']);
    echo "</div>";
    echo "<br>";
}
?>


</body>
</html>

