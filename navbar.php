<?php 
	if (isset($_GET["id"])) {
		// Get the ID from the URL (id pass is the section name)
		$id = $_GET["id"];
        $decryptedid = $crypt->decryptId($id);
		// Define the query
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
            <li class="nav-item ml-2 ">
                <a class="btn btn-primary text-white" style="margin-right: 7px" href="update_list.php?id='<?php echo $newsectionid; ?>'">
                    <i class="fas fa-pen pr-2"></i>Update
                </a>
            </li>
            
            <br>
            <li class="nav-item ml-2">
                <a class="btn btn-secondary" id='<?php echo $newsectionid; ?>' style="margin-right: 7px" data-toggle="modal" data-target="#exampleModal">
                    <i class="fas fa-plus pr-2"></i>Add
                </a>
            </li>
            <br>
        </ul>
        </div>
    </div>
</nav> <!-- end of top Nav -->