<?php 
    ob_start();
    session_start();

    require($_SERVER['DOCUMENT_ROOT'] . '/glaps/config.php');
    require($_SERVER['DOCUMENT_ROOT'] . '/glaps/fpdf/fpdf.php');

    $pdf =new FPDF();
    $pdf->AddPage();

    $account_ID = $_SESSION['account_ID'];

    $sql_user = "SELECT u.account_ID, a.username, u.session_start, u.session_end FROM user_log u
    INNER JOIN account a ON u.account_ID = a.account_ID ORDER BY user_log_ID DESC";
    $result_user = $con->query($sql_user);

    $pdf->SetFont('Arial', 'B', 20);
	$pdf->Cell(100, 15, 'USER AUDIT LOGS',0,1);
	$pdf->SetFont('Arial','',9);

	$pdf->Cell(50, 5, 'USERNAME',0,0);
	$pdf->Cell(50, 5, 'SESSION IN',0,0);
	$pdf->Cell(50, 5, 'SESSION OUT',0,1);

    while ($data = mysqli_fetch_array($result_user))
	{
		$username = $data['username'];
        $session_start = $data['session_start'];
        $session_end = $data['session_end'];

		$pdf->Cell(50, 10, $username,1,0);
		$pdf->Cell(50, 10, $session_start,1,0);
		$pdf->Cell(50, 10, $session_end,1,1);
		
	}

	$pdf->Output();
?>