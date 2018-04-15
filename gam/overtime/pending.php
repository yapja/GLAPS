<?php 
	$page_title = "Pending Overtime";
    include_once('../../includes/header_gam.php');


    # displays list of users
	$sql_leave = "SELECT e.first_name, e.last_name, eot.employee_overtime_ID, ott.type, eot.date_start, eot.date_end, eot.reason
	FROM employee e
	INNER JOIN employee_overtime eot ON e.employee_detail_ID = eot.employee_detail_ID
	INNER JOIN overtime_type ott ON ott.overtime_type_ID = eot.overtime_type_ID
	WHERE eot.status = 'Pending'";

    $result_leave = $con->query($sql_leave);

?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
		<table id="tblUsers" class="table table-hover">
			<thead>
				<th>Name</th>
				<th>Overtime Type</th>
				<th>Date Start</th>
                <th>Date End</th>
				<th>Reason</th>
				<th></th>
			</thead>
			<tbody>
				<?php
					while ($row = mysqli_fetch_array($result_leave))
					{
						$employee_overtime_ID = $row['employee_overtime_ID'];
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
									<a href='approval.php?id=$employee_overtime_ID' class='btn btn-xs btn-info'>
										<i class='fa fa-check'></i>
									</a>
									<a href='details.php?id=$employee_overtime_ID' class='btn btn-xs btn-danger'>
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
			    $('#tblUsers').DataTable();
			});
		</script>
	</div>
</form>

<?php
	include_once('../../includes/footer.php');