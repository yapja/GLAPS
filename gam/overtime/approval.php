<?php 
	$page_title = "Approval";
    include_once('../../includes/header_gam.php');
	

	$employee_overtime_ID = $_REQUEST['id'];
	$employee_ID = $_SESSION['employee_detail_ID'];

	$sql_overtime = "SELECT e.first_name, e.last_name, ott.type, eot.date_start, eot.date_end, HOUR(TIMEDIFF(eot.date_start, eot.date_end)) AS hours, eot.reason
	FROM employee e
	INNER JOIN employee_overtime eot ON e.employee_detail_ID = eot.employee_detail_ID
	INNER JOIN overtime_type ott ON ott.overtime_type_ID = eot.overtime_type_ID
	WHERE eot.employee_overtime_ID = $employee_overtime_ID";

	$result_overtime = $con->query($sql_overtime);

	while ($row = mysqli_fetch_array($result_overtime))
	{
		$name = $row['last_name'] . ', ' . $row['first_name'];
		$date_start = $row['date_start'];
		$date_end = $row['date_end'];
		$reason = $row['reason'];
		$type = $row['type'];
		$hours = $row['hours'];
	}



	if (isset($_POST['approve']))
	{
		$remark = mysqli_real_escape_string($con, $_POST['remark']);
		$sql_approve = "UPDATE employee_overtime SET date_updated = NOW(), validated_by = $employee_ID, status = 'Approved', remark = '$remark' WHERE employee_overtime_ID = $employee_overtime_ID";
		$con->query($sql_approve) or die(mysqli_error($con));
        
        $sql_select = "SELECT e.first_name, e.last_name, ot.type FROM employee e 
            INNER JOIN employee_detail ed ON e.employee_ID = ed.employee_ID
            INNER JOIN employee_overtime eo ON ed.employee_detail_ID = eo.employee_detail_ID
            INNER JOIN overtime_type ot ON eo.overtime_type_ID = ot.overtime_type_ID
            WHERE employee_overtime_ID = $employee_overtime_ID";
			$result = $con->query($sql_select) or die(mysqli_error($con));
            while ($row = mysqli_fetch_array($result))
					{
						$name = $row['last_name'] . ', ' . $row['first_name'];
                        $otype = $row['type'];
                    }
            
            $account_ID = $_SESSION['account_ID'];
            $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $account_ID, 'Approved $otype overtime of $name')";
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
			<label class="control-label col-lg-4">Reason</label>
			<div class="col-lg-8">
				<textarea name="reason" style="width:100%;height:80px;" disabled>  <?php echo $reason ?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Total Hours</label>
			<div class="col-lg-8">
				<input name="total_hours" type="text" class="form-control" value="<?php echo $hours; ?>" disabled />
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