<?php 
	$page_title = "Payroll Summary";
    include_once('../../includes/header_po.php');

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
		$sql_period = "SELECT period_ID FROM period WHERE date_start <= CURDATE() AND date_end >= CURDATE()";
		$result_period = $con->query($sql_period);
		while ($row = mysqli_fetch_array($result_period))
		{
			$selected_periodID = $row['period_ID'];
		}
	
	}

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
        $sql_paysum = "SELECT t1.employee_detail_ID, t1.first_name, t1.last_name, t2.basic_salary, t2.transportation, t2.gas, t2.food, t2.SSS, t2.HDMF, t2.PhilHealth, t2.late, t2.absent, t2.legal, t2.sunday, t2.sunday_excess, t2.special_day, t2.special_excess, t2.regular_holiday, t2.regular_excess, t2.bonus, t2.withholding_tax, t2.net_pay
        FROM
        (SELECT e.employee_ID, e.employee_detail_ID, e.first_name, e.last_name from employee e
        INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID WHERE ed.status='Active') t1
        LEFT OUTER JOIN
        (SELECT ed.employee_ID, ed.employee_detail_ID, SUM(s.basic_salary) AS basic_salary, SUM(s.transportation) AS transportation, SUM(s.gas) AS gas, SUM(s.food) AS food, SUM(s.SSS) AS SSS, SUM(s.HDMF) AS HDMF, SUM(s.PhilHealth) AS PhilHealth, SUM(s.late) AS late, SUM(s.absent) AS absent, SUM(s.legal) AS legal, SUM(s.sunday) AS sunday, SUM(s.sunday_excess) AS sunday_excess, SUM(s.special_day) AS special_day, SUM(s.special_excess) AS special_excess, SUM(s.regular_holiday) AS regular_holiday, SUM(s.regular_excess) AS regular_excess, SUM(s.bonus) AS bonus, SUM(s.withholding_tax) AS withholding_tax, SUM(s.net_pay) AS net_pay FROM employee_detail ed INNER JOIN salary_report s ON ed.employee_detail_ID = s.employee_detail_ID WHERE s.period_ID IN (". $periodIDs . ") AND s.status='Approved' GROUP BY employee_detail_ID) t2
        ON
        t1.employee_ID = t2.employee_ID";
    }
    else
    {
        $sql_paysum = "SELECT t1.first_name, t1.last_name, t2.basic_salary, t2.transportation, t2.gas, t2.food, t2.SSS, t2.HDMF, t2.PhilHealth, t2.late, t2.absent, t2.legal, t2.sunday, t2.sunday_excess, t2.special_day, t2.special_excess, t2.regular_holiday, t2.regular_excess, t2.bonus, t2.withholding_tax, t2.net_pay
        FROM
        (SELECT e.employee_ID, e.first_name, e.last_name from employee e
        INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID WHERE ed.status='Active') t1
        LEFT OUTER JOIN
        (SELECT ed.employee_ID, ed.employee_detail_ID, SUM(s.basic_salary) AS basic_salary, SUM(s.transportation) AS transportation, SUM(s.gas) AS gas, SUM(s.food) AS food, SUM(s.SSS) AS SSS, SUM(s.HDMF) AS HDMF, SUM(s.PhilHealth) AS PhilHealth, SUM(s.late) AS late, SUM(s.absent) AS absent, SUM(s.legal) AS legal, SUM(s.sunday) AS sunday, SUM(s.sunday_excess) AS sunday_excess, SUM(s.special_day) AS special_day, SUM(s.special_excess) AS special_excess, SUM(s.regular_holiday) AS regular_holiday, SUM(s.regular_excess) AS regular_excess, SUM(s.bonus) AS bonus, SUM(s.withholding_tax) AS withholding_tax, SUM(s.net_pay) AS net_pay FROM employee_detail ed INNER JOIN salary_report s ON ed.employee_detail_ID = s.employee_detail_ID WHERE s.period_ID=". $selected_periodID ." AND s.status='Approved' GROUP BY employee_detail_ID) t2
        ON
        t1.employee_ID = t2.employee_ID";
    }

    
    
    $result_paysum = $con->query($sql_paysum);
    
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

    $list_year = "";
    for ($i=2017; $i<=$year; $i++) 
    {
		$list_year .= "<option value='" . ($i). "'";
        if ($i == $selected_year)
        {
         $list_year .= " selected='selected'";   
        }
        $list_year .= ">" . $i . "</option>";
    }
    
    if (isset($_POST['next']))
	{
        $period_ID = mysqli_real_escape_string($con, $_POST['period']);
        header('location: payslipsummary.php?pid=' . $period_ID);
    }

    if (isset($_POST['choose']))
	{
        $year = mysqli_real_escape_string($con, $_POST['year']);
        header('location: payslipsummary.php?year=' . $year);
    }
?>

<form method="POST" class="form-horizontal">
    <div class="col-lg-6">
        <div class="form-group">
            <label class="control-label col-lg-2">Semi-Monthly</label>
            <div class="col-lg-6">
                <select name='period' class='form-control' required>
                    <option value=''>Select one...</option>
                    <?php echo " . $list_period . "?>
                </select>
			</div>
            <div class="col-lg-4">
                <button name="next" type="submit" class="btn btn-success">
                    Choose
                </button>
                <?php 
                echo "
                    <a href='../../reports/paytotal.php?pid=$selected_periodID' class='btn btn-danger' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
                    ";
                    ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2">Annual</label><br/>
            <div class="col-lg-6">
                <select name='year' class='form-control' required>
                    <option value=''>Select one...</option>
                    <?php echo " . $list_year . "?>
                </select>
			</div>
            <div class="col-lg-4">
                <button name="choose" type="submit" class="btn btn-success">
                    Choose
                </button>
                <?php 
                echo "
                    <a href='../../reports/paytotal.php?year=$year' class='btn btn-danger' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
                    ";
                    ?>
            </div>
        </div>
    </div>
    
	<div class="col-lg-6">
            <table class="table table-hover">
                <thead>
                    <th>Name</th>
                    <th>Payslip</th>
                </thead>
                <tbody>
                    <?php
                        $total = 0;
                        while ($row = mysqli_fetch_array($result_paysum))
                        {
                            $first_name = $row['first_name'];
                            $last_name = $row['last_name'];
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
                            $total += $net_pay;
                            echo "
                                <tr>
                                    <td>$last_name, $first_name</td>
                                    <td>$netpay</td>
                                </tr>
                            ";
                        }
                        
                        echo "
                                <tr>
                                    <td><strong>TOTAL</strong></td>
                                    <td><strong>". number_format($total, 2, '.', ', ') . "</strong></td>
                                </tr>
                            ";

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