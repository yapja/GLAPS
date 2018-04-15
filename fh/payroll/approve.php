<?php 
# checks if record is selected
if (isset($_REQUEST['srid']))
{
    # checks if selected record is an ID value
    if (ctype_digit($_REQUEST['srid']))
    {
        session_start();
        $salary_report_ID = $_REQUEST['srid'];
        require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');
        require($_SERVER['DOCUMENT_ROOT'] . '/glaps/function.php');
        validateAccess(3);

        $employee_detail_ID = $_SESSION['employee_detail_ID'];
        $sql_delete = "UPDATE salary_report SET status = 'Approved', approved_by = $employee_detail_ID WHERE salary_report_ID = $salary_report_ID"; 
        $result = $con->query($sql_delete) or die(mysqli_error($con));

        $sql_salary = "SELECT period_ID FROM salary_report WHERE salary_report_ID = $salary_report_ID";
        $result_salary = $con->query($sql_salary);
        while ($row = mysqli_fetch_array($result_salary))
        {
            $period_ID = $row['period_ID'];
        }

        
        $sql_select = "SELECT e.employee_detail_ID, e.first_name, e.last_name FROM employee e 
            INNER JOIN employee_detail ed ON e.employee_ID = ed.employee_ID
            INNER JOIN salary_report sr ON ed.employee_detail_ID = sr.employee_detail_ID
            WHERE salary_report_ID = $salary_report_ID";
        $result = $con->query($sql_select) or die(mysqli_error($con));
        while ($row = mysqli_fetch_array($result))
        {
            $name = $row['last_name'] . ", " . $row['first_name'];
            $employee_detail_ID2 = $row['employee_detail_ID'];
        }

        $sql_discrepancy = "SELECT salary_report_discrepancy_ID FROM salary_report_discrepancy WHERE employee_detail_ID = $employee_detail_ID2 AND status = 'Pending' AND DATEDIFF(DATE(NOW()), date_filed) <= 5 AND DATEDIFF(DATE(NOW()), date_filed) >= 0";
        $result_discrepancy = $con->query($sql_discrepancy);

        while ($row = mysqli_fetch_array($result_discrepancy))
        {
            $salary_report_discrepancy_ID = $row['salary_report_discrepancy_ID'];

            $sql_discrepancy_update = "UPDATE salary_report_discrepancy SET status = 'Approved' WHERE salary_report_discrepancy_ID = $salary_report_discrepancy_ID"; 
            $con->query($sql_discrepancy_update) or die(mysqli_error($con));
        }
        
        $account_ID = $_SESSION['account_ID'];
        $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $account_ID, 'Approved $name payslip')";
        $con->query($sql_log) or die(mysqli_error($con));

        header('location: index.php?pid=' . $period_ID);
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