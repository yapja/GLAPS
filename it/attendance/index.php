    <?php 
	$page_title = "View my pending Attendance";
    include_once('../../includes/header_it.php');
	$account_ID = $_SESSION['account_ID'];

    # displays list of users
    $employee_detail_ID = $_SESSION['employee_detail_ID'];
    
    $sql_attendance = "SELECT e.first_name, e.last_name, af.attendance_flexible_ID, af.date, af.time_in, af.time_out, af.reason, af.status, af.date_filed FROM employee e
	INNER JOIN attendance_flexible af ON e.employee_detail_ID = af.employee_detail_ID WHERE af.employee_detail_ID = $employee_detail_ID
    ORDER BY attendance_flexible_ID DESC";

    $result_attendance = $con->query($sql_attendance);

?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
		<table id="tblUsers" class="table table-hover">
			<thead>
				<th>Name</th>
				<th>Date</th>
				<th>Time In</th>
                <th>Time Out</th>
				<th>Reason</th>
				<th>Status</th>
			</thead>
			<tbody>
				<?php
					while ($row = mysqli_fetch_array($result_attendance))
					{
						$attendance_flexible_ID = $row['attendance_flexible_ID'];
						$name = $row['last_name'] . ', ' . $row['first_name'];
						$date = $row['date'];
						$time_in = $row['time_in'];
						$time_out = $row['time_out'];
						$reason = $row['reason'];
						$status = $row['status'];
						
						echo "
							<tr>
								<td>$name</td>
								<td>$date</td>
								<td>$time_in</td>
								<td>$time_out</td>
								<td>$reason</td>
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