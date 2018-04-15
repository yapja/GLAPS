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

        $employee_detail_ID = $_GET['id'];
        if (isset($_GET['ds']) && isset($_GET['de']))
        {
            $date_start = $_GET['ds'];
            $date_end = $_GET['de'];
            $sql_employee = "SELECT e.employee_ID, e.first_name, e.middle_name, e.last_name, ed.profile_picture, (SELECT DATE_FORMAT(date, '%M %d, %Y') FROM attendance WHERE attendance_ID = a.attendance_ID) AS date, (SELECT DATE_FORMAT(time_in, '%h:%i %p')FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_in, (SELECT DATE_FORMAT(time_out, '%h:%i %p') FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_out, a.in_image, a.out_image, a.status, (SELECT TIMESTAMPDIFF(HOUR, time_in, time_out) FROM attendance WHERE attendance_id = a.attendance_ID) AS total_hours, a.attendance_ID
            FROM employee e
            INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
            
            INNER JOIN attendance a ON a.employee_detail_ID = ed.employee_detail_ID
            WHERE ed.status != 'Archived' AND ed.employee_detail_ID = $employee_detail_ID AND DATE(a.time_in) >= '$date_start' AND DATE(a.time_in) <= '$date_end'";
        }
        else
        {
            $sql_employee = "SELECT e.employee_ID, e.first_name, e.middle_name, e.last_name, ed.profile_picture, (SELECT DATE_FORMAT(date, '%M %d, %Y') FROM attendance WHERE attendance_ID = a.attendance_ID) AS date, (SELECT DATE_FORMAT(time_in, '%h:%i %p')FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_in, (SELECT DATE_FORMAT(time_out, '%h:%i %p') FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_out, a.in_image, a.out_image, a.status, (SELECT TIMESTAMPDIFF(HOUR, time_in, time_out) FROM attendance WHERE attendance_id = a.attendance_ID) AS total_hours, a.attendance_ID
            FROM employee e
            FROM employee e
            INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
            
            INNER JOIN attendance a ON a.employee_detail_ID = ed.employee_detail_ID
            WHERE ed.status != 'Archived' AND ed.employee_detail_ID = $employee_detail_ID";
        }
        
        $result_employee = $con->query($sql_employee);

        if (!isset($_GET['ds']) || !isset($_GET['de']))
        {
            $today = strtotime("today");
            $date_end = date('Y-m-d', $today);
                            
            $sql_first = "SELECT MIN(DATE(time_in)) AS first_day FROM attendance WHERE employee_detail_ID = $employee_detail_ID";
            $result_first = $con->query($sql_first);
            while ($row = mysqli_fetch_array($result_first))
            {
                $first_day = strtotime($row['first_day']);
            }
            
            $date_start = date('Y-m-d', $first_day);
        }
                        
        $sql_leave = "SELECT DATE(elt.date_start) AS date_start, DATE(elt.date_end) as date_end, lt.type 
        FROM employee_leave_taken elt 
        INNER JOIN leave_type lt ON elt.leave_type_ID = lt.leave_type_ID WHERE elt.employee_detail_ID = 
        $employee_detail_ID AND DATE(elt.date_start) <= '$date_end' AND DATE(elt.date_end) >= '$date_start' 
        AND elt.status = 'Approved'";
                
        $result_leave = $con->query($sql_leave);

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

        if (!isset($_GET['ds']) || !isset($_GET['de']))
        {
            $today = strtotime("today");
            $date_end = date('Y-m-d', $today);
                            
            $sql_first = "SELECT MIN(DATE(time_in)) AS first_day FROM attendance WHERE employee_detail_ID = $employee_detail_ID";
            $result_first = $con->query($sql_first);
            while ($row = mysqli_fetch_array($result_first))
            {
                $first_day = strtotime($row['first_day']);
            }
            
            $date_start = date('Y-m-d', $first_day);
        }
                        
        $sql_leave = "SELECT DATE_FORMAT(elt.date_start, '%M %d, %Y') AS date_start, DATE_FORMAT(elt.date_end, '%M %d, %Y') as date_end, lt.type, elt.total_days 
        FROM employee_leave_taken elt 
        INNER JOIN leave_type lt ON elt.leave_type_ID = lt.leave_type_ID WHERE elt.employee_detail_ID = 
        $employee_detail_ID AND DATE(elt.date_start) <= '$date_end' AND DATE(elt.date_end) >= '$date_start' 
        AND elt.status = 'Approved'";
                
        $result_leave = $con->query($sql_leave);
                        
    }

    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(100, 15, 'GLAPS LEAVES TIMESHEET',0,1);
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(30, 15, 'Employee:', 0,0);
    $pdf->SetFont('Arial', '', 15);

    $name = mysqli_fetch_array($result_employee);
    
        $first_name = $name['first_name'];
        $middle_name = $name['middle_name'];
        $last_name = $name['last_name'];
        $full_name = $last_name . ', ' . $first_name . ' ' . $middle_name;
        $pdf->Cell(10,15, $full_name, 0,1);
   
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(20,8,'',0,0);
    $pdf->Cell(40,8, 'DATE START','TB',0);
    $pdf->Cell(35,8, 'DATE END','TB',0);
    $pdf->Cell(40,8, 'TOTAL DAYS', 'TB', 0);
    $pdf->Cell(30,8, 'LEAVE TYPE', 'TB',1);

    while($leave = mysqli_fetch_array($result_leave))
    {
    
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20,8,'',0,0);
        
        $pdf->Cell(40, 8, $leave['date_start'],0,0);
        $pdf->Cell(42, 8, $leave['date_end'],0,0);
        $pdf->Cell(32,8, $leave['total_days'], 0,0);
        $pdf->Cell(25,8, $leave['type'],0,1);
       

/*        $pdf->Cell(30, 8, $emp['total_hours'],0,0);

        if($emp['total_hours']>8)
            $overtime = $emp['total_hours'] - 8;

        else
            $overtime = 0;

        $pdf->Cell(20,8, $overtime, 0,1);*/
    }

    $pdf->Output('', 'Timesheet.pdf');
?>

