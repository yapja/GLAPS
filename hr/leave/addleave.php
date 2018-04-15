<?php 
	$page_title = "Add Leave";
    include_once('../../includes/header_hr.php');


	# displays list of users
    if (isset($_GET['id']))
    {
        $employee_detail_ID = $_GET['id'];
	
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
            $accrued = $row['accrued'] - $total_accrued;
        }

        if (isset($_POST['add']))
        {
            $accrued = mysqli_real_escape_string($con, $_POST['accrued_leave']);

            $sql_add = "UPDATE employee_leave SET accrued = $accrued WHERE employee_detail_ID = $employee_detail_ID";
            $con->query($sql_add) or die(mysqli_error($con));

            $account_ID = $_SESSION['account_ID'];
            $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $account_ID, 'Added accrued leave to employee $employee_detail_ID')";
            $con->query($sql_log) or die(mysqli_error($con));

            header('location: addleave.php?id='. $employee_detail_ID);
        }
    }
    
?>

<form method="POST" class="form-horizontal">
	<div class="col-lg-6">
        <div class="form-group">
			<label class="control-label col-lg-4">Sick Leave</label>
			<div class="col-lg-4">
				<input name="sick_leave" type="number" class="form-control" value="<?php echo $sick_leave; ?>" disabled />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Vacation Leave</label>
			<div class="col-lg-4">
				<input name="vacation_leave" type="number" class="form-control" value="<?php echo $vacation_leave; ?>" disabled />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Parental Leave</label>
			<div class="col-lg-4">
				<input name="parental_leave" type="number" class="form-control" value="<?php echo $parental_leave; ?>" disabled />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Emergency Leave</label>
			<div class="col-lg-4">
				<input name="emergency_leave" type="number" class="form-control" value="<?php echo $emergency_leave; ?>" disabled />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Accrued Leave</label>
			<div class="col-lg-4">
				<input name="accrued_leave" type="number" class="form-control" value="<?php echo $accrued; ?>" />
			</div>
		</div>
        <div class="form-group">
			<div class="col-lg-offset-4 col-lg-8">
				<button name="add" type="submit" class="btn btn-success">
					Add Leave
				</button>
			</div>
		</div>
	</div>
</form>

<?php
	include_once('../../includes/footer.php');