
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>GPTA</title>
        <link rel="stylesheet" href="css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <link rel="shortcut icon" href="assets/log_icon.png" type="img/x-icon" />
        <style>
        .paper{
            font-family: 'Times New Roman', Times, serif;
            display: inline-block;   
            text-align: center; 
            padding: 20px; 
            overflow-x: auto;     
        }
        .container {
            font-family: 'Times New Roman', Times, serif;
            display: flex;
            justify-content: center;
            background-color: white;
            padding: auto;
            margin-left: auto;
            margin-right: auto;
            margin-top: 10px;
            border-radius: 15px;
            max-width: 900px;
            margin-bottom: 10px;
            
            
        }
        /* table */
        .t {
            font-family: 'Times New Roman', Times, serif;
            width: 700px;
            text-align: center;
            font-size: 12px;
        }

        table {
            font-family: 'Times New Roman', Times, serif;
            text-align: center;
            font-size: 15px;
        }

        td img {
            width: 100px;
        }

        td h4 {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            text-align: center;
            font-size: 20px;
        }
        h3{
            font-family: 'Times New Roman', Times, serif;
        }

        td p {
            font-family: 'Times New Roman', Times, serif;
            text-align: center;
            margin-top: 0;
            margin-bottom: 0;
            font-size: 15px;
        }

        p + h4 {
            font-family: 'Times New Roman', Times, serif;
            margin-top: 0;
        }

        /* table for the report */
        .t {
            font-family: 'Times New Roman', Times, serif;
            border-left: 0.01em solid black;
            border-right: 0;
            border-top: 0.01em solid black;
            border-bottom: 0;
            border-collapse: collapse;
        }
        .t td,
        .t th {
            font-family: 'Times New Roman', Times, serif;
            border-left: 0;
            border-right: 0.01em solid black;
            border-top: 0;
            border-bottom: 0.01em solid black;
        }
        td{
            font-family: 'Times New Roman', Times, serif;
        }
        .t th {
            font-family: 'Times New Roman', Times, serif;
            text-align: left;
        }

        </style>
	</head>

	<body style="background-image: url('images/gpta_watermarks.png'); ">
        <?php 
        include("db_connection.php");
        include("sidebar.php") 
        ?>
        <section class="home-section">
            <div class="home-content">
            
            <nav class="navbar navbar-expand-lg navbar-light bg-light w-100">
                <div class="container-fluid">
                <i class='bx bx-menu'></i>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        
                    <ul class="navbar-nav ms-auto mt-2 mt-lg-0 justify-content-center justify-content-sm-start">
                        <br>
                        <li class="nav-item ml-2 ">
                            <a class="btn btn-danger text-white" style="margin-right: 7px" href="print_report.php">
                                <i class="bi bi-file-earmark-pdf pe-2"></i>PDF
                            </a>
                        </li>
                        <br>
                        <li class="nav-item ml-2 mb-2">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal_update"><i class="bi bi-pencil-square me-2"></i>Edit</button>
                        </li>
                    </ul>
                    </div>
                </div>
            </nav> <!-- end of top Nav -->
            </div>
            <?php
                $query = "SELECT * FROM report_data";
                $result = mysqli_query($conn, $query);
                
                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                
                    if ($row) {
                        // Now you can access the data using array keys
                        $prepared_by = $row['prepared_by'];
                        $reported_by = $row['reported_by'];   
                    }
                    
                }

                // Fetch sections from the database
                $query = "SELECT DISTINCT section FROM student_lists";
                $result = $conn->query($query);

                // Initialize counts array with sections from the database
                $counts = array();
                while ($row = $result->fetch_assoc()) {
                    $section = $row['section'];
                    $counts[$section] = 0;
                }

                // Fetch other required data from the database
                $query = "SELECT amount FROM fee WHERE id = 1";
                $result = $conn->query($query);

                $amount = 0; // Initialize the variable

                if ($result->num_rows > 0) {
                    // Output data of the first row
                    $row = $result->fetch_assoc();
                    $amount = $row["amount"];
                }

                // Loop through the sections
                foreach ($counts as $section => $count) {
                    // Execute a query to get the count of paid students in this section
                    $query = "SELECT COUNT(*) as count FROM student_lists WHERE section = '$section' AND status = 1 AND active = 0";
                    $result = $conn->query($query);
                    $row = $result->fetch_assoc();

                    // Update the count for this section
                    $counts[$section] = $row['count'];
                }

                // Set up an array to store the total fees for each section
                $totals = array();

                // Loop through the sections again
                foreach ($counts as $section => $count) {
                    // Calculate the total fees for this section
                    $total = $count * $amount;

                    // Store the total fees for this section in the array
                    $totals[$section] = $total;
                }

                // Set up an array to store the total fees for each grade level
                $grade_totals = array('7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0);

                // Loop through the sections again
                foreach ($totals as $section => $total) {
                    // Execute a query to get the grade level for this section
                    $query = "SELECT grade_level FROM student_lists WHERE section = '$section' LIMIT 1";
                    $result = $conn->query($query);
                    $row = $result->fetch_assoc();

                    // Update the total fees for this grade level
                    $grade_totals[$row['grade_level']] += $total;
                }

                // Initialize the grand total to 0
                $grand_total = 0;

                // Print out the totals for each grade level
                foreach ($grade_totals as $grade_level => $total) {
                    $grand_total += $total;
                }
                // Print out the grand total
                $formatted_grand_total = number_format($grand_total, 2);
            ?>
            <div class="container">
                <div class="paper">
                    <!-- for the header of page -->
                    <table class="header" style='margin-top:2%; margin-left: auto; margin-right: auto; min-width: 800px;'>
                        <tr>
                            <td><img style="width: 58px;" src="images/blank.png" alt=""></td>
                            <td><img src="images/logo.png" alt=""></td>
                            <td style="min-width: 300px;">
                                <h4>EASTERN MINDORO COLLEGE</h4>
                                <p>Bagong Bayan II, Bongabong Oriental Mindoro</p>
                                <h4>GENERAL PTA</h4>
                            </td>
                            <td><img src="images/blank.png" alt=""></td>
                            <td><img style="width: 58px;" src="images/blank.png" alt=""></td>
                        </tr>
                    </table>

                    <div style='margin-left: auto; margin-right: auto; min-width: 800px;'>
                        <h3> Collection Report</h3>
                    </div>

                    <?php
                    echo "<table style='margin-top:2%; margin-left: auto; margin-right: auto;' class='t'>";
                    echo "<thead style='background-color: #D3D3D3;'>";
                    echo "<th style='text-align:center; width:180px;' colspan='2'>Sections per grade level</th>";
                    echo "<th style='text-align:center; width:150px;'>Amount per section</td>";
                    echo "<th style='text-align:center; width:150px;'>Amount per grade level</td>";
                    echo "</thead>";
                    // Print out the totals for each grade level
                    foreach ($grade_totals as $grade_level => $total) {
                        echo "<tr>";
                        echo "<th scope='row' colspan='2'>Grade $grade_level</th>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "</tr>";
                        // Loop through the sections
                        foreach ($totals as $section => $section_total) {
                            // Execute a query to get the grade level for this section
                            $query = "SELECT grade_level FROM student_lists WHERE section = '$section' LIMIT 1";
                            $result = $conn->query($query);
                            $row = $result->fetch_assoc();
                            // Check if the grade level for this section matches the current grade level in the outer loop
                            if ($row['grade_level'] == $grade_level) {
                                echo "<tr>";
                                echo "<th style='width:20px;'></th>";
                                echo "<td>$section</td>";
                                echo "<td><img style='width:10px;' src='images/peso.png'/>" . number_format($section_total, 2) . "</td>";
                                echo "<td></td>";
                                echo "</tr>";
                            }
                        }
                        echo "<tr>";
                        echo "<th scope='row' colspan='2'></th>";
                        echo "<td  style='text-align:right; font-family: 'Times New Roman', Times, serif;'><b>Sub Total:</b></td>";
                        echo "<td><img style='width:10px;' src='images/peso.png'/>" . number_format($total, 2) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                    <tr class="total">
                        <th scope="row" colspan="3"></th>
                        <td style="color: white;">...</td>
                    </tr>
                    <tr style='background-color: #D3D3D3;' class="total">
                        <th scope="row" colspan="3"> OVERALL TOTAL COLLECTED CONTRIBUTION</th>
                        <td><img style='width:10px;' src='images/peso.png'/><?php echo $formatted_grand_total ?></td>
                    </tr>
                    <?php
                    echo "</table>";
                    ?>
                    <table class="officer" style="margin-top: 12px; margin-left: auto; margin-right: auto; min-width: 800px;">
                        <tr>
                            <td colspan="2" style="text-align:left;">Prepared by:</td>
                            <td style="width: 250px;"></td>
                            <td colspan="2" style="text-align:left;">Reported by:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="height: 18px;"></td>
                        </tr>
                        <tr>
                            <td style="width: 20px;"></td>
                            <td style="text-align: left;"><?php echo $prepared_by ?></td>
                            <td style="width: 20px;"></td>
                            <td style="width: 20px;"></td>
                            <td style="text-align:left;"><?php echo $reported_by ?></td>
                        </tr>
                    </table>
                    <table class="check" style="margin-left: auto; margin-right: auto; min-width: 800px;">
                        <tr>
                            <td colspan="2" style="text-align:left;">Checked by:</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width: 50px;"></td>
                            <td>____________________</td>
                            <td>____________________</td>
                            <td>____________________</td>
                            <td style="width: 50px;"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>

        <div class="modal fade" id="myModal_update" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Update Proponents</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="collection_record_update.php" method="POST" id="updateForm">
                        
                            <div class="">
                                <label for="prepared_by">Prepared by:</label>
                                <input type="text" class="form-control" id="prepared_by" name="prepared_by" value="<?php echo $prepared_by; ?>">
                            </div>
                            <div class="">
                                <label for="reported_by">Reported by:</label>
                                <input type="text" class="form-control" id="reported_by" name="reported_by" value="<?php echo $reported_by; ?>">
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" id="submit-button" class="btn btn-primary" name="submited">Confirm</button>
                    
                    </form>
                </div>
                </div>
            </div>
        </div>
        <!-- Modal Update -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('updateForm'); // Replace 'updateForm' with your actual form ID

                form.addEventListener('submit', function (event) {
                    // Convert input values to uppercase
                    document.getElementById('prepared_by').value = document.getElementById('prepared_by').value.toUpperCase();
                    document.getElementById('reported_by').value = document.getElementById('reported_by').value.toUpperCase();

                    // Validate the form before submission
                    if (!validateForm()) {
                        event.preventDefault(); // Prevent the form from being submitted
                    }
                });

                function validateForm() {
                    const preparedByInput = document.getElementById('prepared_by');
                    const reportedByInput = document.getElementById('reported_by');

                    if (!isValidText(preparedByInput.value)) {
                        alert('Invalid characters in Prepared by. Only letters, spaces, and a period are allowed.');
                        return false;
                    }

                    if (!isValidText(reportedByInput.value)) {
                        alert('Invalid characters in Reported by. Only letters, spaces, and a period are allowed.');
                        return false;
                    }

                    return true;
                }

                function isValidText(text) {
                    // Regular expression to allow only letters (uppercase and lowercase), spaces, and a period
                    const textRegex = /^[A-Za-z.\s]+$/;
                    return textRegex.test(text);
                }
            });
        </script>


    <script>
        let arrow = document.querySelectorAll(".arrow");
        for (var i = 0; i < arrow.length; i++) {
            arrow[i].addEventListener("click", (e)=>{
        let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
        arrowParent.classList.toggle("showMenu");
            });
        }
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".bx-menu");
        console.log(sidebarBtn);
        sidebarBtn.addEventListener("click", ()=>{
            sidebar.classList.toggle("close");
        });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
        
	</body>
</html>
