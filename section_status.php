<tr>
    <td><?php echo $section ?></td>
    <td>
    <?php
        $paid = "SELECT * FROM student_lists WHERE (status = 1 OR status = 2) AND section = '$section' AND active =0";
        $paid_run = mysqli_query($conn, $paid);

        if($paid_total = mysqli_num_rows($paid_run)){
            echo '<span class="badge bg-success badge-pill  px-3">'.$paid_total.'</span>';
        }
        else{
            echo '<span class="badge bg-success badge-pill  px-3">0</span>';
        }
    ?>
    </td>
    <td>
    <?php
        $not_paid = "SELECT * FROM student_lists WHERE status = 0 AND section = '$section' AND active = 0";
        $not_paid_run = mysqli_query($conn, $not_paid);

        if($not_paid_total = mysqli_num_rows($not_paid_run)){
            echo '<span class="badge bg-danger badge-pill  px-3">'.$not_paid_total.'</span>';
        }
        else{
            echo '<span class="badge bg-danger badge-pill  px-3">0</span>';
        }
    ?>                                
    </td>
    <td>
    <?php
        $total = "SELECT * FROM student_lists WHERE section = '$section' AND active = 0";
        $total_run = mysqli_query($conn, $total);

        if($total_total = mysqli_num_rows($total_run)){
            echo '<span class="badge bg-secondary badge-pill  px-3">'.$total_total.'</span>';
        }
        else{
            echo '<span class="badge bg-secondary badge-pill  px-3">0</span>';
        }
    ?>
    </td>
</tr>