<?php 
$page_title = "File Overtime";
include_once('../../includes/header_po.php');


# displays list of user types
$sql_types = "SELECT overtime_type_ID, type FROM overtime_type ORDER BY type";
$result_types = $con->query($sql_types);

$list_types = "";
while ($row = mysqli_fetch_array($result_types))
{
    $overtime_type_ID = $row['overtime_type_ID'];
    $overtime_type = $row['type'];
    $list_types .= "<option value='$overtime_type_ID'>$overtime_type</option>";
}

if (isset($_POST['file']))
{
    $overtime_type_ID = mysqli_real_escape_string($con, $_POST['type']);
    $date_start = mysqli_real_escape_string($con, $_POST['date_start']);
    $date_end = mysqli_real_escape_string($con, $_POST['date_end']);
    $reason = mysqli_real_escape_string($con, $_POST['reason']);
    $employee_detail_ID = $_SESSION['employee_detail_ID'];

    $sql_add = "INSERT INTO employee_overtime VALUES ('', $employee_detail_ID, $overtime_type_ID, '$date_start', '$date_end', '$reason', NOW(), NULL, NULL, 'Pending', NULL)";
    $con->query($sql_add) or die(mysqli_error($con));

    $sql_select = "SELECT type FROM overtime_type WHERE overtime_type_ID = $overtime_type_ID";
    $result = $con->query($sql_select) or die(mysqli_error($con));
    while ($row = mysqli_fetch_array($result))
    {
        $otype = $row['type'];
    }

    $account_ID = $_SESSION['account_ID'];
    $sql_log = "INSERT INTO system_log VALUES ('', NOW(), $account_ID, 'Filed $otype overtime')";
    $con->query($sql_log) or die(mysqli_error($con));
}
?>
<form method="POST" class="form-horizontal">
    <div class="col-lg-6">
        <div class="form-group">
            <label class="control-label col-lg-4">Overtime Type</label>
            <div class="col-lg-8">
                <select name="type" class="form-control" required>
                    <option value="">Select one...</option>
                    <?php echo $list_types; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Date Start</label>
            <div class="col-lg-8">
                <input name="date_start" type="datetime-local" class="form-control" required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Date End</label>
            <div class="col-lg-8">
                <input name="date_end" type="datetime-local" class="form-control " required />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4">Reason</label>
            <div class="col-lg-8">
                <textarea name="reason" style="width:100%;height:80px;" placeholder="Reason for filing overtime"></textarea>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-4 col-lg-8">
                <button name="file" type="submit" class="btn btn-success">
                    File
                </button>
            </div>
        </div>
    </div>
</form>

<?php
include_once('../../includes/footer.php');