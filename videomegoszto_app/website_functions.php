<?php
function insert_video_to_site($video_url){
    $video_id = explode("?v=", $video_url);
    $video_id = $video_id[1];
    $video_id = explode("&", $video_id);
    $video_id = $video_id[0];
    echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$video_id.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
}

function redirect_if_not_logged_in(){
    if (!isset($_SESSION['user_id'])){
        header("Location: login.php");
    }
}

function redirect_if_not_admin(){
    if (get_user_by_id($_SESSION['user_id'])["USERNAME"] != "admin"){
        header("Location: index.php");
    }
}

