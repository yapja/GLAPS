<?php 
	$page_title = "Approval";
    include_once('../../includes/header_gam.php');
	

	$employee_leave_taken_ID = $_REQUEST['id'];
	$employee_detail_ID = $_SESSION['employee_detail_ID'];

  	$sql_leave = "SELECT e.first_name, e.last_name, lt.type, elt.leave_type_ID, elt.date_start, elt.date_end, elt.reason
	FROM employee e
	INNER JOIN employee_leave_taken elt ON e.employee_detail_ID = elt.employee_detail_ID
	INNER JOIN leave_type lt ON lt.leave_type_ID = elt.leave_type_ID
	WHERE elt.employee_leave_taken_ID = $employee_leave_taken_ID";

	$result_leave = $con->query($sql_leave);

	while ($row = mysqli_fetch_array($result_leave))
	{
		$name = $row['last_name'] . ', ' . $row['first_name'];
		$date_start = $row['date_start'];
		$date_end = $row['date_end'];
		$reason = $row['reason'];
		$type_ID = $row['leave_type_ID'];
		$type = $row['type'];
	}

	$sql_count = "SELECT SUM(total_days) AS consumed_days FROM employee_leave_taken WHERE employee_detail_ID = $employee_detail_ID AND leave_type_ID = $type_ID AND YEAR(date_start) = YEAR(NOW()) AND status = 'Approved'";
	$result_count = $con->query($sql_count);
	while ($row = mysqli_fetch_array($result_count))
	{
		$consumed_days = $row['consumed_days'];
	}
	$result_count = $con->query($sql_count);

	if ($type_ID == 1)
	{
		$sql_count = "SELECT sick_leave AS total_days FROM employee_leave WHERE employee_detail_ID = $employee_detail_ID AND year = YEAR(NOW())";
	}
	elseif ($type_ID == 2)
	{
		$sql_count = "SELECT vacation_leave AS total_days FROM employee_leave WHERE employee_detail_ID = $employee_detail_ID AND year = YEAR(NOW())";
	}
	elseif ($type_ID == 3)
	{
		$sql_count = "SELECT parental_leave AS total_days FROM employee_leave WHERE employee_detail_ID = $employee_detail_ID AND year = YEAR(NOW())";
	}
	elseif ($type_ID == 4)
	{
		$sql_count = "SELECT emergency_leave AS total_days FROM employee_leave WHERE employee_detail_ID = $employee_detail_ID AND year = YEAR(NOW())";
	}
	elseif ($type_ID == 5)
	{
		$sql_count = "SELECT accrued AS total_days FROM employee_leave WHERE employee_detail_ID = $employee_detail_ID AND year = YEAR(NOW())";
	}
	$result_count = $con->query($sql_count);
	while ($row = mysqli_fetch_array($result_count))
	{
		$total_days = $row['total_days'];
	}
	
	$list_leave = "";
	for ($i = 0.5; $i <= $total_days - $consumed_days; $i+= 0.5)
	{
		$list_leave .= "<option value=$i selected>$i</option>";
	}

	if (isset($_POST['approve']))
	{
		$remark = mysqli_real_escape_string($con, $_POST['remark']);
		$total_days = mysqli_real_escape_string($con, $_POST['available']);
		$sql_reject = "UPDATE employee_leave_taken SET date_updated = NOW(), validated_by = $employee_detail_ID, total_days = $total_days, remark = '$remark', status='Approved' WHERE employee_leave_taken_ID = $employee_leave_taken_ID";
		$con->query($sql_reject) or die(mysqli_error($con));
        
        $sql_select = "SELECT e.first_name, e.last_name, lt.type FROM employee e 
            INNER JOIN employee_detail ed ON e.employee_ID = ed.employee_ID
            INNER JOIN employee_leave_taken et ON ed.employee_detail_ID = et.employee_detail_ID
            INNER JOIN leave_type lt ON et.leave_type_ID = lt.leave_type_ID
            WHERE employee_leave_taken_ID = $employee_leave_taken_ID";
			$result = $con->query($sql_select) or die(mysqli_error($con));
            while ($row = mysqli_fetch_array($result))
					{
						$name = $row['last_name'] . ', ' . $row['first_name'];
                        $ltype = $row['type'];
                    }
            
            $account_ID = $_SESSION['account_ID'];
            $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $account_ID, 'Approved $ltype of $name')";
            $con->query($sql_log) or die(mysqli_error($con));
        
		header('location: pending.php');
	}
?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-6">
        <div class="form-group">
			<label class="control-label col-lg-4"></label>
			<div class="col-lg-8">
				<input name="name" type="text" class="form-control" value="<?php echo $name; ?>" disabled />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Leave Type</label>
			<div class="col-lg-8">
				<select name="type" class="form-control" disabled>
					<option value=""><?php echo $type; ?></option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Date Start</label>
			<div class="col-lg-8">
				<input name="date_start" type="text" class="form-control" disabled value="<?php echo $date_start; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Date End</label>
			<div class="col-lg-8">
				<input name="date_end" type="text" class="form-control" disabled value="<?php echo $date_end; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4"></label>
			<div class="col-lg-8">
				<textarea name="reason" style="width:100%;height:80px;" disabled>  <?php echo $reason ?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Total Days</label>
			<div class="col-lg-8">
				<select name="available" class="form-control" required>
					<?php echo $list_leave; ?>
				</select>
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-4"></label>
			<div class="col-lg-8">
				<textarea name="remark" style="width:100%;height:80px;" placeholder="Remarks"></textarea>
			</div>
		</div>
		<div class="form-group">
			<div class="col-lg-offset-4 col-lg-8">
				<button name="approve" type="submit" class="btn btn-success">
					Approve
				</button>
			</div>
		</div>
	</div>
</form>

<?php
	include_once('../../includes/footer.php');