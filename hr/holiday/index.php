<?php 
	$page_title = "Holidays <a href='add.php'>
										<i class='fa fa-plus-circle'></i>
									</a>";
    include_once('../../includes/header_hr.php');

    # displays list of users
	$sql_holiday = "SELECT holiday_ID, name, date, type FROM holiday";
    $result_holiday = $con->query($sql_holiday);

?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
		<table id="tblUsers" class="table table-hover">
			<thead>
				<th>Holiday</th>
				<th>Date</th>
                <th>Type</th>
				<th></th>
			</thead>
			<tbody>
				<?php
					while ($row = mysqli_fetch_array($result_holiday))
					{
                        $holiday_ID = $row['holiday_ID'];
						$name = $row['name'];
						$date = $row['date'];
						$type = $row['type'];

						echo "
							<tr>
								<td>$name</td>
								<td>$date</td>
								<td>$type</td>
								<td align='center'>
									<a href='details.php?id=$holiday_ID' class='btn btn-xs btn-info'>
										<i class='fa fa-edit'></i>
									</a>
									<a href='delete.php?id=$holiday_ID' class='btn btn-xs btn-danger' 
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