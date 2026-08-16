<?php
require('fpdf.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Add university title with box
        $this->SetFont('Arial', 'B', 18);
        
        // Calculate the width of the remaining space for the text
        $this->Cell(0, 12, 'MAHATMA GANDHI UNIVERSITY', 0, 1, 'C');
        
        // Draw a single row for the main text and box together
        $this->SetFont('Arial', '', 13);
        
        // Move left and add first part of the text
        $this->Cell(170, 10, 'STATEMENT TO ACCOMPANY ANSWER PAPER PACKET SENT TO THE', 0, 0, 'C');
        
        // Add the number "205" box (aligned vertically with the text)
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(20, 10, '', 1, 1, 'C'); // Added on the same line as the text
        
        // Add the next line of text
        $this->SetFont('Arial', '', 13);
        $this->Cell(170, 5, 'CONTROLLER OF EXAMINATIONS BY THE CHIEF SUPERINTENDENT', 0, 0, 'C');
        
        // Empty cell to maintain structure and alignment
        $this->Cell(20, 10, '', 0, 1);

   
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Instantiation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

// Content
$pdf->Cell(0,10,'Centre No: ',0,1);
$pdf->Cell(0,5,'Name of the Centre: SAS SNDP YOGAM COLLEGE, KONNI Examination.....        Sem         202.........',0,1);
$pdf->Cell(0,5,'No. of Packets: 1(ONE)',0,1);
$pdf->Ln(3); // Line break


// Set table headers
$pdf->Cell(25, 10, 'Date', 1, 0, 'C');
$pdf->Cell(25, 10, 'Hours', 1, 0, 'C');
$pdf->Cell(65, 10, 'Subject', 1, 0, 'C');
$pdf->Cell(45, 10, 'code no.of  Q.P', 1, 0, 'C'); // Empty cell for symmetry
$pdf->multiCell(35, 5, 'Total No.of Answer books', 1, 'C'); // Empty cell for symmetry

$pdf->Cell(25, 25, '     /   /202', 1, 0, 'L');
$pdf->Cell(25, 25, '....TO..... ', 1, 0, 'C');

// Store current position
$x = $pdf->GetX();
$y = $pdf->GetY();

// Create a cell with height 25
$pdf->Cell(65, 25, '', 1); // Empty cell with border

// Reset the position to the top of the cell
$pdf->SetXY($x, $y);

// Insert text at the top of the cell
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(65, 5, 'Semester G Examination', 0, 0, 'C');
$pdf->Cell(45, 25, '', 1, 0, 'C'); // Empty cell for symmetry
$pdf->Cell(35, 25, '', 1, 1, 'C'); // Empty cell for symmetry




// Register Number of Books Title
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,'REGISTER NUMBER OF BOOKS',0,1,'C'); // Center align the title
$pdf->Ln(2); // Line break

// Register Number of Books Table 1
$pdf->SetFont('Arial','',12);
$pdf->Cell(15,10,'From',1,0,'C');
$pdf->Cell(15,10,'To',1,0,'C');
$pdf->Cell(30,10,'No. of Books',1,0,'C');
$pdf->Cell(5,10,'',0,0,'C');

// Register Number of Books Table 2
$pdf->Cell(15,10,'From',1,0,'C');
$pdf->Cell(15,10,'To',1,0,'C');
$pdf->Cell(30,10,'No. of Books',1,0,'C');
$pdf->Cell(5,10,'',0,0,'C');

// Register Number of Books Table 3
$pdf->Cell(15,10,'From',1,0,'C');
$pdf->Cell(15,10,'To',1,0,'C');
$pdf->Cell(30,10,'No. of Books',1,1,'C');

// $pdf->Ln(10); // Line break

// Data rows
$pdf->Cell(30,60,'',1,0,'C');
$pdf->Cell(30,60,'',1,0,'C');

$pdf->Cell(5,10,'',0,0,'C');

$pdf->Cell(30,60,'',1,0,'C');
$pdf->Cell(30,60,'',1,0,'C');

$pdf->Cell(5,10,'',0,0,'C');

$pdf->Cell(30,60,'',1,0,'C');
$pdf->Cell(30,60,'',1,1,'C');



$pdf->Ln(3); // Line break

// Register Number of Absentees Title
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,'REGISTER NUMBER OF ABSENTEES',0,1,'C'); // Center align the title
$pdf->Ln(5); // Line break



$pdf->Ln(20); // Line break

// Set the line thickness (optional)
$pdf->SetLineWidth(0.5);

// Draw a line from (x1, y1) to (x2, y2)
$pdf->Line(20, 200, 200, 200);

// Set the line thickness (optional)
$pdf->SetLineWidth(0.5);

// Draw a line from (x1, y1) to (x2, y2)
$pdf->Line(20, 210, 200, 210);

// Footer content
$pdf->Cell(0,10,'Konni',0,1);
$pdf->Cell(0,10,'Date:........  /      / 202............',0,1);
$pdf->Cell(0,10,'Total:..............',0,1,'R');


$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,"NB:This statement should accompany the answer papers for each subject and should be carefully verified ",0,1);
$pdf->Cell(0,5,"with the answer books by cheif superintendent before dispatch",0,1,'C');



$pdf->Output();
?>