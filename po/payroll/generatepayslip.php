<?php 
# checks if record is selected
if (isset($_REQUEST['eid']) && isset($_REQUEST['pid']))
{
    # checks if selected record is an ID value
    if (ctype_digit($_REQUEST['eid']) && ctype_digit($_REQUEST['pid']))
    {
        require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');
        require($_SERVER['DOCUMENT_ROOT'] . '/glaps/function.php');
        # validateAccess(3);

        $employee_detail_ID = $_REQUEST['eid'];
        $period_ID = $_REQUEST['pid'];

        $sql_period = "SELECT date_start, date_end, cutoff, previous_cutoff FROM period WHERE period_ID = $period_ID";
        $result_period = $con->query($sql_period);
        while ($row = mysqli_fetch_array($result_period))
        {
            $date_start = $row['date_start'];
            $date_end = $row['date_end'];
            $cutoff = $row['cutoff'];
            $previous_cutoff = $row['previous_cutoff'];
            $count_sunday = 0;
            $start = strtotime($previous_cutoff);
            $current_date = $row['date_end'];
            $current_end = $row['cutoff'];
            while ($start <= strtotime($cutoff))
            {
                if(date("N", $start) == 7)
                {
                    $count_sunday++;
                }
                $start += 86400;
            }
            $working_days = (((strtotime($cutoff) - strtotime($previous_cutoff))) / 86400) - $count_sunday - 1;
        }


        $sql_employee = "SELECT basic_salary, allowance_ID FROM employee_salary WHERE employee_detail_ID = $employee_detail_ID";
        $result_employee = $con->query($sql_employee);
        while ($row = mysqli_fetch_array($result_employee))
        {
            $basic_salary = $row['basic_salary'];
            $allowance_ID = $row['allowance_ID'];
            $basicsalary = $basic_salary/2;
            $daily_rate = ($basic_salary / 2) / $working_days;
            $hourly_rate = $daily_rate / 8;
        }

        $sql_allowance = "SELECT transportation, food, gas FROM allowance WHERE allowance_ID = $allowance_ID";
        $result_allowance = $con->query($sql_allowance);
        while ($row = mysqli_fetch_array($result_allowance))
        {
            $transportation = $row['transportation'];
            $food = $row['food'];
            $gas = $row['gas'];
        }

        $sql_overtime = "SELECT SUM(TIMESTAMPDIFF(HOUR, date_start, date_end)) AS overtime FROM employee_overtime WHERE employee_detail_ID = $employee_detail_ID AND status = 'Approved' AND date_start > '$previous_cutoff' AND date_start <= '$cutoff' AND overtime_type_ID = 1";
        $result_overtime = $con->query($sql_overtime);
        while ($row = mysqli_fetch_array($result_overtime))
        {
            $overtime = $row['overtime'];
            $legal_overtime = (($hourly_rate * 2) * 1.25) * $overtime;
        }

        $sql_overtime = "SELECT SUM(TIMESTAMPDIFF(HOUR, date_start, date_end)) AS overtime FROM employee_overtime WHERE employee_detail_ID = $employee_detail_ID AND status = 'Approved' AND date_start > '$previous_cutoff' AND date_start <= '$cutoff' AND overtime_type_ID = 2";
        $result_overtime = $con->query($sql_overtime);
        while ($row = mysqli_fetch_array($result_overtime))
        {
            $overtime = $row['overtime'];
            if ($overtime > 8)
            {
                $sunday_overtime = (($hourly_rate * 2) * 1.3) * 8;
                $sunday_excess = (($hourly_rate * 2) * 1.6 ) * ($overtime - 8);
            }
            else
            {
                $sunday_overtime = (($hourly_rate * 2) * 1.3) * $overtime;
                $sunday_excess = 0;
            }
        }

        $sql_overtime = "SELECT SUM(TIMESTAMPDIFF(HOUR, date_start, date_end)) AS overtime FROM employee_overtime WHERE employee_detail_ID = $employee_detail_ID AND status = 'Approved' AND date_start > '$previous_cutoff' AND date_start <= '$cutoff' AND overtime_type_ID = 3";
        $result_overtime = $con->query($sql_overtime);
        while ($row = mysqli_fetch_array($result_overtime))
        {
            $overtime = $row['overtime'];
            if ($overtime > 8)
            {
                $special_overtime = (($hourly_rate * 2) * 1.3) * 8;
                $special_excess = (($hourly_rate * 2) * 1.6 ) * ($overtime - 8);
            }
            else
            {
                $special_overtime = (($hourly_rate * 2) * 1.3) * $overtime;
                $special_excess = 0;
            }
        }

        $sql_overtime = "SELECT SUM(TIMESTAMPDIFF(HOUR, date_start, date_end)) AS overtime FROM employee_overtime WHERE employee_detail_ID = $employee_detail_ID AND status = 'Approved' AND date_start > '$previous_cutoff' AND date_start <= '$cutoff' AND overtime_type_ID = 4";
        $result_overtime = $con->query($sql_overtime);
        while ($row = mysqli_fetch_array($result_overtime))
        {
            $overtime = $row['overtime'];
            if ($overtime > 8)
            {
                $regular_holiday = (($hourly_rate * 2) * 2 ) * 8;
                $regular_holiday_excess = (($hourly_rate * 2) * 2.6 ) * ($overtime - 8);
            }
            else
            {
                $regular_holiday = (($hourly_rate * 2) * 2) * $overtime;
                $regular_holiday_excess = 0;
            }
        }


        $sql_leave = "SELECT DATE(elt.date_start) AS date_start, lt.type FROM employee_leave_taken elt INNER JOIN leave_type lt ON elt.leave_type_ID = lt.leave_type_ID WHERE elt.employee_detail_ID = $employee_detail_ID AND elt.date_start < '$cutoff' AND elt.date_end > '$cutoff' AND elt.status = 'Approved'";
        $result_leave = $con->query($sql_leave);


        $sql_late = "SELECT SUM(TIMESTAMPDIFF(MINUTE, time_in, time_out)) AS late FROM attendance WHERE employee_detail_ID = $employee_detail_ID AND STATUS = 'Late' AND date > '$previous_cutoff' AND date <= '$cutoff'";
        $result_late = $con->query($sql_late);
        while ($row = mysqli_fetch_array($result_late))
        {
            $late = $row['late'];
        }

        $sql_undertime = "SELECT SUM(TIMESTAMPDIFF(MINUTE, time_out, '18:00:00')) AS undertime FROM attendance WHERE employee_detail_ID = $employee_detail_ID AND date > '$previous_cutoff' AND date <= '$cutoff'";
        $result_undertime = $con->query($sql_undertime);
        while ($row = mysqli_fetch_array($result_undertime))
        {
            $undertime = $row['undertime'];
            $tardiness = $daily_rate * (($late + $undertime) / 480);
        }

        $sql_present = "SELECT COUNT(*) AS total_present FROM attendance WHERE employee_detail_ID = $employee_detail_ID AND DATE(time_in) >= '$previous_cutoff' AND DATE(time_in) <= '$cutoff' AND status != 'Invalid'"; 
        $result_present = $con->query($sql_present);
        while ($row = mysqli_fetch_array($result_present))
        {
            $total_present = $row['total_present'];
        }
        $total_absent = $working_days - $total_present;
        $absence =  $daily_rate * $total_absent;

        $sql_count = "SELECT date_start, date_end, total_days FROM employee_leave_taken WHERE employee_detail_ID = $employee_detail_ID AND DATE(date_start) <= '$cutoff' AND DATE(date_end) >= '$cutoff' AND status = 'Approved'";
        $result_count = $con->query($sql_count);

        $leave_attendance = 0;
        while ($row = mysqli_fetch_array($result_count))
        {
            $total_days = $row['total_days'];
            $date_start = strtotime($row['date_start']);
            $date_end = strtotime($row['date_end']);
            if (((strtotime($cutoff) - $date_start) / 86400) >= $total_days)
            {
                $leave_attendance = $total_days;
            }
            else
                $leave_attendance = (strtotime($cutoff) - $date_start) / 86400;
        }

        $sql_count = "SELECT date_start, date_end, total_days FROM employee_leave_taken WHERE employee_detail_ID = $employee_detail_ID AND DATE(date_start) <= '$cutoff' AND DATE(date_end) >= '$cutoff' AND status = 'Approved'";
        $result_count = $con->query($sql_count);

        while ($row = mysqli_fetch_array($result_count))
        {
            $total_days = $row['total_days'];
            $date_start = strtotime($row['date_start']);
            $date_end = strtotime($row['date_end']);
            if (((strtotime($previous_cutoff) - $date_start) / 86400) < $total_days)
            {
                $leave_attendance = $leave_attendance + ($total_days - (((strtotime($previous_cutoff) - $date_start)) / 86400));
            }
        }

        $sql_count = "SELECT SUM(total_days) AS total_days FROM employee_leave_taken WHERE employee_detail_ID = $employee_detail_ID AND DATE(date_start) <= '$cutoff' AND DATE(date_end) >= '$cutoff' AND status = 'Approved'";
        $result_count = $con->query($sql_count);

        while ($row = mysqli_fetch_array($result_count))
        {
            $total_days = $row['total_days'];
            $leave_attendance = $leave_attendance + $total_days;
        }


        if ($basic_salary == 1000 && $basic_salary <= 1249.99)
            $SSS_deduction = 36.30;
        else if ($basic_salary <= 1749.99)
            $SSS_deduction = 54.50;
        else if ($basic_salary <= 2249.99)
            $SSS_deduction = 72.70;
        else if ($basic_salary <= 2749.99)
            $SSS_deduction = 90.80;
        else if ($basic_salary <= 3249.99)
            $SSS_deduction = 109.00;
        else if ($basic_salary <= 3749.99)
            $SSS_deduction = 127.20;
        else if ($basic_salary <= 4249.99)
            $SSS_deduction = 145.30;
        else if ($basic_salary <= 4749.99)
            $SSS_deduction = 163.50;
        else if ($basic_salary <= 5249.99)
            $SSS_deduction = 181.70;
        else if ($basic_salary <= 5749.99)
            $SSS_deduction = 199.80;
        else if ($basic_salary <= 6249.99)
            $SSS_deduction = 218.00;
        else if ($basic_salary <= 6749.99)
            $SSS_deduction = 236.20;
        else if ($basic_salary <= 7249.99)
            $SSS_deduction = 254.30;
        else if ($basic_salary <= 7749.99)
            $SSS_deduction = 272.50;
        else if ($basic_salary <= 8249.99)
            $SSS_deduction = 290.70;
        else if ($basic_salary <= 8749.99)
            $SSS_deduction = 308.80;
        else if ($basic_salary <= 9249.99)
            $SSS_deduction = 327.00;
        else if ($basic_salary <= 9749.99)
            $SSS_deduction = 345.20;
        else if ($basic_salary <= 10249.99)
            $SSS_deduction = 363.30;
        else if ($basic_salary <= 10749.99)
            $SSS_deduction = 381.50;
        else if ($basic_salary <= 11249.99)
            $SSS_deduction = 399.70;
        else if ($basic_salary <= 11749.99)
            $SSS_deduction = 417.80;
        else if ($basic_salary <= 12249.99)
            $SSS_deduction = 436.00;
        else if ($basic_salary <= 12749.99)
            $SSS_deduction = 454.20;
        else if ($basic_salary <= 13249.99)
            $SSS_deduction = 472.30;
        else if ($basic_salary <= 13749.99)
            $SSS_deduction = 490.50;
        else if ($basic_salary <= 14249.99)
            $SSS_deduction = 508.70;
        else if ($basic_salary <= 14749.99)
            $SSS_deduction = 526.80;
        else if ($basic_salary <= 15249.99)
            $SSS_deduction = 545.00;
        else if ($basic_salary <= 15749.99)
            $SSS_deduction = 563.20;
        else if ($basic_salary >= 15750)
            $SSS_deduction = 581.30;

        $sql_additional_SSS = "SELECT amount FROM additional_contribution WHERE employee_detail_ID = $employee_detail_ID AND contribution_type = 0 AND status = 'Approved' AND period_end >= '$current_date' AND date_filed < '$current_date'";
        $result_additional_SSS = $con->query($sql_additional_SSS);

        while ($row = mysqli_fetch_array($result_additional_SSS))
        {
            $SSS_deduction += $row['amount'];
        }

        if ($basic_salary <= 8999.99)
            $PhilHealth_deduction = 100;
        else if ($basic_salary <= 9999.99)
            $PhilHealth_deduction = 112.50;
        else if ($basic_salary <= 10999.99)
            $PhilHealth_deduction = 125.00;
        else if ($basic_salary <= 11999.99)
            $PhilHealth_deduction = 137.50;
        else if ($basic_salary <= 12999.99)
            $PhilHealth_deduction = 150.00;
        else if ($basic_salary <= 13999.99)
            $PhilHealth_deduction = 162.50;
        else if ($basic_salary <= 14999.99)
            $PhilHealth_deduction = 175.00;
        else if ($basic_salary <= 15999.99)
            $PhilHealth_deduction = 187.50;
        else if ($basic_salary <= 16999.99)
            $PhilHealth_deduction = 200.00;
        else if ($basic_salary <= 17999.99)
            $PhilHealth_deduction = 212.50;
        else if ($basic_salary <= 18999.99)
            $PhilHealth_deduction = 225.00;
        else if ($basic_salary <= 19999.99)
            $PhilHealth_deduction = 237.50;
        else if ($basic_salary <= 20999.99)
            $PhilHealth_deduction = 250.00;
        else if ($basic_salary <= 21999.99)
            $PhilHealth_deduction = 262.50;
        else if ($basic_salary <= 22999.99)
            $PhilHealth_deduction = 275.00;
        else if ($basic_salary <= 23999.99)
            $PhilHealth_deduction = 287.50;
        else if ($basic_salary <= 24999.99)
            $PhilHealth_deduction = 300.00;
        else if ($basic_salary <= 25999.99)
            $PhilHealth_deduction = 312.50;
        else if ($basic_salary <= 26999.99)
            $PhilHealth_deduction = 325.00;
        else if ($basic_salary <= 27999.99)
            $PhilHealth_deduction = 337.50;
        else if ($basic_salary <= 28999.99)
            $PhilHealth_deduction = 350.00;
        else if ($basic_salary <= 29999.99)
            $PhilHealth_deduction = 362.50;
        else if ($basic_salary <= 30999.99)
            $PhilHealth_deduction = 375.00;
        else if ($basic_salary <= 31999.99)
            $PhilHealth_deduction = 387.50;
        else if ($basic_salary <= 32999.99)
            $PhilHealth_deduction = 400.00;
        else if ($basic_salary <= 33999.99)
            $PhilHealth_deduction = 412.50;
        else if ($basic_salary <= 34999.99)
            $PhilHealth_deduction = 425.00;
        else
            $PhilHealth_deduction = 437.50;

        $HDMF_deduction = 100;
        $sql_additional_HDMF = "SELECT amount FROM additional_contribution WHERE employee_detail_ID = $employee_detail_ID AND contribution_type = 1 AND status = 'Approved' AND period_end >= '$current_date' AND date_filed < '$current_date'";
        $result_additional_HDMF = $con->query($sql_additional_HDMF);

        while ($row = mysqli_fetch_array($result_additional_HDMF))
        {
            $HDMF_deduction += $row['amount'];
        }

        $sql_additional_PhilHealth = "SELECT amount FROM additional_contribution WHERE employee_detail_ID = $employee_detail_ID AND contribution_type = 2 AND status = 'Approved' AND period_end >= '$current_date' AND date_filed < '$current_date'";
        $result_additional_PhilHealth = $con->query($sql_additional_PhilHealth);

        while ($row = mysqli_fetch_array($result_additional_PhilHealth))
        {
            $PhilHealth_deduction += $row['amount'];
        }
        
        $taxable_income = $basicsalary - $SSS - $HDMF - $PhilHealth - $late - $absent + $legal + $sunday + $sunday_excess + $special_day + $special_excess + $regular_excess + $regular_holiday;

        if ($taxable_income <= 10417)
            $withholding_tax_deduction = 0;
        else if ($taxable_income <= 16666.99)
            $withholding_tax_deduction = (0.20 * ($taxable_income - 10417));
        else if ($taxable_income <= 33332.99)
            $withholding_tax_deduction = (1250 + (0.25 * ($taxable_income - 16667)));
        else if ($taxable_income <= 83332.99)
            $withholding_tax_deduction = (5416.67 + (0.30 * ($taxable_income - 33333)));
        else if ($taxable_income <= 333332.99)
            $withholding_tax_deduction = (20416.67 + (0.32 * ($taxable_income - 83333)));
        else
            $withholding_tax_deduction = (100416.67 + (0.35 * ($taxable_income - 333333)));



        $first_day = NULL;
        $sql_first = "SELECT DATE(MIN(time_in)) AS first_day FROM attendance WHERE employee_detail_ID = $employee_detail_ID AND status != 'Invalid'";
        $result_first = $con->query($sql_first);
        while ($row = mysqli_fetch_array($result_first))
        {
            $first_day = $row['first_day'];
        }

        if ($first_day != NULL)
        {
            $december = date_create(DATE('Y') . '-12-20');
            if (DATE('Y-m-d', strtotime($cutoff)) >= $december)
            {
                $first_day = date_create($first_day);
                $difference = date_diff($december, $first_day); 
                if ($difference->m > 1)
                {	
                    $sql_salary = "SELECT SUM(basic_salary) AS total_salary FROM salary_report sr WHERE sr.employee_detail_ID = $employee_detail_ID AND sr.status != 'Rejected' AND YEAR(NOW()) = (SELECT YEAR(date_start) FROM period WHERE period_ID = sr.period_ID)";
                    $result_salary = $con->query($sql_salary);
                    while ($row = mysqli_fetch_array($result_salary))
                    {
                        $total_salary = $row['total_salary'];
                    }
                    $bonus = $total_salary / 12;
                }
                else
                    $bonus = 0;
            }
            else
                $bonus = 0;
        }
        else
            $bonus = 0;

        if ($period_ID % 2 == 0)
        {
            //compute
        }
        else
        {
            $SSS_deduction = 0;
            $HDMF_deduction = 0;
            $PhilHealth_deduction = 0;
        }

        $net_pay = $basicsalary + $transportation + $gas + $food - $withholding_tax_deduction;

        $sql_discrepancy = "SELECT salary_report_discrepancy_ID, amount FROM salary_report_discrepancy WHERE employee_detail_ID = $employee_detail_ID AND status = 'Approved' AND DATEDIFF('$current_end', period_end) > 5 AND DATEDIFF('$current_end', period_end) <= 16";
        $result_discrepancy = $con->query($sql_discrepancy);
        while ($row = mysqli_fetch_array($result_discrepancy))
        {
            $amount = $row['amount'];
            $salary_report_discrepancy_ID = $row['salary_report_discrepancy_ID'];
            $net_pay += $amount;
        }



        $sql_payslip = "INSERT INTO salary_report SET employee_detail_ID = $employee_detail_ID, basic_salary = $basicsalary, transportation = $transportation, gas = $gas, food = $food, SSS = $SSS_deduction, HDMF = $HDMF_deduction, PhilHealth = $PhilHealth_deduction, late = $tardiness, absent = $absence, legal = $legal_overtime, sunday = $sunday_overtime, sunday_excess = $sunday_excess, special_day = $special_overtime, special_excess = $special_excess, regular_holiday = $regular_holiday, regular_excess = $regular_holiday_excess, bonus = $bonus, withholding_tax = $withholding_tax_deduction, net_pay = $net_pay, period_ID = $period_ID, date_issued = NOW(), date_received = NULL, approved_by = NULL, status = 'Pending'"; 

        $con->query($sql_payslip) or die(mysqli_error($con));

        $sql_select = "SELECT username FROM account WHERE employee_detail_ID = $employee_detail_ID";
        $result = $con->query($sql_select) or die(mysqli_error($con));
        while ($row = mysqli_fetch_array($result))
        {
            $username = $row['username'];
        }

        session_start();
        $aid = $_SESSION['account_ID'];
        $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $aid, 'Generated payslip of $username')";
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