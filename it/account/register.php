<?php 
	$page_title = "Register Account";
    include_once('../../includes/header_it.php');

    
    # displays list of user types
    $sql_types = "SELECT account_type_ID, type FROM account_type ORDER BY type";
    $result_types = $con->query($sql_types);

    $list_types = "";
	while ($row = mysqli_fetch_array($result_types))
	{
		$account_ID = $row['account_type_ID'];
		$account_type = $row['type'];
		$list_types .= "<option value='$account_ID'>$account_type</option>";
	}

	if (isset($_POST['add']))
	{
		$account_type = mysqli_real_escape_string($con, $_POST['type']);
		$email = mysqli_real_escape_string($con, $_POST['email']);
		$username = mysqli_real_escape_string($con, $_POST['username']);
		$password = password_hash(mysqli_real_escape_string($con, $_POST['password']), PASSWORD_BCRYPT);
		$confirmpassword = password_hash(mysqli_real_escape_string($con, $_POST['confirmpassword']), PASSWORD_BCRYPT);
        
		if ($_POST['password'] == $_POST['confirmpassword']) 
        {
			$sql_add = "INSERT INTO account VALUES ('', NULL, $account_type, '$email', '$username', '$password', 'Pending')";
			$con->query($sql_add) or die(mysqli_error($con));
		
			$aid = $_SESSION['account_ID'];
			echo "<script> alert('$con->insert_id') </script>";
			$sql_log = "INSERT INTO system_log VALUES ('', NOW(), $aid, 'Added $username account')";
			$con->query($sql_log) or die(mysqli_error($con));
        
        	header('location: ../index.php');
		} 

		else 
        {
			echo "<div class='col-lg-6'>
                        <div class='alert alert-danger' style='text-align:center'>
                            Passwords do not match.
                        </div>
				</div>
                    ";
		}
		
	}
?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
		<div class="form-group">
			<label class="control-label col-lg-2">Account Type</label>
			<div class="col-lg-4">
				<select name="type" class="form-control" required>
					<option value="">Select one...</option>
					<?php echo $list_types; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-2">Email Address</label>
			<div class="col-lg-4">
				<input name="email" type="email" class="form-control" placeholder="Email Address" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-2">Username</label>
			<div class="col-lg-4">
				<input name="username" type="text" class="form-control" placeholder="Username (GLB_firstname.lastname)" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-2">Password</label>
			<div class="col-lg-4">
				<input name="password" type="password" class="form-control" placeholder="Password" required />
			</div>
		</div>
        <div class="form-group">
			<label class="control-label col-lg-2">Retype Password</label>
			<div class="col-lg-4">
				<input name="confirmpassword" type="password" class="form-control" placeholder="Password" required />
			</div>
		</div>


		<div class="form-group">
			<div class="col-lg-offset-2 col-lg-4">
				<button name="add" type="submit" class="btn btn-success">
					Add
				</button>
			</div>
		</div>
	</div>
</form>

<?php
	include_once('../../includes/footer.php');