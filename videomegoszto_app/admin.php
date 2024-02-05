<?php
include_once "database_connection.php";
include_once "database_functions.php";
include_once "website_functions.php";
session_start();
$video_delete_success = false;
redirect_if_not_logged_in();
redirect_if_not_admin();

// Video törlése ID alapján
if (isset($_POST['delete_video'])) {
    $video_id = $_POST['video_id'];
    delete_video($video_id);
}

// Felhasználó törlése ID alapján
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    delete_user($user_id);
}

// Új kategória hozzáadása
if (isset($_POST['new_category'])) {
    $category_name = $_POST['category_name'];
    add_category($category_name);
}

// Kategória törlése ID alapján
if (isset($_POST['delete_category'])) {
    $category_id = $_POST['category_id'];
    delete_category($category_id);
}

// Kategória frissítése ID alapján
if (isset($_POST['update_category'])) {
    $category_id = $_POST['update_id'];
    $name = $_POST['update_name'];
    update_category($category_id, $name);
}

if (isset($_POST['old_registers'])) {
$old_registers = $_POST['old_number'];
call_delete_old_users($old_registers);
}

?>

<html>
<head>
    <link rel=stylesheet type="text/css" href="mystyle.css" />
    <meta charset="UTF-16" />
    <Title>Admin felület</Title>
</head>
<body>
<div class="topright">
    <a href="index.php">Vissza a főoldalra</a>
</div>
<h1>Admin felület</h1>

<h3>Videók törlése</h3>
<form action="admin.php" method="post">
    <select name="video_id">
        <?php
        $videos = get_videos();
        foreach ($videos as $video){
            echo "<option value='" . $video['VIDEO_ID'] . "'>" . $video['TITLE'] . "</option>";
        }
        ?>
    </select>
    <input type="submit" name="delete_video" value="Videó törlése">
</form>

<?php
if (isset($_POST['video_id'])){
    echo "<script>
    alert('A videó törlése sikeres volt!');
</script>";
}
?>

<h3>Felhasználók törlése</h3>
<form action="admin.php" method="post">
    <select name="user_id">
        <?php
        $users = get_users();
        foreach ($users as $user){
            echo "<option value='" . $user['USER_ID'] . "'>" . $user['USERNAME'] . "</option>";
        }
        ?>
    </select>
    <input type="submit" name="delete_user" value="Felhasználó törlése">
</form>

<?php
if (isset($_POST['user_id'])){
    echo "<script>
    alert('A felhasználó törlése sikeres volt!');
</script>";
}
?>

<h3>Új kategória hozzáadása</h3>
<form action="admin.php" method="post">
    <input type="text" name="category_name" placeholder="Kategória neve">
    <input type="submit" name="new_category" value="Kategória hozzáadása">
</form>

<?php
if (isset($_POST['category_name'])){
    echo "<script>
    alert('A kategória hozzáadása sikeres volt!');
</script>";
}
?>

<h3>Kategóriák törlése</h3>
<form action="admin.php" method="post">
    <select name="category_id">
        <?php
        $categories = get_categories();
        foreach ($categories as $key => $value){
            echo "<option value='" . $key . "'>" . $value . "</option>";
        }
        ?>
    </select>
    <input type="submit" name="delete_category" value="Kategória törlése">
</form>

<?php
if (isset($_POST['category_id'])){
    echo "<script>
    alert('A kategória törlése sikeres volt!');
</script>";
}
?>

<h3>Kategóriák frissítése</h3>
<form action="admin.php" method="post">
    <select name="update_id">
        <?php
        $categories = get_categories();
        foreach ($categories as $key => $value){
            echo "<option value='" . $key . "'>" . $value . "</option>";
        }
        ?>
    </select>
    <input type="text" name="update_name" placeholder="Kategória új neve">
    <input type="submit" name="update_category" value="Kategória frissítése">
</form>

<?php
if (isset($_POST['update_id'])){
    echo "<script>
    alert('A kategória frissítése sikeres volt!');
</script>";
}
?>

<?php
get_user_registration_date();
?>
<h4>Adott napnál korábban regisztráltak törlése a segédtáblából</h4>
<form action="admin.php" method="post">
    <input type="number" name="old_number">
    <input type="submit" name="old_registers" value="Törlés">
</form>

</body>
</html>