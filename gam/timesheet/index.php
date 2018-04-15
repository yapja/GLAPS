<?php 
	$page_title = "Timesheet";
    include_once('../../includes/header_gam.php');
	
    if(isset($_GET['id']))
	{	
		$employee_detail_ID = $_GET['id'];
	}
	else
	{
		$employee_detail_ID = $_SESSION['employee_detail_ID'];
	}

    if (isset($_GET['ds']) && isset($_GET['de']))
    {
        $date_start = $_GET['ds'];
        $date_end = $_GET['de'];
        $sql_employee = "SELECT e.employee_ID, e.first_name, e.middle_name, e.last_name, ed.profile_picture, a.attendance_ID, (SELECT DATE_FORMAT(date, '%M %d, %Y') FROM attendance WHERE attendance_ID = a.attendance_ID) AS date, (SELECT DATE_FORMAT(time_in, '%h:%s %p') FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_in, (SELECT DATE_FORMAT(time_out, '%h:%s %p') FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_out, a.in_image, a.out_image, a.status, (SELECT TIMESTAMPDIFF(HOUR, time_in, time_out) FROM attendance WHERE attendance_id = a.attendance_ID) AS total_hours
        FROM employee e
        INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
        INNER JOIN attendance a ON a.employee_detail_ID = ed.employee_detail_ID
        WHERE ed.status != 'Archived' AND ed.employee_detail_ID = $employee_detail_ID AND DATE(a.time_in) >= '$date_start' AND DATE(a.time_in) <= '$date_end'";
    }
    else
    {
        $sql_employee = "SELECT e.employee_ID, e.first_name, e.middle_name, e.last_name, ed.profile_picture, a.attendance_ID, (SELECT DATE_FORMAT(date, '%M %d, %Y') FROM attendance WHERE attendance_ID = a.attendance_ID) AS date, (SELECT DATE_FORMAT(time_in, '%h:%s %p') FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_in, (SELECT DATE_FORMAT(time_out, '%h:%s %p') FROM attendance WHERE attendance_ID = a.attendance_ID) AS time_out, a.in_image, a.out_image, a.status, (SELECT TIMESTAMPDIFF(HOUR, time_in, time_out) FROM attendance WHERE attendance_id = a.attendance_ID) AS total_hours
        FROM employee e
        INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
        INNER JOIN attendance a ON a.employee_detail_ID = ed.employee_detail_ID
        WHERE ed.status != 'Archived' AND ed.employee_detail_ID = $employee_detail_ID";
    }
    $result_employee = $con->query($sql_employee);
	
	if (isset($_POST['generate']))
	{	
		$date_start = mysqli_real_escape_string($con, $_POST['date_start']);
		$date_end = mysqli_real_escape_string($con, $_POST['date_end']);

		header('location: index.php?ds=' . $date_start . '&de=' . $date_end);
	}

