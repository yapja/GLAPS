<?php 
	$page_title = "My Overtime";
    include_once('../../includes/header_gam.php');


	# displays list of users
	$employee_detail_ID = $_SESSION['employee_detail_ID'];

	$sql_overtime = "SELECT eot.employee_overtime_ID, ott.type, eot.date_start, eot.date_end, (SELECT CONCAT(last_name, ', ', first_name) FROM employee WHERE employee_detail_ID = eot.validated_by) AS validated_by, eot.status, eot.remark
	FROM employee_overtime eot
	INNER JOIN overtime_type ott ON ott.overtime_type_ID = eot.overtime_type_ID
	WHERE eot.employee_detail_ID = $employee_detail_ID ORDER BY employee_overtime_ID DESC";

    $result_overtime = $con->query($sql_overtime);

?>

<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
		<table id="tblUsers" class="table table-hover">
			<thead>
				<th>Overtime Type</th>
				<th>Date Start</th>
                <th>Date End</th>
				<th>Validated By</th>
                <th>Remarks</th>
                <th>Status</th>
			</thead>
			<tbody>
				<?php
					while ($row = mysqli_fetch_array($result_overtime))
					{
						$employee_overtime_ID = $row['employee_overtime_ID'];
						$overtime_type = $row['type'];
						$date_start = $row['date_start'];
						$date_end = $row['date_end'];
						$validated_by = $row['validated_by'];
						$remark = $row['remark'];
						$status = $row['status'];
                        

						echo "
							<tr>
								<td>$overtime_type</td>
								<td>$date_start</td>
								<td>$date_end</td>
								<td>$validated_by</td>
								<td>$remark</td>
								<td>$status</td>
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