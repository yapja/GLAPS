<?php 
	$page_title = "My Leaves";
    include_once('../../includes/header_gam.php');

	# displays list of users
	$employee_detail_ID = $_SESSION['employee_detail_ID'];

	$sql_leave = "SELECT elt.employee_leave_taken_ID, lt.type, elt.date_start, elt.date_end, (SELECT CONCAT(last_name, ', ', first_name) from employee WHERE employee_detail_ID = elt.validated_by) AS validated_by, elt.status, elt.remark
	FROM employee_leave_taken elt
	INNER JOIN leave_type lt ON lt.leave_type_ID = elt.leave_type_ID
	WHERE elt.employee_detail_ID = $employee_detail_ID ORDER BY employee_leave_taken_ID DESC";

    $result_leave = $con->query($sql_leave);

?>

<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
		<table id="tblUsers" class="table table-hover">
			<thead>
				<th>Leave Type</th>
				<th>Date Start</th>
                <th>Date End</th>
				<th>Validated By</th>
                <th>Remarks</th>
                <th>Status</th>
			</thead>
			<tbody>
				<?php
					while ($row = mysqli_fetch_array($result_leave))
					{
						$employee_leave_taken_ID = $row['employee_leave_taken_ID'];
						$leave_type = $row['type'];
						$date_start = $row['date_start'];
						$date_end = $row['date_end'];
						$validated_by = $row['validated_by'];
						$remark = $row['remark'];
						$status = $row['status'];
                        

						echo "
							<tr>
								<td>$leave_type</td>
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