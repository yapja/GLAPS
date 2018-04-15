<?php 
	$page_title = "File Leave";
    include_once('../../includes/header_gam.php');

    
	# displays list of user types
	if(isset($_GET['id']))
	{
		$leave_type_ID = $_REQUEST['id'];
		$sql_types = "SELECT leave_type_ID, type FROM leave_type WHERE leave_type_ID = $leave_type_ID";
		$result_types = $con->query($sql_types);

		$list_types = "";
		while ($row = mysqli_fetch_array($result_types))
		{
			$leave_type_ID = $row['leave_type_ID'];
			$leave_type = $row['type'];
			$list_types .= "<option value='$leave_type_ID' disabled selected>$leave_type</option>";
		}
	}
	else
	{
		$sql_types = "SELECT leave_type_ID, type FROM leave_type ORDER BY type";
		$result_types = $con->query($sql_types);

		$list_types = "<option value=''>Select one...</option>";
		while ($row = mysqli_fetch_array($result_types))
		{
			$leave_type_ID = $row['leave_type_ID'];
			$leave_type = $row['type'];
			$list_types .= "<option value='$leave_type_ID'>$leave_type</option>";
		}
	}

	if (isset($_POST['file']))
	{
		$date_start = mysqli_real_escape_string($con, $_POST['date_start']);
		$date_end = mysqli_real_escape_string($con, $_POST['date_end']);
		$reason = mysqli_real_escape_string($con, $_POST['reason']);
		$employee_detail_ID = $_SESSION['employee_detail_ID'];

		$sql_add = "INSERT INTO employee_leave_taken VALUES ('', $employee_detail_ID, $leave_type_ID, '$date_start', '$date_end', NULL, '$reason', 'NOW()', NULL, NULL, 'Pending', NULL)";
		$con->query($sql_add) or die(mysqli_error($con));
        
        $sql_select = "SELECT type FROM leave_type WHERE leave_type_ID = $leave_type_ID";
			$result = $con->query($sql_select) or die(mysqli_error($con));
            while ($row = mysqli_fetch_array($result))
					{
						$ltype = $row['type'];
                    }
        
        $account_ID = $_SESSION['account_ID'];
        $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $account_ID, 'Filed $ltype')";
        $con->query($sql_log) or die(mysqli_error($con));
        
		header('location: index.php');
	}
?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-6">
		<div class="form-group">
			<label class="control-label col-lg-4">Leave Type</label>
			<div class="col-lg-8">
				<select name="type" class="form-control" required>
					<?php echo $list_types; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Date Start</label>
			<div class="col-lg-8">
				<input name="date_start" type="datetime-local" class="form-control" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Date End</label>
			<div class="col-lg-8">
				<input name="date_end" type="datetime-local" class="form-control " required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Reason</label>
			<div class="col-lg-8">
				<textarea name="reason" style="width:100%;height:80px;" placeholder="Reason for filing leave"></textarea>
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