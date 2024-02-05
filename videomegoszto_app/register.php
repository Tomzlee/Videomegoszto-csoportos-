<?php
include_once "database_connection.php";
include_once "database_functions.php";
include_once "website_functions.php";
session_start();
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password2']) && isset($_POST['email'])){
    $errors = array();

    if(user_exists($_POST['username'])){
        $errors[] = "A felhasználónév már foglalt!";
    }

    if ($_POST['password'] != $_POST['password2']){
        $errors[] = "A két jelszó nem egyezik!";
    }

    if (count($errors) > 0) {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }else{
        $user_id = insert_user($_POST['username'],$_POST['email'], $_POST['password']);
        if ($user_id != -1){
            $_SESSION['user_id'] = $user_id;
            header("Location: index.php");
        }
    }
}
?>
<html>
<head>
    <link rel=stylesheet type="text/css" href="mystyle.css" />
    <meta charset="UTF-16" />
    <Title>Regisztráció</Title>
</head>
<body>
    <a href="index.php" class="topright">Vissza a főoldalra</a>
    <div class="container">
        <div class="header">
            <h1>Regisztráció</h1>
        </div>
        <div class="content">
            <form action="register.php" method="post">
                <label for="username">Felhasználónév:</label>
                <input type="text" name="username" id="username" required>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                <label for="password">Jelszó:</label>
                <input type="password" name="password" id="password" required>
                <label for="password2">Jelszó megerősítése:</label>
                <input type="password" name="password2" id="password2" required>
                <input type="submit" value="Regisztráció">
            </form>
        </div>
    </div>
</body>
</html>
