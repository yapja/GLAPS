<?php 
	$page_title = "My Contributions <a href='file.php'>
										<i class='fa fa-plus-circle'></i>
									</a>";
    include_once('../../includes/header_fh.php');


	# displays list of users
	$employee_detail_ID = $_SESSION['employee_detail_ID'];

	$sql_contributions = "SELECT e.first_name, e.last_name, ac.contribution_type, ac.status, ac.additional_contribution_ID, ac.period_end, ac.reason, ac.approved_by
	FROM employee e
	INNER JOIN additional_contribution ac ON e.employee_detail_ID = ac.employee_detail_ID
	WHERE ac.employee_detail_ID = $employee_detail_ID ORDER BY additional_contribution_ID DESC";

    $result_contributions = $con->query($sql_contributions);

?>

<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
		<table id="tblUsers" class="table table-hover">
			<thead>
				<th>Name</th>
				<th>Contribution</th>
				<th>Date End</th>
				<th>Reason</th>
				<th>Status</th>
			</thead>
			<tbody>
				<?php
					while ($row = mysqli_fetch_array($result_contributions))
					{
						$additional_contribution_ID = $row['additional_contribution_ID'];
						$name = $row['last_name'] . ', ' . $row['first_name'];
						$contribution = $row['contribution_type'];
                        if ($contribution == 0)
                        {
                            $contribution = 'SSS';
                        }
                        else if ($contribution == 1)
                        {
                            $contribution = 'HDMF';
                        }
                        else
                        {
                            $contribution = 'PhilHealth';
                        }
						$period_end = $row['period_end'];
						$reason = $row['reason'];
						$status = $row['status'];
                        

						echo "
							<tr>
								<td>$name</td>
								<td>$contribution</td>
								<td>$period_end</td>
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