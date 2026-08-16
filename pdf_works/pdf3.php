<?php

// Include the FPDF library
require_once('fpdf.php');

// Create a new PDF document
$pdf = new FPDF();

// Add a new page
$pdf->AddPage();

// Add some space before the title
$pdf->Ln(20); // adjust the value to your liking

// Set font for the title
$pdf->SetFont('Arial', 'B', 16);

// Title of the document
$pdf->Cell(0, 10, "MAHATMA GANDHI UNIVERSITY", 0, 1, 'C');

// Get the current y-position of the cursor
$y = $pdf->GetY();

// Draw a line under the title
$pdf->SetLineWidth(0.5);
$pdf->Line(60, $y, 150, $y);

// Set font for the rest of the document
$pdf->SetFont('Arial', 'B', 12);

// Create the table headers
$pdf->Cell(70, 20, "Centre Number & Name", 0, 0, 'L');
$pdf->Cell(0, 20, ":  120, S.A.S S.N.D.P Yogam College, Konni", 0, 1, 'L');

$pdf->Cell(70, 10, "Hall No", 0, 0, 'L');
$pdf->Cell(0, 10, ":  Hall No-", 0, 1, 'L');

$pdf->Cell(70, 10, "Date", 0, 0, 'L');
$pdf->Cell(0, 10, ": ", 0, 1, 'L');

$pdf->Cell(70, 10, "Name of Examination", 0, 0, 'L');
$pdf->Cell(0, 10, ": ", 0, 1, 'L');

$pdf->Cell(70, 10, "Register Numbers", 0, 0, 'L');
$pdf->Cell(0, 10, ": ", 0, 1, 'L');

// Set font to bold for "TOTAL"
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, "TOTAL", 0, 1, 'C');

// Set font back to regular for the rest of the document
$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(70, 10, "REGISTER NUMBER OF ABSENTEES :", 0, 0, 'L');
$pdf->Cell(0, 10, " ", 0, 1, 'L');

$pdf->Cell(70, 10, "TOTAL NUMBER OF ABSENTEES", 0, 0, 'L');
$pdf->Cell(0, 10, ": ", 0, 1, 'L');

$pdf->Cell(70, 10, "SIGNATURE OF INVIGILATOR", 0, 0, 'L');
$pdf->Cell(0, 10, ": ", 0, 1, 'L');

$pdf->Cell(70, 10, "NAME OF INVIGILATOR", 0, 0, 'L');
$pdf->Cell(0, 10, ": ", 0, 1, 'L');

$pdf->Cell(70, 10, "SIGNATURE OF CHIEF SUPERINTENDENT", 0, 0, 'L');
$pdf->Cell(0, 10, " ", 0, 1, 'L');

// Output the PDF
$pdf->Output();