?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
        <div class="form-group">
            <div class="col-lg-2">
                <input name="date_start" type="date" class="form-control" value="<?php echo $date_start ?>" required /> 
            </div>
            <div class="col-lg-2">
                <input name="date_end" type="date" class="form-control" value="<?php echo $date_end ?>" required />
                
            </div>
            <div class="col-lg-2">
                <button name="generate" type="submit" class="btn btn-info">
                    Sort Timesheet
                </button>
            </div>
            <?php 
                if(isset($_GET['ds']) && isset($_GET['de']))
                {
                    echo "
                    <a href='../../reports/timesheetattendance.php?ds=$date_start&de=$date_end' class='btn btn-danger' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
                    <a href='../../reports/timesheetleaves.php?ds=$date_start&de=$date_end' class='btn btn-success' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
                    ";
                }
                else
                {
                    echo "
                    <a href='../../reports/timesheetattendance.php' class='btn btn-danger' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
                    <a href='../../reports/timesheetleaves.php' class='btn btn-success' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
                    ";
                }
                
                    ?>
        </div>
		<table id="tblUsers" class="table table-hover">
			<thead>
				<th>Date</th>
				<th>Name</th>
				<th>Profile Picture</th>
				<th>Shift</th>
				<th>Time in</th>
				<th></th>
				<th>Time out</th>
				<th></th>
				<th>Total Hours</th>
				<th>Status</th>
			</thead>
			<tbody>
				<?php
					if (mysqli_num_rows($result_employee))
					{
						$dates = array();
						while ($row = mysqli_fetch_array($result_employee))
						{	
							$date = $row['date'];
							$name = $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name'];
							$profile_picture = $row['profile_picture'];
							$time_in = $row['time_in'];
							$in_image = $row['in_image'];
							$time_out = $row['time_out'];
							$out_image = $row['out_image'];
							$status = $row['status'];
							$hours = $row['total_hours'];
							$dates[] = strtotime($date);

							if (strtotime($time_out) - strtotime($time_in) < 3600)
							{
								$hours = 0;
							}
							else if (strtotime($time_in) <= strtotime("12:00 PM") && strtotime($time_out) >= strtotime("1:00 PM"))
							{
								$hours -= 1;
							}
							else if (strtotime($time_in) <= strtotime("12:00 PM") && strtotime($time_out) <= strtotime("1:00 PM"))
							{
								$hours -= 1;
							}
							echo "
								<tr>
									<td>$date</td>
									<td>$name</td>
									<td><img src='../../images/profile_picture/$profile_picture' width='50px' /></td>
									<td>8am - 5pm</td>
									<td>$time_in</td>
									<td><img src='../../images/attendance/$in_image' width='50px' /></td>
									<td>$time_out</td>";
									
							if ($time_out == NULL)
							{
								echo "
									<td></td>
									<td>$hours</td>
									<td>$status</td>
									";
							}
							else
							{
								echo "
									<td><img src='../../images/attendance/$out_image' width='50px'/></td>
									<td>$hours</td>
									<td>$status</td>
									";
							}
							echo "</tr>";						
						}
						
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
                        
                        $sql_leave = "SELECT DATE(elt.date_start) AS date_start, DATE(elt.date_end) as date_end, lt.type FROM employee_leave_taken elt INNER JOIN leave_type lt ON elt.leave_type_ID = lt.leave_type_ID WHERE elt.employee_detail_ID = $employee_detail_ID AND DATE(elt.date_start) <= '$date_end' AND DATE(elt.date_end) >= '$date_start' AND elt.status = 'Approved'";
						$result_leave = $con->query($sql_leave);
                        
                        while ($row = mysqli_fetch_array($result_leave))
                        {
                            $date_start2 = strtotime($row['date_start']);
                            $date_end2 = strtotime($row['date_end']);
                            $leave_type = $row['type'];
                            
                            while ($date_start2 < strtotime($date_start)) // start date of leave is before start date of filter
                            {
                                $date_start2 += 86400;
                            }
                            
                            // date_start2 == date_start (same day)
                            
                            if ($date_end2 > strtotime($date_end)) 
                            {
                                $date_end2 = strtotime($date_end);
                            }
                            
                            while ($date_start2 <= $date_end2)
                            {
                                echo "
                                    <tr>
                                        <td>" . date('F d, Y', $date_start2) . "</td>
                                        <td>$name</td>
                                        <td><img src='../../images/profile_picture/$profile_picture' width='50px' /></td>
                                        <td>8am - 5pm</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>$leave_type</td>
                                    </tr>";	
                                $dates[] = $date_start2;
                                $date_start2 += 86400;
                            }
                        }

                        $sql_first = "SELECT MIN(DATE(time_in)) AS first_day FROM attendance WHERE employee_detail_ID = $employee_detail_ID";
                        $result_first = $con->query($sql_first);
                        while ($row = mysqli_fetch_array($result_first))
                        {
                            $first_day = strtotime($row['first_day']);
                        }
                        
                        if ($first_day <= strtotime($date_start)) 
                        {
                            $first_day = strtotime($date_start);
                        }

                        $sql_holiday = "SELECT name, date FROM holiday";
                        $result_holiday = $con->query($sql_holiday);
                        while ($row = mysqli_fetch_array($result_holiday))
                        {
                            $holiday_name = $row['name'];
                            $holiday_date = strtotime($row['date']);
                            if ($first_day <= $holiday_date AND $holiday_date <= strtotime($date_end))
                            {
                                echo "
                                        <tr>
                                            <td>" . date('F d, Y', $holiday_date) . "</td>
                                            <td>$name</td>
                                            <td><img src='../../images/profile_picture/$profile_picture' width='50px' /></td>
                                            <td>8am - 5pm</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>$holiday_name</td>
                                        </tr>";	
                                $dates[] = $holiday_date;
                            }
                        }

                    
                        $sql_flexible = "SELECT a.date, a.time_in, a.time_out, (SELECT TIMESTAMPDIFF(HOUR, time_in, time_out) FROM attendance_flexible WHERE attendance_flexible_id = a.attendance_flexible_ID) AS total_hours FROM attendance_flexible a WHERE a.employee_detail_ID = $employee_detail_ID AND a.status = 'Approved'";
                        $result_flexible = $con->query($sql_flexible);
                        while ($row = mysqli_fetch_array($result_flexible))
                        {
                            $date = strtotime($row['date']);
                            $time_in = $row['time_in'];
                            $time_out = $row['time_out'];
                            $total_hours = $row['total_hours'];
                            if ($date >= strtotime($date_start) AND $date <= strtotime($date_end))
                            {
                                echo "
                                        <tr>
                                            <td>" . date('F d, Y', $date) . "</td>
                                            <td>$name</td>
                                            <td><img src='../../images/profile_picture/$profile_picture' width='50px' /></td>
                                            <td>Flexible</td>
                                            <td>$time_in</td>
                                            <td></td>
                                            <td>$time_out</td>
                                            <td></td>
                                            <td>$total_hours</td>
                                            <td>Approved</td>
                                        </tr>";	
                                $dates[] = $date;
                            }
                        }

                        //ABSENT
                        $sql_first = "SELECT MIN(DATE(time_in)) AS first_day FROM attendance WHERE employee_detail_ID = $employee_detail_ID";
                        $result_first = $con->query($sql_first);
                        while ($row = mysqli_fetch_array($result_first))
                        {
                            $first_day = strtotime($row['first_day']);
                        }
                        
                        if ($first_day <= strtotime($date_start)) 
                        {
                            $first_day = strtotime($date_start);
                        }
                        
                        while ($first_day <= strtotime($date_end))
                        {
                            if (!in_array($first_day, $dates))
                            {
                                if(date("N", $first_day) <= 6)
                                {
                                    echo "
                                        <tr>
                                            <td>" . date('F d, Y', $first_day) . "</td>
                                            <td>$name</td>
                                            <td><img src='../../images/profile_picture/$profile_picture' width='50px' /></td>
                                            <td>8am - 5pm</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Absent</td>
                                        </tr>";	
                                }
                            }
                            $first_day += 86400;
                        }

					}

				?>
			</tbody>
		</table>
		<script>
			$(document).ready( function() {
				$('#tblUsers').dataTable.moment('MMMM D, YYYY');
				$('#tblUsers').dataTable({
					"order": [0, 'desc']
				});
			});
		</script>
	</div>
</form>

<?php
	include_once('../../includes/footer.php');