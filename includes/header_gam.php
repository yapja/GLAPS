<?php
    ob_start(); # Quick fix to 'Warning: Cannot modify header information - headers already sent by...'
    
    # sets path of application folder
    $protocol  = empty($_SERVER['HTTPS']) ? 'http' : 'https';
    $port      = $_SERVER['SERVER_PORT'];
    $disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";
    $domain    = $_SERVER['SERVER_NAME'];

    define('app_path', "${protocol}://${domain}${disp_port}" . '/glaps/');

    require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');
    require($_SERVER['DOCUMENT_ROOT'] . '/glaps/function.php');

    session_start();
    validateAccess(5);
    if (isset($_SESSION['employee_ID']))
    {
        $employee_ID = $_SESSION['employee_ID'];
        $sql_display = "SELECT first_name, last_name FROM employee WHERE employee_ID = $employee_ID";
        $result_display = $con->query($sql_display) or die(mysqli_error($con));
        while ($row = mysqli_fetch_array($result_display))
        {
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];

            $name = $first_name . ' ' . $last_name;
        }
    }
?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo $page_title ?></title>
    <link href="<?php echo app_path ?>css/bootstrap.css" rel="stylesheet" />
    <link href="<?php echo app_path ?>css/custom.css" rel="stylesheet" />
    <link href="<?php echo app_path ?>css/font-awesome.min.css" rel="stylesheet" />
    <link href="<?php echo app_path ?>css/jasny-bootstrap.min.css" rel="stylesheet" />
    <link href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script type="text/javascript" src='<?php echo app_path ?>js/jquery-3.2.0.min.js'></script>
    <script type="text/javascript" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src='<?php echo app_path ?>ckeditor/ckeditor.js'></script>
    <script type="text/javascript" src='<?php echo app_path ?>js/moment.min.js'></script>
    <script type="text/javascript" src='<?php echo app_path ?>js/datetime-moment.js'></script>
</head>
<body>
    <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a id="home" href="<?php echo app_path ?>gam" class="navbar-brand"><img src="<?php echo app_path ?>images/glb.png" style="height: 30px; width: 300px;"></a>
                <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse" id="navbar-main" style="height: 1px;">
                <ul class="nav navbar-nav pull-left">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo app_path ?>">Attendance
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo app_path ?>gam/webcam/webcam.php">Take a Photo</a></li>
                            <li><a href="<?php echo app_path ?>gam/attendance/file.php">File Attendance</a></li>
                            <li><a href="<?php echo app_path ?>gam/attendance/index.php">View MyAttendance</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo app_path ?>gam/attendance/pending.php">Pending Attendance</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="<?php echo app_path ?>gam/employees/index.php">Employees</a>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo app_path ?>">Leave
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo app_path ?>gam/leave/pending.php">Pending Leave</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo app_path ?>gam/leave/index.php">View my Leave</a></li>
                            <li><a href="<?php echo app_path ?>gam/leave/file.php">File a Leave</a></li>
                            <li><a href="<?php echo app_path ?>gam/leave/leavecount.php">Leave Count</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo app_path ?>">Overtime
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo app_path ?>gam/overtime/pending.php">Pending Overtime</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo app_path ?>gam/overtime/index.php">View my Overtime</a></li>
                            <li><a href="<?php echo app_path ?>gam/overtime/file.php">File an Overtime</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="<?php echo app_path ?>gam/dashboard.php">Dashboard</a>
                    </li>
                    <li class="dropdown">
                        <a href="<?php echo app_path ?>gam/timesheet/index.php">Timesheet</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" id="users" href="#"><?php echo $name; ?><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo app_path ?>gam/payroll/mypayslip.php">View My Payslip</a></li>
                            <li><a href="<?php echo app_path ?>gam/contribution/index.php">View My Contributions</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo app_path ?>gam/resetpassword.php">Reset Password</a></li>
                            <li><a href="<?php echo app_path ?>logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="clearfix">
            <div class="page-header">
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo $page_title; ?></h1>
                    </div>
                </div>
            </div>
            <div class="row">
