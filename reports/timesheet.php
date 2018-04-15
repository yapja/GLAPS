<?php 
    ob_start();
    session_start();

    require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');
    require($_SERVER['DOCUMENT_ROOT'] . '/glaps/fpdf/fpdf.php');

    $pdf =new FPDF();
    $pdf->AddPage();

    if(isset($_GET['id']))
    {   
        $employee_detail_ID = $_GET['id'];
        if (isset($_GET['ds']) && isset($_GET['de']))
        {
            $date_start = $_GET['ds'];
            $date_end = $_GET['de'];
            $sql_employee = "SELECT e.employee_ID, e.first_name, e.middle_name, e.last_name, ed.profile_picture, (SELECT DATE_FORMAT(date, '%M %d, %Y') FROM attendance WHERE attendance_ID = a.attendance_ID) AS date, (SELECT DATE_FORMAT(time_in, '%h:%i %p')FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_in, (SELECT DATE_FORMAT(time_out, '%h:%i %p') FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_out, a.in_image, a.out_image, a.status, (SELECT TIMESTAMPDIFF(HOUR, time_in, time_out) FROM attendance WHERE attendance_id = a.attendance_ID) AS total_hours, a.attendance_ID 
            FROM employee e
            INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
            INNER JOIN attendance a ON a.employee_detail_ID = ed.employee_detail_ID
            WHERE ed.status != 'Archived' AND ed.employee_detail_ID = $employee_detail_ID AND a.time_in >= '$date_start' AND a.time_in <= '$date_end'";
        }
        else
        {
            $sql_employee = "SELECT e.employee_ID, e.first_name, e.middle_name, e.last_name, ed.profile_picture, (SELECT DATE_FORMAT(date, '%M %d, %Y') FROM attendance WHERE attendance_ID = a.attendance_ID) AS date, (SELECT DATE_FORMAT(time_in, '%h:%i %p')FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_in, (SELECT DATE_FORMAT(time_out, '%h:%i %p') FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_out, a.in_image, a.out_image, a.status, (SELECT TIMESTAMPDIFF(HOUR, time_in, time_out) FROM attendance WHERE attendance_id = a.attendance_ID) AS total_hours, a.attendance_ID 
            FROM employee e
            INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
            INNER JOIN attendance a ON a.employee_detail_ID = ed.employee_detail_ID
            WHERE ed.status != 'Archived' AND ed.employee_detail_ID = $employee_detail_ID";
        }
        $result_employee = $con->query($sql_employee);
    }
    else
    {
        $employee_detail_ID = $_SESSION['employee_detail_ID'];
        if (isset($_GET['ds']) && isset($_GET['de']))
        {
            $date_start = $_GET['ds'];
            $date_end = $_GET['de'];
            $sql_employee = "SELECT e.employee_ID, e.first_name, e.middle_name, e.last_name, ed.profile_picture, (SELECT DATE_FORMAT(date, '%M %d, %Y') FROM attendance WHERE attendance_ID = a.attendance_ID) AS date, (SELECT DATE_FORMAT(time_in, '%h:%s %p')FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_in, (SELECT DATE_FORMAT(time_out, '%h:%s %p') FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_out, a.in_image, a.out_image, a.status, (SELECT TIMESTAMPDIFF(HOUR, time_in, time_out) FROM attendance WHERE attendance_id = a.attendance_ID) AS total_hours
            FROM employee e
            INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
            INNER JOIN attendance a ON a.employee_detail_ID = ed.employee_detail_ID
            WHERE ed.status != 'Archived' AND ed.employee_detail_ID = $employee_detail_ID AND a.time_in >= '$date_start' AND a.time_in <= '$date_end'";
        }
        else
        {
            $sql_employee = "SELECT e.employee_ID, e.first_name, e.middle_name, e.last_name, ed.profile_picture, (SELECT DATE_FORMAT(date, '%M %d, %Y') FROM attendance WHERE attendance_ID = a.attendance_ID) AS date, (SELECT DATE_FORMAT(time_in, '%h:%s %p')FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_in, (SELECT DATE_FORMAT(time_out, '%h:%s %p') FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_out, a.in_image, a.out_image, a.status, (SELECT TIMESTAMPDIFF(HOUR, time_in, time_out) FROM attendance WHERE attendance_id = a.attendance_ID) AS total_hours
            FROM employee e
            INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
            INNER JOIN attendance a ON a.employee_detail_ID = ed.employee_detail_ID
            WHERE ed.status != 'Archived' AND ed.employee_detail_ID = $employee_detail_ID";
        }

        $result_employee = $con->query($sql_employee);
    }

    $date = mysqli_fetch_array($result_employee);
    $date_start = $date['date_start'];
    $date_end = $date['date_end'];

    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(20, 15, '', 0,0);
    $pdf->Cell(100, 15, 'TIMESHEET',0,1);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(20,8, 'Duration:', 0,0);
    $pdf->Cell(10,8,$date_start, 0,0);
    $pdf->Cell(10,8,$date_end, 0,1);
    $pdf->Cell(20,8, '',0,1);
    $pdf->Cell(42,8, 'DATE','TB',0);
    $pdf->Cell(30,8, 'SHIFT','TB',0);
    $pdf->Cell(30,8, 'TIME-IN','TB',0);
    $pdf->Cell(25,8, 'TIME-IN PHOTO','TB',0);
    $pdf->Cell(30,8, 'TIME-OUT','TB',0);
    $pdf->Cell(20,8, 'TIME-OUT PHOTO','TB',1);

    while($emp = mysqli_fetch_array($result_employee))
    {
    $pdf->Cell(40, 8, $emp['date'], 0,0);
    $pdf->Cell(30, 8, '8am - 5pm', 0,0);
    $pdf->Cell(32, 8, $emp['time_in'],0,0);
    $pdf->Cell(25, 8, $emp['time_out'],0,0);
    $pdf->Cell(10, 8, '',0,0);
    $pdf->Cell(30, 8, $emp['total_hours'],0,0);

    if($emp['total_hours']>8)
        $overtime = $emp['total_hours'] - 8;

    else
        $overtime = 0;

    $pdf->Cell(20,8, $overtime, 0,1);
    }

    $pdf->Output('', 'Timesheet.pdf');
?>

