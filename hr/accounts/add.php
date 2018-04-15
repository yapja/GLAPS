<?php 
	$page_title = "Add Employee Profile";
    include_once('../../includes/header_hr.php');
    
    # displays list of position
    $sql_position = "SELECT position_ID, title FROM position ORDER BY title";
    $result_position = $con->query($sql_position);

    $list_position = "";
	while ($row = mysqli_fetch_array($result_position))
	{
		$position_ID = $row['position_ID'];
		$title = $row['title'];
		$list_position .= "<option value='$position_ID'>$title</option>";
	}

	# displays list of department
    $sql_department = "SELECT department_ID, department FROM department ORDER BY department";
    $result_department = $con->query($sql_department);

    $list_department = "";
	while ($row = mysqli_fetch_array($result_department))
	{
		$department_ID = $row['department_ID'];
		$department = $row['department'];
		$list_department .= "<option value='$department_ID'>$department</option>";
	}

	# displays list of supervisors
	$sql_supervisor = "SELECT e.employee_ID, e.first_name, e.gender, e.last_name
	FROM employee e
	INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
	INNER JOIN position  p ON ed.position_ID = p.position_ID
	WHERE p.title = 'Supervisor' ORDER BY e.last_name";
	$result_supervisor = $con->query($sql_supervisor);
	$list_supervisors = "";
	while ($row = mysqli_fetch_array($result_supervisor))
	{
		$supervisor_ID2 = $row['employee_ID'];
		if ($row['gender'] == 'M')
			$name = 'Mr. ' . $row['first_name'] . ' ' . $row['last_name'];
		else if ($row['gender']  == 'F')
			$name = 'Ms. ' . $row['first_name'] . ' ' . $row['last_name'];

		$list_supervisors .= "<option value=$supervisor_ID2>$name</option>";
	}

	# add a user record
	if (isset($_POST['add']))
	{
		$first_name = mysqli_real_escape_string($con, ucwords($_POST['first_name']));
		$middle_name = mysqli_real_escape_string($con, ucwords($_POST['middle_name']));
		$last_name = mysqli_real_escape_string($con, ucwords($_POST['last_name']));
		$birth_date = mysqli_real_escape_string($con, $_POST['birth_date']);
		$gender = mysqli_real_escape_string($con, $_POST['gender']);
		$position = mysqli_real_escape_string($con, $_POST['position']);
		$department = mysqli_real_escape_string($con, $_POST['department']);
		$supervisor = mysqli_real_escape_string($con, $_POST['supervisor']);
		$civil_status = mysqli_real_escape_string($con, $_POST['civil_status']);
		$address1 = mysqli_real_escape_string($con, ucwords($_POST['address1']));
		$address2 = mysqli_real_escape_string($con, ucwords($_POST['address2']));
		$city = mysqli_real_escape_string($con, ucwords($_POST['city']));
		$zip_code = mysqli_real_escape_string($con, $_POST['zip_code']);
		$mobile = mysqli_real_escape_string($con, $_POST['mobile']);
		$landline = mysqli_real_escape_string($con, $_POST['landline']);
		$bank = mysqli_real_escape_string($con, $_POST['bank']);
		$dependent = mysqli_real_escape_string($con, $_POST['dependent']);
		$SSS = mysqli_real_escape_string($con, $_POST['SSS']);
		$TIN = mysqli_real_escape_string($con, $_POST['TIN']);
		$philhealth = mysqli_real_escape_string($con, $_POST['philhealth']);
		$HDMF = mysqli_real_escape_string($con, $_POST['HDMF']);
		$date_hired = mysqli_real_escape_string($con, $_POST['date_hired']);
		$basic_pay = mysqli_real_escape_string($con, $_POST['basic_pay']);
		$transportation = mysqli_real_escape_string($con, $_POST['transportation']);
		$gas = mysqli_real_escape_string($con, $_POST['gas']);
		$food = mysqli_real_escape_string($con, $_POST['food']);
		$assigned_ID = "123";
		$account_ID = $_REQUEST['id'];

		$upload = "../../images/profile_picture/"; # location where to upload the image
		$image = $_FILES["image"]["name"]; # gets the file from file upload
		$newImage = date('YmdHis-') . basename($image); # eg. 20170322051234-sample.jpg
		$file = $upload . $newImage;

		move_uploaded_file($_FILES["image"]["tmp_name"], $file);
		

		$sql_employee = "INSERT INTO employee VALUES ('', '$first_name', '$middle_name', '$last_name', '$birth_date', '$gender', '$civil_status', NULL, NULL, '$bank', NULL)";
		$con->query($sql_employee) or die(mysqli_error($con));
		$employee_ID = $con->insert_id;

		$sql_address = "INSERT INTO address VALUES ('', $employee_ID, '$address1', '$address2', '$city', '$zip_code')";
		$con->query($sql_address) or die(mysqli_error($con));
		$address_ID = $con->insert_id;

		$sql_contact = "INSERT INTO contact VALUES ('', $employee_ID, '$landline', '$mobile')";
		$con->query($sql_contact) or die(mysqli_error($con));
		$contact_ID = $con->insert_id;

		if ($supervisor == '')
			$sql_detail = "INSERT INTO employee_detail VALUES ('', $employee_ID, '$assigned_ID', $position, $department, NULL, '$dependent', '$SSS', '$TIN', '$philhealth', '$HDMF', '$newImage', '$date_hired', NOW(), 'Active', $account_ID)";
		else
			$sql_detail = "INSERT INTO employee_detail VALUES ('', $employee_ID, '$assigned_ID', $position, $department, $supervisor, '$dependent', '$SSS', '$TIN', '$philhealth', '$HDMF', '$newImage', '$date_hired', NOW(), 'Active', $account_ID)";
		
		$con->query($sql_detail) or die(mysqli_error($con));
		$employee_detail_ID = $con->insert_id;

		$sql_allowance = "INSERT INTO allowance VALUES ('', $employee_detail_ID, $transportation, $gas, $food)";
		$con->query($sql_allowance) or die(mysqli_error($con));
		$allowance_ID = $con->insert_id;

		$sql_salary = "INSERT INTO employee_salary VALUES ('', $employee_detail_ID, $basic_pay, $allowance_ID)";
		$con->query($sql_salary) or die(mysqli_error($con));
		$salary_ID = $con->insert_id;

		if ($gender == 'M')
			$parental_leave = 7;
		else
			$parental_leave = 120;
		$sql_leave = "INSERT INTO employee_leave VALUES ('', $employee_detail_ID, YEAR(NOW()), 5, 5, $parental_leave, 5, 0)";
		$con->query($sql_leave) or die(mysqli_error($con));
		$leave_ID = $con->insert_id;


		$sql_employee_update = "UPDATE employee SET address_ID = $address_ID, contact_ID = $contact_ID, employee_detail_ID = $employee_detail_ID WHERE employee_ID = $employee_ID";
		$con->query($sql_employee_update) or die(mysqli_error($con));


		$sql_account_update = "UPDATE account SET employee_detail_ID = $employee_detail_ID, status = 'Active' WHERE account_ID = $account_ID";
		$con->query($sql_account_update) or die(mysqli_error($con));
		
        $sql_select = "SELECT username FROM account WHERE account_ID = $account_ID";
			$result = $con->query($sql_select) or die(mysqli_error($con));
            while ($row = mysqli_fetch_array($result))
					{
						$username = $row['username'];
                    }
        
        $account_ID = $_SESSION['account_ID'];
                $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $account_ID, 'Added $username details')";
                $con->query($sql_log) or die(mysqli_error($con));
        
		header('location: index.php');
	}

