<?php
if (isset($_REQUEST['request']))
{
    $page_title = "Reset Password";
    include_once('includes/header_admin.php');
    
    $request_password_ID = $_REQUEST['request'];
    
    $sql_req = "SELECT account_ID, code FROM request_password WHERE request_password_ID = $request_password_ID";
    $result_req = $con->query($sql_req);
    
    $aid = null;
    $code = null;
    while ($row = mysqli_fetch_array($result_req))
    {
        $aid = $row['account_ID'];
        $code = $row['code'];
    }
    
    if ($aid == null || $code == null) 
    {
        header('location: invalidrequest.php');
    }
    
    # display existing record
    $sql_account = "SELECT username FROM account WHERE account_ID = $aid";
    $result_account = $con->query($sql_account);

    while ($row = mysqli_fetch_array($result_account))
    {
        $username = $row['username'];
    }

    if (isset($_POST['update']))
    {
        if ($_POST['code'] == $code)
        {
            $newpassword = mysqli_real_escape_string($con, $_POST['password']);
            $newpassword = password_hash(mysqli_real_escape_string($con, $_POST['password']), PASSWORD_BCRYPT);

            if ($_POST['password'] == $_POST['confirmpassword'])
            {
                $sql_update = "UPDATE account SET password = '$newpassword' WHERE account_ID = $aid";
                $result = $con->query($sql_update) or die(mysqli_error($con));

                $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $aid, 'Changed Password')";
                $con->query($sql_log) or die(mysqli_error($con));

                header('location: index.php');
            }

            else
            {
                echo "<div class='col-lg-6'>
                        <div class='alert alert-danger' style='text-align:center'>
                            Password do not match.
                        </div>
				</div>
                    ";
            }
        }
        else
        {
            echo "<div class='col-lg-6'>
                        <div class='alert alert-danger' style='text-align:center'>
                            Invalid request code.
                        </div>
				</div>
                    ";
        }
    }
}
?>
<form method="POST" class="form-horizontal">
    <div class="col-lg-12">
        <div class="form-group">
            <label class="control-label col-lg-2">Username</label>
            <div class="col-lg-4">
                <input name="username" type="text" class="form-control" value="<?php echo $username ?>" disabled />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2">Code</label>
            <div class="col-lg-4">
                <input name="code" type="text" class="form-control" placeholder="Code" required/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2">Password</label>
            <div class="col-lg-4">
                <input name="password" type="password" class="form-control" placeholder="New Password" required/>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-lg-2">Retype Password</label>
            <div class="col-lg-4">
                <input name="confirmpassword" type="password" class="form-control" placeholder="Retype Password" required />
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-4">
                <button name="update" type="submit" class="btn btn-success">
                    Update
                </button>
            </div>
        </div>
    </div>
</form>

<?php
    include_once('includes/footer.php');