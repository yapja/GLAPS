<?php 
	$page_title = "Department Payroll Summary";
    include_once('../../includes/header_po.php');

    $now = new DateTime('now');
    $month = $now->format('m');
    $year = $now->format('Y');

    $selected_periodID;
    if (isset($_GET['pid']))
	{
		$selected_periodID = $_GET['pid'];
	}
	else
	{
		$sql_period = "SELECT period_ID FROM period WHERE date_start <= CURDATE() AND date_end >= CURDATE()";
		$result_period = $con->query($sql_period);
		while ($row = mysqli_fetch_array($result_period))
		{
			$selected_periodID = $row['period_ID'];
		}
	
	}

    $sql_deptpay = "SELECT t1.department, t2.basic_salary, t2.transportation, t2.gas, t2.food, t2.SSS, t2.HDMF, t2.PhilHealth, t2.late, t2.absent, t2.legal, t2.sunday, t2.sunday_excess, t2.special_day, t2.special_excess, t2.regular_holiday, t2.regular_excess, t2.bonus, t2.withholding_tax, t2.net_pay
    FROM
    (SELECT c.department, c.department_ID from department c) t1
    LEFT OUTER JOIN
    (SELECT d.department, d.department_ID, ed.employee_detail_ID, SUM(s.basic_salary) AS basic_salary, SUM(s.transportation) AS transportation, SUM(s.gas) AS gas, SUM(s.food) AS food, SUM(s.SSS) AS SSS, SUM(s.HDMF) AS HDMF, SUM(s.PhilHealth) AS PhilHealth, SUM(s.late) AS late, SUM(s.absent) AS absent, SUM(s.legal) AS legal, SUM(s.sunday) AS sunday, SUM(s.sunday_excess) AS sunday_excess, SUM(s.special_day) AS special_day, SUM(s.special_excess) AS special_excess, SUM(s.regular_holiday) AS regular_holiday, SUM(s.regular_excess) AS regular_excess, SUM(s.bonus) AS bonus, SUM(s.withholding_tax) AS withholding_tax, SUM(s.net_pay) AS net_pay FROM employee_detail ed INNER JOIN department d ON ed.department_ID = d.department_ID INNER JOIN salary_report s ON ed.employee_detail_ID = s.employee_detail_ID WHERE s.period_ID=". $selected_periodID ." AND s.status='Approved' GROUP BY d.department) t2
    ON
    t1.department_ID = t2.department_ID";
    $result_deptpay = $con->query($sql_deptpay);
    
    $sql_period = "SELECT period_ID, date_start, date_end FROM period ORDER BY date_start";
    $result_period = $con->query($sql_period);

    $list_period = "";
	while ($row = mysqli_fetch_array($result_period))
	{
		$period_ID= $row['period_ID'];
		$date_start = strtotime($row['date_start']);
		$date_end = strtotime($row['date_end']);
		$list_period .= "<option value='" . $period_ID . "'";
        if ($period_ID == $selected_periodID) 
        {
            $list_period .= " selected='selected'";
        }
        $list_period .= "'>" . DATE('F d, Y', $date_start) . " - " . DATE('F d, Y', $date_end) . "</option>";
    }
    
    if (isset($_POST['next']))
	{
        $period_ID = mysqli_real_escape_string($con, $_POST['period']);
        header('location: deptpayslip.php?pid=' . $period_ID);
    }
?>



<form method="POST" class="form-horizontal">
    <div class="col-lg-6">
        <div class="form-group">
            <div class="col-lg-6">
                <select name='period' class='form-control' required>
                    <option value=''>Select one...</option>
                    <?php echo " . $list_period . "?>
                </select>
			</div>
            <div class="col-lg-6">
                <button name="next" type="submit" class="btn btn-success">
                    Choose
                </button>
                <?php 
                echo "
                    <a href='../../reports/deptpayreport.php?pid=$selected_periodID' class='btn btn-danger' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
                    ";
                    ?>
            </div>
        </div>
    </div>
    
	<div class="col-lg-6">
            <table class="table table-hover">
                <thead>
                    <th>Department</th>
                    <th>Total Payslip</th>
                </thead>
                <tbody>
                    <?php
                        while ($row = mysqli_fetch_array($result_deptpay))
                        {
                            $department = $row['department'];
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
                            $net_pay = $row['net_pay'];
                            $netpay = number_format($net_pay, 2, '.', ', ');
                            echo "
                                <tr>
                                    <td>$department</td>
                                    <td>$netpay</td>
                                </tr>
                            ";
                        }

                    ?>
                </tbody>
            </table>
            <script>
                $(document).ready( function() {
                    $('#tblUsers').dataTable({
                        "order": []
                    });
                });
            </script>
        </div>
    
</form>

<?php
	include_once('../../includes/footer.php');