<?php 
$page_title = "GLAPS Login";
include_once('includes/header_admin.php');
if (isset($_POST['login']))
{

    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $sql_login = "SELECT a.account_ID, a.employee_detail_ID, a.account_type_ID, a.username, a.password, a.status, ed.employee_ID, ed.position_ID FROM account a
        INNER JOIN employee_detail ed ON a.employee_detail_ID = ed.employee_detail_ID
        WHERE a.username = '$username' AND a.status != 'Archived'";
    $result_login = $con->query($sql_login) or die(mysqli_error($con));


    if (mysqli_num_rows($result_login) > 0)
    {
        while ($row = mysqli_fetch_array($result_login))
        {
            if (password_verify($password, $row['password']))
            {  
                $_SESSION['account_ID'] = $row['account_ID'];
                $_SESSION['employee_ID'] = $row['employee_ID'];
                $_SESSION['employee_detail_ID'] = $row['employee_detail_ID'];
                $_SESSION['position_ID'] = $row['position_ID'];
                $_SESSION['status'] = $row['status'];


                $account_ID = $_SESSION['account_ID'];
                $sql_log = "INSERT INTO user_log VALUES ('', $account_ID, NOW(), NULL)";
                $con->query($sql_log) or die(mysqli_error($con));

                if ($_SESSION['position_ID'] == 1) // IT
                    header('location: it/index.php');   
                else if ($_SESSION['position_ID'] == 2) // HR
                    header('location: hr/index.php');
                else if ($_SESSION['position_ID'] == 3) // Finance Head
                    header('location: fh/index.php');
                else if ($_SESSION['position_ID'] == 4) // Payroll Officer
                    header('location: po/timesheet/index.php');
                else if ($_SESSION['position_ID'] == 5) // General Administrative Manager
                    header('location: gam/index.php');
                else
                    header('location: emp/timesheet/index.php'); // Employees
            }
        }
    }
}

?>
<form method="POST" class="form-horizontal">
    <div class="col-lg-offset-3 col-lg-6">

        <p style="text-align: center;"><img src="images/glaps.png" style="height: 150px; width: 180px;"></p>

        <?php
        if (isset($_POST['login']))
        {
            if (mysqli_num_rows($result_login) == 0  || !password_verify($password, $row['password']))
            {
                echo "
                        <div class='alert alert-danger'>
                            Incorrect username or password.
                        </div>
                    ";
            }

        }
        ?>
        <div class="form-group" style="padding-top: 50px;">
            <div class="col-lg-offset-2 col-lg-8">
                <input name="username" type="text" placeholder="Username" class="form-control" required />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8">
                <input name="password" type="password" placeholder="Password" class="form-control" required />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8">
                <a href="emailpassword.php">Forgot Password</a>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-5 col-lg-4">
                <button name="login" type="submit" class="btn btn-success">
                    Login
                </button>
            </div>
        </div>
    </div>
</form>
<?php
include_once('includes/footer.php');
?>