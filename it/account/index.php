    <?php 
	$page_title = "View Accounts";
    include_once('../../includes/header_it.php');
	$account_ID = $_SESSION['account_ID'];

    # displays list of users
    $sql_employee = "SELECT a.account_ID, a.username, a.status, at.type FROM account a 
    INNER JOIN account_type at ON at.account_type_ID = a.account_type_ID
    WHERE status!='Anything' ORDER BY account_ID DESC";
    $result_employee = $con->query($sql_employee);

?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
		<table id="tblUsers" class="table table-hover">
			<thead>
				<th>Username</th>
				<th>Account Type</th>
				<th>Status</th>
				<th></th>
			</thead>
			<tbody>
				<?php
					while ($row = mysqli_fetch_array($result_employee))
					{
						$account_ID = $row['account_ID'];
						$username = $row['username'];
						$type = $row['type'];
						$status = $row['status'];

						echo "
							<tr>
								<td>$username</td>
								<td>$type</td>
								<td>$status</td>
								<td align='center'>
									<a href='details.php?id=$account_ID' class='btn btn-xs btn-info'>
										<i class='fa fa-edit'></i>
									</a>
									<a href='delete.php?id=$account_ID' class='btn btn-xs btn-danger' 
										onclick='return confirm(\"Archive record?\");''>
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