<?php
require('fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();

// Set font for the headings
$pdf->SetFont('Arial', 'B', 16);

// First Heading
$pdf->Cell(0, 10, 'CENTER NO: ', 0, 1, 'C');

// Second Heading
$pdf->Cell(0, 10, 'S.A.S.S.N.D.P YOGAM COLLEGE', 0, 1, 'C');

// Third Heading
$pdf->Cell(0, 10, 'KONNI, PATHANAMTHITTA', 0, 1, 'C');

// Add a line break before the subheading
$pdf->Ln(10);

// Subheading
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Examination Details: ', 0, 1, 'C');

// Add a line break before the key-value pairs
$pdf->Ln(10);

// Set font for key-value pairs
$pdf->SetFont('Arial', '', 12);

// Key-Value Pair 1
$pdf->Cell(0, 10, 'Name of Examination  :  ', 0, 1,'C');

// Key-Value Pair 2
$pdf->Cell(0, 10, 'Date of Examination  : ', 0, 1,'C');

// Key-Value Pair 3
$pdf->Cell(0, 10, 'Question Paper Code  : ', 0, 1,'C');

// Key-Value Pair 4
$pdf->Cell(0, 10, 'No of answer scipts  : ', 0, 1,'C');

// Output the PDF to the browser
$pdf->Output('I', 'formatted_example.pdf'); // 'I' stands for inline (open in browser); use 'D' to force download

?>
