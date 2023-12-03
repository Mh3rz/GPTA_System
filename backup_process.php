<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "db_connection.php";

    // Columns to export
    $columns = array('lastname', 'firstname', 'm_i', 'type', 'grade_level', 'room', 'section', 'status', 'date_paid', 'active');

    // Fetch data from the "student_lists" table
    $query = "SELECT " . implode(", ", $columns) . " FROM student_lists";
    $result = mysqli_query($conn, $query);

    // Check if there is data to export
    if (mysqli_num_rows($result) > 0) {
        // Output the CSV to the browser
        $currentDate = date('Y-m-d');

        // Replace any characters that are not safe in a filename
        $currentDateForFilename = preg_replace("/[^a-zA-Z0-9]+/", "_", $currentDate);

        // Set the CSV header
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="student_lists_backup__SY_' . $currentDateForFilename . '.csv"');

        // Open a file handle for writing CSV data
        $output = fopen('php://output', 'w');

        // Custom column headers
        $customHeaders = array('Last_Name', 'First_Name', 'Middle_Initial', 'Type', 'Grade_Level', 'Room', 'Section', 'Status', 'Date_Paid', 'Active');

        // Output the custom headers
        fputcsv($output, $customHeaders);

        // Reset the result pointer to the beginning
        mysqli_data_seek($result, 0);

        // Fetch and output the data
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, $row);
        }

        // Close the file handle
        fclose($output);

        // Free the result set
        mysqli_free_result($result);
    } 

    // Close the database connection
    mysqli_close($conn);
}
?>
