<?php
    // Connect to the database
    include "db_connection.php";
    require_once("class.cryptor.php");
    $crypt = new Cryptor();

    // get the id and password from the form
    $id = $_POST['id'];
    $searchQuery =  $_POST['search'];
    $currentPage = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    // Retrieving name
    $query = "SELECT * FROM student_lists WHERE id= '$id'";
            // Execute the query
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    $sectionid = $row['section'];
    $newsectionid = $crypt->encryptId($sectionid);

    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $enteredPassword = isset($_POST['password']) ? $_POST['password'] : '';
    
        // Retrieve the hashed password from the database
        $storedHashedPassword = getStoredPassword();

        if ($storedHashedPassword && password_verify($enteredPassword, $storedHashedPassword)) {
            // Password is correct
            $sql = "UPDATE student_lists SET status = 0, date_paid = NULL WHERE id = '$id'";
            mysqli_query($conn, $sql);

            $stat = 'change';
            header("Location: specific_sections.php?id='".$newsectionid. "'&page=" . $currentPage ."&stat=".$stat."&message=" . $row['lastname'] . ", " . $row['firstname'] . ". Status changed back to <b>Not Paid</b>!");
            exit;
        } else {
            // Password is incorrect
            $stat = 'error';
            header("Location: specific_sections.php?id='".$newsectionid. "'&page=" . $currentPage ."&stat=".$stat."&message= Incorrect Password");
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
