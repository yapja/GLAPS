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
</head>
<body>
    <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a id="home" href="<?php echo app_path ?>" class="navbar-brand"><img src="<?php echo app_path ?>images/glb.png" style="height: 30px; width: 300px;"></a>
                <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse" id="navbar-main" style="height: 1px;">
                <ul class="nav navbar-nav pull-right">
                    <!--<li class="dropdown">
                        <a href="<?php echo app_path ?>admin/register.php">Register</a>
                    </li>-->
                   <!-- <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" id="users" href="#">Admin<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo app_path ?>admin/users">View Users</a></li>
                            <li><a href="<?php echo app_path ?>admin/users/add.php">Add a User</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo app_path ?>admin/users/logs.php">View System Logs</a></li>
                        </ul>
                    </li>-->
                </ul>
                <ul class="nav navbar-nav pull-right" <?php toggleUser(); ?>>
                    <li id="user" class="dropdown" visible="true">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <?php echo $userName . ' (' . $userType . ')'; ?><span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo app_path ?>admin/profile.php">View Profile</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo app_path ?>admin/logout.php">Logout</a></li>
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
