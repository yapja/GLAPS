<?php 
ob_start();
session_start();

require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');
require($_SERVER['DOCUMENT_ROOT'] . '/glaps/fpdf/fpdf.php');

$pdf =new FPDF();
$pdf->AddPage();

if (isset($_GET['pid']))
{
    $period_ID = $_GET['pid'];
}
else
{
    $sql_period = "SELECT period_ID FROM period WHERE date_start < CURDATE() AND date_end >= CURDATE()";
    $result_period = $con->query($sql_period);
    while ($row = mysqli_fetch_array($result_period))
    {
        $period_ID = $row['period_ID'];
    }

}

$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(100, 15, 'Grand Legacy Builders',0,1);
$pdf->SetFont('Arial','B',12);


$sql_period = "SELECT DATE_FORMAT(date_start, '%M %d, %Y') as date_start, DATE_FORMAT(date_end,'%M %d, %Y') as date_end, DATE_FORMAT(NOW(), '%M %d, %Y') as date
		FROM period p
		WHERE p.period_ID = $period_ID";
$result_period = $con->query($sql_period);
while($per = mysqli_fetch_array($result_period))
{
    $pdf->Cell(20, 8, 'DATE:', 'TB',0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(50, 8, $per['date'], 'TB',0);
    $date_start = $per['date_start'];
    $date_end = $per['date_end'];
    $pay_period = $date_start . ' - ' . $date_end;

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(30, 8, 'PAY PERIOD:', 'TB',0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(70, 8, $pay_period, 'TB',1);
}



if (isset($_GET['eid'])) 
{
    $employee_detail_ID = $_GET['eid'];
}
else
{
    $employee_detail_ID = $_SESSION['employee_detail_ID'];
}


$sql_period = "SELECT date_start, date_end, cutoff, previous_cutoff FROM period WHERE period_ID = $period_ID";
$result_period = $con->query($sql_period);
while ($row = mysqli_fetch_array($result_period))
{
    $date_start = $row['date_start'];
    $date_end = $row['date_end'];
    $cutoff = $row['cutoff'];
    $previous_cutoff = $row['previous_cutoff'];
    $count_sunday = 0;
    $start = strtotime($previous_cutoff);
    $current_date = $row['date_end'];
    $current_end = $row['cutoff'];
    while ($start <= strtotime($cutoff))
    {
        if(date("N", $start) == 7)
        {
            $count_sunday++;
        }
        $start += 86400;
    }
    $working_days = (((strtotime($cutoff) - strtotime($previous_cutoff))) / 86400) - $count_sunday - 1;
}


$sql_employee = "SELECT e.first_name, e.middle_name, e.last_name 
		FROM employee e
		WHERE e.employee_detail_ID = $employee_detail_ID";
$result_employee = $con->query($sql_employee);
while($emp = mysqli_fetch_array($result_employee))
{
    $last_name = $emp['last_name'];
    $first_name = $emp['first_name'];
    $middle_name = $emp['middle_name'];
    $fullname = $last_name .', '. $first_name .' '. $middle_name;
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(35, 20, 'Employee Name:',0,0);
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50, 20, $fullname, 0,1);
}

$sql_salary = "SELECT basic_salary, transportation, gas, food, SSS, HDMF, PhilHealth, late, absent, legal, sunday, sunday_excess, special_day, special_excess, regular_holiday, regular_excess, bonus, withholding_tax, net_pay 
		FROM salary_report
		WHERE employee_detail_ID = $employee_detail_ID AND period_ID = $period_ID";
$result_salary = $con->query($sql_salary);
while ($data = mysqli_fetch_array($result_salary))
{


    $basic_salary = $data['basic_salary'];
    $transportation = $data['transportation'];
    $gas = $data['gas'];
    $food = $data['food'];
    $SSS = $data['SSS'];
    $HDMF = $data['HDMF'];
    $PhilHealth = $data['PhilHealth'];
    $late = $data['late'];
    $absent = $data['absent'];
    $legal = $data['legal'];
    $sunday = $data['sunday'];
    $sunday_excess = $data['sunday_excess'];
    $special_day = $data['special_day'];
    $special_excess = $data['special_excess'];
    $regular_holiday = $data['regular_holiday'];
    $regular_excess = $data['regular_excess'];
    $bonus = $data['bonus'];
    $withholding_tax = $data['withholding_tax'];
    $taxable_income = $basic_salary + $transportation + $gas + $food - $SSS - $HDMF - $PhilHealth - $late - $absent + $legal + $sunday + $sunday_excess + $special_day + $special_excess + $regular_excess + $regular_holiday + $bonus;
    $net_pay = $data['net_pay'];;



    $pdf->Cell(20,8,'',0,1);
    $pdf->Cell(20,8,'',0,0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(100,8,'Basic pay',0,0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(50,8,$basic_salary,0,1);
    $pdf->Cell(20,8,'',0,0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(120,8,'Allowance','T',1);
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'Transportation',0,0);
    $pdf->Cell(50,8,$transportation,0,1);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'Gas',0,0);
    $pdf->Cell(50,8,$gas,0,1);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'Food',0,0);
    $pdf->Cell(50,8,$food,0,1);

    $pdf->Cell(20,8,'',0,0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(120,8,'Overtime pay','T',1);
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'Legal',0,0);
    $pdf->Cell(50,8,$legal,0,1);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'Sunday',0,0);
    $pdf->Cell(50,8,$sunday,0,1);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'in excess of 8 hours',0,0);
    $pdf->Cell(50,8,$sunday_excess,0,1);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'Special day',0,0);
    $pdf->Cell(50,8,$special_day,0,1);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'in excess of 8 hours',0,0);
    $pdf->Cell(50,8,$special_excess,0,1);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'Regular Holiday',0,0);
    $pdf->Cell(50,8,$regular_holiday,0,1);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'in excess of 8 hours',0,0);
    $pdf->Cell(50,8,$regular_excess,0,1);

    $pdf->Cell(20,8,'',0,0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(120,5,'Attendance','T',1);
    $pdf->Cell(20,5,'',0,0);
    $pdf->Cell(70,5,'Deduction',0,1);
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'Tardiness',0,0);
    $pdf->Cell(50,8,$late,0,1);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'Absence',0,0);
    $pdf->Cell(50,8,$absent,0,1);

    $pdf->Cell(20,8,'',0,0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(120,8,'Bonus','T',1);
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'13th Month',0,0);
    $pdf->Cell(50,8,$bonus,0,1);

    $pdf->Cell(20,8,'',0,0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(120,8,'Contributions','T',1);
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'SSS',0,0);
    $pdf->Cell(50,8,$SSS,0,1);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'HDMF',0,0);
    $pdf->Cell(50,8,$HDMF,0,1);
    $pdf->Cell(60,8,'',0,0);
    $pdf->Cell(60,8,'PhilHealth',0,0);
    $pdf->Cell(50,8,$PhilHealth,0,1);

    $pdf->Cell(20,8,'',0,0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(100,8,'Taxable Income','T',0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(20,8,$taxable_income,'T',1);
    

    $pdf->Cell(20,8,'',0,0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(100,8,'Withholding Tax','T',0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(20,8,$withholding_tax,'T',1);

    $sql_discrepancy = "SELECT salary_report_discrepancy_ID, amount FROM salary_report_discrepancy WHERE employee_detail_ID = $employee_detail_ID AND status = 'Approved' AND DATEDIFF('$current_end', period_end) > 5 AND DATEDIFF('$current_end', period_end) <= 16";
    $result_discrepancy = $con->query($sql_discrepancy);
    $amount = 0;
    while ($row = mysqli_fetch_array($result_discrepancy))
    {
        $amount = $row['amount'];
        $salary_report_discrepancy_ID = $row['salary_report_discrepancy_ID'];
    }

    if ($amount != 0) 
    {
        $pdf->Cell(20,8,'',0,0);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(100,8,'Discrepancy Amount','T',0);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(20,8,$amount,'T',1);
    }
    
    
    $pdf->Cell(20,8,'',0,0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(100,8,'Net Pay','TB',0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(20,8,$net_pay,'TB',1);
}

$pdf->Output('', 'Payslip.pdf');
?>