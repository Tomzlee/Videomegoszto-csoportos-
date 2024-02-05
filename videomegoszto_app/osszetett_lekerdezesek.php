<?php
include_once "database_functions.php";
function get_most_active_user_by_video_upload(){
    $sql = "SELECT USERNAME, COUNT(VIDEO_ID) FROM USERS, VIDEOS WHERE USERS.USER_ID = VIDEOS.USER_ID GROUP BY USERNAME ORDER BY COUNT(VIDEO_ID) DESC FETCH FIRST 1 ROWS ONLY";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    oci_close($GLOBALS["conn"]);

    $row = oci_fetch_array($result, OCI_ASSOC+OCI_RETURN_NULLS);
    echo "<table>";
    echo "<tr>";
    echo "<th>Felhasználónév</th>";
    echo "<th>Videók száma</th>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>" . $row['USERNAME'] . "</td>";
    echo "<td>" . $row['COUNT(VIDEO_ID)'] . "</td>";
    echo "</tr>";
    echo "</table>";
}

function get_most_active_users_by_comment(){
    $sql = "SELECT USERNAME, COUNT(COMMENT_ID) FROM USERS, COMMENTS WHERE USERS.USER_ID = COMMENTS.USER_ID GROUP BY USERNAME ORDER BY COUNT(COMMENT_ID) DESC FETCH FIRST 3 ROWS ONLY";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    oci_close($GLOBALS["conn"]);

    echo "<table>";
    echo "<tr><th>Felhasználónév</th><th>Kommentek száma</th></tr>";
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        echo "<tr>";
        echo "<td>" . $row['USERNAME'] . "</td>";
        echo "<td>" . $row['COUNT(COMMENT_ID)'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function get_similar_videos($video_id){
    $sql = "SELECT * FROM VIDEOS WHERE CATEGORY_ID = (SELECT CATEGORY_ID FROM VIDEOS WHERE VIDEO_ID = $video_id) AND VIDEO_ID != $video_id ";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $videos = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $videos[] = $row;
    }
    oci_close($GLOBALS["conn"]);
    return $videos;
}


function get_hot_videos_by_category(){
    $sql = "SELECT v.VIDEO_ID, v.USER_ID, v.title, v.description, v.url, v.category_id, v.created_at, COUNT(c.comment_id) AS comment_count
        FROM videos v
        JOIN comments c ON v.video_id = c.video_id
        GROUP BY v.VIDEO_ID, v.USER_ID, v.title, v.description, v.url, v.category_id, v.created_at
        HAVING COUNT(c.comment_id) = (
            SELECT MAX(comment_count)
            FROM (
                SELECT COUNT(comment_id) AS comment_count
                FROM videos v2
                JOIN comments c2 ON v2.video_id = c2.video_id
                WHERE v2.category_id = v.category_id
                GROUP BY v2.video_id
            )
        )
        ORDER BY v.category_id";


    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);


    $videos = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $videos[] = $row;
    }
    oci_close($GLOBALS["conn"]);
    return $videos;
}

/*
 * Sajnos ez a newest videos valszeg nem lesz összetett lekérdezés de próbálom bonyolítani, hátha lesz valami
 */
function get_newest_videos_by_category(){
    $sql = "SELECT v.VIDEO_ID, v.USER_ID, v.title, v.description, v.url, v.category_id, v.created_at
        FROM videos v
        WHERE v.created_at IN (
            SELECT MAX(created_at)
            FROM videos
            WHERE category_id = v.category_id
        )
        ORDER BY v.category_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $videos = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $videos[] = $row;
    }
    oci_close($GLOBALS["conn"]);
    return $videos;
}

function get_other_videos($user_id, $video_id){
    $sql = "SELECT * FROM VIDEOS, USERS WHERE VIDEOS.USER_ID = USERS.USER_ID AND USERS.USER_ID = (SELECT USERS.USER_ID FROM USERS WHERE USERS.USER_ID = $user_id) AND VIDEO_ID != $video_id ";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $videos = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $videos[] = $row;
    }
    oci_close($GLOBALS["conn"]);
    return $videos;
}

function get_most_watched_categs(){
    return get_categs();
}
