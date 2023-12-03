<?php
include("db_connection.php");
include("security_login.php");
?>
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
            .table-wrapper {
            overflow-x: auto;
            max-width: 100%; /* Set a maximum width if necessary */
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
                require_once("class.cryptor.php");
                $crypt = new Cryptor();
            
                if (isset($_GET["id"])) {
                    // Get the ID from the URL (id pass is the section name)
                    $id = $_GET["id"];
                    $decryptedid = $crypt->decryptId($id);
                
                    $query = "SELECT * FROM student_lists WHERE section = '$decryptedid' AND active = 0";
                    $result = mysqli_query($conn, $query);
                    $row = mysqli_fetch_assoc($result);
                    $sectionid = $row['section'];
                    $newsectionid = $crypt->encryptId($sectionid);
                }
            ?>
            <nav class="navbar navbar-expand-lg navbar-light bg-light w-100">
                <div class="container-fluid">
                <i class='bx bx-menu'></i>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        
                    <ul class="navbar-nav ms-auto mt-2 mt-lg-0 justify-content-center justify-content-sm-start">
                        <br>
                        <li class="nav-item ml-2">
                            <a class="btn btn-success text-white" style="margin-right: 7px" href="specific_sections.php?id='<?php echo $newsectionid; ?>'">
                                <i class="fas fa-money-bill-wave"></i>Payment
                            </a>
                        </li>
                        <br>
                    </ul>
                    </div>
                </div>
            </nav> <!-- end of top Nav -->

            </div>
        
            <div class="container mt-3">
                <div class="table-wrapper">
                <?php

                    // Fetch unique values from the sections table
                    $sql3 = "SELECT DISTINCT CONCAT(grade_level, ' - ', section, ' - ', room, ' - ', type) AS combined_info FROM sections";
                    $result3 = $conn->query($sql3);

                    // Store the unique values in an array
                    $combinedInfoOptions = array();
                    while ($row = $result3->fetch_assoc()) {
                        $combinedInfoOptions[] = $row['combined_info'];
                    }

                ?>
                <?php
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

                    // message
					$message = "";
					if (isset($_GET['message'])) {
						$message = $_GET['message'];
					}
                    $stat = isset($_GET['stat']) ? $_GET['stat'] : '';
					if (!empty($message)) {
						if ($stat =='deactivated'){
							echo "<div class='alert alert-danger'><i class='bi bi-x-octagon-fill'></i>&nbsp; $message</div>";
						} elseif ($stat == 'updated'){
							echo "<div class='alert alert-primary'><i class='bi bi-pencil-square'></i>&nbsp; $message</div>";
						} elseif ($stat == 'restored'){
							echo "<div class='alert alert-warning'><i class='bi bi-clock-fill'></i>&nbsp; $message</div>";
						}
					}
                    
                    // Print header
                    echo "<h1 style='display: inline;'>Grade " .
                        $for_header["grade_level"] .
                        " Room " .
                        $for_header["room"] .
                        "-" .
                        $for_header["section"] .
                        "</h1> <a type='button' style='float: right; display: inline;' class='btn btn-danger' href='print_record.php?id=" . $newsectionid . "'><i class='bi bi-file-earmark-pdf pr-2'></i><strong>PDF</strong></a>";

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
                            echo '<tr><th class="col-4">Name</th><th class="col-1 text-center">Status</th><th class="col-1 text-center">Date Paid</th><th class="col-2 text-center">Action</th></tr>';
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

                                echo '<td class="text-center" ><button type="button" id="'. $row['id'].'"class="btn btn-danger me-2" data-toggle="modal" data-target="#myModal_'.$row['id'].'"><i class="bi bi-x-octagon me-2"></i>Deactivate</button><button type="button" id="' . $row['id'] . '"class="btn btn-primary" data-toggle="modal" data-target="#myModal_update_'.$row['id'].'"><i class="bi bi-pencil-square me-2"></i>Update</button></td>';
                                echo '</tr>';
                                ?>
                        <!-- Modal Deactivate-->
                        <div class="modal fade" id="myModal_<?php echo $row['id']; ?>" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h5 class="modal-title text-white" id="myModalLabel ">Confirm
                                            Deactivation of Record</h5>
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="modal-body">
                                        <form action="deactivate_process.php" method="post">
                                            <label for="name"><?php echo $row['lastname'].", ".$row['firstname']." ". $row['m_i']; ?></label>
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="page" value=<?php echo $currentPage; ?>>
                                            <div class="modal-footer d-flex justify-content-center">
                                                <button type="submit" name="submitted" class="btn btn-danger" style="margin: 0 auto;">Deactivate</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <!-- Modal Update -->
                        <div class="modal fade" id="myModal_update_<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white" id="exampleModalLabel">Update Record</h5>
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="update.php" method="POST" class="container" id="insertForm">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                        <input type="hidden" name="page" value=<?php echo $currentPage; ?>>
                                                        <label for="lastname">Last name</label>
                                                        <input type="text" class="form-control" id="Lastname" name="Lastname" value="<?php echo $row['lastname'];?>">
                                                    </div><br>
                                                    <div class="form-group">
                                                        <label for="firstname">First name</label>
                                                        <input type="text" class="form-control" id="Firstname" name="Firstname" value="<?php echo $row['firstname'];?>">
                                                    </div><br>
                                                    <div class="form-group">
                                                        <label for="middle_initial">Middle initial</label>
                                                        <input type="text" class="form-control" id="Middle_Initial" name="Middle_Initial" value="<?php echo $row['m_i'];?>">
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="page" value="<?php echo $currentPage; ?>">
                                                    <label for="combined_info">Grade - Section - Room - Type</label>
                                                    <select class="form-control" id="combined_info" name="combined_info">
                                                        <?php
                                                        foreach ($combinedInfoOptions as $option) {
                                                            echo "<option value=\"$option\"";
                                                            if ($row['grade_level'] . ' - ' . $row['section'] . ' - ' . $row['room'] . ' - ' . $row['type'] === $option) {
                                                                echo " selected";
                                                            }
                                                            echo ">$option</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
                                        <button type="submit" id="submit-button" class="btn btn-primary" name="submited">Confirm</button>
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

                ?>
                </div>
            </div><!-- end of page content -->
        </section>
    <?php
    include "update_modal_script.php";
    ?>
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