<?php 
	$page_title = "View Rejected Payslips";
    include_once('../../includes/header_po.php');

    # displays list of users
    $sql_employee = "SELECT e.employee_ID, e.last_name, e.first_name, e.middle_name, sr.salary_report_ID, p.date_start, p.date_end
		FROM employee e
		INNER JOIN salary_report sr ON e.employee_detail_ID = sr.employee_detail_ID
		INNER JOIN period p ON sr.period_ID = p.period_ID
		WHERE sr.status = 'Rejected'";
    $result_employee = $con->query($sql_employee);

?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
		<table id="tblUsers" class="table table-hover">
			<thead>
				<th>Name</th>
				<th>Period</th>
				<th></th>
				<th></th>
			</thead>
			<tbody>
				<?php
					while ($row = mysqli_fetch_array($result_employee))
					{	
						$salary_report_ID = $row['salary_report_ID'];
						$name = $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name'];
						$date_start = $row['date_start'];
                        $date_end = $row['date_end'];
                        
						echo "
							<tr>
								<td>$name</td>
								<td>" . DATE('F d, Y', strtotime($date_start)) . " - " . DATE('F d, Y', strtotime($date_end)) . "</td>
								<td align='center'>
                                    <a href='payslipview.php?id=$salary_report_ID' class='btn btn-xs btn-warning'><i class='fa fa-edit'></i></a>
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