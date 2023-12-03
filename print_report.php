<?php
include "db_connection.php"; 
// Include DOMPDF
require_once './dompdf/autoload.inc.php';
use Dompdf\Dompdf;
//add this Option class to help fix image not found
use Dompdf\Options;

$options = new Options();
$options->set('chroot', realpath(''));

// Create a new DOMPDF instance
$dompdf = new DOMPDF($options);


ob_start();
require('collection_computation.php');
$html =ob_get_contents();
ob_get_clean();



// Load the HTML content
$dompdf->loadhtml($html);

// Set the paper size and orientation
$dompdf->setpaper('A4', 'portrait');

// Render the PDF
$dompdf->render();
//make custom pdf name when downloaded

// Output the PDF to the browser
$currentDate = date('Y-m-d');

// Replace any characters that are not safe in a filename
$currentDateForFilename = preg_replace("/[^a-zA-Z0-9]+/", "_", $currentDate);

$filename = "Collection_Report_SY_$currentDateForFilename.pdf";

// Generate the PDF and set it as an attachment with the dynamic filename
$dompdf->stream($filename, array('Attachment' => 0));
?>