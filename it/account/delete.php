<?php 

	if (isset($_REQUEST['id']))
	{
		# checks if selected record is an ID value
		if (ctype_digit($_REQUEST['id']))
		{
			$id = $_REQUEST['id'];
            
			require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');
			require($_SERVER['DOCUMENT_ROOT'] . '/glaps/function.php');
            
            $sql_select = "SELECT username FROM account WHERE account_ID = $id";
			$result = $con->query($sql_select) or die(mysqli_error($con));
            while ($row = mysqli_fetch_array($result))
					{
						$username = $row['username'];
                    }
            
			# archives existing record
			$sql_delete = "UPDATE account SET status = 'Archived' WHERE account_ID = $id";
			$result = $con->query($sql_delete) or die(mysqli_error($con));
            
            session_start();
            $aid = $_SESSION['account_ID'];
            $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $aid, 'Archived $username account')";
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