?>
<form method="POST" class="form-horizontal" enctype="multipart/form-data">
	<div class="col-lg-6">
		<div class="form-group">
			<label class="control-label col-lg-4">First Name</label>
			<div class="col-lg-8">
				<input name="first_name" type="text" class="form-control" placeholder="First Name" required />
			</div>
		</div><div class="form-group">
			<label class="control-label col-lg-4">Middle Name</label>
			<div class="col-lg-8">
				<input name="middle_name" type="text" class="form-control" placeholder="Middle Name" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Last Name</label>
			<div class="col-lg-8">
				<input name="last_name" type="text" class="form-control" placeholder="Last Name" required />
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-4">Birthdate</label>
			<div class="col-lg-8">
				<input name="birth_date" type="date" class="form-control" required />
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-4">Gender</label>
			<div class="col-lg-8">
				<select name="gender" class="form-control" required>
					<option value="">Select one...</option>
					<option value="M">Male</option>
					<option value="F">Female</option>
				</select>
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-4">Position</label>
			<div class="col-lg-8">
				<select name="position" class="form-control" required>
					<option value="">Select one...</option>
					<?php echo $list_position; ?>
				</select>
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-4">Department</label>
			<div class="col-lg-8">
				<select name="department" class="form-control" required>
					<option value="">Select one...</option>
					<?php echo $list_department; ?>
				</select>
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-4">Supervisor</label>
			<div class="col-lg-8">
				<select name="supervisor" class="form-control">
					<option value="">Select one...</option>
					<?php echo $list_supervisors; ?>
				</select>
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-4">Civil Status</label>
			<div class="col-lg-8">
				<select name="civil_status" class="form-control" required>
					<option value="">Select one...</option>
					<option value="Single">Single</option>
					<option value="Married">Married</option>
					<option value="Widowed">Widowed</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Address</label>
			<div class="col-lg-8">
				<textarea name="address1" style="width:100%;height:80px;" placeholder="Address 1"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4"></label>
			<div class="col-lg-8">
				<textarea name="address2" style="width:100%;height:80px;" placeholder="Address 2"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">City</label>
			<div class="col-lg-8">
				<input name="city" type="text" class="form-control" placeholder="City" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">ZIP Code</label>
			<div class="col-lg-8">
				<input name="zip_code" type="number" class="form-control" placeholder="ZIP Code" min="0" max="9999" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Mobile Number</label>
			<div class="col-lg-8">
				<input name="mobile" type="text" class="form-control" placeholder="Mobile Number" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Landline</label>
			<div class="col-lg-8">
				<input name="landline" type="text" class="form-control" placeholder="Landline" />
			</div>
		</div>
	</div>
    
	<div class="col-lg-6">
        
        <div class="form-group">
			<label class="control-label col-lg-4">Profile Picture</label>
			<div class="col-lg-8">
				<div class="fileinput fileinput-new" data-provides="fileinput">
			  		<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
			    		<img src='<?php echo app_path; ?>images/placeholder.png' alt="...">
			  		</div>
			  		<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
				  	<div>
				    	<span class="btn btn-default btn-file">
				    		<span class="fileinput-new">Select image</span>
				    		<span class="fileinput-exists">Change</span>
				    		<input type="file" name="image" required>
				    	</span>
				    	<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
				  	</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Bank Number</label>
			<div class="col-lg-8">
				<input name="bank" type="text" class="form-control" placeholder="Bank Number" required />
			</div>
		</div>
	</div>
    
    <div class="col-lg-6">
		<div class="form-group">
			<label class="control-label col-lg-4">Dependent</label>
			<div class="col-lg-8">
				<input name="dependent" type="text" class="form-control" placeholder="Dependent" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">SSS</label>
			<div class="col-lg-8">
				<input name="SSS" type="text" class="form-control" placeholder="SSS" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">TIN</label>
			<div class="col-lg-8">
				<input name="TIN" type="text" class="form-control" placeholder="TIN" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">HDMF</label>
			<div class="col-lg-8">
				<input name="HDMF" type="text" class="form-control" placeholder="HDMF" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Philhealth</label>
			<div class="col-lg-8">
				<input name="philhealth" type="text" class="form-control" placeholder="Philhealth" required />
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-4">Date Hired</label>
			<div class="col-lg-8">
				<input name="date_hired" type="date" class="form-control" required />
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-4">Basic Pay</label>
			<div class="col-lg-8">
				<input name="basic_pay" type="text" class="form-control" placeholder="Basic Pay" required />
			</div>
		</div>
        <div class="form-group">
			<h4><label class="control-label col-lg-4">Allowance</label></h4>
			<div class="col-lg-8">
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-4">Transportation</label>
			<div class="col-lg-8">
				<input name="transportation" type="text" class="form-control" placeholder="Transportation Allowance" required />
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-4">Food</label>
			<div class="col-lg-8">
				<input name="food" type="text" class="form-control" placeholder="Food Allowance" required />
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-4">Gas</label>
			<div class="col-lg-8">
				<input name="gas" type="text" class="form-control" placeholder="Gas Allowance" required />
			</div>
		</div>
		<div class="form-group">
			<div class="col-lg-offset-4 col-lg-8">
				<button name="add" type="submit" class="btn btn-success">
					Add
				</button>
			</div>
		</div>
    </div>
    
</form>
<?php
	include_once('../../includes/footer.php');