<?php
if (session_id() == '') {
    session_start();
}
$host = "localhost";
$user = "root";
$db_password = "";
$dbname = "gpta";
$conn = mysqli_connect($host, $user, $db_password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>