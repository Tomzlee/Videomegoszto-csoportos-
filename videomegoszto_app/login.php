<?php
include_once "database_connection.php";
include_once "database_functions.php";
include_once "website_functions.php";
session_start();
if (isset($_POST['username']) && isset($_POST['password'])){
    $user_id = login_user($_POST['username'], $_POST['password']);
    if ($user_id != -1){
        $_SESSION['user_id'] = $user_id;
        header("Location: index.php");
    }
    else{
        echo "Hibás felhasználónév vagy jelszó!";
    }
}
?>
<html>
<head>
    <link rel=stylesheet type="text/css" href="mystyle.css" />
    <meta charset="UTF-16" />
    <Title>Bejelentkezés</Title>
</head>
<body>
    <a href="index.php" class="topright">Vissza a főoldalra</a>
    <div class="container">
        <div class="header">
            <h1>Bejelentkezés</h1>
        </div>
        <div class="content">
            <form action="login.php" method="post">
                <label for="username">Felhasználónév:</label>
                <input type="text" name="username" id="username" required>
                <label for="password">Jelszó:</label>
                <input type="password" name="password" id="password" required>
                <input type="submit" value="Bejelentkezés">
            </form>
        </div>
    </div>
</body>
</html>
