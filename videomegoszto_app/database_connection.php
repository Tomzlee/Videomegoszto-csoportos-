<?php
include_once "privat_jelszavak.php";

$servername = "localhost"; // módosítandó
$username = $GLOBALS['database_username'];
$password = $GLOBALS['database_password'];
$dbname = "orcl"; // módosítandó
$charset = "AL32UTF8";


global $conn;
$conn = oci_connect($username, $password, $servername . '/' . $dbname, $charset);