<?php
include("db_connection.php");
include("security_login.php");
?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Content-type" value="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>GPTA</title>
        <link rel="stylesheet" href="css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <link rel="shortcut icon" href="assets/log_icon.png" type="img/x-icon" />

        <style>
            .table-wrapper {
            overflow-x: auto;
            max-width: 100%; /* Set a maximum width if necessary */
            padding: 20px;
            }

        </style>
	</head>

	<body style="background-image: url('images/gpta_watermarks.png'); ">
        <?php 
        include("sidebar.php"); 
        ?>
        <section class="home-section">
            <div class="home-content">
            
            <?php
            include("navbar.php");
            ?>
            </div>
            
            <div class="container mt-1">
                <div class="table-wrapper">
                <?php
                    require_once("class.cryptor.php");
                    $crypt = new Cryptor();
                    
                    if (isset($_GET["id"])) {
                        // Get the ID from the URL (id pass is the section name)
                        $id = $_GET["id"];
                        
                        $decryptedid = $crypt->decryptId($id);
                        
                        
                        // Define the query
                        $title = "SELECT * FROM sections WHERE section = '$decryptedid'";
                        // Execute the query
                        $for_title = mysqli_query($conn, $title);
                    }
                    $for_header = mysqli_fetch_assoc($for_title);
                    $sectionid = $for_header["section"];
                    $newsectionid = $crypt->encryptId($sectionid);
                    // message
					$message = "";
					if (isset($_GET['message'])) {
						$message = $_GET['message'];
					}
                    $stat = isset($_GET['stat']) ? $_GET['stat'] : '';
					if (!empty($message)) {
						if ($stat == 'change'){
                            echo "<div class='alert alert-dark'><i class='bi bi-check-circle-fill '></i>&nbsp; $message</div>";
                        } elseif ($stat == 'error'){
                            echo "<div class='alert alert-danger'><i class=''></i>&nbsp; $message</div>";
                        } elseif ($stat == 'paid_by'){
                            echo "<div class='alert alert-primary'><i class='bi bi-check-circle-fill '></i>&nbsp; $message</div>";
                        } elseif ($stat == 'payee'){
                            echo "<div class='alert alert-success'><i class='bi bi-check-circle-fill '></i>&nbsp; $message</div>";
                        } elseif ($stat == 'added'){
							echo "<div class='alert alert-primary'><i class='bi bi-check-circle-fill '></i>&nbsp; $message</div>";
						}
					}
            
                    // Print header
                    echo "<h1 style='display: inline;'>Grade " .
                        $for_header["grade_level"] .
                        " Room " .
                        $for_header["room"] .
                        "-" .
                        $for_header["section"] .
                        "</h1> <a type='button' style='float: right; display: inline;' class='btn btn-danger' href='print_record.php?id=" . $newsectionid . "'><i class='bi bi-file-earmark-pdf pe-2'></i><strong>PDF</strong></a>";

                        // Define the number of data per page
                        $dataPerPage = 7;

                        // Get the current page number from the query string
                        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

                        // Calculate the starting index for the current page
                        $startIndex = ($currentPage - 1) * $dataPerPage;

                        // Modify your SQL query to include pagination logic
                        $sql = "SELECT * FROM student_lists WHERE section = '$decryptedid' AND active = 0 ORDER BY lastname";
                        // Execute the query
                        $result = mysqli_query($conn, $sql);

                        // Calculate the total number of rows
                        $totalRows = mysqli_num_rows($result);

                        // Calculate the total number of pages
                        $totalPages = ceil($totalRows / $dataPerPage);

                        // Modify the SQL query to include pagination logic with the starting index and the number of data per page
                        $sql .= " LIMIT $startIndex, $dataPerPage";
                        // Execute the modified query to get the data for the current page
                        $result = mysqli_query($conn, $sql);

                        // Check if there are any results
                        if (mysqli_num_rows($result) > 0) {
                            // Loop through the results and display them in a table
                            
                            echo '<table class="table table-hover table-bordered bg-light w-80 table align-middle mt-2 ">';
                            echo '<thead class="thead-light">';
                            echo '<tr><th class="col-4">Name</th><th class="col-1 text-center">Status</th><th class="col-1 text-center">Date Paid</th><th class="col-1 text-center">Action</th></tr>';
                            echo "</thead>";
                            echo "<tbody class='table-group-divider'>";
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Display each row in the table
                                echo "<tr>";
                                echo "<td>" .
                                    $row["lastname"] .
                                    ", " .
                                    $row["firstname"] .
                                    " " .
                                    $row["m_i"] .
                                    "</td>";
                                echo "<td class='text-center'>";
                                if ($row["status"] == 2) {
                                    echo '<span class="badge bg-primary px-3 rounded-pill">Paid By</span>';
                                }
                                if ($row["status"] == 1) {
                                    echo '<span class="badge bg-success px-3 rounded-pill">Paid</span>';
                                }
                                if ($row["status"] == 0) {
                                    echo '<span class="badge bg-danger px-3 rounded-pill">Not Paid</span>';
                                }


                                echo '</td>';
                                echo '<td class="text-center">' . $row['date_paid'] . '</td>';

                                if ($row['status'] == 1 || $row['status'] == 2) {
                                    // Change Button
                                    echo '<td class="text-center" ><button type="button" id="' . $row['id'] . '"class="btn btn-secondary" data-toggle="modal" data-target="#myModalchange_' . $row['id'] . '">Change</button></td>';
                                } else {
                                    // Payment Button
                                    echo '<td class="text-center" ><button type="button" id="' . $row['id'] . '"class="btn btn-success" data-toggle="modal" data-target="#myModal_' . $row['id'] . '">Payment</button></td>';
                                }
                                echo '</tr>';
                                ?>
                                
                                <!-- Modal Paymet -->
                                <div class="modal fade" id="myModal_<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success">
                                                <h5 class="modal-title text-white" id="myModalLabel ">Choose the Student Payment Status</h5>
                                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" id="modal-body">
                                                <form action="payment_process.php" method="post">
                                                    
                                                    <label for="name"><?php echo "<b>Name: </b>". $row['lastname'] . ", " . $row['firstname'] . " " . $row['m_i']; ?></label>
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="section" value="<?php $row["section"]; ?>">
                                                    <input type="hidden" name="page" value="<?php echo $currentPage; ?>">
                                                    <br>
                                                    <br>
                                                    <div class="modal-footer d-flex justify-content-center">
                                                        <button type="submit" class="btn btn-primary w-30" name="paid_by" style="margin: 0 auto;">Paid By</button>
                                                        <button type="submit"class="btn btn-success w-30" name="payee" style="margin: 0 auto;">Payee</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Modal Change -->
                                <div class="modal fade" id="myModalchange_<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-secondary">
                                                <h5 class="modal-title text-white" id="myModalLabel ">Enter Password to Confirm Changes</h5>
                                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" id="modal-body">
                                                <form action="change_payment_process.php" method="post">
                                                    <label for="name"><?php echo $row['lastname'] . ", " . $row['firstname'] . " " . $row['m_i']; ?></label>
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="section" value="<?php $row["section"]; ?>">
                                                    <input type="hidden" name="page" value="<?php echo $currentPage; ?>">
                                                    
                                                    <div class="form-group">
                                                        <input type="password" class="form-control" name="password" placeholder="Password">
                                                    </div>
                                                    <div class="modal-footer d-flex justify-content-center">
                                                        <button type="submit" class="btn btn-secondary" style="margin: 0 auto;">Confirm</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Insert New Record</h5>
                                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                        <form action="insert.php" method="POST" class="container" id="insertForm">
                                            <div class="form-row">
                                                <div class="form-group col-lg-12">
                                                <label for="lastname">Last name</label>
                                                <input type="text" class="form-control" id="Lastname" name="Lastname">
                                                </div>
                                                <div class="form-group col-lg-12">
                                                <label for="firstname">First name</label>
                                                <input type="text" class="form-control" id="Firstname" name="Firstname">
                                                </div>
                                                <div class="form-group col-lg-12">
                                                <label for="middle_initial">Middle initial</label>
                                                <input type="text" class="form-control" id="Middle_Initial" name="Middle_Initial">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-lg-12">
                                                <label for="type">Type</label>
                                                <div class="form-check" readonly>
                                                    <input class="form-check-input" type="radio" name="type" id="type-regular" value="<?php echo $row['type']; ?>" checked>
                                                    <label class="form-check-label" for="type"><?php echo $row['type']; ?></label>
                                                </div>
                                                
                                                </div>
                                                <div class="form-group col-lg-12">
                                                <label for="grade_level">Grade level</label>
                                                <input type="text" class="form-control" id="grade_level" name="grade_level" value="<?php echo $row['grade_level']; ?>"readonly>
                                                </div>
                                                <div class="form-group col-lg-12">
                                                <label for="room">Room</label>
                                                <input type="text" class="form-control" id="room" name="room" value=
                                                "<?php echo $row['room']; ?>"readonly>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-lg-12">
                                                <label for="section">Section</label>
                                                <input type="text" class="form-control" id="section" name="section" value="<?php echo $row['section']; ?>"readonly>
                                                <input type="hidden" name="id" name="id" value="<?php echo $newsectionid; ?>">
                                                <input type="hidden" name="page" value=<?php echo $currentPage; ?>>
                                                </div>
                                            </div>
                                            
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" id="submit-button" class="btn btn-primary" name="submited" >Submit</button>
                                        </div>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                        <?php
                        
                        }
                        echo "</tbody>";
                        echo "</table>";
                        // Add navigation links to navigate through the pages
                        echo '<nav aria-label="Page navigation ">';
                        echo '<ul class="pagination justify-content-center mt-4">';

                        // Show the previous page link
                        if ($currentPage > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?id=' . $id . '&page=' . ($currentPage - 1) .'">Previous</a></li>';
                        }

                        // Show the page numbers with "..." for additional pages
                        $visiblePages = 5;
                        $halfVisible = floor($visiblePages / 2);

                        for ($i = 1; $i <= $totalPages; $i++) {
                            if ($i == 1 || $i == $totalPages || ($i >= $currentPage - $halfVisible && $i <= $currentPage + $halfVisible)) {
                                echo '<li class="page-item ' . ($i == $currentPage ? 'active' : '') . '"><a class="page-link" href="?id=' . $id . '&page=' . $i . '">' . $i . '</a></li>';
                            } elseif ($i == $currentPage - $halfVisible - 1 || $i == $currentPage + $halfVisible + 1) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        }
                        
                        // Show the next page link
                        if ($currentPage < $totalPages) {
                            echo '<li class="page-item"><a class="page-link" href="?id=' . $id . '&page=' . ($currentPage + 1) . '">Next</a></li>';
                        }
                        echo '</ul>';
                        echo '</nav>';
                    } else {
                        // No results found
                        echo "No results found.";
                    }

                    // Close the connection
                    // mysqli_close($conn);
                ?>
                </div>
            </div><!-- end of page content -->
        </section>
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

        <?php
        include "update_modal_script.php";
        ?>
	</body>
</html>