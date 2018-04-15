<?php 
	# checks if record is selected
	if (isset($_REQUEST['id']))
	{
		# checks if selected record is an ID value
		if (ctype_digit($_REQUEST['id']))
		{
			$id = $_REQUEST['id'];
			require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');
			require($_SERVER['DOCUMENT_ROOT'] . '/glaps/function.php');
			validateAccess(2);
			

			$sql_delete = "UPDATE employee_detail SET status = 'Archived', date_updated = NOW() WHERE employee_detail_ID = $id";
			$result = $con->query($sql_delete) or die(mysqli_error($con));
            
            $sql_select = "SELECT username FROM account WHERE employee_detail_ID = $id";
			$result = $con->query($sql_select) or die(mysqli_error($con));
            while ($row = mysqli_fetch_array($result))
					{
						$username = $row['username'];
                    }
        
            session_start();
            $aid = $_SESSION['account_ID'];
            $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $aid, 'Archived $username')";
            $con->query($sql_log) or die(mysqli_error($con));
            
			header('location: index.php');
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