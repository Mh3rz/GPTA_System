<?php
    // Connect to the database
    include "db_connection.php";


    // get the id and password from the form
    $id = $_POST['id'];
    $searchQuery =  $_POST['search'];
    $currentPage = isset($_POST['page']) ? (int)$_POST['page'] : 1;

    // Retrieving name
    $query = "SELECT * FROM student_lists WHERE id = '$id'";
    // Execute the query
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $enteredPassword = isset($_POST['password']) ? $_POST['password'] : '';
    
        // Retrieve the hashed password from the database
        $storedHashedPassword = getStoredPassword();

        if ($storedHashedPassword && password_verify($enteredPassword, $storedHashedPassword)) {
            // Password is correct
            $sql = "DELETE FROM student_lists WHERE id = '$id'";
            mysqli_query($conn, $sql);
            $stat = "delete";
            header("Location: restore.php?search=".$searchQuery. "&stat=".$stat."&page=" . $currentPage ."&message=". $row['lastname'] .", ".$row['firstname']." ". $row['m_i'].", Records has been <b>Deleted</b>.");
        } else {
            // password is incorrect
            $stat = 'error';
            header("Location: restore.php?search=".$searchQuery. "&page=" . $currentPage ."&stat=".$stat."&message= Incorrect Password.");
        }
    }
    function getStoredPassword() {
        include "db_connection.php";

        $query = "SELECT pass FROM password";
        $result = mysqli_query($conn, $query);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            mysqli_close($conn);
            return $row['pass'];
        } else {
            mysqli_close($conn);
            return false;
        }
    }
?>



