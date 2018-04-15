<?php
# displays total number of records from a chosen table
function countData($table)
{
    include 'config.php';
    $sql_count = "SELECT COUNT(*) AS total FROM $table
			WHERE status != 'Archived'";
    $result = $con->query($sql_count);

    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

# Displays the total number of pending accounts
function countPendingAccounts()
{
    include 'config.php';
    $sql_count = "SELECT COUNT(*) AS total FROM account WHERE status ='Pending'";
    $result = $con->query($sql_count);

    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}
# Displays the total number of pending accounts
function countPendingContributions()
{
    include 'config.php';
    $sql_count = "SELECT COUNT(*) AS total FROM additional_contribution WHERE status ='Pending'";
    $result = $con->query($sql_count);

    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}
# Displays the total number of active employee
function countActiveEmployees()
{
    include 'config.php';
    $sql_count = "SELECT COUNT(*) AS total FROM employee_detail WHERE status = 'Active'";
    $result = $con->query($sql_count);

    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

# Displays the total number of active accounts
function countActiveAccounts()
{
    include 'config.php';
    $sql_count = "SELECT COUNT(*) AS total FROM account WHERE status ='Active'";
    $result = $con->query($sql_count);

    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

# Displays the total number of pending leaves
function countLeavePending()
{
    include 'config.php';
    $sql_count = "SELECT COUNT(*) AS total FROM employee_leave_taken WHERE status ='Pending'";
    $result = $con->query($sql_count);

    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}
# Displays the total number of pending OT
function countOTPending()
{
    include 'config.php';
    $sql_count = "SELECT COUNT(*) AS total FROM employee_overtime WHERE status ='Pending'";
    $result = $con->query($sql_count);

    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}
function countAttendancePending()
{
    include 'config.php';
    $sql_count = "SELECT COUNT(*) AS total FROM attendance_flexible WHERE status ='Pending'";
    $result = $con->query($sql_count);

    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}
# hides elements if customer is not logged in
function toggleUser()
{
    if (!isset($_SESSION['userid']))
    {
        echo 'style="display:none;"';
    }
}

# hides elements if customer is logged in
function toggleGuest()
{
    if (isset($_SESSION['userid']))
    {
        echo 'style="display:none;"';
    }
}

# gets path of application folder
function getAppFolder()
{
    $protocol  = empty($_SERVER['HTTPS']) ? 'http' : 'https';
    $port      = $_SERVER['SERVER_PORT'];
    $disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";
    $domain    = $_SERVER['SERVER_NAME'];

    return "${protocol}://${domain}${disp_port}" . "/glaps/";
}

function validateAccess($position)
{
    if ($_SESSION['status'] != 'Active' || $position != $_SESSION['position_ID'])
    {
        $admin_login = getAppFolder() . 'index.php';
        $lastURL = $_SERVER['REQUEST_URI'];
        header('location: ' . $admin_login .'?url=' . $lastURL);
    }
}

# sends a message to a chosen email address
function sendEmail($email, $subject, $message)
{
    require('phpmailer/PHPMailerAutoload.php');
    $mail = new PHPMailer;

    if(!$mail->validateAddress($email))
    {
        echo 'Invalid Email Address';
        exit;
    }

    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "glapsthesis@gmail.com";
    $mail->Password = "agustinkids11";
    $mail->SetFrom("glapsthesis@gmail.com");
    $mail->FromName = "The Administrator";
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AddAddress($email);
    $mail->Send();
}
?>