<?php
// Connect to the database
include "db_connection.php";
$searchQuery =  $_POST['search'];
$currentPage = isset($_POST['page']) ? (int)$_POST['page'] : 1;
if (isset($_POST['submitted'])) {
    // Get the form data
    
    $id = $_POST['id'];
    $active = 1;


    // update record to active 1 (deactivated)
    $sql = "UPDATE student_lists SET `active`= '$active' WHERE `id`='$id'";


    // Retrieving name
    $query = "SELECT * FROM student_lists WHERE id = '$id'";
    // Execute the query
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    if (mysqli_query($conn, $sql)) {
        $stat = 'deactivated';
        header("Location: search.php?search=".$searchQuery. "&page=" . $currentPage ."&stat=".$stat."&message=". $row['lastname'] .", ".$row['firstname']." ". $row['m_i'].", Records has been <b>Deactivated</b>");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
