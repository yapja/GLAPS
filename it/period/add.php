<?php 
	$page_title = "Add Period";
	include_once('../../includes/header_it.php');


	$sql_period = "SELECT date_start, date_end, cutoff, previous_cutoff FROM period ORDER BY date_start DESC";
	$result_period = $con->query($sql_period);
	
	if (isset($_POST['add']))
	{
		$date_start = mysqli_real_escape_string($con, $_POST['date_start']);
		$date_end = mysqli_real_escape_string($con, $_POST['date_end']);
		$previous_cutoff = mysqli_real_escape_string($con, $_POST['previous_cutoff']);
		$cutoff = mysqli_real_escape_string($con, $_POST['cutoff']);

		$sql_add = "INSERT INTO period VALUES ('', '$date_start', '$date_end', '$cutoff', '$previous_cutoff')";
		$con->query($sql_add) or die(mysqli_error($con));
		header('location: add.php');
        
        $account_ID = $_SESSION['account_ID'];
        $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $account_ID, 'Added a period')";
        $con->query($sql_log) or die(mysqli_error($con));
	}
?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-5">
		<div class="form-group">
			<label class="control-label col-lg-4">Date Start</label>
			<div class="col-lg-6">
				<input name="date_start" type="date" class="form-control" required />
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-4">Date End</label>
			<div class="col-lg-6">
				<input name="date_end" type="date" class="form-control" required />
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-4">Cuttoff</label>
			<div class="col-lg-6">
				<input name="cutoff" type="date" class="form-control" required />
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-4">Previous Cutoff</label>
			<div class="col-lg-6">
				<input name="previous_cutoff" type="date" class="form-control" required />
			</div>
		</div>
		<div class="form-group">
			<div class="col-lg-offset-4 col-lg-8">
				<button name="add" type="submit" class="btn btn-success">
					Submit
				</button>
			</div>
		</div>
	</div>
    
    <div class="col-lg-7">
        <table class="table table-hover">
			<thead>
				<th>Date Start</th>
				<th>Date End</th>
				<th>Cutoff</th>
				<th>Previous Cutoff</th>
			</thead>
			<tbody>
				<?php
					while ($row = mysqli_fetch_array($result_period))
                    {
                        $date_start = $row['date_start'];
                        $date_end = $row['date_end'];
                        $previous_cutoff = $row['previous_cutoff'];
                        $cutoff = $row['cutoff'];
                        
						echo "
							<tr>
								<td>$date_start</td>
								<td>$date_end</td>
								<td>$cutoff</td>
								<td>$previous_cutoff</td>
							</tr>
						";
					}

				?>
			</tbody>
		</table>
		<script>
			$(document).ready( function() {
				$('#tblUsers').dataTable({
					"order": []
				});
			});
		</script>    
    </div>
    
</form>

<?php
	include_once('../../includes/footer.php');