<?php 
	# checks if record is selected
	if (isset($_REQUEST['id']))
	{
		# checks if selected record is an ID value
		if (ctype_digit($_REQUEST['id']))
		{
			session_start();
			$additional_contribution_ID = $_REQUEST['id'];
			require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');
			require($_SERVER['DOCUMENT_ROOT'] . '/glaps/function.php');
			validateAccess(5);
			
			$employee_detail_ID = $_SESSION['employee_detail_ID'];
			$sql_approve = "UPDATE additional_contribution SET status = 'Approved', approved_by = $employee_detail_ID WHERE additional_contribution_ID = $additional_contribution_ID"; 
			$result = $con->query($sql_approve) or die(mysqli_error($con));
            
            
            $sql_select = "SELECT e.first_name, e.last_name, ed.employee_detail_ID, ac.additional_contribution_ID FROM additional_contribution ac
            INNER JOIN employee_detail ed ON ac.employee_detail_ID = ed.employee_detail_ID
            INNER JOIN employee e ON ed.employee_detail_ID = e.employee_detail_ID
            WHERE additional_contribution_ID = $additional_contribution_ID";
			$result = $con->query($sql_select) or die(mysqli_error($con));
            while ($row = mysqli_fetch_array($result))
					{
						$name = $row['last_name'] . ', ' . $row['first_name'];
                    }
            
            $account_ID = $_SESSION['account_ID'];
            $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $account_ID, 'Approved $name contribution')";
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