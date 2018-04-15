<?php 
	$page_title = "Government Contributions";
    include_once('../../includes/header_po.php');

    $now = new DateTime('now');
    $month = $now->format('m');
    $year = $now->format('Y');

    $selected_month;
    if (isset($_GET['month']))
	{
		$selected_month = $_GET['month'];
	}
	else
	{
        $selected_month = $month;
	}

    $sql_periodID = "SELECT period_ID FROM period where MONTH(date_end) = " . $selected_month . " AND YEAR(date_end) = " . $year;
    $result_periodID = $con->query($sql_periodID);
    $ids = array();
    while ($row = mysqli_fetch_array($result_periodID)) 
    {
        $period_ID = $row['period_ID'];
        array_push($ids, $period_ID);
    }

    $periodIDs = "";
    if (count($ids) == 2) 
    {
        $periodIDs .= $ids[0] . ", " . $ids[1];
    }
    else 
    {
        $periodIDs .= "NULL";
    }

	$sql_contrib = "SELECT SUM(SSS) AS SSS, SUM(HDMF) AS HDMF, SUM(PhilHealth) AS PhilHealth FROM salary_report WHERE period_ID IN (" . $periodIDs . ") AND status = 'Approved'";
	$result_contrib = $con->query($sql_contrib);
	$row = mysqli_fetch_array($result_contrib);
    $sss = $row['SSS'];
    $hdmf = $row['HDMF'];
    $philhealth = $row['PhilHealth'];
    $total = $sss + $hdmf + $philhealth;

    $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $list_month = "";
    for ($i=0; $i<$month; $i++)
	{
		$list_month .= "<option value='" . ($i+1). "'";
        if (($i+1) == $selected_month) 
        {
            $list_month .= " selected='selected'";
        }
        $list_month .= ">" . $months[$i] . "</option>";
    }
    
    if (isset($_POST['next']))
	{
        $month = mysqli_real_escape_string($con, $_POST['month']);
        header('location: contributions.php?month=' . $month);
    }
?>

<form method="POST" class="form-horizontal">
    <div class="col-lg-6">
        <div class="form-group">
            <div class="col-lg-6">
                <select name='month' class='form-control' required>
                    <option value=''>Select one...</option>
                    <?php echo " . $list_month . "?>
                </select>
			</div>
            <div class="col-lg-6">
                <button name="next" type="submit" class="btn btn-success">
                    Choose
                </button>
            </div>
        </div>
    </div>
    
	<div class="col-lg-6">
        <div class="form-group">
			<label class="control-label col-lg-4">SSS</label>
			<div class="col-lg-4">
				<input name="name" type="text" class="form-control" value="<?= number_format($sss, 2, '.', ', ') ?>" disabled />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">PhilHealth</label>
			<div class="col-lg-4">
				<input name="name" type="text" class="form-control" value="<?= number_format($philhealth, 2, '.', ', ') ?>" disabled />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4">Pagibig</label>
			<div class="col-lg-4">
				<input name="name" type="text" class="form-control" value="<?= number_format($hdmf, 2, '.', ', ') ?>" disabled />
			</div>
		</div>
        <hr/>
		<div class="form-group">
			<label class="control-label col-lg-4">Total</label>
			<div class="col-lg-4">
				<input name="name" type="text" class="form-control" value="<?= number_format($total, 2, '.', ', ') ?>" disabled />
			</div>
		</div>
	</div>
</form>

<?php
	include_once('../../includes/footer.php');