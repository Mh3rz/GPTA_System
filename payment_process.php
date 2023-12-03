<?php
    // Connect to the database
    include "db_connection.php";
    require_once("class.cryptor.php");
    

    
    // get the id and password from the form
    $id = $_POST['id'];
    
    
    // Retrieving name
    $query = "SELECT * FROM student_lists WHERE id= '$id'";
      
    // Execute the query
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $currentPage = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $sectionid = $row['section'];
    $crypt = new Cryptor();
    $newsectionid = $crypt->encryptId($sectionid);
    
    if (isset($_POST['payee'])) {
        // update the record in the database using the id
        $sql = "UPDATE student_lists SET status = 1, date_paid = NOW() WHERE id = '$id'";
        mysqli_query($conn, $sql);
        $stat = 'payee';
        header("Location: specific_sections.php?id='".$newsectionid. "'&page=" . $currentPage ."&stat=".$stat."&message=" . $row['lastname'] . ", " . $row['firstname'] . ". Status changed to <b>Paid</b>!");
          
      }
      elseif (isset($_POST['paid_by'])) {
        // update the record in the database using the id
        $sql = "UPDATE student_lists SET status = 2, date_paid = NOW() WHERE id = '$id'";
        mysqli_query($conn, $sql);
        $stat = 'paid_by';
        header("Location: specific_sections.php?id='".$newsectionid. "'&page=" . $currentPage ."&stat=".$stat."&message=" . $row['lastname'] . ", " . $row['firstname'] . ". Status changed to <b>Paid By</b>!");
      }
    
    // close the database connection
    mysqli_close($conn);
?>
