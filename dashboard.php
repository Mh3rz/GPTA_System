<?php
include("db_connection.php");
include("security_login.php");
?>

<!DOCTYPE html>
<!-- Coding by CodingNepal | www.codingnepalweb.com -->
<html lang="en" dir="ltr">
    <head>
        <meta charset="UTF-8">
        <title> Dashboard </title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/style_dashboard.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="shortcut icon" href="assets/log_icon.png" type="img/x-icon" />
        <style>
            .custom-icon-color {
                color: #FFCF3F; 
            }
            .custom-icon-color2 {
                color: #4BA179; 
            }
            .custom-icon-color3 {
                color: #4A92FE; 
            }
            .custom-icon-color4 {
                color: black; 
            }

            .quote-container {
                padding: 10px;
                padding-top: 40px;
                border-radius: 10px;
                font-family: 'Dancing Script', cursive;
                text-align: end;
            }

            h6 {
                font-size: 18px;
                font-style: italic;
                margin: 0;
            }
        </style>
    </head>
<body>
<?php
include ('sidebar.php');

// SQL query to get the total number of active students
$sql = "SELECT COUNT(*) AS total_active_students FROM student_lists WHERE active = 0";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    $row = $result->fetch_assoc();
    $totalActiveStudents = $row["total_active_students"];
}

$sql2 = "SELECT COUNT(*) AS total_paid_students FROM student_lists WHERE status = 1 AND active = 0";
$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {
    // Output data of each row
    $row2 = $result2->fetch_assoc();
    $totalPaidStudents = $row2["total_paid_students"];
}

$sql3 = "SELECT COUNT(*) AS total_paidby_students FROM student_lists WHERE status = 2 AND active = 0";
$result3 = $conn->query($sql3);

if ($result3->num_rows > 0) {
    // Output data of each row
    $row3 = $result3->fetch_assoc();
    $totalPaidByStudents = $row3["total_paidby_students"];
}


$query= "SELECT amount FROM fee WHERE id = 1";
$result = $conn->query($query);

$amount = 0; // Initialize the variable

if ($result->num_rows > 0) {
    // Output data of the first row
    $row = $result->fetch_assoc();
    $amount = $row["amount"];
}


$sql4 = "SELECT CONCAT('₱', COUNT(*) * $amount) AS total FROM student_lists WHERE status = 1 AND active = 0";
$result4 = $conn->query($sql4);

if ($result4->num_rows > 0) {
    // Output data of each row
    $row4 = $result4->fetch_assoc();
    $total = $row4["total"];
}



