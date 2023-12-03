<?php
// Connect to the database
include "db_connection.php";

if (isset($_POST['submited'])) {
    // Get the form data
    $prepared_by = $_POST['prepared_by'];
    $reported_by = $_POST['reported_by'];

    // Update the record in the report_data table
    $update_sql = "UPDATE report_data SET `prepared_by`='$prepared_by', `reported_by`='$reported_by'";

    // Execute the update query
    if (mysqli_query($conn, $update_sql)) {
        // Successful update, redirect or set a success message as needed
        header("Location: collection_report.php");
    } else {
        // Error handling
        echo "Error updating record: " . mysqli_error($conn);
    }
}

?>