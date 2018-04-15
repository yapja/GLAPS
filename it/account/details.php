<?php 

	if (isset($_REQUEST['id']))
	{
		# checks if selected record is an ID value
		if (ctype_digit($_REQUEST['id']))
		{
			$account_ID = $_REQUEST['id'];

			$page_title = "Account #$account_ID Details";
		    include_once('../../includes/header_it.php');

		    # display existing record
			$sql_account = "SELECT username, password, account_type_ID, status FROM account WHERE account_ID = $account_ID";
			$result_account = $con->query($sql_account);

			while ($row = mysqli_fetch_array($result_account))
			{
				$username = $row['username'];
				$password = $row['password'];
				$account_type_ID = $row['account_type_ID'];
				$status = $row['status'];
			}

			# displays list of user types
            $sql_types = "SELECT account_type_ID, type FROM account_type ORDER BY type";
            $result_types = $con->query($sql_types);

            $list_types = "";
            while ($row = mysqli_fetch_array($result_types))
            {
                $account_type_ID2 = $row['account_type_ID'];
				$type = $row['type'];
				if ($account_type_ID == $account_type_ID2)
					$list_types .= "<option value=$account_type_ID2 selected>$type</option>";
				else
					$list_types .= "<option value=$account_type_ID2>$type</option>";

            }

			if (isset($_POST['update']))
			{
				$account_type_ID = mysqli_real_escape_string($con, $_POST['type']);
				$username = mysqli_real_escape_string($con, $_POST['username']);
				$password = mysqli_real_escape_string($con, $_POST['password']);
				$status = mysqli_real_escape_string($con, $_POST['status']);

				if ($_POST['password'] == "")
				{
					$sql_update = "UPDATE account SET account_type_ID = $account_type_ID, username = '$username', status = '$status' WHERE account_ID = $account_ID";
				}
				else
				{
					$password = password_hash(mysqli_real_escape_string($con, $_POST['password']), PASSWORD_BCRYPT);
					$sql_update = "UPDATE account SET account_type_ID = $account_type_ID, username = '$username', password = '$password', status = '$status' WHERE account_ID = $account_ID";
				}
                
				$result = $con->query($sql_update) or die(mysqli_error($con));
				
                $aid = $_SESSION['account_ID'];
                $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $aid, 'Updated $username')";
                $con->query($sql_log) or die(mysqli_error($con));
                
				header('location: index.php');
			}
		}
		else
		{
			header('location: index.php');
		}
	}
	else
	{
		header('location: index.php');
	}
?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-6">
		<div class="form-group">
			<label class="control-label col-lg-4">Account Type</label>
			<div class="col-lg-8">
				<select name="type" class="form-control" required>
					<?php echo $list_types; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4"></label>
			<div class="col-lg-8">
				<input name="username" type="text" class="form-control" value="<?php echo $username ?>" required />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4"></label>
			<div class="col-lg-8">
				<input name="password" type="password" class="form-control" placeholder="Password" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Status</label>
			<div class="col-lg-8">
				<select name="status" class="form-control" required>
					<option <?php if ($status == "Pending") echo 'selected' ; ?>>Pending</option>
					<option <?php if ($status == "Active") echo 'selected' ; ?>>Active</option>
					<option <?php if ($status == "Suspended") echo 'selected' ; ?>>Suspended</option>
					<option <?php if ($status == "Archived") echo 'selected' ; ?>>Archived</option>
				</select>
			</div>
		</div>
        
		<div class="form-group">
			<div class="col-lg-offset-4 col-lg-8">
				<button name="update" type="submit" class="btn btn-success">
					Update
				</button>
			</div>
		</div>
	</div>
</form>

<?php
	include_once('../../includes/footer.php');