<?php 
	$page_title = "Employees";
    include_once('../../includes/header_gam.php');

    # displays list of users
    $sql_employee = "SELECT e.employee_ID, e.employee_detail_ID, e.last_name, e.first_name, e.middle_name, ed.profile_picture, p.title, a.username
		FROM employee e
		INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
        INNER JOIN account a ON a.account_ID = ed.account_ID
        INNER JOIN position p ON p.position_ID = ed.position_ID
		WHERE a.status != 'Archived' AND a.status != 'Pending' and ed.status != 'Archived'";
    $result_employee = $con->query($sql_employee);

?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
		<table id="tblUsers" class="table table-hover">
			<thead>
				<th>Name</th>
				<th>Username</th>
				<th>Position</th>
				<th>Profile Picture</th>
				<th></th>
			</thead>
			<tbody>
				<?php
					while ($row = mysqli_fetch_array($result_employee))
					{
						$employee_detail_ID = $row['employee_detail_ID'];
						$name = $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name'];
						$profile_picture = $row['profile_picture'];
						$position = $row['title'];
                        $username = $row['username'];

						echo "
							<tr>
								<td>$name</td>
								<td>$username</td>
								<td>$position</td>
								<td><img src='../../images/profile_picture/$profile_picture' width=50></td>
								<td align='center'>
                                    <a href='../timesheet/index.php?id=$employee_detail_ID' class='btn btn-xs btn-warning'>View Timesheet</a>
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