<?php
function get_next_id($table_name, $id_name){
    $sql = "SELECT MAX($id_name) FROM $table_name";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS);
    $next_id = $row['MAX(' . $id_name . ')'] + 1;
    return $next_id;
}


function insert_user($username,$email, $password){
    $password = password_hash($password, PASSWORD_DEFAULT);
    $user_id = get_next_id('USERS', 'USER_ID');
    $sql = "INSERT INTO USERS (USER_ID, USERNAME, EMAIL,PASSWORD) VALUES ($user_id, '$username','$email','$password')";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    oci_close($GLOBALS["conn"]);
    return $user_id;
}

function get_user_by_id($user_id){
    $sql = "SELECT * FROM USERS WHERE USER_ID = $user_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS);
    return $row;
}
function get_user_id_by_name($user_name){
    $sql = "SELECT * FROM USERS WHERE USERNAME = '$user_name'";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS);
    return $row;
}

function get_username_by_id($user_id){
    $sql = "SELECT USERNAME FROM USERS WHERE USER_ID = $user_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS);
    return $row;
}

function delete_user($user_id){
    $sql = "DELETE FROM USERS WHERE USER_ID = $user_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    oci_close($GLOBALS["conn"]);
}

function get_users(){
    $sql = "SELECT * FROM USERS";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $users = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $users[] = $row;
    }
    return $users;
}

function get_uploaders(){
    $sql = "SELECT USERNAME, USERS.USER_ID  FROM USERS,VIDEOS WHERE VIDEOS.USER_ID = USERS.USER_ID";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $users = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $users[$row['USER_ID']] = $row['USERNAME'];


    }
    return $users;
}

function select_users_to_table(){
// "user" tábla lekérdezése
    $sql = 'SELECT * FROM USERS';
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);

// Tábla kiírása
    echo "<table>";
    echo "<tr><th>User ID</th><th>Username</th><th>Email</th><th>Password</th></tr>";
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        echo "<tr>";
        echo "<td>" . $row['USER_ID'] . "</td>";
        echo "<td>" . $row['USERNAME'] . "</td>";
        echo "<td>" . $row['EMAIL'] . "</td>";
        echo "<td>" . $row['PASSWORD'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

// Adatbáziskapcsolat lezárása
    oci_close($GLOBALS["conn"]);
}

function update_user($user_id, $username, $email, $password){
    // current user load by id
    $current_user = get_user_by_id($user_id);
    // if username is empty, set it to the current username
    if(empty($username)){
        $username = $current_user['USERNAME'];
    }
    // if email is empty, set it to the current email
    if(empty($email)){
        $email = $current_user['EMAIL'];
    }
    // if password is empty, set it to the current password
    if(empty($password)){
        $password = $current_user['PASSWORD'];
    }
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE USERS SET USERNAME = '$username', EMAIL = '$email', PASSWORD = '$password' WHERE USER_ID = $user_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    oci_close($GLOBALS["conn"]);
}
function login_user($username, $password){
    $sql = "SELECT * FROM USERS WHERE USERNAME = '$username'";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS);
    if(!empty($row) && password_verify($password, $row['PASSWORD'])){
        $id = $row['USER_ID'];
        oci_close($GLOBALS["conn"]);
        return $id;
    }else{
        oci_close($GLOBALS["conn"]);
        return -1;
    }

}

function user_exists($username){
    $sql = "SELECT * FROM USERS WHERE USERNAME = '$username'";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS);
    oci_close($GLOBALS["conn"]);
    if($row){
        return true;
    }else{
        return false;
    }
}

function insert_video($user_id, $title, $description, $category_id, $video_path, $created_at){
    $success = false;
    // check if the video path is a valid youtube url
    if(!preg_match('/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+$/', $video_path)){
        return $success;
    }
    try {
        $video_id = get_next_id('VIDEOS', 'VIDEO_ID');
        $sql = "INSERT INTO VIDEOS (VIDEO_ID, USER_ID, TITLE, DESCRIPTION,URL, CATEGORY_ID, CREATED_AT) VALUES ($video_id, $user_id, '$title', '$description','$video_path', $category_id, '$created_at')" ;
        $result = oci_parse($GLOBALS["conn"], $sql);
        oci_execute($result);
        oci_close($GLOBALS["conn"]);
        $success = true;
    }catch (Exception $e){
        echo $e->getMessage();
    }
    return $success;
}

