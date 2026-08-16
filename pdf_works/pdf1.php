<?php
$classID=$_GET['Class'];
$Course=$_GET['Course'];
$Programme=$_GET['Programme'];
include("../Common/Connections.php");
$sql="SELECT exam_students.RollNo FROM `exam_stu_seating` 
INNER JOIN exam_students ON exam_students.exam_sub_stu_ID=exam_stu_seating.exam_sub_stu_ID
INNER JOIN examsubjects ON examsubjects.examsubjectsID=exam_students.examsubjectsID
INNER JOIN students_details AS TheStudent ON TheStudent.RollNo=exam_students.RollNo 
WHERE exam_stu_seating.ClassID=$classID AND examsubjects.examsubjectsID=$Course  AND TheStudent.programmes_id=$Programme";
$result = $conn->query($sql);
$Roll=[];
while ($row = $result->fetch_assoc()) {
    $Roll[]=$row['RollNo'];
}
require_once('fpdf.php');

// Create a new PDF document
$pdf = new FPDF();

// Add a new page
$pdf->AddPage();

// Set font
$pdf->SetFont('Arial', 'B', 16);

// Write the title
$pdf->Cell(0, 10, 'MAHATMA GANDHI UNIVERSITY', 0, 1, 'C');

// Add some text
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 5, '(To be prepared in duplicate)', 0, 'C');
$pdf->MultiCell(0, 9, 'Account of Answer Books issued to candidates for the conduct of University Examination on .... at', 0, 'L');
$pdf->MultiCell(0, 9, 'Hall No......                Center of Exam: SAS SNDP YOGAM COLLEGE, KONNI        Center No: .....', 0, 'L');

// Set font for table headers
$pdf->SetFont('Arial', 'B', 10);

// Column headers
$header = array(
    "\nSl\nNo\n\n\n", 
    "\nName of Exam\n\n", 
    "\nRegister NO\n\n\n\n", 
    "No of Main\nAnswer \nBook\n\n", 
    "No of Addnl.\nAnswer \nBook\n\n", 
    "Serial No of\nAnswer \nBook 1\n\n\n", 
    "Serial No of\nAnswer \nBook 2\n\n\n", 
    "Signature of\nCandidate\n\n\n\n"
);

// Set the width for each column
$widths = array(10, 15, 40, 20, 20, 25, 25, 35);

// Store current X and Y positions
$xPos = $pdf->GetX();
$yPos = $pdf->GetY();

// Header
foreach($header as $i => $col) {
    // Set the X and Y positions for the next cell
    $pdf->SetXY($xPos, $yPos);
    // Create a MultiCell for each header
    $pdf->MultiCell($widths[$i], 6, $col, 1, 'C');
    // Update the X position to the right of the current cell
    $xPos += $widths[$i];
}

// // Move cursor to the next line after headers
// $pdf->Ln();

// Rows
$pdf->SetFont('Arial', '', 12);
for ($i = 1; $i <= 50; $i++) {
    $pdf->Cell($widths[0], 6, $i, 1);
    // Span a large column
    if(($i==50)||($i==33)){
        $par='B';
    }elseif($i==34){
        $par='T';
    }else{
        $par='0';
    }
    $pdf->Cell($widths[1], 6, '', $par); // Adjust the width as needed
    
    $pdf->Cell($widths[2], 6, (isset($Roll[$i-1])?$Roll[$i-1]:""), 1);
    for ($j = 3; $j < count($header); $j++) {
        $pdf->Cell($widths[$j], 6, '', 1);
    }
    $pdf->Ln();
}
// Add the title
$pdf->Cell(0, 10, 'Details of Answer Book', 0, 1, 'C');

// Add the table header
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(80, 10, 'Details of Answer Book', 1, 0, 'C');
$pdf->Cell(55, 10, 'Main', 1, 0, 'C');
$pdf->Cell(55, 10, 'Additional', 1, 1, 'C');

// Add the table rows
$pdf->Cell(80, 10, 'Total No of Answer Book received', 1, 0, 'L');
$pdf->Cell(55, 10, '', 1, 0, 'C');
$pdf->Cell(55, 10, '', 1, 1, 'C');
$pdf->Cell(80, 10, 'Total No of Answer Book issued', 1, 0, 'L');
$pdf->Cell(55, 10, '', 1, 0, 'C');
$pdf->Cell(55, 10, '', 1, 1, 'C');
$pdf->Cell(80, 10, "Balance returned to the\n Chef Superintendent", 1, 0, 'L');
$pdf->Cell(55, 10, '', 1, 0, 'C');
$pdf->Cell(55, 10, '', 1, 1, 'C');

// Add some space
$pdf->Cell(0, 10, '', 0, 1, 'C');

// Add the signature and name
$pdf->Cell(190, 10, 'Signature of Invigilator', 1, 0, 'L');
$pdf->Ln();
$pdf->Cell(190, 10, 'Name of Invigilator', 1, 0, 'L');


// Add some space
$pdf->Cell(0, 10, '', 0, 1, 'C');

// Add some space
$pdf->Cell(0, 40, '', 0, 1, 'C');

// Add the invigilator's name and date
$pdf->Cell(0, 10, 'Konni', 0, 1, 'L');
$pdf->Cell(0, 10, '...... / .... / .......', 0, 1, 'L');


// Add the note
// Add the note
$pdf->Cell(0, 10, "NB: A copy of to be retained in the office.One copy to be arranged date wise and sent to the University with the statement", 0, 1, 'L');
$pdf->Cell(0, 10, "of accounts of answer books after the close of the Examination", 0, 1, 'L');




// Output the PDF
$pdf->Output('my_pdf.pdf', 'I'); // 'I' for inline display

?>
