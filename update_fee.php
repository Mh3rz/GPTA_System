<?php
include("db_connection.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve new amount from the form
    $newAmount = $_POST["newAmount"];

    // Update the 'fee' table with the new amount
    $updateSql = "UPDATE fee SET amount = $newAmount WHERE id = 1"; // Assuming you have a row with id=1
    if ($conn->query($updateSql) === TRUE) {
        header("Location: dashboard.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>