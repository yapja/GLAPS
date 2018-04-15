<?php 
	$page_title = "Dashboard";
    include_once('../includes/header_gam.php');
    
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

    # displays record
    $sql_dashboard = "SELECT e.employee_ID, e.employee_detail_ID, e.last_name, e.first_name
		FROM employee e
		INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
        INNER JOIN account a ON a.account_ID = ed.account_ID
		WHERE a.status != 'Archived' AND a.status != 'Pending'";
    $result_dashboard = $con->query($sql_dashboard);

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
    
    $sql_period_dates = "SELECT date_start, date_end FROM period WHERE period_ID=" . $selected_periodID;
    $result_period_dates = $con->query($sql_period_dates);

    while ($row = mysqli_fetch_array($result_period_dates))
    {
        $period_start = $row['date_start'];
        $period_end = $row['date_end'];
    }
    
    if (isset($_POST['next']))
	{
        $period_ID = mysqli_real_escape_string($con, $_POST['period_ID']);
        header('location: dashboard.php?pid=' . $period_ID);
    }
?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
        <div class="col-lg-6">
        <div class="form-group">
            <div class="col-lg-6">
                <select name='period_ID' class='form-control' required>
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
                    <a href='../reports/dashboardreport.php?pid=$selected_periodID' class='btn btn-danger' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
                    ";
                    ?>
            </div>
        </div>
    </div>
		<table id="tblUsers" class="table table-hover">
			<thead>
				<th>Name</th>
				<th>Overtime (hours)</th>
				<th>Lates</th>
				<th>Absents</th>
				<th>Leaves</th>
			</thead>
			<tbody>
				<?php
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

                            echo "
                                <tr>
                                    <td>$name</td>
                                    <td>$overtime</td>
                                    <td>$late</td>
                                    <td>$absent</td>
                                    <td>$leave</td>
                                </tr>
                            ";
                        }
                        else
                        {
                            echo "
							<tr>
								<td>$name</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
							</tr>
						";
                        }
						
					}

				?>
			</tbody>
		</table>
		<script>
			$(document).ready(function(){
			    $('#tblUsers').DataTable();
			});
		</script>
	</div>
</form>

<?php
	include_once('../includes/footer.php');