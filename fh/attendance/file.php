<?php 
	$page_title = "File Attendance";
    include_once('../../includes/header_fh.php');
	
    #validateAccess();

	if (isset($_POST['file']))
	{
		$date = mysqli_real_escape_string($con, $_POST['date']);
		$time_in = mysqli_real_escape_string($con, $_POST['time_in']);
		$time_out = mysqli_real_escape_string($con, $_POST['time_out']);
		$reason = mysqli_real_escape_string($con, $_POST['reason']);
		$employee_detail_ID = $_SESSION['employee_detail_ID'];

		$sql_add = "INSERT INTO attendance_flexible VALUES ('', $employee_detail_ID, '$date', '$time_in', '$time_out', '$reason', NOW(), 'Pending', NULL)";
		$con->query($sql_add) or die(mysqli_error($con));
        
        $aid = $_SESSION['account_ID'];
        $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $aid, 'Added a flexible schedule')";
        $con->query($sql_log) or die(mysqli_error($con));
        
		header('location: index.php');
	}

    
?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-6">
		<div class="form-group">
			<label class="control-label col-lg-4">Date</label>
			<div class="col-lg-8">
				<input name="date" type="date" class="form-control" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Time In</label>
			<div class="col-lg-8">
				<input name="time_in" type="time" class="form-control" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Time Out</label>
			<div class="col-lg-8">
				<input name="time_out" type="time" class="form-control " required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4"></label>
			<div class="col-lg-8">
				<textarea name="reason" style="width:100%;height:80px;" placeholder="Reason for filing attendance"></textarea>
			</div>
		</div>
        
		<div class="form-group">
			<div class="col-lg-offset-4 col-lg-8">
				<button name="file" type="submit" class="btn btn-success">
					File
				</button>
			</div>
		</div>
	</div>
</form>

<?php
	include_once('../../includes/footer.php');