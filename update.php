<?php

require_once("class.cryptor.php");

class StudentUpdater
{
    private $cryptor;
    private $conn;

    public function __construct($cryptor, $conn)
    {
        $this->cryptor = $cryptor;
        $this->conn = $conn;
    }

    public function updateStudent()
    {
        $currentPage = isset($_POST['page']) ? (int)$_POST['page'] : 1;

        if (isset($_POST['submited'])) {
            $id = $_POST['id'];
            $lastname = $_POST['Lastname'];
            $firstname = $_POST['Firstname'];
            $m_i = $_POST['Middle_Initial'];
            $combinedInfo = $_POST['combined_info'];

            // Split the combined_info value into an array
            $infoArray = explode(' - ', $combinedInfo);

            // Extract values
            $type = $infoArray[3];
            $grade_level = $infoArray[0];
            $room = $infoArray[2];
            $section = $infoArray[1];
            

            $sql = "UPDATE student_lists SET `lastname`='$lastname', `firstname`='$firstname', `m_i`='$m_i', `type`='$type', `grade_level`='$grade_level', `room`='$room', `section`='$section' WHERE `id`='$id'";

            $query = "SELECT * FROM student_lists WHERE id = '$id'";
            $result = mysqli_query($this->conn, $query);
            $row = mysqli_fetch_assoc($result);

            $sectionid = $row['section'];
            $newsectionid = $this->cryptor->encryptId($sectionid);

            if (mysqli_query($this->conn, $sql)) {
                $stat = 'updated';
                $message = $row['lastname'] . ", " . $row['firstname'] . " " . $row['m_i'] . ", Records has been <b>Updated</b>";
                $location = "Location: update_list.php?id='" . $newsectionid . "'&stat=" . $stat . "&page=" . $currentPage . "&message=" . urlencode($message);
                header($location);
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($this->conn);
            }
        }
    }
}

// Usage
include "db_connection.php";
$cryptor = new Cryptor();
$studentUpdater = new StudentUpdater($cryptor, $conn);
$studentUpdater->updateStudent();

?>
