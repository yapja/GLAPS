<?php 
ob_start();
session_start();

require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');
require($_SERVER['DOCUMENT_ROOT'] . '/glaps/fpdf/fpdf.php');

$pdf =new FPDF();
$pdf->AddPage();

$now = new DateTime('now');
$month = $now->format('m');
$year = $now->format('Y');

$selected_year;
if (isset($_GET['year']))
{
    $selected_year = $_GET['year'];
}
else
{
    $selected_year = $year;
}

$selected_periodID;
if (isset($_GET['pid']))
{
    $selected_periodID = $_GET['pid'];
}
else
{
    $sql_period = "SELECT period_ID FROM period WHERE date_start < CURDATE() AND date_end >= CURDATE()";
    $result_period = $con->query($sql_period);
    while ($row = mysqli_fetch_array($result_period))
    {
        $selected_periodID = $row['period_ID'];
    }

}

$pdf->SetFont('Arial', 'B', 30);
$pdf->Cell(100, 15, 'Grand Legacy Builders',0,1);
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(100, 15, 'Payroll Summary',0,1);

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
$pdf->Cell(8, 8, 'NAME', 'TB',0);
$pdf->Cell(90, 8, '', 'TB',0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30, 8, 'PAYSLIP', 'TB',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(20, 8, '', 'TB',1);



$sql_paysum = "";
if (isset($_GET['year']))
{
    $selected_year = $_GET['year'];

    $sql_periodID = "SELECT period_ID FROM period where YEAR(date_end) = " . $selected_year;
    $result_periodID = $con->query($sql_periodID);
    $ids = array();
    while ($row = mysqli_fetch_array($result_periodID)) 
    {
        $period_ID = $row['period_ID'];
        array_push($ids, $period_ID);

    }

    $periodIDs = "";
    if (count($ids) >= 1) 
    {
        for ($idx=0; $idx<count($ids)-1; $idx++)
        {
            $periodIDs .= $ids[$idx] . ", ";
        }
        $periodIDs .= $ids[count($ids)-1];
    }
    else
    {
        $periodIDs .= "NULL";
    }
    $sql_paysum = "SELECT t1.employee_detail_ID, t1.first_name, t1.last_name, t2.basic_salary, t2.transportation, t2.gas, t2.food, t2.SSS, t2.HDMF, t2.PhilHealth, t2.late, t2.absent, t2.legal, t2.sunday, t2.sunday_excess, t2.special_day, t2.special_excess, t2.regular_holiday, t2.regular_excess, t2.bonus, t2.withholding_tax
        FROM
        (SELECT e.employee_ID, e.employee_detail_ID, e.first_name, e.last_name from employee e
        INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID WHERE ed.status='Active') t1
        LEFT OUTER JOIN
        (SELECT ed.employee_ID, ed.employee_detail_ID, SUM(s.basic_salary) AS basic_salary, SUM(s.transportation) AS transportation, SUM(s.gas) AS gas, SUM(s.food) AS food, SUM(s.SSS) AS SSS, SUM(s.HDMF) AS HDMF, SUM(s.PhilHealth) AS PhilHealth, SUM(s.late) AS late, SUM(s.absent) AS absent, SUM(s.legal) AS legal, SUM(s.sunday) AS sunday, SUM(s.sunday_excess) AS sunday_excess, SUM(s.special_day) AS special_day, SUM(s.special_excess) AS special_excess, SUM(s.regular_holiday) AS regular_holiday, SUM(s.regular_excess) AS regular_excess, SUM(s.bonus) AS bonus, SUM(s.withholding_tax) AS withholding_tax FROM employee_detail ed INNER JOIN salary_report s ON ed.employee_detail_ID = s.employee_detail_ID WHERE s.period_ID IN (". $periodIDs . ") AND s.status='Approved' GROUP BY employee_detail_ID) t2
        ON
        t1.employee_ID = t2.employee_ID";
}
else
{
    $sql_paysum = "SELECT t1.first_name, t1.last_name, t2.basic_salary, t2.transportation, t2.gas, t2.food, t2.SSS, t2.HDMF, t2.PhilHealth, t2.late, t2.absent, t2.legal, t2.sunday, t2.sunday_excess, t2.special_day, t2.special_excess, t2.regular_holiday, t2.regular_excess, t2.bonus, t2.withholding_tax
        FROM
        (SELECT e.employee_ID, e.first_name, e.last_name from employee e
        INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID WHERE ed.status='Active') t1
        LEFT OUTER JOIN
        (SELECT ed.employee_ID, ed.employee_detail_ID, SUM(s.basic_salary) AS basic_salary, SUM(s.transportation) AS transportation, SUM(s.gas) AS gas, SUM(s.food) AS food, SUM(s.SSS) AS SSS, SUM(s.HDMF) AS HDMF, SUM(s.PhilHealth) AS PhilHealth, SUM(s.late) AS late, SUM(s.absent) AS absent, SUM(s.legal) AS legal, SUM(s.sunday) AS sunday, SUM(s.sunday_excess) AS sunday_excess, SUM(s.special_day) AS special_day, SUM(s.special_excess) AS special_excess, SUM(s.regular_holiday) AS regular_holiday, SUM(s.regular_excess) AS regular_excess, SUM(s.bonus) AS bonus, SUM(s.withholding_tax) AS withholding_tax FROM employee_detail ed INNER JOIN salary_report s ON ed.employee_detail_ID = s.employee_detail_ID WHERE s.period_ID=". $selected_periodID ." AND s.status='Approved' GROUP BY employee_detail_ID) t2
        ON
        t1.employee_ID = t2.employee_ID";
}



$result_paysum = $con->query($sql_paysum);

$total = 0;
while ($row = mysqli_fetch_array($result_paysum))
{
    $name = $row['last_name'] . ', ' . $row['first_name'];
    $basic_salary = $row['basic_salary'];
    $transportation = $row['transportation'];
    $gas = $row['gas'];
    $food = $row['food'];
    $SSS = $row['SSS'];
    $HDMF = $row['HDMF'];
    $PhilHealth = $row['PhilHealth'];
    $late = $row['late'];
    $absent = $row['absent'];
    $legal = $row['legal'];
    $sunday = $row['sunday'];
    $sunday_excess = $row['sunday_excess'];
    $special_day = $row['special_day'];
    $special_excess = $row['special_excess'];
    $regular_holiday = $row['regular_holiday'];
    $regular_excess = $row['regular_excess'];
    $bonus = $row['bonus'];
    $withholding_tax = $row['withholding_tax'];
    $net_pay = $basic_salary + $transportation + $gas + $food - $SSS - $HDMF - $PhilHealth - $late - $absent + $legal + $sunday + $sunday_excess + $special_day + $special_excess + $regular_excess + $regular_holiday + $bonus - $withholding_tax;
    $netpay = number_format($net_pay, 2, '.', ', ');
    $total += $net_pay;
    $num_total = number_format($total, 2, '.', ', ');


    $pdf->SetFont('Arial','',12);
    $pdf->Cell(8, 8, $name, '',0);
    $pdf->Cell(90, 8, '', '',0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(30, 8, 'Php '. $netpay, '',0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(20, 8, '', '',1);
}

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(8, 8, 'TOTAL', 'TB',0);
    $pdf->Cell(90, 8, '', 'TB',0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(30, 8, 'Php '. $num_total, 'TB',0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(20, 8, '', 'TB',1);

$pdf->Output('','PayslipTotal');
?>