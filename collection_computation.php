<html lang="en">
<head>
    <style>
        .container{
            display: flex;
            text-align: center;
            justify-content: center;
        }
        /* table */
        .t {
        width: 100%;
        text-align: center;
        font-size: 12px;
        }
        table {
        width: 100%;
        text-align: center;
        font-size: 15px;
        }

        td img {
        width: 100px;
        }

        td h4 {
        margin: 0;
        text-align: center;
        font-size: 20px;
        }

        td p {
        text-align: center;
        margin-top: 0;
        margin-bottom: 0;
        font-size: 15px;
        }
        p + h4 {
        margin-top: 0;
        }
        /* table for the report */
        .t{
            border-left: 0.01em solid black;
            border-right: 0;
            border-top: 0.01em solid black;
            border-bottom: 0;
            border-collapse: collapse;
        }
        .t td,
        .t th {
            border-left: 0;
            border-right: 0.01em solid black;
            border-top: 0;
            border-bottom: 0.01em solid black;
        }
        .t th{
            text-align: left;
        }

        
    </style>
</head>
<body>
<?php
// Connect to the database
include("db_connection.php");

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
$result = mysqli_query($conn, $query);

// Initialize counts array with sections from the database
$counts = array();
while ($row = mysqli_fetch_assoc($result)) {
    $section = $row['section'];
    $counts[$section] = 0;
}

// Fetch other required data from the database
$query = "SELECT amount FROM fee WHERE id = 1";
$result = mysqli_query($conn, $query);

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
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

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
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

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

// Now $counts array contains the counts for each section dynamically fetched from the database
?>

<div class="container">
    <!-- for the header of page -->
    <table class="header">
        <tr>
            <td><img style="width: 58px;" src="images/blank.png" alt=""></td>
            <td><img src="images/logo.png"alt=""></td>
            <td style="width: 70%;">
                <h4>EASTERN MINDORO COLLEGE</h4>
                <p>Bagong Bayan II, Bongabong Oriental Mindoro</p>
                <h4>GENERAL PTA</h4>
            </td>
            <td><img src="images/blank.png" alt=""></td>
            <td><img style="width: 58px;" src="images/blank.png" alt=""></td>
        </tr>
    </table>

    <div>
        <h3> Collection Report</h3>
    </div>

    <?php
    echo "<table style='margin-top:2%; width:90%; margin-left: auto; margin-right: auto;' class='t'>";
    echo "<thead style='background-color:	#D3D3D3;'>";
    echo "<th style='text-align:center; width:180px;' colspan='2'>Sections per grade level</th>";
    echo "<th style='text-align:center; width:150px;'>Amount per section</td>";
    echo "<th style='text-align:center; width:150px;'>Amount per grade level</td>";
    echo "</thead>";
    // Print out the totals for each grade level
    foreach ($grade_totals as $grade_level => $total) {
        echo "<tr>";
        echo "<th scope='row' colspan='2'>Grade $grade_level</th>";
        echo "<td'></td>";
        echo "<td></td>";
        echo "</tr>";
        // Loop through the sections
        foreach ($totals as $section => $section_total) {
            // Execute a query to get the grade level for this section
            $query = "SELECT grade_level FROM student_lists WHERE section = '$section' LIMIT 1";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
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
        echo "<td  style='text-align:right;'><b>Sub Total:</b></td>";
        echo "<td><img style='width:10px;' src='images/peso.png'/>" . number_format($total, 2) . "</td>";
        echo "</tr>";
    }
    ?>
    <tr class="total">
        <th scope="row" colspan="3"></th>
        <td style="color: white;">...</td>
    </tr>
    <tr style='background-color:	#D3D3D3;' class="total">
        <th scope="row" colspan="3"> OVERALL TOTAL COLLECTED CONTRIBUTION</th>
        <td><img style='width:10px;' src='images/peso.png'/><?php echo $formatted_grand_total ?></td>
    </tr>
    <?php
    echo "</table>";
    ?>

    <table class="officer" style="margin-top: 12px;">
        <tr>
            <td colspan="2" style="text-align:left;">Prepared by:</td>
            <td style="width: 250px;"></td>
            <td colspan="2" style="text-align:left;">Reported by:</td>
            <td></td>
        </tr>
        <tr><td style="height: 18px;"></td></tr>
        <tr>
            <td style="width: 20px;"></td>
            <td style="text-align: left;"><?php echo $prepared_by ?></td>
            <td style="width: 20px;"></td>
            <td style="width: 20px;"></td>
            <td style="text-align:left;"><?php echo $reported_by ?></td>
        </tr>
    </table>
    <table class="check">
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
</body>
</html>
