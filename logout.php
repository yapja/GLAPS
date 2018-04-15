<?php
require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');


session_start();
$account_ID = $_SESSION['account_ID'];

$sql_log = "UPDATE user_log SET session_end = NOW() WHERE account_ID = $account_ID ORDER BY user_log_ID DESC LIMIT 1";
$con->query($sql_log) or die(mysqli_error($con));
                    
session_destroy();

header('location: index.php');
?> 