<?php
include "db_connection.php"; 
require_once("class.cryptor.php");
$crypt = new Cryptor();
// Include DOMPDF
require_once './dompdf/autoload.inc.php';
use Dompdf\Dompdf;
//add this Option class to help fix image not found
use Dompdf\Options;

$options = new Options();
$options->set('chroot', realpath(''));

// Get the ID from the URL
$id = $_GET['id'];
$decryptedid = $crypt->decryptId($id);


$title = mysqli_query($conn,"SELECT * FROM student_lists WHERE section='$decryptedid'");
$data = mysqli_fetch_assoc($title);

$sql = "SELECT * FROM student_lists WHERE section = '$decryptedid' AND active = 0 ORDER BY lastname";
// Execute the query
$result = mysqli_query($conn, $sql);

// Create a new DOMPDF instance
$dompdf = new DOMPDF($options);


ob_start();
require('specific_print_layout.php');
$html =ob_get_contents();
ob_get_clean();


// Load the HTML content
$dompdf->loadhtml($html);

// Set the paper size and orientation
$dompdf->setpaper('A4', 'portrait');

// Render the PDF
$dompdf->render();

//make custom pdf name when downloaded
$data = mysqli_fetch_assoc($title);
$filename = "Grade". $data["grade_level"]." Room(".$data["room"]."-".$data["section"].")";

// Output the PDF to the browser
$dompdf->stream($filename,array('Attachment'=>0));
?>