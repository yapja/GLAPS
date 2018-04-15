<?php 
	$page_title = "Pending Contributions";
    include_once('../../includes/header_fh.php');


    # displays list of users
	$sql_pending = "SELECT e.first_name, e.last_name, ac.contribution_type, ac.status, ac.additional_contribution_ID, ac.period_end, ac.reason, ac.approved_by
	FROM employee e
	INNER JOIN additional_contribution ac ON e.employee_detail_ID = ac.employee_detail_ID
	WHERE ac.status = 'Pending'";
    $result = $con->query($sql_pending);

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
				<th></th>
			</thead>
			<tbody>
				<?php
					while ($row = mysqli_fetch_array($result))
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
                                <td align='center'>
									<a href='approve.php?id=$additional_contribution_ID' class='btn btn-xs btn-info'>
										<i class='fa fa-check'></i>
									</a>
									<a href='deny.php?id=$additional_contribution_ID' class='btn btn-xs btn-danger'>
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