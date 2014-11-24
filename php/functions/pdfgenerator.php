<?php
require_once('../fpdi/fpdf.php');
require_once('../fpdi/fpdi.php');
ini_set('display_errors', 'On');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Hello World!');

$filename="../pdf/test";
$pdf->Output($filename.'.pdf','F');

?>
