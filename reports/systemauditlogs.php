<?php 
    ob_start();
    session_start();

    require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');
    require($_SERVER['DOCUMENT_ROOT'] . '/glaps/fpdf/fpdf.php');

    $pdf =new FPDF();
    $pdf->AddPage();

    $account_ID = $_SESSION['account_ID'];

    $sql_system = "SELECT s.account_ID, a.username, s.action, s.timestamp FROM system_log s
    INNER JOIN account a ON s.account_ID = a.account_ID ORDER BY system_log_ID DESC";
    $result_system = $con->query($sql_system);

    $pdf->SetFont('Arial', 'B', 20);
	$pdf->Cell(100, 15, 'SYSTEM AUDIT LOGS',0,1);
	$pdf->SetFont('Arial','',9);

	$pdf->Cell(50, 5, 'USERNAME',0,0);
	$pdf->Cell(50, 5, 'TIME STAMP',0,0);
	$pdf->Cell(50, 5, 'ACTION',0,1);

    while ($data = mysqli_fetch_array($result_system))
	{
		$username = $data['username'];
        $timestamp = $data['timestamp'];
        $action = $data['action'];

		$pdf->Cell(50, 10, $username,1,0);
		$pdf->Cell(50, 10, $timestamp,1,0);
		$pdf->Cell(50, 10, $action,1,1);
		
	}

	$pdf->Output();
?>