<?php 
    ob_start();
    session_start();

    require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');
    require($_SERVER['DOCUMENT_ROOT'] . '/glaps/fpdf/fpdf.php');

    $pdf =new FPDF();
    $pdf->AddPage();

    $selected_periodID;
    if (isset($_GET['pid']))
	{
		$selected_periodID = $_GET['pid'];
	}
	else
	{
		$sql_period = "SELECT period_ID FROM period WHERE NOW() > previous_cutoff AND NOW() <= cutoff";
		$result_period = $con->query($sql_period);
		while ($row = mysqli_fetch_array($result_period))
		{
			$selected_periodID = $row['period_ID'];
		}
	
	}

    $pdf->SetFont('Arial', 'B', 30);
    $pdf->Cell(100, 15, 'Grand Legacy Builders',0,1);
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(100, 15, 'Dashboard',0,1);

    $sql_period = "SELECT date_start, date_end
		FROM period
		WHERE period_ID = $selected_periodID";
	$result_period = $con->query($sql_period);

	while($per = mysqli_fetch_array($result_period))
	{
		$date_start = $per['date_start'];
		$date_end = $per['date_end'];
		$pay_period = $date_start . ' to ' . $date_end;

		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(30, 15, 'FOR PERIOD:', '',0);
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(60, 15, $pay_period, '',1);
	}

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(50, 8, 'Name', 'TB',0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(50, 8, 'Overtime(hours)', 'TB',0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(30, 8, 'Lates', 'TB',0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(30, 8, 'Absents', 'TB',0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(30, 8, 'Leaves', 'TB',0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(20, 8, '', '',1);
    

    $sql_dashboard = "SELECT e.employee_ID, e.employee_detail_ID, e.last_name, e.first_name
		FROM employee e
		INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
        INNER JOIN account a ON a.account_ID = ed.account_ID
		WHERE a.status != 'Archived' AND a.status != 'Pending'";
    $result_dashboard = $con->query($sql_dashboard);
    
    $sql_period_dates = "SELECT date_start, date_end FROM period WHERE period_ID=" . $selected_periodID;
    $result_period_dates = $con->query($sql_period_dates);

    while ($row = mysqli_fetch_array($result_period_dates))
    {
        $period_start = $row['date_start'];
        $period_end = $row['date_end'];
    }

    while ($row = mysqli_fetch_array($result_dashboard))
    {
        $name = $row['last_name'] . ', ' . $row['first_name'];
        $employee_detail_ID = $row['employee_detail_ID'];

        $sql_overtime = "SELECT SUM(TIMESTAMPDIFF(HOUR, date_start, date_end)) AS hours FROM employee_overtime WHERE employee_detail_ID = $employee_detail_ID AND status = 'Approved' AND DATE(date_start) >= '$period_start' AND DATE(date_start) <= '$period_end'";
        $result_overtime = $con->query($sql_overtime);
        $overtime = 0;
        while ($row = mysqli_fetch_array($result_overtime))
        {
            $overtime += $row['hours'];
        }

        $sql_late = "SELECT COUNT(*) AS late FROM attendance WHERE employee_detail_ID = $employee_detail_ID AND STATUS = 'Late' AND date > '$period_start' AND date <= '$period_end'";
        $result_late = $con->query($sql_late);
        while ($row = mysqli_fetch_array($result_late))
        {
            $late = $row['late'];
        }

        $sql_first_day = "SELECT MIN(DATE(time_in)) AS first_day FROM attendance
        WHERE employee_detail_ID = $employee_detail_ID AND date >= '$period_start' AND date <= '$period_end'";
        $result_first_day = $con->query($sql_first_day);

        while ($row = mysqli_fetch_array($result_first_day))
        {
            $first_day = $row['first_day'];
        }

        if ($first_day != NULL)
        {
            $start_date = strtotime($first_day);
            $current_date = strtotime($period_end);

            $total_sundays = 0;
            $total_days = ((($current_date - $start_date)) / 86400) + 1;
            while ($start_date <= $current_date)
            {
                if(date("N", $start_date) == 7)
                {
                    $total_sundays++;
                }
                $start_date += 86400;
            }
            $total_days -= $total_sundays;

            $sql_leave = "SELECT DATE(elt.date_start) AS date_start, DATE(elt.date_end) as date_end, lt.type FROM employee_leave_taken elt INNER JOIN leave_type lt ON elt.leave_type_ID = lt.leave_type_ID WHERE elt.employee_detail_ID = $employee_detail_ID AND DATE(elt.date_start) <= '$period_end' AND DATE(elt.date_end) >= '$period_start' AND elt.status = 'Approved'";
            $result_leave = $con->query($sql_leave);

            $leave = 0;
            while ($row = mysqli_fetch_array($result_leave))
            {
                $date_start2 = strtotime($row['date_start']);
                $date_end2 = strtotime($row['date_end']);
                $leave_type = $row['type'];

                while ($date_start2 < strtotime($period_start)) // start date of leave is before start date of filter
                {
                    $date_start2 += 86400;
                }

                if ($date_end2 > strtotime($period_end)) 
                {
                    $date_end2 = strtotime($period_end);
                }

                while ($date_start2 <= $date_end2)
                {
                    $leave++;
                    $date_start2 += 86400;
                }
            }
            $total_days -= $leave;

            $sql_present = "SELECT COUNT(*) AS total_present FROM attendance WHERE employee_detail_ID = $employee_detail_ID AND DATE(time_in) >= '$period_start' AND DATE(time_in) <= '$period_end' AND status != 'Invalid'"; 
            $result_present = $con->query($sql_present);

            while ($row = mysqli_fetch_array($result_present))
            {
                $absent = $total_days - $row['total_present'];
            }

            $pdf->SetFont('Arial','',12);
            $pdf->Cell(50, 8, $name, '',0);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(50, 8, $overtime, '',0);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(30, 8, $late, '',0);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(30, 8, $absent, '',0);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(30, 8, $leave, '',0);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(20, 8, '', '',1);
        }
        else 
        {
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(50, 8, $name, '',0);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(50, 8, 0, '',0);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(30, 8, 0, '',0);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(30, 8, 0, '',0);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(30, 8, 0, '',0);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(20, 8, '', '',1);
        }
    }

	$pdf->Output('','dashboard');
?>