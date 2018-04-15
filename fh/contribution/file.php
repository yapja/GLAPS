<?php 
	$page_title = "File Additional Contribution";
    include_once('../../includes/header_fh.php');

	if (isset($_POST['file']))
	{
		$contribution_type = mysqli_real_escape_string($con, $_POST['contribution_type']);
		$additional_contribution = mysqli_real_escape_string($con, $_POST['additional_contribution']);
		$period_end = mysqli_real_escape_string($con, $_POST['period_end']);
		$reason = mysqli_real_escape_string($con, $_POST['reason']);
		$employee_detail_ID = $_SESSION['employee_detail_ID'];

		$sql_add = "INSERT INTO additional_contribution VALUES ('', $employee_detail_ID, $contribution_type, $additional_contribution , '$period_end', '$reason', NOW(), NULL, 'Pending')";
		$con->query($sql_add) or die(mysqli_error($con));
        
        $account_ID = $_SESSION['account_ID'];
        $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $account_ID, 'Filed additional contribution')";
        $con->query($sql_log) or die(mysqli_error($con));
        
		header('location: index.php');
	}
?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-6">
		<div class="form-group">
			<label class="control-label col-lg-4">Contribution Type</label>
			<div class="col-lg-8">
				<select name="contribution_type" class="form-control" required>
					<option value="">Select one...</option>
					<option value="0">SSS</option>
					<option value="1">HDMF</option>
					<option value="2">PhilHealth</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Additional Contribution (per month)</label>
			<div class="col-lg-8">
				<input name="additional_contribution" type="number" class="form-control" min="1" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Period End</label>
			<div class="col-lg-8">
				<input name="period_end" type="date" class="form-control" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Reason</label>
			<div class="col-lg-8">
				<textarea name="reason" style="width:100%;height:80px;" placeholder="Reason for filing additional contribution"></textarea>
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