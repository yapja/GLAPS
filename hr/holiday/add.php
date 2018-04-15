<?php 
	$page_title = "Add Holiday";
    include_once('../../includes/header_hr.php');

	if (isset($_POST['add']))
	{
		$name = mysqli_real_escape_string($con, $_POST['name']);
		$type = mysqli_real_escape_string($con, $_POST['type']);
		$date = mysqli_real_escape_string($con, $_POST['date']);
        
		$sql_add = "INSERT INTO holiday VALUES ('', '$name', '$date', '$type')";
		$con->query($sql_add) or die(mysqli_error($con));
		
        $aid = $_SESSION['account_ID'];
        $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $aid, 'Added $name as a holiday')";
        $con->query($sql_log) or die(mysqli_error($con));
        
        header('location: index.php');
	}
?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-6">
		<div class="form-group">
			<label class="control-label col-lg-4">Holiday</label>
			<div class="col-lg-8">
				<input name="name" type="text" class="form-control" placeholder="Holiday" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Date</label>
			<div class="col-lg-8">
				<input name="date" type="date" class="form-control" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Type</label>
			<div class="col-lg-8">
				<input name="type" type="text" class="form-control" placeholder="Type of Holiday" required />
			</div>
		</div>
		<div class="form-group">
			<div class="col-lg-offset-4 col-lg-8">
				<button name="add" type="submit" class="btn btn-success">
					Add
				</button>
			</div>
		</div>
	</div>
</form>

<?php
	include_once('../../includes/footer.php');