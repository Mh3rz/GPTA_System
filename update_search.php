<?php

// Connect to the database
include "db_connection.php";
$searchQuery =  $_POST['search'];
$currentPage = isset($_POST['page']) ? (int)$_POST['page'] : 1;
if (isset($_POST['submited'])) {
    // Get the form data
    $id = $_POST['id'];
    $lastname = $_POST['Lastname'];
    $firstname = $_POST['Firstname'];
    $m_i = $_POST['Middle_Initial'];
    $combinedInfo = $_POST['combined_info'];

    // Split the combined_info value into an array
    $infoArray = explode(' - ', $combinedInfo);

    // Extract values
    $type = $infoArray[3];
    $grade_level = $infoArray[0];
    $room = $infoArray[2];
    $section = $infoArray[1];

    // Insert the data into the student_lists table
    $sql = "UPDATE student_lists SET `lastname`='$lastname',`firstname`='$firstname',`m_i`=' $m_i',`type`='$type',`grade_level`='$grade_level',`room`='$room',`section`='$section' WHERE `id`='$id'";

    // Retrieving name
    $query = "SELECT * FROM student_lists WHERE id = '$id'";
    // Execute the query
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    if (mysqli_query($conn, $sql)) {
        $stat = 'updated';
        header("Location: search.php?&search=". $searchQuery. "&page=" . $currentPage ."&stat=".$stat."&message=". $row['lastname'] .", ".$row['firstname']." ". $row['m_i'].", Records has been <b>Updated</b>");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>



  