function select_videos_to_table(){
// "video" tábla lekérdezése
    $sql = 'SELECT * FROM VIDEOS';
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);

// Tábla kiírása video_id,user_id,title,description, url, category_id, created_at
    echo "<table>";
    echo "<tr><th>Video ID</th><th>User ID</th><th>Title</th><th>Description</th><th>URL</th><th>Category ID</th><th>Created at</th></tr>";
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        echo "<tr>";
        echo "<td>" . $row['VIDEO_ID'] . "</td>";
        echo "<td>" . $row['USER_ID'] . "</td>";
        echo "<td>" . $row['TITLE'] . "</td>";
        echo "<td>" . $row['DESCRIPTION'] . "</td>";
        echo "<td><a href='" . $row['URL'] . "'>" . $row['URL'] . "</a></td>";
        echo "<td>" . $row['CATEGORY_ID'] . "</td>";
        echo "<td>" . $row['CREATED_AT'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function get_videos(){
    $sql = 'SELECT * FROM VIDEOS';
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $videos = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $videos[] = $row;
    }
    oci_close($GLOBALS["conn"]);
    return $videos;
}
function get_videos_by_uploader($uploaderID){
    $sql = "SELECT * FROM VIDEOS WHERE VIDEOS.USER_ID = '" . $uploaderID . "'";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $videos = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $videos[] = $row;
    }
    oci_close($GLOBALS["conn"]);
    return $videos;
}
function get_videos_by_category($categoryID){
    $sql = "SELECT * FROM VIDEOS WHERE VIDEOS.CATEGORY_ID = '" . $categoryID . "'";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $videos = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $videos[] = $row;
    }
    oci_close($GLOBALS["conn"]);
    return $videos;
}
function get_videos_by_uploader_and_category($uploaderID,$categoryID){
    $sql = "SELECT * FROM VIDEOS WHERE VIDEOS.USER_ID = '" . $uploaderID. "' AND VIDEOS.CATEGORY_ID = '" . $categoryID . "'";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $videos = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $videos[] = $row;
    }
    oci_close($GLOBALS["conn"]);
    return $videos;
}

function get_rec_video(){
    $sql = 'SELECT * FROM
            ( SELECT * FROM VIDEOS
            ORDER BY dbms_random.value )
            WHERE rownum = 1';
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $video = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $video[] = $row;
    }
    oci_close($GLOBALS["conn"]);
    return $video;
}

//delete video function
function delete_video($video_id){
    try{
        $sql = "DELETE FROM VIDEOS WHERE VIDEO_ID = $video_id";
        $result = oci_parse($GLOBALS["conn"], $sql);
        oci_execute($result);
        oci_close($GLOBALS["conn"]);
        $success = true;
    }catch (Exception $e){
        echo $e->getMessage();
        $success = false;
    }
}

function select_categories_to_table(){
    // "category" tábla lekérdezése
    $sql = 'SELECT * FROM CATEGORY';
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);

    // Tábla kiírása category_id, name
    echo "<table>";
    echo "<tr><th>Category ID</th><th>Category name</th></tr>";
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        echo "<tr>";
        echo "<td>" . $row['CATEGORY_ID'] . "</td>";
        echo "<td>" . $row['NAME'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function get_categories(){
    $sql = 'SELECT * FROM CATEGORY';
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    global $categories;
    $categories = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $categories[$row['CATEGORY_ID']] = $row['NAME'];
    }
    oci_close($GLOBALS["conn"]);
    return $categories;
}


function select_comments_to_table(){
    // "comment" tábla lekérdezése
    $sql = 'SELECT * FROM COMMENTS';
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);

    // Tábla kiírása comment_id, comments, user_id, video_id, created_at
    echo "<table>";
    echo "<tr><th>Comment ID</th><th>Comment</th><th>User ID</th><th>Video ID</th><th>Created at</th></tr>";
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        echo "<tr>";
        echo "<td>" . $row['COMMENT_ID'] . "</td>";
        echo "<td>" . $row['COMMENTS'] . "</td>";
        echo "<td>" . $row['USER_ID'] . "</td>";
        echo "<td>" . $row['VIDEO_ID'] . "</td>";
        echo "<td>" . $row['CREATED_AT'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function get_comment($comment_id){
    $sql = "SELECT * FROM COMMENTS WHERE COMMENT_ID = $comment_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $comment = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $comment[$row['COMMENT_ID']] = $row['COMMENTS'];
    }
    oci_close($GLOBALS["conn"]);
    return $comment;
}

