<?php 
$page_title = "Payslip";
include_once('../../includes/header_it.php');

if (isset($_GET['pid']))
{
    $period_ID = $_GET['pid'];
}
else
{
    $sql_period = "SELECT period_ID FROM period WHERE date_start <= CURDATE() AND date_end >= CURDATE()";
    $result_period = $con->query($sql_period);
    while ($row = mysqli_fetch_array($result_period))
    {
        $period_ID = $row['period_ID'];
    }

}
$employee_detail_ID = $_SESSION['employee_detail_ID'];
$sql_salary = "SELECT basic_salary, transportation, gas, food, SSS, HDMF, PhilHealth, late, absent, legal, sunday, sunday_excess, special_day, special_excess, regular_holiday, regular_excess, bonus, withholding_tax, net_pay, status FROM salary_report WHERE employee_detail_ID = $employee_detail_ID AND period_ID = $period_ID";
$result_salary = $con->query($sql_salary);

$form = "hide";
$no_payslip = "show";

while ($row = mysqli_fetch_array($result_salary))
{
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
    $status = $row['status'];
    $withholding_tax = $row['withholding_tax'];
    $taxable_income = $basic_salary + $transportation + $gas + $food - $SSS - $HDMF - $PhilHealth - $late - $absent + $legal + $sunday + $sunday_excess + $special_day + $special_excess + $regular_excess + $regular_holiday + $bonus;
    $net_pay = $row['net_pay'];


    if ($status == 'Approved')
    {
        $form = "show";
        $no_payslip = "hide";
    }
    else
    {
        $form = "hide";
        $no_payslip = "show";
    }
}

$sql_period = "SELECT period_ID, date_start, date_end FROM period ORDER BY date_start";
$result_period = $con->query($sql_period);

$list_period = "";
while ($row = mysqli_fetch_array($result_period))
{
    $period_ID2= $row['period_ID'];
    $date_start = strtotime($row['date_start']);
    $date_end = strtotime($row['date_end']);
    $list_period .= "<option value='$period_ID2'>" . DATE('F d, Y', $date_start) . " - " . DATE('F d, Y', $date_end) . "</option>";
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



if (isset($_POST['next']))
{
    $period_ID = mysqli_real_escape_string($con, $_POST['period_ID']);
    header('location: mypayslip.php?pid=' . $period_ID);
}
?>

<form method="POST" class="form-horizontal">
    <div class="col-lg-12">
        <div class="form-group">
            <div class="col-lg-4">
                <select name='period_ID' class='form-control' required>
                    <option value=''>Select one...</option>
                    <?php echo " . $list_period . "?>
                </select>
            </div>
            <div class="col-lg-8">
                <button name="next" type="submit" class="btn btn-success">
                    Choose
                </button>
                <?php 
    echo "
                    <a href='../../reports/payslip.php?pid=$period_ID' class='btn btn-danger' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
                    ";
                ?>
            </div>
        </div>
        <div class="<?php echo $form ?>">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="control-label col-lg-4">Basic Pay</label>
                    <div class="col-lg-4">
                        <input name="basic_salary" type="text" class="form-control" value="₱ <?= number_format($basic_salary, 2, '.', ', ') ?>" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Allowance</label>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Transportation</label>
                    <div class="col-lg-4">
                        <input name="transportation" type="text" class="form-control" value="₱ <?= number_format($transportation, 2, '.', ', ') ?>" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Food</label>
                    <div class="col-lg-4">
                        <input name="food" type="text" class="form-control" value="₱ <?= number_format($food, 2, '.', ', ') ?>" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Gas</label>
                    <div class="col-lg-4">
                        <input name="gas" type="text" class="form-control" value="₱ <?= number_format($gas, 2, '.', ', ') ?>" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Overtime</label>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Legal</label>
                    <div class="col-lg-4">
                        <input name="legal_overtime" type="text" class="form-control" value="₱ <?= number_format($legal, 2, '.', ', ') ?>" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Sunday</label>
                    <div class="col-lg-4">
                        <input name="sunday_overtime" type="text" class="form-control" value="₱ <?= number_format($sunday, 2, '.', ', ') ?>" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Sunday Excess</label>
                    <div class="col-lg-4">
                        <input name="sunday_excess" type="text" class="form-control" value="₱ <?= number_format($sunday_excess, 2, '.', ', ') ?>" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Special Day</label>
                    <div class="col-lg-4">
                        <input name="special_day" type="text" class="form-control" value="₱ <?= number_format($special_day, 2, '.', ', ') ?>" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Special Day Excess</label>
                    <div class="col-lg-4">
                        <input name="special_excess" type="text" class="form-control" value="₱ <?= number_format($special_excess, 2, '.', ', ') ?>" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Regular Holiday</label>
                    <div class="col-lg-4">
                        <input name="regular_holiday" type="text" class="form-control" value="₱ <?= number_format($regular_holiday, 2, '.', ', ') ?>" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Regular Holiday Excess</label>
                    <div class="col-lg-4">
                        <input name="regular_excess" type="text" class="form-control" value="₱ <?= number_format($regular_excess, 2, '.', ', ') ?>" disabled />
                    </div>
                </div>

            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="control-label col-lg-4">13th Month Pay</label>
                    <div class="col-lg-4">
                        <input name="bonus" type="text" class="form-control" value="₱ <?= number_format($bonus, 2, '.', ', ') ?>" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Attendance Deductions</label>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Late</label>
                    <div class="col-lg-4">
                        <input name="late" type="text" class="form-control" value="(₱ <?= number_format($late, 2, '.', ', ') ?>)" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Absent</label>
                    <div class="col-lg-4">
                        <input name="absent" type="text" class="form-control" value="(₱ <?= number_format($absent, 2, '.', ', ') ?>)" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Contributions</label>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">SSS</label>
                    <div class="col-lg-4">
                        <input name="SSS" type="text" class="form-control" value="(₱ <?= number_format($SSS, 2, '.', ', ') ?>)" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Pag-ibig</label>
                    <div class="col-lg-4">
                        <input name="HDMF" type="text" class="form-control" value="(₱ <?= number_format($HDMF, 2, '.', ', ') ?>)" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">PhilHealth</label>
                    <div class="col-lg-4">
                        <input name="PhilHealth" type="text" class="form-control" value="(₱ <?= number_format($PhilHealth, 2, '.', ', ') ?>)" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4"></label>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Taxable Income</label>
                    <div class="col-lg-4">
                        <input name="taxable_income" type="text" class="form-control" value="₱ <?= number_format($taxable_income, 2, '.', ', ') ?>" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4"></label>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">Withholding Tax</label>
                    <div class="col-lg-4">
                        <input name="withholding_tax" type="text" class="form-control" value="(₱ <?= number_format($withholding_tax, 2, '.', ', ') ?>)" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4"></label>
                </div>
                <?php
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
                 echo "
                <div class='form-group'>
			         <label class='control-label col-lg-4'>Discrepancy Amount</label>
			     <div class='col-lg-4'>
				     <input name='net_pay' type='text' class='form-control' value='₱ ". number_format($amount, 2, '.', ', ') ." ' disabled />
			     </div>
		        </div>";
             }
                ?>
                <hr style="color='black' "/>
                <div class="form-group">
                    <label class="control-label col-lg-4">Net Pay</label>
                    <div class="col-lg-4">
                        <input name="net_pay" type="text" class="form-control" value="₱ <?= number_format($net_pay, 2, '.', ', ') ?>" disabled />
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="<?php echo $no_payslip ?>" >
    <h1>Payslip not yet approved</h1>
</div>

<?php
    include_once('../../includes/footer.php');