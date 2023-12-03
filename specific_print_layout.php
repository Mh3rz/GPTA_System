
<?php 
include "db_connection.php"; 
// Login checker
include "security_login.php";
require_once("class.cryptor.php");
$crypt = new Cryptor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$data["type"]?> Grade <?=$data["grade_level"]?> Room(<?=$data["room"]?>-<?=$data["section"]?>)</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/specific_print_layout_style.css">

</head>
<body>
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
		<div style='text-align:center; width:85%; margin-left: auto; margin-right: auto; margin-top:0'>
			<h3> <?=$data["type"]?> Grade <?=$data["grade_level"]?> Room(<?=$data["room"]?>-<?=$data["section"]?>)</h3>
		</div>
		<div style='text-align:start; width:85%; margin-left: auto; margin-right: auto;'>
			<p><strong>Note: </strong>(S) Paid by sibling/common guardian</p>
		</div>
		
		<table class="list" style='margin-top:2%; width:85%; margin-left: auto; margin-right: auto;'>
			<tr>
				<th class="list_header" style="background-color: #CCCCCC;">Student Name</th>
				<th class="list_header"  style="background-color: #CCCCCC;">Status</th>
			</tr>
			<?php
				// Check if there are any results
				if (mysqli_num_rows($result) > 0) {
			?>
			<?php
				$rowNumber = 0;
				while ($row = mysqli_fetch_assoc($result)) {
					$rowNumber++;
					// Display each row in the table
			?>
			<tr>
				<td class="data">
					<?=
						$rowNumber . '. ' . $row["lastname"] .", " .$row["firstname"] ." " .$row["m_i"]
					?>
				</td>

				<td class="data">
					<?php
						switch ($row["status"]) {
							case 1:
								echo '<p class="paid">Paid</p>';
								break;
							case 2:
								echo '<p class="paid_s">Paid(S)</p>';
								break;
							default:
								echo '<p class="not-paid">Not Paid</p>';
						}
					?>
			
				</td>
			</tr>
		<?php
			}
		}
		?>
		</table>
	</div>
</body>
</html>