function get_comments_by_video_id($video_id){
    $sql = "SELECT * FROM COMMENTS WHERE VIDEO_ID = $video_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $comments = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $comments[] = $row;
    }
    oci_close($GLOBALS["conn"]);
    return $comments;
}

function insert_comment($user_id, $video_id, $comment, $created_at){
    $success = false;

    try {
        $comment_id = get_next_id('COMMENTS', 'COMMENT_ID');
        $sql = "INSERT INTO COMMENTS (COMMENT_ID, COMMENTS, USER_ID, VIDEO_ID, CREATED_AT) VALUES ($comment_id, '$comment', $user_id, $video_id, '$created_at')" ;
        $result = oci_parse($GLOBALS["conn"], $sql);
        oci_execute($result);
        oci_close($GLOBALS["conn"]);
        $success = true;
    }catch (Exception $e){
        echo $e->getMessage();
    }
    return $success;
}

function delete_comment($comment_id){
    try{
        $sql = "DELETE FROM COMMENTS WHERE COMMENT_ID = $comment_id";
        $result = oci_parse($GLOBALS["conn"], $sql);
        oci_execute($result);
        oci_close($GLOBALS["conn"]);
        $success = true;
    }catch (Exception $e){
        echo $e->getMessage();
        $success = false;
    }
}

function update_comment($comment_id, $updated_comment){
    // current comment load by id
    $comment = get_comment($comment_id);
    $current_comment = $comment[$comment_id];
    // if comment is empty, set it to the current comment
    if (empty($updated_comment)) {
        $updated_comment = $current_comment;
    }
    $sql = "UPDATE COMMENTS SET COMMENTS = '$updated_comment' WHERE COMMENT_ID = $comment_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    oci_close($GLOBALS["conn"]);
}

function add_category($category_name){
    $success = false;

    try {
        $category_id = get_next_id('CATEGORY', 'CATEGORY_ID');
        $sql = "INSERT INTO CATEGORY (CATEGORY_ID, NAME) VALUES ($category_id, '$category_name')" ;
        $result = oci_parse($GLOBALS["conn"], $sql);
        oci_execute($result);
        oci_close($GLOBALS["conn"]);
        $success = true;
    }catch (Exception $e){
        echo $e->getMessage();
    }
    return $success;
}

function delete_category($category_id){
    try{
        $sql = "DELETE FROM CATEGORY WHERE CATEGORY_ID = $category_id";
        $result = oci_parse($GLOBALS["conn"], $sql);
        oci_execute($result);
        oci_close($GLOBALS["conn"]);
        $success = true;
    }catch (Exception $e){
        echo $e->getMessage();
        $success = false;
    }
}

function get_category_by_id($category_id){
    $sql = "SELECT * FROM CATEGORY WHERE CATEGORY_ID = $category_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS);
    return $row;
}
function get_category_id_by_name($category_name){
    $sql = "SELECT CATEGORY_ID FROM CATEGORY WHERE NAME = '$category_name'";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS);
    return $row;
}

function update_category($category_id, $name)
{
    // current category load by id
    $current_category = get_category_by_id($category_id);
    // if category name is empty, set it to the current name
    if (empty($name)) {
        $name = $current_category['NAME'];
    }
    $sql = "UPDATE CATEGORY SET NAME = '$name' WHERE CATEGORY_ID = $category_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    oci_close($GLOBALS["conn"]);
}

function get_categs(){
    $sql = "SELECT category.name, COUNT(*) AS video_count
            FROM category
            JOIN videos ON category.category_id = videos.category_id
            WHERE videos.video_id = video_id
            GROUP BY category.name
            ORDER BY video_count DESC";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $categories = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $categories[] = $row;
    }
    oci_close($GLOBALS["conn"]);
    return $categories;
}

