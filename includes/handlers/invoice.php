<?php
require '../../config/config.php';
require '../../lib/fpdf/fpdf.php';
include("../classes/WorkOrder.php");

$wo = new WorkOrder($con);

$woId = '';
$row = '';
$display = '';
$date = '';

if (isset($_POST['id'])){
    $woId = $_POST['id'];
    $query = mysqli_query($con, "SELECT *
        FROM work_orders
        JOIN customers
        ON work_orders.cust_id=customers.cust_id
        WHERE wo_id='$woId'");
    $row = mysqli_fetch_array($query);

    $display = $row['company_name'];
    if ($display == ""){
        $display = $row['first_name']. ' ' . $row['last_name'];
    }


    $date = strtotime($row['service_date']);
	$date = date("m/d/Y", $date);
	$complete = strtotime($row['complete_date']);
	$complete = date("m/d/Y", $complete);

	$jobAdd = $row['job_address'];





	$pdf = new FPDF();

	$pdf->SetFillColor(220,220,220);

	$pdf->addPage();

	$pdf->SetFont('Arial','B', 25);
	$pdf->Image('../../assets/logos/orionelectric.png', 10,10, 80);
	$pdf->Cell(80, 20, '', 0, 0, 'C');
	$pdf->Cell(10, 20, '', 0, 0);
	$pdf->Cell(70, 20, 'ELECTRICAL', 0, 0);
	$pdf->Cell(29, 20, '', 0,1);

	$pdf->SetFont('Arial','B',18);

	$pdf->Cell(90, 8, '', 0, 0);
	$pdf->Cell(70, 8, 'Work Order / Invoice', 0, 0);
	$pdf->Cell(29, 8, '#'.sprintf("%05d",$woId),0,1,'R');

	$pdf->SetFont('Arial', '', 10);

	$pdf->Cell(90, 5, '84 Hubble Drive #100', 0, 0, 'C');
	$pdf->Cell(70, 5, '', 0, 0);
	$pdf->Cell(29, 5, '',0,1);

	$pdf->Cell(90, 5, 'Dardenne Prairie, MO 63368', 0, 0, 'C');
	$pdf->Cell(50, 5, 'DATE OF ORDER:', 0, 0);
	$pdf->Cell(49, 5, $date,0,1);

	$pdf->Cell(90, 5, '(636) 445-3010', 0, 0, 'C');
	$pdf->Cell(50, 5, 'DATE OF COMPLETION:', 0, 0);
	$pdf->Cell(49, 5, $complete,0,1);

	$pdf->Cell(90, 5, '',0,0);
	$pdf->Cell(50,5, 'JOB ADDRESS:',0,0);
	$pdf->Cell(49, 5, '', 0, 1);

	$pdf->Cell(90, 5, '',0,0);
	$pdf->Cell(99,5, $jobAdd,0,1);

	$pdf->SetFont('Arial','B',18);

	$pdf->Cell(100, 8, 'BILL TO:', 0, 1);

	$pdf->SetFont('Arial', '', 10);

	$pdf->Cell(90, 5, $display, 0,1,'C');
	$pdf->Cell(90, 5, $row['street'], 0,1,'C');
	$pdf->Cell(90, 5, $row['city'] . ', ' . $row['state'] . ' ' . sprintf("%05d", $row['zip']), 0,1,'C');

	$pdf->Cell(189, 10, '', 0, 1);

	$pdf->SetFont('Arial','B',18);

	$pdf->Cell(189, 8, 'DESCRIPTION OF WORK', 1, 1, 'C', true);

	$pdf->SetFont('Arial', '', 10);

	$pdf->Cell(20, 20, '', 0,0);
	$pdf->Cell(149, 20, $row['work_description'], 0, 0);
	$pdf->Cell(20, 20, '', 0,1);

	$pdf->SetFont('Arial','B',18);

	$pdf->Cell(189, 8, 'LABOR', 1, 1, 'C', true);

	$pdf->SetFont('Arial', 'B', 10);

	$pdf->Cell(119, 5, 'LABOR', 1, 0, 'C', true);
	$pdf->Cell(15, 5, 'HRS.', 1, 0, 'C',true);
	$pdf->Cell(20, 5, '@', 1, 0, 'C',true);
	$pdf->Cell(35, 5, 'AMOUNT', 1, 1, 'C',true);

	$pdf->SetFont('Arial', '', 10);

	$laborQ = mysqli_query($con, "SELECT * FROM work_order_lines WHERE wo_id='$woId' AND type='Labor' ORDER BY ol_id");

	if(mysqli_num_rows($laborQ) >0){

	    $labor = 0.00;

	    while($laborR = mysqli_fetch_array($laborQ)){
	        $lineQty = $laborR['quantity'];
	        $lineMat = $laborR['material'];
	        $lineCost = $laborR['cost'];
	        $totCost = 0.00;
	        $totCost = $lineCost * $lineQty;
	        $labor += $totCost;

	        $pdf->Cell(119, 5, $lineMat, 1, 0);
	        $pdf->Cell(15, 5, number_format($lineQty,2), 1, 0, 'R');
	        $pdf->Cell(20, 5, number_format($lineCost,2), 1, 0, 'R');
	        $pdf->Cell(4, 5, '$', 1, 0);
	        $pdf->Cell(31, 5, number_format($totCost,2), 1, 1, 'R');

	    }

	    $pdf->SetFont('Arial','B',10);

	    $pdf->Cell(154, 5, 'TOTAL LABOR', 0, 0, 'R');
	    $pdf->Cell(4, 5, '$', 1, 0);
	    $pdf->Cell(31, 5, number_format($labor,2), 1, 1, 'R');
	    
	} else {
	    $pdf->SetFont('Arial','B',10);

	    $pdf->Cell(154, 5, 'TOTAL LABOR', 0, 0, 'R');
	    $pdf->Cell(4, 5, '$', 1, 0);
	    $pdf->Cell(31, 5, number_format(0,2), 1, 1, 'R');
	}

	$pdf->Cell(189,5,'',0,1);

	$pdf->SetFont('Arial','B',18);

	$pdf->Cell(189, 8, 'MATERIALS', 1, 1, 'C',true);

	$pdf->SetFont('Arial', 'B', 10);

	$pdf->Cell(20, 5, 'QTY', 1, 0, 'C',true);
	$pdf->Cell(99, 5, 'MATERIALS', 1, 0, 'C',true);
	$pdf->Cell(35, 5, '@', 1, 0, 'C',true);
	$pdf->Cell(35, 5, 'AMOUNT', 1, 1, 'C',true);

	$pdf->SetFont('Arial', '', 10);

	$matsQ = mysqli_query($con, "SELECT * FROM work_order_lines WHERE wo_id='$woId' AND type='Material' ORDER BY ol_id");

	if(mysqli_num_rows($matsQ) >0){

	    $mats = 0.00;

	    while($matsR = mysqli_fetch_array($matsQ)){
	        $matQty = $matsR['quantity'];
	        $matMat = $matsR['material'];
	        $matCost = $matsR['cost'];
	        $totMatCost = 0.00;
	        $totMatCost = $matCost * $matQty;
	        $mats += $totMatCost;

	        $pdf->Cell(20, 5, number_format($matQty,2), 1, 0, 'R');
	        $pdf->Cell(99, 5, $matMat, 1, 0);
	        $pdf->Cell(35, 5, number_format($matCost,2), 1, 0, 'R');
	        $pdf->Cell(4, 5, '$', 1, 0);
	        $pdf->Cell(31, 5, number_format($totMatCost,2), 1, 1, 'R');
	    }

	    $pdf->SetFont('Arial','B',10);

	    $pdf->Cell(154, 5, 'TOTAL MATERIALS', 0, 0, 'R');
	    $pdf->Cell(4, 5, '$', 1, 0);
	    $pdf->Cell(31, 5, number_format($mats,2), 1, 1, 'R');

	} else {
	    $pdf->SetFont('Arial','B',10);

	    $pdf->Cell(154, 5, 'TOTAL MATERIALS', 0, 0, 'R');
	    $pdf->Cell(4, 5, '$', 1, 0);
	    $pdf->Cell(31, 5, number_format(0,2), 1, 1, 'R');  
	}

	$pdf->Cell(189,5,'',0,1);

	$pdf->SetFont('Arial', '', 10);

	$tl = $wo->getHoursCostTotal($woId);

	$pdf->Cell(119, 5, '', 0, 0);
	$pdf->Cell(35, 5, 'TOTAL LABOR', 0, 0, 'R');
	$pdf->Cell(4, 5, '$', 1, 0);
	$pdf->Cell(31, 5, number_format($tl,2), 1, 1, 'R');

	$tm = $wo->getMatsCostTotal($woId);

	$pdf->Cell(119, 5, '', 0, 0);
	$pdf->Cell(35, 5, 'TOTAL MATERIALS', 0, 0, 'R');
	$pdf->Cell(4, 5, '$', 1, 0);
	$pdf->Cell(31, 5, number_format($tm,2), 1, 1, 'R');

	$pdf->SetFont('Arial','B',14);

	$t = $tl + $tm;

	$pdf->Cell(119, 8, '', 0, 0);
	$pdf->Cell(35, 8, 'TOTAL', 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(4, 8, '$', 1, 0);
	$pdf->Cell(31, 8, number_format($t,2), 1, 1, 'R');

	$pdf->SetFont('Arial', '', 10);

	$pdf->Cell(40,5,'Work Ordered By', 0,0);
	$pdf->Cell(60,5,$display, 0,1);

	$pdf->Cell(189,5,'',0,1);

	$pdf->SetFont('Arial','B',10);

	$pdf->Cell(189,5,'I hereby acknowledge the satisfactory completion of the above described work.', 0, 1);

	$pdf->Cell(90, 20, $pdf->Image('../../assets/images/signatures/wo'.$woId.'.png', $pdf->getX(), $pdf->getY(), 100), 0, 0, 'C');
	$pdf->Cell(5, 0,'', 0, 0);
	$pdf->Cell(29, 20, date("m/d/Y"), 0,0, 'C');
	$pdf->Cell(65, 20, '', 0, 1);

	$pdf->Cell(90, 0,'', 1, 0);
	$pdf->Cell(5, 0,'', 0, 0);
	$pdf->Cell(29, 0,'', 1, 0);
	$pdf->Cell(65, 0, '', 0, 1);

	$pdf->Cell(90, 5,'Signature', 0, 0, 'C');
	$pdf->Cell(5, 0,'', 0, 0);
	$pdf->Cell(29, 5,'Date', 0, 0, 'C');
	$pdf->Cell(65, 5, '', 0, 1);

	//$pdf->Output();
	$pdf->Output('../../assets/pdfs/work_orders/wo'.$woId.'.pdf', 'F');
}

?>