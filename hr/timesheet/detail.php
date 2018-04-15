<?php 
	$page_title = "Timesheet";
    include_once('../../includes/header_hr.php');
	if (isset($_REQUEST['id']))
	{
		# checks if selected record is an ID value
		if (ctype_digit($_REQUEST['id']))
		{
			$employee_detail_ID = $_REQUEST['id'];
			$attendance_ID = $_REQUEST['aid'];
			if (isset($_POST['update']))
			{	
				$time_out = mysqli_real_escape_string($con, $_POST['time_out']);

                $sql_attendance = "SELECT TIMESTAMPDIFF(HOUR, time_in, '$time_out') AS total_hours FROM attendance WHERE attendance_id = $attendance_ID";
                $result = $con->query($sql_attendance);
                
                $hours = 0;
                while ($row = mysqli_fetch_array($result))
                {
                    $hours += $row['total_hours'];
                }
                
                if ($hours >= 8) 
                {
                    $status = 'Present';
                }
                else
                {
                    $status = 'Late';
                }
                
				$sql_delete = "UPDATE attendance SET time_out = '$time_out', status = '$status' WHERE attendance_ID = $attendance_ID"; 
				$result = $con->query($sql_delete) or die(mysqli_error($con));
                
                $account_ID = $_SESSION['account_ID'];
                $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $account_ID, 'Updated attendance $attendance_ID ')";
                $con->query($sql_log) or die(mysqli_error($con));
				
				header('location: index.php?id=' . $employee_detail_ID . "&hours=" . $hours);
			}
		}
		else
			header('location: index.php?' . $employee_detail_ID);
	}
	else
		header('location: index.php?' . $employee_detail_ID);

?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
        <div class="form-group">
            <div class="col-lg-4">
                <input name="time_out" type="datetime-local" class="form-control" required /> 
            </div>
            <div class="col-lg-2">
                <button name="update" type="submit" class="btn btn-info">
                    Add Timeout
                </button>
            </div>
        </div>
	</div>
</form>

<?php
	include_once('../../includes/footer.php');