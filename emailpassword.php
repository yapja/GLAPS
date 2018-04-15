<?php
$page_title = "Forgot Password";
include_once('includes/header_admin.php');

if (isset($_POST['send']))
{
    $email = mysqli_real_escape_string($con, $_POST['email']);

    $sql_select = "SELECT account_ID FROM account WHERE email = '$email'";
    
    $result = $con->query($sql_select) or die(mysqli_error($con));

    $aid = -1;
    while ($row = mysqli_fetch_array($result))
    {
        $aid = $row['account_ID'];
    }

    if($aid != -1)
    {   
        $rand = substr(md5(microtime()),rand(0,26),10);
        
        $sql_select = "INSERT INTO request_password VALUES ('', '$aid', '$rand')";
        $result = $con->query($sql_select) or die(mysqli_error($con));
        
        $reset_link = "localhost/glaps/forgotpassword.php?request=" . $con->insert_id;
        $subject = "Reset GLAPS password";
        $message = "Reset password link : <a href='" . $reset_link . "'> " . $reset_link . "</a><br> Reset code : " . $rand;
        
        sendEmail($email, $subject, $message);
        
        header('location: checkemail.php');
    }
    else
    {
        echo "<div class='col-lg-6'>
                        <div class='alert alert-danger' style='text-align:center'>
                            Email does not exist.
                        </div>
				</div>
                    ";
    }

}
?>
<form method="POST" class="form-horizontal">
    <div class="col-lg-12">
        <div class="form-group">
            <label class="control-label col-lg-2">Email Address</label>
            <div class="col-lg-4">
                <input name="email" type="email" class="form-control" placeholder="Email Address" required />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-4">
                <button name="send" type="submit" class="btn btn-success">
                    Send Email
                </button>
            </div>
        </div>
    </div>
</form>

<?php
include_once('includes/footer.php');