function insert_playlist($playlist_name, $user_id){
    $success = false;

    try {
        $playlist_id = get_next_id('PLAYLIST', 'PLAYLIST_ID');
        $sql = "INSERT INTO PLAYLIST (PLAYLIST_ID, NAME, USER_ID) VALUES ($playlist_id, '$playlist_name', $user_id)" ;
        $result = oci_parse($GLOBALS["conn"], $sql);
        oci_execute($result);
        oci_close($GLOBALS["conn"]);
        $success = true;
    }catch (Exception $e){
        echo $e->getMessage();
    }
    return $success;
}

function delete_playlist($playlist_id){
    try{
        $sql = "DELETE FROM PLAYLIST WHERE PLAYLIST_ID = $playlist_id";
        $result = oci_parse($GLOBALS["conn"], $sql);
        oci_execute($result);
        oci_close($GLOBALS["conn"]);
        $success = true;
    }catch (Exception $e){
        echo $e->getMessage();
        $success = false;
    }
}

function get_playlist_by_id($playlist_id){
    $sql = "SELECT * FROM PLAYLIST WHERE PLAYLIST_ID = $playlist_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS);
    return $row;
}

function update_playlist($playlist_id, $name)
{
    // current playlist load by id
    $current_playlist = get_playlist_by_id($playlist_id);
    // if playlist name is empty, set it to the current name
    if (empty($name)) {
        $name = $current_playlist['NAME'];
    }
    $sql = "UPDATE PLAYLIST SET NAME = '$name' WHERE PLAYLIST_ID = $playlist_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    oci_close($GLOBALS["conn"]);
}

function get_playlists($user_id){
    $sql = "SELECT * FROM PLAYLIST WHERE USER_ID = $user_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $playlists = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $playlists[] = $row;
    }
    oci_close($GLOBALS["conn"]);
    return $playlists;
}

function add_video_to_playlist($video_id,$playlist_id){
    $success = false;

    try {
        $sql = "INSERT INTO PLAYLIST_VIDEO (PLAYLIST_ID, VIDEO_ID) VALUES ($playlist_id, $video_id)" ;
        $result = oci_parse($GLOBALS["conn"], $sql);
        oci_execute($result);
        oci_close($GLOBALS["conn"]);
        $success = true;
    }catch (Exception $e){
        echo $e->getMessage();
    }
    return $success;
}

function get_user_registration_date(){
    $sql = "SELECT * FROM USER_REGISTRATION_DATE";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    echo "<h3>Felhasználók regisztráció dátuma</h3>";
    echo "<table>";
    echo "<tr><th>Felhasználó ID</th><th>Felhasználónév</th><th>Regisztráció dátuma</th></tr>";
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $timestamp = strtotime($row['REGISTRATION_DATE']);
        $datum = date('Y-m-d', $timestamp);
        echo "<tr>";
        echo "<td>" . $row['USER_ID'] . "</td>";
        echo "<td>" . $row['USER_NAME'] . "</td>";
        echo "<td>" . $datum . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    oci_close($GLOBALS["conn"]);
}

function call_delete_old_users($szam){
    $sql = "BEGIN delete_old_users($szam); END;";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    oci_close($GLOBALS["conn"]);
}

function get_videos_from_playlist_video($playlist_id){
    $sql = "SELECT VIDEO_ID FROM PLAYLIST_VIDEO WHERE PLAYLIST_ID = $playlist_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $video_ids = array();
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $video_ids[] = $row;
    }
    oci_close($GLOBALS["conn"]);
    return $video_ids;
}

function get_video_by_id($video_id){
    $sql = "SELECT * FROM VIDEOS WHERE VIDEO_ID = $video_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    $row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS);
    return $row;
}

function delete_from_playlist_video($playlist_id, $video_id){
    $sql = "DELETE FROM PLAYLIST_VIDEO WHERE PLAYLIST_ID = $playlist_id AND VIDEO_ID = $video_id";
    $result = oci_parse($GLOBALS["conn"], $sql);
    oci_execute($result);
    oci_close($GLOBALS["conn"]);
    oci_close($GLOBALS["conn"]);
}

function call_ennyi_hasonlo_van($videoID){
    $sql = 'BEGIN :eredmeny := ennyi_hasonlo_van(:vidID); END;';
    $result = oci_parse($GLOBALS["conn"], $sql);

    oci_bind_by_name($result, ':vidID', $videoID);
    oci_bind_by_name($result, ':eredmeny', $eredmeny, 32);


    oci_execute($result);
    oci_close($GLOBALS["conn"]);

    return $eredmeny;
}