<?php 
	# checks if record is selected
	if (isset($_REQUEST['afid']))
	{
		# checks if selected record is an ID value
		if (ctype_digit($_REQUEST['afid']))
		{
			session_start();
			$attendance_flexible_ID = $_REQUEST['afid'];
			require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');
			require($_SERVER['DOCUMENT_ROOT'] . '/glaps/function.php');
			validateAccess(5);
			
			$employee_detail_ID = $_SESSION['employee_detail_ID'];
			$sql_delete = "UPDATE attendance_flexible SET status = 'Approved', approved_by = $employee_detail_ID WHERE attendance_flexible_ID = $attendance_flexible_ID"; 
			$result = $con->query($sql_delete) or die(mysqli_error($con));
            
            
            $sql_select = "SELECT e.first_name, e.last_name, ed.employee_detail_ID, af.attendance_flexible_ID FROM attendance_flexible af 
            INNER JOIN employee_detail ed ON af.employee_detail_ID = ed.employee_detail_ID
            INNER JOIN employee e ON ed.employee_detail_ID = e.employee_detail_ID
            WHERE attendance_flexible_ID = $attendance_flexible_ID";
			$result = $con->query($sql_select) or die(mysqli_error($con));
            while ($row = mysqli_fetch_array($result))
					{
						$name = $row['last_name'] . ', ' . $row['first_name'];
                    }
            
            $account_ID = $_SESSION['account_ID'];
            $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $account_ID, 'Approved $name attendance')";
            $con->query($sql_log) or die(mysqli_error($con));
            
			header('location: pending.php');
		}
		else
		{
			header('location: pending.php');
		}
	}
	else
	{
		header('location: pending.php');
	}
?>