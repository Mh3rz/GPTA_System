<?php
require_once("class.cryptor.php");


// Fetch data from the database for multiple grade levels
$query = "SELECT type, grade_level, room, section FROM sections"; // Replace your_table_name with the actual table name
$result = mysqli_query($conn, $query);

// Store fetched data into an associative array organized by grade levels
$grade_sections = array();
while ($row = mysqli_fetch_assoc($result)) {
    $grade = $row['grade_level'];
    $grade_sections[$grade][] = $row['section'];
}


$grade_icons = array(
    7 => 'G7',
    8 => 'G8',
    9 => 'G9',
    10 => 'G10',
    11 => 'G11',
    12 => 'G12'
    );
?>
<div class="sidebar close">
    <div class="logo-details">
        <i class='bi bi-google'></i>
        <span class="logo_name">GPTA&nbsp;System</span>
    </div>
        <ul class="nav-links">
        <li class="tabs">
            <a href="dashboard.php">
            <i class='bx bx-grid-alt' ></i>
            <span class="link_name">Dashboard</span>
            </a>
            <ul class="sub-menu blank">
            <li><a class="link_name" href="dashboard.php">Dashboard</a></li>
            </ul>
        </li>
        <li class="tabs">
            <a href="collection_report.php">
            <i class='bi bi-collection' ></i>
            <span class="link_name">Collection&nbsp;Report</span>
            </a>
            <ul class="sub-menu blank">
            <li><a class="link_name" href="collection_report.php">Collection&nbsp;Report</a></li>
            </ul>
        </li>
        <li class="tabs">
            <div class="icon-link">
            <a href="#">
                <?php echo "<i class='poppins'>$grade_icons[7]</i>";?>
                <span class="link_name">Grade&nbsp;7</span>
            </a>
            <i class='bx bxs-chevron-down arrow' ></i>
            </div>
            <ul class="sub-menu">
                <?php
                
                if (isset($grade_sections[7])) {
                    foreach ($grade_sections[7] as $section) {
                        $crypt = new Cryptor();
                        $newid = $crypt->encryptId($section);
                        echo "<li><a href='specific_sections.php?id=%27".$newid."%27'>$section</a></li>";
                    }
                } else {
                    echo "<li>No sections available for Grade 7</li>";
                }
                ?>
            </ul>
        </li>
        <li class="tabs">
            <div class="icon-link">
            <a href="#">
                <?php echo "<i class='poppins'>$grade_icons[8]</i>";?>
                <span class="link_name">Grade&nbsp;8</span>
            </a>
            <i class='bx bxs-chevron-down arrow' ></i>
            </div>
            <ul class="sub-menu">
                <?php
                if (isset($grade_sections[8])) {
                    foreach ($grade_sections[8] as $section) {
                        $crypt = new Cryptor();
                        $newid = $crypt->encryptId($section);
                        echo "<li><a href='specific_sections.php?id=%27".$newid."%27'>$section</a></li>";
                    }
                } else {
                    echo "<li>No sections available for Grade 8</li>";
                }
                ?>
            </ul>
        </li>
        <li class="tabs">
            <div class="icon-link">
            <a href="#">
                <?php echo "<i class='poppins'>$grade_icons[9]</i>";?>
                <span class="link_name">Grade&nbsp;9</span>
            </a>
            <i class='bx bxs-chevron-down arrow' ></i>
            </div>
            <ul class="sub-menu">
                <?php
                    if (isset($grade_sections[9])) {
                        foreach ($grade_sections[9] as $section) {
                            $crypt = new Cryptor();
                            $newid = $crypt->encryptId($section);
                            echo "<li><a href='specific_sections.php?id=%27".$newid."%27'>$section</a></li>";
                        }
                    } else {
                        echo "<li>No sections available for Grade 9</li>";
                    }
                ?>
            </ul>
        </li>
        <li class="tabs">
            <div class="icon-link">
            <a href="#">
                <?php echo "<i class='poppins'>$grade_icons[10]</i>";?>
                <span class="link_name">Grade&nbsp;10</span>
            </a>
            <i class='bx bxs-chevron-down arrow' ></i>
            </div>
            <ul class="sub-menu">
                <?php
                    if (isset($grade_sections[10])) {
                        foreach ($grade_sections[10] as $section) {
                            $crypt = new Cryptor();
                            $newid = $crypt->encryptId($section);
                            echo "<li><a href='specific_sections.php?id=%27".$newid."%27'>$section</a></li>";
                        }
                    } else {
                        echo "<li>No sections available for Grade 10</li>";
                    }
                ?>
            </ul>
        </li>
        <li class="tabs">
            <div class="icon-link">
            <a href="#">
                <?php echo "<i class='poppins'>$grade_icons[11]</i>";?>
                <span class="link_name">Grade&nbsp;11</span>
            </a>
            <i class='bx bxs-chevron-down arrow' ></i>
            </div>
            <ul class="sub-menu">
                <?php
                    if (isset($grade_sections[11])) {
                        foreach ($grade_sections[11] as $section) {
                            $crypt = new Cryptor();
                            $newid = $crypt->encryptId($section);
                            echo "<li><a href='specific_sections.php?id=%27".$newid."%27'>$section</a></li>";
                        }
                    } else {
                        echo "<li>No sections available for Grade 11</li>";
                    }
                ?>
            </ul>
        </li>
        <li class="tabs">
            <div class="icon-link">
            <a href="#">
                <?php echo "<i class='poppins'>$grade_icons[12]</i>";?>
                <span class="link_name">Grade&nbsp;12</span>
            </a>
            <i class='bx bxs-chevron-down arrow' ></i>
            </div>
            <ul class="sub-menu">
                <?php
                    if (isset($grade_sections[12])) {
                        foreach ($grade_sections[12] as $section) {
                            $crypt = new Cryptor();
                            $newid = $crypt->encryptId($section);
                            echo "<li><a href='specific_sections.php?id=%27".$newid."%27'>$section</a></li>";
                        }
                    } else {
                        echo "<li>No sections available for Grade 12</li>";
                    }
                ?>
            </ul>
        </li>
        <li class="tabs">
            <a href="search.php">
            <i class='bi bi-search' ></i>
            <span class="link_name">Search</span>
            </a>
            <ul class="sub-menu blank">
            <li><a class="link_name" href="search.php">Search</a></li>
            </ul>
        </li>
        <li class="tabs">
            <a href="restore.php">
            <i class='bi bi-clock-history' ></i>
            <span class="link_name">Restore</span>
            </a>
            <ul class="sub-menu blank">
            <li><a class="link_name" href="restore.php">Restore</a></li>
            </ul>
        </li>
        <li class="tabs">
            <div class="icon-link">
            <a href="database.php">
                <i class="bi bi-database-fill-gear"></i>
                <span class="link_name">Database</span>
            </a>
            <i class='bx bxs-chevron-down arrow' ></i>
            </div>
            <ul class="sub-menu">
                <li><a href='database.php'>Manage&nbsp;Database</a></li>


            </ul>
        </li>
        <li>
        <div class="profile-details">
        <div class="profile-content">
            <img src="images/logo.png" alt="profileImg">
        </div>
        <div class="name-job">
            <div class="profile_name">
                GPTA Officer
            </div>
            <div class="job">Administrator</div>
        </div>
        <a href="logout_process.php"><i class='bx bx-log-out' ></i></a>
        </div>
    </li>
    </ul>
</div>

    