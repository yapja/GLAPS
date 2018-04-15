<?php
session_start();
$aid = $_SESSION['account_ID'];

$page_title = "Reset Password";
include_once('../includes/header_emp.php');

# display existing record
$sql_account = "SELECT username, password FROM account WHERE account_ID = $aid";
$result_account = $con->query($sql_account);

while ($row = mysqli_fetch_array($result_account))
{
    $username = $row['username'];
    $oldpassword = $row['password'];
}

if (isset($_POST['update']))
{
    if (password_verify($_POST['oldpassword'], $oldpassword))
    {
        $newpassword = mysqli_real_escape_string($con, $_POST['password']);
        $newpassword = password_hash(mysqli_real_escape_string($con, $_POST['password']), PASSWORD_BCRYPT);

        if ($_POST['password'] == $_POST['confirmpassword'])
        {
            $sql_update = "UPDATE account SET password = '$newpassword' WHERE account_ID = $aid";
            $result = $con->query($sql_update) or die(mysqli_error($con));

            $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $aid, 'Changed Password')";
            $con->query($sql_log) or die(mysqli_error($con));

            header('location: ../emp/timesheet/');
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
                            Old Password do not match.
                        </div>
				</div>
                    ";
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
            <label class="control-label col-lg-2">Old Password</label>
            <div class="col-lg-4">
                <input name="oldpassword" type="password" class="form-control" placeholder="Old Password" required/>
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
    include_once('../includes/footer.php');