?>

    <section class="home-section">
        <div class="home-content">
            <nav class="navbar bg-light w-100">
                <div class="container-fluid">
                <i class='bx bx-menu'></i>

                </div>
            </nav> <!-- end of top Nav -->
        </div>
        <div class="container-fluid" id="wall">          
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="card border-top-0 border-start-0 border-end-0 border-bottom-1 border-5 border-warning border-opacity-75"> 
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">
                                <div class="row">
                                    <div class="col-8 m-auto">
                                        <h6>No. of Students</h3>
                                        <p class="m-0"><?php echo $totalActiveStudents ?></p> 
                                    </div>
                                    <div class="col-4 d-flex align-items-center text-center" >
                                        <i class="bi bi-people-fill custom-icon-color" style="font-size: 45px;"></i>
                                    </div>
                            </blockquote>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-md-3">
                    <div class="card border-top-0 border-start-0 border-end-0 border-bottom-1 border-5 border-success border-opacity-75"> 
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">
                                <div class="row">
                                    <div class="col-8 m-auto">
                                        <h6>Number of Paid</h3>
                                        <p class="m-0"><?php echo $totalPaidStudents ?></p> 
                                    </div>
                                    <div class="col-4 d-flex align-items-center text-center" >
                                        <i class="bi bi-person-fill-check custom-icon-color2" style="font-size: 45px;"></i>
                                    </div>
                            </blockquote>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card border-top-0 border-start-0 border-end-0 border-bottom-1 border-5 border-primary border-opacity-75"> 
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">
                                <div class="row">
                                    <div class="col-8 m-auto">
                                        <h6>No. of Paid By</h3>
                                        <p class="m-0"><?php echo $totalPaidByStudents ?></p> 
                                    </div>
                                    <div class="col-4 d-flex align-items-center text-center" >
                                        <i class="bi bi-person-fill-dash custom-icon-color3" style="font-size: 45px;"></i>
                                    </div>
                            </blockquote>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card border-top-0 border-start-0 border-end-0 border-bottom-1 border-5 border-dark border-opacity-75"> 
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">
                                <div class="row">
                                    <div class="col-8 m-auto">
                                        <h6>Total Collection</h3>
                                        <p class="m-0"><?php echo $total ?></p> 
                                    </div>
                                    <div class="col-4 d-flex align-items-center text-center" >
                                        <i class="bi bi-cash-stack custom-icon-color4" style="font-size: 45px;"></i>
                                    </div>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columns are always 50% wide, on mobile and desktop -->
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="row">
                        <div class="col-12">
                            <div id="carouselExample" class="carousel slide carousel-dark">
                                <div class="carousel-inner">
                                    <div class="carousel-item active" >
                                        <div class="card" style="min-height: 337px; background-image:url('images/no_data2.png'); background-repeat: no-repeat; background-position: center;">
                                            <div class="card-header" >
                                                <b>Grade 7</b>
                                            </div>
                                            <div class="card-body">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                        <th scope="col">Sections</th>
                                                        <th scope="col">Paid</th>
                                                        <th scope="col">Not Paid</th>
                                                        <th scope="col">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (isset($grade_sections[7])) {
                                                            foreach ($grade_sections[7] as $section) {
                                                                include "section_status.php";
                                                            }
                                                        } 
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="card" style="min-height: 337px; background-image:url('images/no_data2.png'); background-repeat: no-repeat; background-position: center;" >
                                            <div class="card-header">
                                                <b>Grade 8</b>
                                            </div>
                                            <div class="card-body">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                        <th scope="col">Sections</th>
                                                        <th scope="col">Paid</th>
                                                        <th scope="col">Not Paid</th>
                                                        <th scope="col">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (isset($grade_sections[8])) {
                                                            foreach ($grade_sections[8] as $section) {
                                                                include "section_status.php";
                                                            }
                                                        } 
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="card" style="min-height: 337px; background-image:url('images/no_data2.png'); background-repeat: no-repeat; background-position: center;">
                                            <div class="card-header">
                                                <b>Grade 9</b>
                                            </div>
                                            <div class="card-body">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                        <th scope="col">Sections</th>
                                                        <th scope="col">Paid</th>
                                                        <th scope="col">Not Paid</th>
                                                        <th scope="col">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (isset($grade_sections[9])) {
                                                            foreach ($grade_sections[9] as $section) {
                                                                include "section_status.php";
                                                            }
                                                        } 
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="card" style="min-height: 337px; background-image:url('images/no_data2.png'); background-repeat: no-repeat; background-position: center;">
                                            <div class="card-header">
                                                <b>Grade 10</b>
                                            </div>
                                            <div class="card-body">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                        <th scope="col">Sections</th>
                                                        <th scope="col">Paid</th>
                                                        <th scope="col">Not Paid</th>
                                                        <th scope="col">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (isset($grade_sections[10])) {
                                                            foreach ($grade_sections[10] as $section) {
                                                                include "section_status.php";
                                                            }
                                                        } 
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="card" style="min-height: 337px; background-image:url('images/no_data2.png'); background-repeat: no-repeat; background-position: center;">
                                            <div class="card-header">
                                                <b>Grade 11</b>
                                            </div>
                                            <div class="card-body">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                        <th scope="col">Sections</th>
                                                        <th scope="col">Paid</th>
                                                        <th scope="col">Not Paid</th>
                                                        <th scope="col">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (isset($grade_sections[11])) {
                                                            foreach ($grade_sections[11] as $section) {
                                                                include "section_status.php";
                                                            }
                                                        } 
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="card" style="min-height: 337px; background-image:url('images/no_data2.png'); background-repeat: no-repeat; background-position: center;">
                                            <div class="card-header">
                                                <b>Grade 12</b>
                                            </div>
                                            <div class="card-body">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                        <th scope="col">Sections</th>
                                                        <th scope="col">Paid</th>
                                                        <th scope="col">Not Paid</th>
                                                        <th scope="col">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (isset($grade_sections[12])) {
                                                            foreach ($grade_sections[12] as $section) {
                                                                include "section_status.php";
                                                            }
                                                        } 
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button id="btn_prev" class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button id="btn_next" class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card border-top-0 border-start-0 border-end-0 border-bottom-1 border-5 border-dark border-opacity-75"> 
                                <div class="card-body">
                                    <blockquote class="blockquote mb-0">
                                        <div class="row">
                                            <div class="col-8 m-auto">
                                                <h6>GPTA Fee</h3>
                                                <p class="m-0"><?php echo '₱' . $amount . " Per Parent"; ?></p> 
                                            </div>
                                            <div class="col-4 d-flex  text-center justify-content-center" >
                                            <p><a href="" data-bs-toggle="modal" data-bs-target="#editModal">Edit</a></p>
                                            </div>
                                    </blockquote>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit GPTA Fee</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Add your form or input field for editing the amount here -->
                                    <form method="post" action="update_fee.php">
                                    <label for="newAmount">New Amount:</label>
                                    <input type="number" name="newAmount" id="newAmount" class="form-control" value="<?php echo $amount; ?>" required>
                                    <br>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                                </div>
                            </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                        
                            
                            <?php
                            // Query to fetch data
                            $query = "SELECT COUNT(*) as count, status FROM student_lists WHERE active = 0 GROUP BY status";
                            $result = mysqli_query($conn, $query);

                            // Process the data
                            $blueCount = $greenCount = $redCount = 0;

                            while ($row = mysqli_fetch_assoc($result)) {
                                $count = $row['count'];
                                $status = $row['status'];

                                switch ($status) {
                                    case 2:
                                        $blueCount = $count;
                                        break;
                                    case 1:
                                        $greenCount = $count;
                                        break;
                                    case 0:
                                        $redCount = $count;
                                        break;
                                }
                            }

                            if ($redCount != 0) {
                                // Calculate the percentage
                                $totalPercentage = ($blueCount + $greenCount) / $redCount * 100;
                                ?>
                                <canvas id="percentageChart" height="300" width="600"></canvas>
                            
                                <script>
                                    // Create a circular percentage chart
                                    var ctx = document.getElementById('percentageChart').getContext('2d');
                                    var percentageChart = new Chart(ctx, {
                                        type: 'doughnut',
                                        data: {
                                            labels: ['Paid By', 'Paid', 'Not Paid'],
                                            datasets: [{
                                                data: [<?php echo $blueCount; ?>, <?php echo $greenCount; ?>, <?php echo $redCount; ?>],
                                                backgroundColor: ['blue', 'green', 'red']
                                            }]
                                        },
                                        options: {
                                            cutoutPercentage: 5,
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            legend: {
                                                display: false
                                            },
                                            onRender: function(chart) {
                                                var width = chart.chart.width,
                                                    height = chart.chart.height,
                                                    ctx = chart.chart.ctx;
                            
                                                ctx.restore();
                                                var fontSize = (height / 114).toFixed(2);
                                                ctx.font = fontSize + "em sans-serif";
                                                ctx.fillStyle = "#333";
                                                ctx.textBaseline = "middle";
                            
                                                var text = "<?php echo 'Total Percentage: ' . $totalPercentage . '%'; ?>",
                                                    textX = Math.round((width - ctx.measureText(text).width) / 2),
                                                    textY = height / 2;
                            
                                                ctx.fillText(text, textX, textY);
                                                ctx.save();
                                            }
                                        }
                                    });
                                </script>
                            <?php
                            } else {
                                ?>
                                <div style="background-image:url('images/no_data.png'); background-repeat: no-repeat; background-position: center;  height:300px;"></div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="quote-container">
                                <h6>- This system is intended to help the GPTA to manage its fee digitally -</h6>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
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
    
</body>
</html>