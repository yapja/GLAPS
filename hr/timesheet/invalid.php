<?php 
	# checks if record is selected
	if (isset($_REQUEST['id']) && isset($_REQUEST['aid']))
	{
		# checks if selected record is an ID value
		if (ctype_digit($_REQUEST['id']) && ctype_digit($_REQUEST['aid']))
		{
            $employee_detail_ID = $_REQUEST['id'];
			$attendance_ID = $_REQUEST['aid'];
			require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');
			require($_SERVER['DOCUMENT_ROOT'] . '/glaps/function.php');
			validateAccess(2);
			

			$sql_invalid = "UPDATE attendance SET status = 'Invalid' WHERE attendance_ID = $attendance_ID"; 
				
			$result = $con->query($sql_invalid) or die(mysqli_error($con));
			header('location: index.php?id=' . $employee_detail_ID);
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