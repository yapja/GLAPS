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
    validateAccess(4);
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
    $sql_period = "SELECT period_ID, date_start, date_end FROM period WHERE date_start < NOW() AND date_end >= NOW()";
    $result_period = $con->query($sql_period);
    while ($row = mysqli_fetch_array($result_period))
    {
        $period_ID = $row['period_ID'];
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
                <a id="home" href="<?php echo app_path ?>po/timesheet/index.php" class="navbar-brand"><img src="<?php echo app_path ?>images/glb.png" style="height: 30px; width: 300px;"></a>
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
                            <li><a href="<?php echo app_path ?>po/webcam/webcam.php">Take a Photo</a></li>
                            <li><a href="<?php echo app_path ?>po/attendance/file.php">File Attendance</a></li>
                            <li><a href="<?php echo app_path ?>po/attendance/index.php">View MyAttendance</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo app_path ?>">Leave
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo app_path ?>po/leave/index.php">View my Leave</a></li>
                            <li><a href="<?php echo app_path ?>po/leave/file.php">File a Leave</a></li>
                            <li><a href="<?php echo app_path ?>po/leave/leavecount.php">Leave Count</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo app_path ?>">Overtime
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo app_path ?>po/overtime/index.php">View my Overtime</a></li>
                            <li><a href="<?php echo app_path ?>po/overtime/file.php">File an Overtime</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo app_path ?>">Payroll
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo app_path ?>po/payroll/index.php">Employee Payroll</a></li>
                            <li><a href="<?php echo app_path ?>po/payroll/deptpayslip.php">View Department Payroll Summary</a></li>
                            <li><a href="<?php echo app_path ?>po/gov/contributions.php">View Government Contributions</a></li>
                            <li><a href="<?php echo app_path ?>po/payroll/payslipsummary.php">Payroll Total</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="<?php echo app_path ?>po/timesheet/index.php">Timesheet</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" id="users" href="#"><?php echo $name; ?><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo app_path ?>po/payroll/mypayslip.php">View My Payslip</a></li>
                            <li><a href="<?php echo app_path ?>po/contribution/index.php">View My Contributions</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo app_path ?>po/resetpassword.php">Reset Password</a></li>
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
