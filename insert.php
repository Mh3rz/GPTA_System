<?php
    // Connect to the MySQL database
    include "db_connection.php"; 
    require_once("class.cryptor.php");
    $crypt = new Cryptor();
    // fetch id or section
    if (isset($_GET["id"])) {
    // Get the ID from the URL (id pass is the section name)
    $id = $_GET["id"];
    
    $decryptedid = $crypt->decryptId($id);
    // Define the query
    echo "$decryptedid";
    $query = "SELECT * FROM student_lists WHERE section= '$decryptedid' AND active = 0";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    }

    if (isset($_POST['submited'])) {
        // Get the form data
        $lastname = $_POST['Lastname'];
        $firstname = $_POST['Firstname'];
        $m_i = $_POST['Middle_Initial'];
        $type = $_POST['type'];
        $grade_level = $_POST['grade_level'];
        $room = $_POST['room'];
        $section = $_POST['section'];

        $sectionid = $section;
        $newsectionid = $crypt->encryptId($sectionid);

        // Insert the data into the student_lists table
        $sql = "INSERT INTO student_lists (lastname, firstname, m_i, type, grade_level, room, section) VALUES ('$lastname', '$firstname', '$m_i', '$type', '$grade_level', '$room', '$section')";


        if (mysqli_query($conn, $sql)) {
            $stat = "added";
            header("Location: specific_sections.php?id='".$newsectionid. "'&stat=".$stat."&message=" . $lastname . ", " . $firstname .  " " . $m_i .". Has been <b>Added</b> to <b>".$section. "</b>!");

        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
?>



