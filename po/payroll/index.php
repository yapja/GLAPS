<?php 
	$page_title = "View Employee Payslips";
    include_once('../../includes/header_po.php');

	
	if(isset($_GET['pid']))
	{	
		$period_ID = $_GET['pid'];
		$sql_employee = "SELECT e.employee_ID, e.employee_detail_ID, e.last_name, e.first_name, e.middle_name, ed.profile_picture, a.username, p.title
			FROM employee e
			INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
			INNER JOIN account a ON a.account_ID = ed.account_ID
			INNER JOIN position p ON ed.position_ID = p.position_ID
			WHERE a.status != 'Archived' AND a.status != 'Pending' and ed.status != 'Archived'";
		$result_employee = $con->query($sql_employee);
	}
	else
	{
		$sql_employee = "SELECT e.employee_ID, e.employee_detail_ID, e.last_name, e.first_name, e.middle_name, ed.profile_picture, a.username, p.title
			FROM employee e
			INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
			INNER JOIN account a ON a.account_ID = ed.account_ID
			INNER JOIN position p ON ed.position_ID = p.position_ID
			WHERE a.status != 'Archived' AND a.status != 'Pending' and ed.status != 'Archived'";
		$result_employee = $con->query($sql_employee);
        
		$sql_period = "SELECT period_ID FROM period WHERE date_start < CURDATE() AND date_end >= CURDATE()";
		$result_period = $con->query($sql_period);
		while ($row = mysqli_fetch_array($result_period))
		{
			$period_ID = $row['period_ID'];
		}

	}

    $sql_period = "SELECT period_ID, date_start, date_end FROM period ORDER BY date_start";
    $result_period = $con->query($sql_period);

    $list_period = "";
	while ($row = mysqli_fetch_array($result_period))
	{
		$period_ID2= $row['period_ID'];
		$date_start = strtotime($row['date_start']);
		$date_end = strtotime($row['date_end']);
		$list_period .= "<option value='$period_ID2'>" . DATE('F d, Y', $date_start) . " - " . DATE('F d, Y', $date_end) . "</option>";
	}
	
	if (isset($_POST['next']))
	{
        $period_ID = mysqli_real_escape_string($con, $_POST['period_ID']);
        header('location: index.php?pid=' . $period_ID);
    }

?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
        <div class="form-group">
            <div class="col-lg-4">
                <select name='period_ID' class='form-control' required>
                    <option value=''>Select one...</option>
                    <?php echo " . $list_period . "?>
                </select>
			</div>
            <div class="col-lg-8">
                <button name="next" type="submit" class="btn btn-success">
                    Choose
                </button>
            </div>
        </div>
        
		<table id="tblUsers" class="table table-hover">
			<thead>
				<th>Name</th>
				<th>Username</th>
				<th>Position</th>
				<th>Profile Picture</th>
				<th>Payslip Status</th>
				<th></th>
			</thead>
			<tbody>
				<?php
					while ($row = mysqli_fetch_array($result_employee))
					{
						$employee_ID = $row['employee_ID'];
						$employee_detail_ID = $row['employee_detail_ID'];
						$name = $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name'];
						$profile_picture = $row['profile_picture'];
						$username = $row['username'];
						$position = $row['title'];
						
						$sql_salary = "SELECT COUNT(*) AS has_salary FROM salary_report WHERE employee_detail_ID = $employee_detail_ID AND period_ID = $period_ID";
						$result_salary = $con->query($sql_salary);
						while ($row = mysqli_fetch_array($result_salary))
						{
							$has_salary = $row['has_salary'];
						}
						if ($has_salary > 0)
						{
							$sql_status = "SELECT salary_report_ID, status FROM salary_report WHERE employee_detail_ID = $employee_detail_ID AND period_ID = $period_ID";
							$result_status = $con->query($sql_status);
							while ($row = mysqli_fetch_array($result_status))
							{
								$status = $row['status'];
								$salary_report_ID = $row['salary_report_ID'];
							}
							if ($status == 'Pending')
							{
								echo "
									<tr>
										<td>$name</td>
										<td>$username</td>
										<td>$position</td>
										<td><img src='../../images/profile_picture/$profile_picture' width=50></td>
										<td>$status</td>
										<td align='center'>
											<a href='payslipview.php?eid=$employee_detail_ID&pid=$period_ID' class='btn btn-xs btn-success'>View Payslip</a>
										</td>
									</tr>
								";
							}
                            else if ($status == 'Approved')
							{
								echo "
									<tr>
										<td>$name</td>
										<td>$username</td>
										<td>$position</td>
										<td><img src='../../images/profile_picture/$profile_picture' width=50></td>
										<td>$status</td>
										<td align='center'>
											<a href='payslipview.php?eid=$employee_detail_ID&pid=$period_ID' class='btn btn-xs btn-success'>View Payslip</a>
                                            <a href='../../reports/payslip.php?eid=$employee_detail_ID&pid=$period_ID' class='btn btn-xs btn-info' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
                                            <a href='delete.php?eid=$employee_detail_ID&sid=$salary_report_ID&pid=$period_ID' class='btn btn-xs btn-danger' onclick='return confirm(\"Archive payslip?\");''> <i class='fa fa-trash'></i></a>
										</td>
									</tr>
								";
							}
                            else if ($status == 'Archived')
							{
								echo "
									<tr>
										<td>$name</td>
										<td>$username</td>
										<td>$position</td>
										<td><img src='../../images/profile_picture/$profile_picture' width=50></td>
										<td>$status</td>
										<td align='center'>
											<a href='generatepayslip.php?eid=$employee_detail_ID&pid=$period_ID' class='btn btn-xs btn-danger'>Generate Payslip</a>
										</td>
									</tr>
								";
							}
							else
							{
								echo "
									<tr>
										<td>$name</td>
										<td>$username</td>
										<td>$position</td>
										<td><img src='../../images/profile_picture/$profile_picture' width=50></td>
										<td>$status</td>
										<td align='center'>
											<a href='details.php?srid=$salary_report_ID&eid=$employee_detail_ID&pid=$period_ID' class='btn btn-xs btn-warning'>Edit Payslip Details</a>
                                            <a href='payslipview.php?eid=$employee_detail_ID&pid=$period_ID' class='btn btn-xs btn-success'>View Payslip</a>
										</td>
									</tr>
								";
							}
						}
						else
						{
							echo "
								<tr>
									<td>$name</td>
									<td>$username</td>
									<td>$position</td>
									<td><img src='../../images/profile_picture/$profile_picture' width=50></td>
									<td></td>
									<td align='center'>
										<a href='generatepayslip.php?eid=$employee_detail_ID&pid=$period_ID' class='btn btn-xs btn-danger'>Generate Payslip</a>
									</td>
								</tr>
							";
						}
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