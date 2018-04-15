<?php 
	$page_title = "View Leaves";
	include_once('../../includes/header_gam.php');
	
	# displays list of users
	$employee_detail_ID = $_SESSION['employee_detail_ID'];

	
	$sql_count = "SELECT SUM(total_days) AS consumed_days FROM employee_leave_taken WHERE employee_detail_ID = $employee_detail_ID AND leave_type_ID = 1 AND YEAR(date_start) = YEAR(NOW()) AND status = 'Approved'";
	$result_count = $con->query($sql_count);
	while ($row = mysqli_fetch_array($result_count))
	{
		$total_sick = $row['consumed_days'];
	}
	$result_count = $con->query($sql_count);

	$sql_count = "SELECT SUM(total_days) AS consumed_days FROM employee_leave_taken WHERE employee_detail_ID = $employee_detail_ID AND leave_type_ID = 2 AND YEAR(date_start) = YEAR(NOW()) AND status = 'Approved'";
	$result_count = $con->query($sql_count);
	while ($row = mysqli_fetch_array($result_count))
	{
		$total_vacation = $row['consumed_days'];
	}
	$result_count = $con->query($sql_count);

	$sql_count = "SELECT SUM(total_days) AS consumed_days FROM employee_leave_taken WHERE employee_detail_ID = $employee_detail_ID AND leave_type_ID = 3 AND YEAR(date_start) = YEAR(NOW()) AND status = 'Approved'";
	$result_count = $con->query($sql_count);
	while ($row = mysqli_fetch_array($result_count))
	{
		$total_parental = $row['consumed_days'];
	}
	$result_count = $con->query($sql_count);

	$sql_count = "SELECT SUM(total_days) AS consumed_days FROM employee_leave_taken WHERE employee_detail_ID = $employee_detail_ID AND leave_type_ID = 4 AND YEAR(date_start) = YEAR(NOW()) AND status = 'Approved'";
	$result_count = $con->query($sql_count);
	while ($row = mysqli_fetch_array($result_count))
	{
		$total_emergency = $row['consumed_days'];
	}
	$result_count = $con->query($sql_count);

	$sql_count = "SELECT SUM(total_days) AS consumed_days FROM employee_leave_taken WHERE employee_detail_ID = $employee_detail_ID AND leave_type_ID = 5 AND YEAR(date_start) = YEAR(NOW()) AND status = 'Approved'";
	$result_count = $con->query($sql_count);
	while ($row = mysqli_fetch_array($result_count))
	{
		$total_accrued = $row['consumed_days'];
	}
	$result_count = $con->query($sql_count);

	$sql_leave = "SELECT sick_leave, vacation_leave, parental_leave, emergency_leave, accrued FROM employee_leave WHERE employee_detail_ID = $employee_detail_ID AND year = YEAR(NOW())";
	$result_leave = $con->query($sql_leave);
	while ($row = mysqli_fetch_array($result_leave))
	{
		$sick_leave = $row['sick_leave'] - $total_sick;
		$vacation_leave = $row['vacation_leave'] - $total_vacation;
		$parental_leave = $row['parental_leave'] - $total_parental;
		$emergency_leave = $row['emergency_leave'] - $total_emergency;
		$accrued = $row['accrued'] - $total_emergency;
	}

?>

<form method="POST" class="form-horizontal">
	<div class="col-lg-6">
        <div class="form-group">
			<label class="control-label col-lg-4">Sick Leave</label>
			<div class="col-lg-4">
				<input name="name" type="text" class="form-control" value="<?php echo $sick_leave; ?>" disabled />
			</div>
            <div class="col-lg-4"><a href="file.php?id=1" class="btn btn-md btn-success">File a Leave</a></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Vacation Leave</label>
			<div class="col-lg-4">
				<input name="name" type="text" class="form-control" value="<?php echo $vacation_leave; ?>" disabled />
			</div>
            <div class="col-lg-4"><a href="file.php?id=2" class="btn btn-md btn-success">File a Leave</a></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Parental Leave</label>
			<div class="col-lg-4">
				<input name="name" type="text" class="form-control" value="<?php echo $parental_leave; ?>" disabled />
			</div>
            <div class="col-lg-4"><a href="file.php?id=3" class="btn btn-md btn-success">File a Leave</a></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Emergency Leave</label>
			<div class="col-lg-4">
				<input name="name" type="text" class="form-control" value="<?php echo $emergency_leave; ?>" disabled />
			</div>
            <div class="col-lg-4"><a href="file.php?id=4" class="btn btn-md btn-success">File a Leave</a></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Accrued Leave</label>
			<div class="col-lg-4">
				<input name="name" type="text" class="form-control" value="<?php echo $accrued; ?>" disabled />
			</div>
            <div class="col-lg-4"><a href="file.php?id=5" class="btn btn-md btn-success">File a Leave</a></div>
		</div>
	</div>
</form>

<?php
	include_once('../../includes/footer.php');