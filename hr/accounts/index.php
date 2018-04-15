<?php 
	$page_title = "Accounts Pending";
    include_once('../../includes/header_hr.php');

    # displays list of users
	$sql_account = "SELECT a.account_ID, a.username, at.type FROM account a
	INNER JOIN account_type at ON a.account_type_ID = at.account_type_ID
	WHERE a.status = 'Pending'";
    $result_account = $con->query($sql_account);

?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
		<table id="tblUsers" class="table table-hover">
			<thead>
				<th>Username</th>
                <th>Type</th>
				<th></th>
			</thead>
			<tbody>
				<?php
					while ($row = mysqli_fetch_array($result_account))
					{
						$account_ID = $row['account_ID'];
						$username = $row['username'];
						$type = $row['type'];

						echo "
							<tr>
								<td>$username</td>
								<td>$type</td>
								<td align='center'>
									<a href='add.php?id=$account_ID' class='btn btn-xs btn-info'>
										Add Employee Details</i>
									</a>
									<a href='delete.php?id=$account_ID' class='btn btn-xs btn-danger' 
										onclick='return confirm(\"Archived record?\");''>
										<i class='fa fa-trash'></i>
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