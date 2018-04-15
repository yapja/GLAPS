<?php 
	$page_title = "Pending Leave";
    include_once('../../includes/header_gam.php');

    # displays list of users
	$sql_leave = "SELECT e.first_name, e.last_name, lt.type, elt.employee_leave_taken_ID, elt.date_start, elt.date_end, elt.reason, elt.validated_by, elt.status, elt.remark
	FROM employee e
	INNER JOIN employee_leave_taken elt on e.employee_detail_ID = elt.employee_detail_ID
	INNER JOIN leave_type lt ON lt.leave_type_ID = elt.leave_type_ID
	WHERE elt.status = 'Pending' ORDER BY employee_leave_taken_ID DESC";

    $result_leave = $con->query($sql_leave);

?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
		<table id="tblUsers" class="table table-hover">
			<thead>
				<th>Name</th>
				<th>Leave Type</th>
				<th>Date Start</th>
                <th>Date End</th>
				<th>Reason</th>
				<th></th>
			</thead>
			<tbody>
				<?php
					while ($row = mysqli_fetch_array($result_leave))
					{
						$employee_leave_taken_ID = $row['employee_leave_taken_ID'];
						$name = $row['last_name'] . ', ' . $row['first_name'];
						$date_start = $row['date_start'];
						$date_end = $row['date_end'];
						$reason = $row['reason'];
						$type = $row['type'];
                        

						echo "
							<tr>
								<td>$name</td>
								<td>$type</td>
								<td>$date_start</td>
								<td>$date_end</td>
								<td>$reason</td>
                                <td>
									<a href='approval.php?id=$employee_leave_taken_ID' class='btn btn-xs btn-info'>
										<i class='fa fa-check'></i>
									</a>
									<a href='details.php?id=$employee_leave_taken_ID' class='btn btn-xs btn-danger'>
										<i class='fa fa-times'></i>
									</a>
								</td>
							</tr>
						";
					}

				?>
			</tbody>
		</table>
		<script>
			$(document).ready(function(){
			    $('#tblUsers').DataTable({"order": [0, 'desc']});
			});
		</script>
	</div>
</form>

<?php
	include_once('../../includes/footer.php');