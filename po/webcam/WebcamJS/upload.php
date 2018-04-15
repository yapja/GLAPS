<?php
    session_start();
    include_once('../../../config.php');
    $uploaddir = '../../../images/attendance/';
    $uploadname = date('d-m-Y_H-i-s') . '.jpg';
    $uploadfile = $uploaddir . $uploadname;
    $employee_detail_ID = $_SESSION['employee_detail_ID'];

    $sql_check = "SELECT attendance_ID, HOUR(time_in) as hour FROM attendance WHERE employee_detail_ID = $employee_detail_ID AND date = DATE(NOW())";
    $result_data = $con->query($sql_check);

    if (mysqli_fetch_array($result_data) > 0)
    {
        $sql_attendance = "UPDATE attendance SET time_out = NOW(), out_image = '$uploadname' WHERE employee_detail_ID = $employee_detail_ID AND date = DATE(NOW())";
    }
    else
    {
        if ($row['hour'] < 8)
            $sql_attendance = "INSERT INTO attendance VALUES ('', $employee_detail_ID, NOW(), NOW(), NULL, '$uploadname', NULL, 'Present')";
        else
            $sql_attendance = "INSERT INTO attendance VALUES ('', $employee_detail_ID, NOW(), NOW(), NULL, '$uploadname', NULL, 'Late')";
    }
    $con->query($sql_attendance) or die(mysqli_error($con));

    move_uploaded_file($_FILES['webcam']['tmp_name'], $uploadfile);
    $_SESSION['uploadname'] = $uploadname;
       
?>