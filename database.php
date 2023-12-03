<?php
include("db_connection.php");
include("security_login.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="shortcut icon" href="assets/log_icon.png" type="img/x-icon" />
    <title>Data Management</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #3498db;
            margin: 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            max-width: 450px;
            margin: 0 auto;
            padding: 40px;
            border-radius: 5px;
            background-color: #ffffff;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        h2 {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: #3498db;
        }

        .mb-3 {
            width: 100%;
            padding: 20px;
            font-size: 16px;
            border-radius: 5px;
            background-color: #fff;
            border: 2px dashed #3498db;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        label {
            font-size: 16px;
            font-weight: normal;
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        .btn-primary {
            width: 100%;
            margin: 0;
            padding: 15px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-back {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1;
        }

        .message {
            font-size: 16px;
            margin-bottom: 20px;
            text-align: center;
        }

        .message.success {
            color: #4caf50;
        }

        .message.error {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <a href="dashboard.php" class="btn btn-secondary btn-back">Back</a>
    <div class="container">
        <?php
        if (session_id() == '') {
            session_start();
        }
        // Display success message
        if (isset($_GET['success'])) {
            $success_message = urldecode($_GET['success']);
            echo '<p class="message success">' . $success_message . '</p>';
        }

        // Display error message
        if (isset($_GET['error'])) {
            $error_message = urldecode($_GET['error']);
            echo '<p class="message error">' . $error_message . '</p>';
        }
        ?>

        <?php
        

        // Check if the student_lists table has data
        $result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM student_lists");
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];

        // Close the database connection
        mysqli_close($conn);

        // Check if there is data in the table
        if ($count > 0) {
            // Data exists, so show the Backup and Truncate form
            ?>
            <h2>Data Management</h2>
            <form action="backup_process.php" method="post">
                <button type="submit" class="btn-primary">Download Backup</button>
            </form>

            <br>

            <!-- Display text reminder to backup data first -->
            <p style="color: #555; margin-top: 10px; text-align: center; font-size: 16px;">
                <strong style="color: #e74c3c;">Important:</strong> It's recommended to download a backup of your data before proceeding to <b>Removing all Record</b>.
            </p>

            <!-- Add Truncate form and logic here -->
            <form action="truncate_process.php" method="post">
                <button type="submit" class="btn-primary" onclick="return confirm('Are you sure you want to truncate the table?');">Empty the Student Record</button>
            </form>
        <?php
        } else {
            // No data exists, so show the CSV upload form
            ?>
            <h2>Upload a CSV File</h2>
            <form action="csv.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="file">Select CSV file:</label>
                    <input type="file" id="file" name="file">
                </div>

                <input type="submit" name="submit" value="Upload" class="btn-primary">
            </form>
            <!-- Show sample CSV and description -->
            <div style="margin-top: 25px; text-align: center;">
                <h3>Sample CSV File</h3>
                <p>This is a sample CSV file that will guide you on how to format your CSV.</p>
                <a href="files/Sample_CSV_Format.csv" download>Download Sample CSV</a>
            </div>
        <?php
        }
        ?>
    </div>
</body>
</html>
