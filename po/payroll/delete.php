<?php 
	# checks if record is selected
	if (isset($_REQUEST['sid'])&&isset($_REQUEST['eid'])&&isset($_REQUEST['pid']))
	{
		# checks if selected record is an ID value
		if (ctype_digit($_REQUEST['sid'])&&ctype_digit($_REQUEST['eid'])&&ctype_digit($_REQUEST['pid']))
		{
			$id = $_REQUEST['sid'];
			$eid = $_REQUEST['eid'];
			$pid = $_REQUEST['pid'];
			require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');
			require($_SERVER['DOCUMENT_ROOT'] . '/glaps/function.php');
			

			$sql_delete = "UPDATE salary_report SET status = 'Archived' WHERE salary_report_ID = $id";
			$result = $con->query($sql_delete) or die(mysqli_error($con));
            
            $sql_select = "SELECT username FROM account WHERE employee_detail_ID = $eid";
			$result = $con->query($sql_select) or die(mysqli_error($con));
            while ($row = mysqli_fetch_array($result))
					{
						$username = $row['username'];
                    }
            
            session_start();
            $aid = $_SESSION['account_ID'];
            $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $aid, 'Archived payslip of $username')";
            $con->query($sql_log) or die(mysqli_error($con));
            
			header('location: index.php?pid=' . $pid);
		}
		else
		{
			header('location: index.php');
		}
	}
	else
	{
		header('location: index.php');
	}
?>