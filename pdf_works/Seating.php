<?php
require('fpdf.php');
include("../Common/Connections.php");
function hexToRGB($hex) {
    $hex = ltrim($hex, '#');
    if (strlen($hex) === 3) {
        $r = hexdec(str_repeat($hex[0], 2));
        $g = hexdec(str_repeat($hex[1], 2));
        $b = hexdec(str_repeat($hex[2], 2));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    return [$r, $g, $b];
}


$pdf = new FPDF();
$pdf->AddPage();

// Column headers
$colors=['#3f98df','#2912a6','#5a00e6','#0c66e6','#437899','#08456a'];


// Set the width for each column
// Set font for the headings
$Eid=$_GET['ExamID'];
$firstPage = true;
$ExamDateG=$_GET['ExamDate'];
$selectdate="SELECT DISTINCT(ExamDate),session FROM `examsubjects` WHERE ExamID=$Eid AND ExamDate='$ExamDateG'";
$datedetails=$conn->query($selectdate);
    while($datedetailsrow=$datedetails->fetch_assoc()){
        $ClassIDSelect="";
        if(isset($_GET['Class'])){
            $ClassIDSelect=" AND c.ClassID={$_GET['Class']}";
        }
        $departmentDatav1=[];
        $ExamDates=$datedetailsrow['ExamDate'];
        $Session=$datedetailsrow['session'];
        $classselect="SELECT 
            c.*, 
            a.a_exam_date,
            i.*,
            ms.MaxRow,
            ms.MaxColumn
        FROM classroom c
        INNER JOIN assignment a ON a.ClassID = c.ClassID
        INNER JOIN invigilators i ON i.invid = a.inv_id
        LEFT JOIN (
            SELECT 
                s.ClassID,
                MAX(s.class_row) AS MaxRow,
                MAX(s.class_col) AS MaxColumn
            FROM exam_stu_seating s
            INNER JOIN exam_students es ON es.exam_sub_stu_ID = s.exam_sub_stu_ID
            INNER JOIN examsubjects exsub ON exsub.examsubjectsID = es.examsubjectsID
            WHERE exsub.ExamDate = '$ExamDates'
            GROUP BY s.ClassID
        ) ms ON ms.ClassID = c.ClassID
        WHERE a.a_exam_date = '$ExamDates'
        AND c.ClassID IN (
            SELECT DISTINCT s.ClassID
            FROM exam_stu_seating s
            INNER JOIN exam_students es ON es.exam_sub_stu_ID = s.exam_sub_stu_ID 
            INNER JOIN examsubjects exsub ON exsub.examsubjectsID = es.examsubjectsID AND exsub.session=a.session
            INNER JOIN examination ON examination.ExamID=exsub.ExamID
            WHERE exsub.ExamDate = '$ExamDates' AND a.session='$Session'  $ClassIDSelect)";
        // echo $classselect;
        $classrooms=$conn->query($classselect);
        $classnum=1;
        while ($classroom=$classrooms->fetch_assoc()) {
            $TheclassID=$classroom['ClassID'];
            if ($firstPage) {
                $firstPage = false; 
            } else {
                $pdf->AddPage(); 
            }
            $TheclassID=$classroom['ClassID'];
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'Date: '.$ExamDates, 0, 1, 'C');
            $pdf->Cell(0, 10, 'Class Room: '.$classroom['ClassName'], 0, 1, 'C');

            // Second Heading

            // Third Heading
            $pdf->Cell(0, 10, 'Session: '.(($Session=="AM")?"Morning Session":"Afternoon Session"), 0, 1, 'C');

            // Add a line break before the subheading
            $pdf->Ln(10);

            // Subheading
            $pdf->SetFont('Arial', 'B', 13);
            // $pdf->Cell(0, 10, 'Examination Details: ', 0, 1, 'C');

            // Add a line break before the key-value pairs
            $pdf->Ln(0);
            
            $columnCount = $classroom['MaxColumn'];
            $pageWidth = ($pdf->GetPageWidth() - 20)-20; 
            $colWidth = $pageWidth / $columnCount;
            $pdf->Cell(20, 10, "", 1, 0, 'C');
            for($rowsHead=0;$rowsHead<$classroom['MaxColumn'];$rowsHead++) {
                $pdf->Cell($colWidth, 10, $rowsHead+1, 1, 0, 'C');
            }
            $pdf->Ln();
            $studentsSelect="SELECT exam_students.RollNo,students_details.programmes_id,exam_stu_seating.class_row,exam_stu_seating.class_col
            FROM `exam_stu_seating` 
            INNER JOIN exam_students on exam_students.exam_sub_stu_ID=exam_stu_seating.exam_sub_stu_ID
            INNER JOIN examsubjects on examsubjects.examsubjectsID=exam_students.examsubjectsID
            INNER JOIN students_details on students_details.RollNo=exam_students.RollNo
            INNER JOIN examination ON examination.ExamID=examsubjects.ExamID
            WHERE examsubjects.ExamDate='$ExamDates' AND exam_stu_seating.ClassID=$TheclassID AND examination.ExamID=$Eid AND examsubjects.session='$Session'" ;
            // echo $studentsSelect."<br>";
            $studentsseats=$conn->query($studentsSelect);
            $classseatings=[];
            $classseatings=array_fill(0, $classroom['MaxRow'], array_fill(0, $classroom['MaxColumn'], ''));
            while ($seats=$studentsseats->fetch_assoc()) {
                // echo $seats['class_row']." : ".$seats['class_col']."  =  ".$seats['RollNo']."<br>";
                $classseatings[$seats['class_row']-1][$seats['class_col']-1]=$seats['programmes_id']."-".$seats['RollNo'];
            }
            $j=1;
            foreach ($classseatings as $row) {
                $pdf->SetFont('Arial', 'B', 13);
                $pdf->Cell(20, 10, $j, 1, 0, 'C');
                $pdf->SetFont('Arial', '', (($classroom['MaxColumn']<6)?12:16-($classroom['MaxColumn'])));
                $j++;
                $temp=1;
                foreach ($row as $seat) {
                    $Details = explode('-', $seat);
                    $index = array_search($Details[0], $departmentDatav1);
                    if($index === false){
                        $departmentDatav1[] = $Details[0];
                        $index = array_search($Details[0], $departmentDatav1);
                    }
                    list($r, $g, $b) = hexToRGB($colors[$index%(count($colors))]);
                    $g=($seat ? $g : 0);
                    $b=($seat ? $b : 0);
                    $r=($seat ? $r : 200);
                    $pdf->SetFillColor($r, $g, $b);
                    $pdf->SetTextColor(250, 250, 250);
                    $pdf->Cell($colWidth, 10, ($seat ? $Details[1] : '___'), 1, 0, 'C',true);
                    $pdf->SetTextColor(0, 0, 0);
                    $temp++;
                }
                $pdf->Ln();
            }

            
        }
    }

// Output the PDF to the browser
$pdf->Output("$ExamDateG.pdf","I"); // 'I' stands for inline (open in browser); use 'D' to force download

?>
