<?php
include "db_connection.php";

// Truncate the student_lists table
$sql = "TRUNCATE TABLE student_lists";
if (mysqli_query($conn, $sql)) {
    $success_message = "Data has been removed successfully";
} else {
    $error_message = "Data did not get processed properly." . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);

// Redirect back to test.php with success or error message
if (isset($success_message)) {
    header("Location: database.php?success=" . urlencode($success_message));
} elseif (isset($error_message)) {
    header("Location: database.php?error=" . urlencode($error_message));
}
?>