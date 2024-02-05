<?php
include_once "database_connection.php";
include_once "database_functions.php";
include_once "website_functions.php";
session_start();
redirect_if_not_logged_in();
$succes = false;

if(isset($_POST['title']) && isset($_POST['description']) && isset($_POST['category']) && isset($_POST['url'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $url = $_POST['url'];
    $date = date("Y-m-d H:i:s");
    $user_id = $_SESSION['user_id'];
    if (insert_video($user_id, $title, $description, $category, $url, $date)){
        $succes = true;
    }
    else{
        $succes = false;
    }
}

?>

<html>
<head>
    <link rel=stylesheet type="text/css" href="mystyle.css" />
    <meta charset="UTF-16" />
    <Title>Videók feltöltése</Title>
</head>

<div class="topright"">
    <a href="index.php">Vissza a főoldalra</a>
</div>



<body>
    <div>

        <div>
            <h1>Videók feltöltése</h1>
        </div>

        <div>
            <form name="insert_video" action="videok_feltoltese.php" method="post">

                <label for="title">Cím:</label>
                <input type="text" name="title" id="title" required>


                <label for="description">Leírás:</label>
                <input type="text" name="description" id="description" required>


                <label for="category">Kategória:</label>
                <select name="category" id="category">
                    <?php
                    $categories = get_categories();
                    foreach ($categories as $key => $value){
                        echo "<option value='" . $key . "'>" . $value . "</option>";
                    }
                    ?>
                </select>

                <label for="url">URL:</label>
                <input type="text" name="url" id="url" required>

                <input type="submit" value="Hozzáadás">
            </form>
        </div>
    </div>

    <?php
    // if the form was submitted
    if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['category']) && isset($_POST['url'])){
        if ($succes){
            echo "<script>
            alert('Sikeres feltöltés!');
                </script>";
        }else{
            echo "<script>
            alert('Sikertelen feltöltés!');
                </script>";
        }
    }
    ?>

</body>
</html>
