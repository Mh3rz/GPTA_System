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
                <nav class="navbar bg-light w-100">
                    <div class="container-fluid">
                    <i class='bx bx-menu'></i>
                        
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
                        // message
                        $message = "";
                        if (isset($_GET['message'])) {
                            $message = $_GET['message'];
                        }
                        $stat = isset($_GET['stat']) ? $_GET['stat'] : '';
                        if (!empty($message)) {
                            if ($stat == 'error'){
                                echo "<div class='alert alert-danger'><i class=''></i>&nbsp; $message</div>";
                            } elseif ($stat == 'delete'){
                                echo "<div class='alert alert-danger'><i class='bi bi-check-circle-fill '></i>&nbsp; $message</div>";
                            } elseif ($stat == 'restored'){
                                echo "<div class='alert alert-warning'><i class='bi bi-clock-fill'></i>&nbsp; $message</div>";
                            }
                        }
                    ?>
                    <?php
                        
                        // Define the number of data per page
                        $dataPerPage = 7;

                        // Get the current page number from the query string
                        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

                        // Calculate the starting index for the current page
                        $startIndex = ($currentPage - 1) * $dataPerPage;

                        // Retrieve all records from the student_lists table where active is equal to 1
                        $sql = "SELECT * FROM student_lists WHERE active = 1";

                        // Execute the query
                        $result = mysqli_query($conn, $sql);

                        // Fetch all rows from the result set into an associative array
                        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

                        // Check if a search query is provided
                        $searchQuery = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

                        // Filter the array using linear search if a search query is present
                        if (!empty($searchQuery)) {
                            $filteredRows = array_filter($rows, function ($row) use ($searchQuery) {
                                return (
                                    stripos($row['full_name'], $searchQuery) !== false
                                );
                            });
                        } else {
                            // If no search query, use the original rows
                            $filteredRows = $rows;
                            
                        }

                        // Calculate the total number of rows
                        $totalRows = count($filteredRows);

                        // Calculate the total number of pages
                        $totalPages = ceil($totalRows / $dataPerPage);

                        // Paginate the filtered array
                        $paginatedRows = array_slice($filteredRows, $startIndex, $dataPerPage);
                    ?>
                    <div class="table_container" style="background-color: #fff; padding: 10px; height: 100%; border-radius: 15px;" >
                        <!-- Print Header with Search Bar -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h1>Restore Data Record</h1>
                            <form class="form-inline" action="" method="GET">
                                <div class="input-group">
                                    <?php if (!empty($searchQuery)) : ?>
                                        <div class="input-group-append">
                                            <a href="restore.php" class="btn btn-outline-secondary me-2">clear</a>
                                        </div>
                                    <?php endif; ?>
                                    <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2" name="search" value="<?php echo $searchQuery; ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary ms-2" type="submit"><i class="bi bi-search"></i></button>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>


                        <?php
                            // Check if there are any results
                            if (mysqli_num_rows($result) > 0) {
                                // Loop through the results and display them in a table
                        ?>        
                                <table class="table table-hover w-100 align-middle mt-2" style="background-color: #efeff3;">
                                <thead class="thead-light">
                                <tr>
                                    <th class="col-4">Name</th>
                                    <th class="col-1 text-center">Status</th>
                                    <th class="col-1 text-center">Action</th></tr>
                                </thead>
                                <tbody class='table-group-divider'>
                                <?php
                                if (empty($paginatedRows)) {
                                    echo '<tr><td colspan="3" class="text-center">No records found.</td></tr>';
                                } else {
                                foreach ($paginatedRows as $row)  {
                                    // Display each row in the table
                                    echo "<tr>";
                                    echo "<td>" .
                                        $row["lastname"] .
                                        ", " .
                                        $row["firstname"] .
                                        " " .
                                        $row["m_i"] .
                                        "</td>";
                                    echo "<td class='text-center text-danger'> Deactivated </td>"; 

                                    // restoreButton
                                    echo '<td class="text-center" ><button type="button" id="' . $row['id'] . '"class="btn btn-outline-primary" data-toggle="modal" data-target="#myModal_update_'.$row['id'].'"><i class="bi bi-arrow-counterclockwise me-1"></i>RESTORE</button> <button type="button" id="' . $row['id'] . '" data-toggle="modal" data-target="#myModal_delete_'.$row['id'].'" class="btn btn-link text-primary" onmouseover="this.children[0].classList.replace(\'bi-trash3\', \'bi-trash3-fill\')" onmouseout="this.children[0].classList.replace(\'bi-trash3-fill\', \'bi-trash3\')">
                                    <i class="bi bi-trash3"></i></button></td>';

                                    echo '</tr>';
                                    ?>

                                    <!-- Modal Restore -->
                                    <div class="modal fade" id="myModal_update_<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title text-white" id="exampleModalLabel">Update Record</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="restore_process.php" method="POST" class="container" id="insertForm">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="form-group">
                                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                                    <input type="hidden" name="page" value=<?php echo $currentPage; ?>>
                                                                    <input type="hidden" name="search" value="<?php echo $searchQuery; ?>">
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

                                    <!-- Modal Delete-->
                                    <div class="modal fade" id="myModal_delete_<?php echo $row['id']; ?>" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title text-white" id="myModalLabel ">Enter Password to Confirm
                                                        Deletion</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                                                
                                            </button>
                                                </div>
                                                <div class="modal-body" id="modal-body">
                                                    <form action="delete_process.php" method="post">
                                                        <label for="name"><?php echo $row['lastname'].", ".$row['firstname']." ". $row['m_i']; ?></label>
                                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                        <input type="hidden" name="page" value=<?php echo $currentPage; ?>>
                                                        <input type="hidden" name="search" value="<?php echo $searchQuery; ?>">
                                                        <div class="form-group">
                                                            <input type="password" class="form-control" name="password" placeholder="Password" >
                                                        </div>
                                                        <div class="modal-footer d-flex justify-content-center">
                                                            <button type="submit" class="btn btn-danger" style="margin: 0 auto;">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            echo "</tbody>";
                            echo "</table>";
                            // Add navigation links to navigate through the pages
                            echo '<nav aria-label="Page navigation ">';
                            echo '<ul class="pagination justify-content-center mt-4">';

                            // Show the previous page link
                            if ($currentPage > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage - 1) . '&search='.$searchQuery.'">Previous</a></li>';
                            }

                            // Show the page numbers with "..." for additional pages
                            $visiblePages = 5;
                            $halfVisible = floor($visiblePages / 2);

                            for ($i = 1; $i <= $totalPages; $i++) {
                                if ($i == 1 || $i == $totalPages || ($i >= $currentPage - $halfVisible && $i <= $currentPage + $halfVisible)) {
                                    echo '<li class="page-item ' . ($i == $currentPage ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                                } elseif ($i == $currentPage - $halfVisible - 1 || $i == $currentPage + $halfVisible + 1) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                            }

                            // Show the next page link
                            if ($currentPage < $totalPages) {
                                echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage + 1) . '&search='.$searchQuery.'">Next</a></li>';
                            }

                            echo '</ul>';
                            echo '</nav>';
                        } else {
                            // No results found
                            echo "No results found.";
                        }
                        ?>
                
                    </div><!-- end of table_container -->
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