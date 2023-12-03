<?php
if (isset($_POST["submit"])) {
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $file = $_FILES["file"]["tmp_name"];
        
        // Check if the file has a CSV extension
        $fileInfo = pathinfo($_FILES["file"]["name"]);
        $fileExtension = strtolower($fileInfo['extension']);
        if ($fileExtension != 'csv') {
            $error_message = "Please upload a valid CSV file.";
        } else {
            if (($handle = fopen($file, "r")) !== false) {
                include("db_connection.php");
                // Flag to skip the first row (header)
                $isFirstRow = true;

                // Define expected header columns
                $expectedColumns = array("Last_Name", "First_Name", "Middle_Initial", "Type", "Grade_Level", "Room", "Section", "Status", "Date_Paid", "Active");

                $header = fgetcsv($handle, 1000, ",");
                if ($header !== false && count($header) === count($expectedColumns)) {
                    // Remove BOM character from the first column name, if present
                    $header[0] = trim($header[0], "\xEF\xBB\xBF");
                    // Trim spaces from both expected and actual headers
                    $expectedColumnsTrimmed = array_map('trim', $expectedColumns);
                    $headerTrimmed = array_map('trim', $header);

                    // Convert both expected and actual headers to lowercase for case-insensitive comparison
                    $expectedColumnsLower = array_map('strtolower', $expectedColumnsTrimmed);
                    $headerLower = array_map('strtolower', $headerTrimmed);
                
                    $columnDifferences = array_diff($headerLower, $expectedColumnsLower);

                    if (!empty($columnDifferences)) {
                        $error_message = "Invalid CSV file. Please make sure the header columns match the expected format.";

                        // Print the differences for debugging
                        echo "Differences in header columns: ";
                        print_r($columnDifferences);
                    } else {
                        // Loop through the CSV file and insert data into the database
                        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                            // Skip the first row (header)
                            if ($isFirstRow) {
                                $isFirstRow = false;
                                continue;
                            }

                            $lastName = mysqli_real_escape_string($conn, $data[0]); // Last Name
                            $firstName = mysqli_real_escape_string($conn, $data[1]); // First Name
                            $middleInitial = mysqli_real_escape_string($conn, $data[2]); // M.I
                            $type = mysqli_real_escape_string($conn, $data[3]); // Type
                            $gradeLevel = mysqli_real_escape_string($conn, $data[4]); // Grade Level
                            $room = mysqli_real_escape_string($conn, $data[5]); // Room
                            $section = mysqli_real_escape_string($conn, $data[6]); // Section
                            $status = mysqli_real_escape_string($conn, $data[7]); // Status
                            $date_paid = mysqli_real_escape_string($conn, $data[8]); // Date_Paid
                            $active = mysqli_real_escape_string($conn, $data[9]); // Active
            
            
                            // Insert the data into the database
                            $sql = "INSERT INTO student_lists (lastname, firstname, m_i, type, grade_level, room, section, status, date_paid, active) 
                                    VALUES ('$lastName', '$firstName', '$middleInitial', '$type', '$gradeLevel', '$room', '$section', '$status', '$date_paid', '$active')";
            
                            if (mysqli_query($conn, $sql)) {
                                // Data inserted successfully
                            } else {
                                $error_message = "Error: " . $sql . "<br>" . mysqli_error($conn);
                            }

                        }

                        fclose($handle);
                        mysqli_close($conn);

                        $success_message = "CSV file uploaded and data saved to the database.";
                    }
                } else {
                    $error_message = "Invalid CSV file. Please make sure the header is present and contains the expected columns.";
                    // Print the differences for debugging
                    echo "Differences in header columns: ";
                    print_r($columnDifferences);
                }
            } else {
                $error_message = "Unable to open CSV file.";
            }
        }
    } else {
        $error_message = "Please select a valid CSV file.";
    }

    // Redirect back to database.php with success or error message
    if (isset($success_message)) {
        header("Location: database.php?success=" . urlencode($success_message));
    } elseif (isset($error_message)) {
        header("Location: database.php?error=" . urlencode($error_message));
    }
}
?>
