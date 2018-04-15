<?php 
# checks if record is selected
if (isset($_REQUEST['id']))
{
    # checks if selected record is an ID value
    if (ctype_digit($_REQUEST['id']))
    {
        $id = $_REQUEST['id'];

        $page_title = "Update Employee Details";
        include_once('../../includes/header_hr.php');

        # display existing record
        $sql_employee = "SELECT e.first_name, e.first_name, e.middle_name, e.last_name, e.birth_date, e.gender, e.civil_status, e.bank_number,
				a.address1, a.address2, a.city, a.zip_code, c.landline, c.mobile, 
				ed.employee_detail_ID, ed.assigned_ID, ed.dependent, ed.SSS, ed.TIN, ed.PhilHealth, ed.HDMF, ed.profile_picture, ed.date_hired, ed.supervisor_ID, ed.status, d.department, p.title, es.basic_salary, al.transportation, al.gas, al.food
				FROM employee e
				INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
				INNER JOIN address a ON e.address_ID = a.address_ID
				INNER JOIN department d ON ed.department_ID = d.department_ID
				INNER JOIN contact c ON e.employee_ID = c.employee_ID
				INNER JOIN position p ON ed.position_ID = p.position_ID
				INNER JOIN allowance al ON ed.employee_detail_ID = al.employee_detail_ID
				INNER JOIN employee_salary es ON ed.employee_detail_ID = es.employee_detail_ID
				WHERE e.employee_ID = $id";
        $result_employee = $con->query($sql_employee);

        # checks if record is not existing
        if (mysqli_num_rows($result_employee) == 0)
        {
            header('location: index.php');
        }

        while ($row = mysqli_fetch_array($result_employee))
        {
            $first_name = $row['first_name'];
            $middle_name = $row['middle_name'];
            $last_name = $row['last_name'];
            $birth_date = $row['birth_date'];
            $gender = $row['gender'];
            $civil_status = $row['civil_status'];
            $bank_number = $row['bank_number'];
            $address1 = $row['address1'];
            $address2 = $row['address2'];
            $city = $row['city'];
            $zip_code = $row['zip_code'];
            $landline = $row['landline'];
            $mobile = $row['mobile'];
            $employee_detail_ID = $row['employee_detail_ID'];
            $assigned_ID = $row['assigned_ID'];
            $dependent = $row['dependent'];
            $SSS = $row['SSS'];
            $TIN = $row['TIN'];
            $PhilHealth = $row['PhilHealth'];
            $HDMF = $row['HDMF'];
            $profile_picture = $row['profile_picture'];
            $date_hired = $row['date_hired'];
            $supervisor_ID = $row['supervisor_ID'];
            $status = $row['status'];
            $department = $row['department'];
            $title = $row['title'];
            $basic_salary = $row['basic_salary'];
            $food = $row['food'];
            $gas = $row['gas'];
            $transportation = $row['transportation'];
        }

        # displays list of departments
        $sql_department = "SELECT department_ID, department FROM department ORDER BY department";
        $result_department = $con->query($sql_department);

        $list_departments = "";
        while ($row = mysqli_fetch_array($result_department))
        {
            $department_ID = $row['department_ID'];
            $department2 = $row['department'];
            if ($department == $department2)
                $list_departments .= "<option value=$department_ID selected>$department2</option>";
            else
                $list_departments .= "<option value=$department_ID>$department2</option>";
        }

        # displays list of position
        $sql_position = "SELECT position_ID, title FROM position ORDER BY title";
        $result_position = $con->query($sql_position);

        $list_positions = "";
        while ($row = mysqli_fetch_array($result_position))
        {
            $position_ID = $row['position_ID'];
            $title2 = $row['title'];
            if ($title == $title2)
                $list_positions .= "<option value=$position_ID selected>$title2</option>";
            else
                $list_positions .= "<option value=$position_ID>$title2</option>";
        }

        # displays list of supervisors
        $sql_supervisor = "SELECT e.employee_ID, e.first_name, e.gender, e.last_name
				FROM employee e
				INNER JOIN employee_detail ed ON e.employee_detail_ID = ed.employee_detail_ID
				INNER JOIN position  p ON ed.position_ID = p.position_ID
				WHERE p.title = 'Supervisor' ORDER BY e.last_name";
        $result_supervisor = $con->query($sql_supervisor);

        $list_supervisors = "";
        while ($row = mysqli_fetch_array($result_supervisor))
        {
            $supervisor_ID2 = $row['employee_ID'];
            if ($row['gender'] == 'M')
                $name = 'Mr. ' . $row['first_name'] . ' ' . $row['last_name'];
            else if ($row['gender']  == 'F')
                $name = 'Ms. ' . $row['first_name'] . ' ' . $row['last_name'];

            if ($supervisor_ID == $supervisor_ID2)
                $list_supervisors .= "<option value=$supervisor_ID2 selected>$name</option>";
            else
                $list_supervisors .= "<option value=$supervisor_ID2>$name</option>";
        }

        # updates existing record
        if (isset($_POST['update']))
        {
            $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
            $middle_name = mysqli_real_escape_string($con, $_POST['middle_name']);
            $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
            $birth_date = mysqli_real_escape_string($con, $_POST['birth_date']);
            $gender = mysqli_real_escape_string($con, $_POST['gender']);
            $civil_status = mysqli_real_escape_string($con, $_POST['civil_status']);
            $bank_number = mysqli_real_escape_string($con, $_POST['bank_number']);
            $address1 = mysqli_real_escape_string($con, $_POST['address1']);
            $address2 = mysqli_real_escape_string($con, $_POST['address2']);
            $city = mysqli_real_escape_string($con, $_POST['city']);
            $zip_code = mysqli_real_escape_string($con, $_POST['zip_code']);
            $landline = mysqli_real_escape_string($con, $_POST['landline']);
            $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
            $assigned_ID = mysqli_real_escape_string($con, $_POST['assigned_ID']);
            $dependent = mysqli_real_escape_string($con, $_POST['dependent']);
            $SSS = mysqli_real_escape_string($con, $_POST['SSS']);
            $TIN = mysqli_real_escape_string($con, $_POST['TIN']);
            $PhilHealth = mysqli_real_escape_string($con, $_POST['philhealth']);
            $HDMF = mysqli_real_escape_string($con, $_POST['HDMF']);
            $date_hired = mysqli_real_escape_string($con, $_POST['date_hired']);
            $supervisor_ID = mysqli_real_escape_string($con, $_POST['supervisor']);
            $status = mysqli_real_escape_string($con, $_POST['status']);
            $department_ID = mysqli_real_escape_string($con, $_POST['department']);
            $position_ID = mysqli_real_escape_string($con, $_POST['position']);
            $profile_picture = mysqli_real_escape_string($con, $_POST['image']);
            $basic_salary = mysqli_real_escape_string($con, $_POST['basic_salary']);
            $transportation = mysqli_real_escape_string($con, $_POST['transportation']);
            $food = mysqli_real_escape_string($con, $_POST['food']);
            $gas = mysqli_real_escape_string($con, $_POST['gas']);

            $sql_employee = "UPDATE employee SET first_name = '$first_name', middle_name = '$middle_name', last_name = '$last_name', birth_date = '$birth_date', gender = '$gender', bank_number = '$bank_number' WHERE employee_ID=$id";
            $result = $con->query($sql_employee) or die(mysqli_error($con));

            $sql_address = "UPDATE address SET address1 = '$address1', address2 = '$address2', city = '$city', zip_code = '$zip_code' WHERE employee_ID = $id";
            $con->query($sql_address) or die(mysqli_error($con));

            $sql_contact = "UPDATE contact SET landline = '$landline', mobile = '$mobile' WHERE employee_ID = $id";
            $con->query($sql_contact) or die(mysqli_error($con));

            if ($profile_picture = '')
                $sql_detail = "UPDATE employee_detail SET assigned_ID = $assigned_ID, position_ID = '$position_ID', department_ID = '$department_ID', supervisor_ID = '$supervisor_ID', dependent = '$dependent', SSS = '$SSS', TIN = '$TIN', PhilHealth = '$PhilHealth', HDMF = '$HDMF', date_hired = '$date_hired', status = '$status' WHERE employee_detail_ID = $employee_detail_ID";
            else
            {
                $upload = "../../images/profile_picture/"; # location where to upload the image
                $image = $_FILES["image"]["name"]; # gets the file from file upload
                $newImage = date('YmdHis-') . basename($image); # eg. 20170322051234-sample.jpg
                $file = $upload . $newImage;
                move_uploaded_file($_FILES["image"]["tmp_name"], $file);

                $sql_detail = "UPDATE employee_detail SET assigned_ID = $assigned_ID, position_ID = '$position_ID', department_ID = '$department_ID', supervisor_ID = '$supervisor_ID', dependent = '$dependent', SSS = '$SSS', TIN = '$TIN', PhilHealth = '$PhilHealth', HDMF = '$HDMF', profile_picture = '$newImage', date_hired = '$date_hired', date_updated = NOW(), status = '$status' WHERE employee_detail_ID = $employee_detail_ID";


            }
            $result = $con->query($sql_detail) or die(mysqli_error($con));

            $sql_salary = "UPDATE employee_salary SET basic_salary = $basic_salary WHERE employee_detail_ID = $employee_detail_ID";
            $con->query($sql_salary) or die(mysqli_error($con));

            $sql_allowance = "UPDATE allowance SET transportation = $transportation, gas = $gas, food = $food WHERE employee_detail_ID = $employee_detail_ID";
            $con->query($sql_allowance) or die(mysqli_error($con));

            $sql_account = "UPDATE account SET status = 'Active' WHERE employee_detail_ID = $employee_detail_ID";
            $con->query($sql_account) or die(mysqli_error($con));

            $sql_select = "SELECT username FROM account WHERE employee_detail_ID = $employee_detail_ID";
            $result = $con->query($sql_select) or die(mysqli_error($con));
            while ($row = mysqli_fetch_array($result))
            {
                $username = $row['username'];
            }

            $account_ID = $_SESSION['account_ID'];
            $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $account_ID, 'Updated $username details')";
            $con->query($sql_log) or die(mysqli_error($con));

            header('location: index.php');
        }
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

<form method="POST" class="form-horizontal" enctype="multipart/form-data">
    <div class="col-lg-6">
        <div class="form-group">
            <label class="control-label col-lg-4">First Name</label>
            <div class="col-lg-8">
                <input name="first_name" type="text" class="form-control" value="<?php echo $first_name ?>" required />
            </div>
        </div><div class="form-group">
        <label class="control-label col-lg-4">Middle Name</label>
        <div class="col-lg-8">
            <input name="middle_name" type="text" class="form-control" value="<?php echo $middle_name ?>"  required />
        </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Last Name</label>
            <div class="col-lg-8">
                <input name="last_name" type="text" class="form-control" value="<?php echo $last_name ?>"  required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Assigned ID</label>
            <div class="col-lg-8">
                <input name="assigned_ID" type="text" class="form-control" value="<?php echo $assigned_ID ?>"  required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Birthdate</label>
            <div class="col-lg-8">
                <input name="birth_date" type="date" class="form-control" value="<?php echo $birth_date ?>"  required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Gender</label>
            <div class="col-lg-8">
                <select name="gender" class="form-control" required>
                    <option <?php if ($gender == "M") echo 'selected' ; ?>>Male</option>
                    <option <?php if ($gender == "F") echo 'selected' ; ?>>Female</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Position</label>
            <div class="col-lg-8">
                <select name="position" class="form-control" required>
                    <?php echo $list_positions; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Department</label>
            <div class="col-lg-8">
                <select name="department" class="form-control" required>
                    <?php echo $list_departments; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Supervisor</label>
            <div class="col-lg-8">
                <select name="supervisor" class="form-control">
                    <?php echo $list_supervisors; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Civil Status</label>
            <div class="col-lg-8">
                <select name="civil_status" class="form-control" required>
                    <option <?php if ($civil_status == "Single") echo 'selected' ; ?>>Single</option>
                    <option <?php if ($civil_status == "Married") echo 'selected' ; ?>>Married</option>
                    <option <?php if ($civil_status == "Widowed") echo 'selected' ; ?>>Widowed</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Address</label>
            <div class="col-lg-8">
                <textarea name="address1" style="width:100%;height:80px;"><?php echo $address1 ?></textarea required>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4"></label>
            <div class="col-lg-8">
                <textarea name="address2" style="width:100%;height:80px;"><?php echo $address2 ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">City</label>
            <div class="col-lg-8">
                <input name="city" type="text" class="form-control" value="<?php echo $city ?>"  required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Zip Code</label>
            <div class="col-lg-8">
                <input name="zip_code" type="number" class="form-control" value="<?php echo $zip_code ?>"  min="0" max="9999" required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Mobile Number</label>
            <div class="col-lg-8">
                <input name="mobile" type="text" class="form-control" value="<?php echo $mobile ?>"  required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Landline</label>
            <div class="col-lg-8">
                <input name="landline" type="text" class="form-control" value="<?php echo $landline ?>"  required />
            </div>
        </div>
    </div>

    <div class="col-lg-6">

        <div class="form-group">
            <label class="control-label col-lg-4">Profile Picture</label>
            <div class="col-lg-8">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                        <img src='<?php echo app_path; ?>/images/profile_picture/<?php echo $profile_picture ?>' alt="...">
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                    <div>
                        <span class="btn btn-default btn-file">
                            <span class="fileinput-new">Select image</span>
                            <span class="fileinput-exists">Change</span>
                            <input type="file" name="image">
                        </span>
                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Bank Number</label>
            <div class="col-lg-8">
                <input name="bank_number" type="text" class="form-control" value="<?php echo $bank_number ?>"  required />
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label class="control-label col-lg-4">Dependent</label>
            <div class="col-lg-8">
                <input name="dependent" type="text" class="form-control" value="<?php echo $dependent ?>"  required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">SSS</label>
            <div class="col-lg-8">
                <input name="SSS" type="text" class="form-control" value="<?php echo $SSS ?>"  required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">TIN</label>
            <div class="col-lg-8">
                <input name="TIN" type="text" class="form-control" value="<?php echo $TIN ?>"  required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">HDMF</label>
            <div class="col-lg-8">
                <input name="HDMF" type="text" class="form-control" value="<?php echo $HDMF ?>" required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">PhilHealth</label>
            <div class="col-lg-8">
                <input name="philhealth" type="text" class="form-control" value="<?php echo $PhilHealth ?>"  required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Status</label>
            <div class="col-lg-8">
                <select name="status" class="form-control" required>
                    <option <?php if ($status == "Active") echo 'selected' ; ?>>Active</option>
                    <option <?php if ($status == "Retired") echo 'selected' ; ?>>Retired</option>
                    <option <?php if ($status == "Suspended") echo 'selected' ; ?>>Suspended</option>
                    <option <?php if ($status == "Terminated") echo 'selected' ; ?>>Terminated</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Date Hired</label>
            <div class="col-lg-8">
                <input name="date_hired" type="date" class="form-control" value="<?php echo $date_hired ?>"  required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Basic Salary</label>
            <div class="col-lg-8">
                <input name="basic_salary" type="text" class="form-control" value="<?php echo $basic_salary ?>" required />
            </div>
        </div>
        <div class="form-group">
            <h4><label class="control-label col-lg-4">Allowance</label></h4>
            <div class="col-lg-8">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Transportation</label>
            <div class="col-lg-8">
                <input name="transportation" type="text" class="form-control" value="<?php echo $transportation ?>" required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Food</label>
            <div class="col-lg-8">
                <input name="food" type="text" class="form-control" value="<?php echo $food?>" required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Gas</label>
            <div class="col-lg-8">
                <input name="gas" type="text" class="form-control" value="<?php echo $gas ?>" required />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-4 col-lg-8">
                <button name="update" type="submit" class="btn btn-info">
                    Update
                </button>
            </div>
        </div>
    </div>
</form>

<?php
    include_once('../../includes/footer.php');