<?php 

	if (isset($_REQUEST['id']))
	{
		# checks if selected record is an ID value
		if (ctype_digit($_REQUEST['id']))
		{
			$holiday_ID = $_REQUEST['id'];

			$page_title = "Holiday details";
		    include_once('../../includes/header_hr.php');

		    # display existing record
			$sql_holiday = "SELECT name, date, type FROM holiday WHERE holiday_ID = $holiday_ID";
			$result_holiday = $con->query($sql_holiday);

			while ($row = mysqli_fetch_array($result_holiday))
			{
				$name = $row['name'];
				$date = $row['date'];
				$type = $row['type'];
			}

			if (isset($_POST['update']))
			{
				$name = mysqli_real_escape_string($con, $_POST['name']);
				$date = mysqli_real_escape_string($con, $_POST['date']);
				$type = mysqli_real_escape_string($con, $_POST['type']);
                
                $sql_update = "UPDATE holiday SET name = '$name', date = '$date', type = '$type' WHERE holiday_ID = $holiday_ID";
				$result = $con->query($sql_update) or die(mysqli_error($con));
				
                $aid = $_SESSION['account_ID'];
                $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $aid, 'Updated $name')";
                $con->query($sql_log) or die(mysqli_error($con));
                
				header('location: index.php');
			}
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
<form method="POST" class="form-horizontal">
	<div class="col-lg-6">
		<div class="form-group">
			<label class="control-label col-lg-4">Holiday</label>
			<div class="col-lg-8">
				<input name="name" type="text" class="form-control" value="<?php echo $name ?>" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Date</label>
			<div class="col-lg-8">
				<input name="date" type="date" class="form-control" value="<?php echo $date ?>" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Type</label>
			<div class="col-lg-8">
				<input name="type" type="text" class="form-control" value="<?php echo $type ?>" />
			</div>
		</div>
        
		<div class="form-group">
			<div class="col-lg-offset-4 col-lg-8">
				<button name="update" type="submit" class="btn btn-success">
					Update
				</button>
			</div>
		</div>
	</div>
</form>

<?php
	include_once('../../includes/footer.php');