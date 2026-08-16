<?php

include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
$Eid=$_GET['ExamID'];
if(isset($_GET['SubjectID'])){
    $sql="SELECT SubjectName FROM `examsubjects` WHERE examsubjectsID='{$_GET['SubjectID']}'";
    $ExamName=$conn->query($sql)->fetch_assoc()['SubjectName'];
}
if(isset($_GET['Class'])){
    $sqlC="SELECT ClassName FROM `classroom` WHERE ClassID='{$_GET['Class']}'";
    $ClassName=$conn->query($sqlC)->fetch_assoc()['ClassName'];
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title>The Examination</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> -->
    <link rel="stylesheet" href="EditSeatingCss.css?v=<?=time()?>">
</head>
<body>
<div class='selected-list'>
    <span class='menu'><ion-icon onclick="meanuOpen()"name="log-out-outline"></ion-icon></span>
    <h4 >Options</h4>
    <div class='options'>
        <button id="Inventory" class="Option-buttons" onclick='pickSelected()'>Add to Inventory </button>
        <button id="Deselect" class="Option-buttons" onclick='Deselectfun()'>Deselect All</button>
        <button id="SaveChanges" class="Option-buttons" onclick='finalSendData()'>Save</button>
    </div>
    <h4 ><span>Inventory</span> <span onclick='selectAllBu()' style='font-size:14px;background-color:#2912a6;cursor:pointer;padding:5px 10px;border-radius:5px;'>Select All</span></h4>
    <div id='pickedRoll'></div>
</div>
    <center >
    <ion-icon style='opacity:0' name="list-outline"></ion-icon>
        <h1>
            <u>
                <?=
                "Seating Arrangements on {$_GET['ExamDate']}"
                ?>
            </u> 
        </h1>
        <ion-icon onclick="meanuOpen()" name="list-outline"></ion-icon>
    </center>
    <hr>
    <a href="<?="ViewExamDates.php?ExamID=$Eid"?>">
        <button style='font-size:30px;display:grid;place-items:center;'><ion-icon name="arrow-back-outline" id='back'></ion-icon></button>
    </a>
    <form name='Myform' action="seatingSaveChanges.php" method="post">
    <textarea style='display:none;' id="outchanges" name='changes'></textarea>
    <input type="hidden" name="ExamID" value="<?=$Eid?>">
    <input type="hidden" name="ExamDate" value="<?=$_GET['ExamDate']?>">
    <input type="hidden" name="Session" value="<?=$_GET['Session']?>">
    </form>
    <div id='theMainTable'>
<?php

$colors=['#3f98df','#2912a6','#5a00e6','#0c66e6','#437899','#08456a'];
$departmentDatav1=[];

            $ExamDates=$_GET['ExamDate'];
            $Session=$_GET['Session'];
            $Eid=$_GET['ExamID'];
                // echo "<h1>Seating Arrangements on {$ExamDates}</h1>";
            SeatsGet($ExamDates,$Session,$departmentDatav1,$Eid);

?>
</div>
<script src='EditSeatingJS.js?v=<?=time()?>'></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>

<?php
    function SeatsGet($ExamDates,$Session,$departmentDatav1,$Eid){
        global $conn,$colors;
        $ClassIDSelect="";
        if(isset($_GET['Class'])){
            $ClassIDSelect=" AND c.ClassID={$_GET['Class']}";
        }
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
            WHERE exsub.ExamDate = '$ExamDates' AND a.session='$Session' AND examination.ExamID=$Eid $ClassIDSelect
        );";
        // echo $classselect."<br>";
        $classrooms=$conn->query($classselect);
        $classnum=1;
        while ($classroom=$classrooms->fetch_assoc()) {
            $delay=0.0;
            $TheclassID=$classroom['ClassID'];
            echo "<div class='classroom'>";
            echo "<h2>Classroom {$classnum} ({$classroom['ClassName']})<b style='font-size:17px;'>(Columns: {$classroom['MaxColumn']}, Rows: {$classroom['MaxRow']})</b></h2>";
            echo "<h2 style='font-weight:600;'>The Invigilators of the class: <b class='invs'>{$classroom['invi_name']}</b></h2>";
            echo "<table border='1' style='border-collapse: collapse;' id='C-$TheclassID'>";
            $i=1;
            echo"<tr>";
            echo"<th></th>";
            while ((int)$classroom['MaxColumn']>=$i) {
                
                echo"<th onclick='selectAllCoumn($TheclassID,$i)'> C$i</th>";
                $i++;

            }
            echo "<td rowspan='".($classroom['MaxRow']+1)."' id='ColumnObject'><button onclick='addColumn($TheclassID,this)'  style='height:100%;'>+".($classroom['MaxColumn']+1)."</button></td>";
                echo"</tr>";
            $j=1;
            $subjectsVise="";
            if(isset($_GET['SubjectID'])){
                $subjectsVise=" AND exam_students.examsubjectsID=".$_GET['SubjectID'];
            }
            $studentsSelect="SELECT exam_students.RollNo,exam_stu_seating.exam_sub_stu_ID,students_details.programmes_id,exam_stu_seating.class_row,exam_stu_seating.class_col
            FROM `exam_stu_seating` 
            INNER JOIN exam_students on exam_students.exam_sub_stu_ID=exam_stu_seating.exam_sub_stu_ID
            INNER JOIN examsubjects on examsubjects.examsubjectsID=exam_students.examsubjectsID
            INNER JOIN students_details on students_details.RollNo=exam_students.RollNo
            INNER JOIN examination ON examination.ExamID=examsubjects.ExamID
            WHERE examsubjects.ExamDate='$ExamDates' AND exam_stu_seating.ClassID=$TheclassID AND examination.ExamID=$Eid AND examsubjects.session='$Session' $subjectsVise" ;
            // echo $studentsSelect."<br>";
            $studentsseats=$conn->query($studentsSelect);
            $classseatings=[];
            $classseatings=array_fill(0, $classroom['MaxRow'], array_fill(0, $classroom['MaxColumn'], ''));;
            while ($seats=$studentsseats->fetch_assoc()) {
                // echo $seats['class_row']." : ".$seats['class_col']."  =  ".$seats['RollNo']."<br>";
                $classseatings[$seats['class_row']-1][$seats['class_col']-1]=$seats['programmes_id']."-".$seats['RollNo']."-".$seats['exam_sub_stu_ID'];
            }
            $rowCount=1;
            foreach ($classseatings as $row) {
                echo "<tr>";
                echo"<th onclick='selectAllRow($TheclassID,$j)' style='width:50px;'>R$j</th>";
                $j++;
                $temp=1;
                $colCount=1;
                foreach ($row as $seat) {
                    $Details = explode('-', $seat);
                    $index = array_search($Details[0], $departmentDatav1);
                    if($index === false){
                        $departmentDatav1[] = $Details[0];
                        $index = array_search($Details[0], $departmentDatav1);
                    }
                    

                   
                    $newcolor="style='background-color:".$colors[$index%(count($colors))]."; animation-delay: ".$delay."s;'";
                    echo "<td data-key='$TheclassID-$rowCount-$colCount'> ".($seat ? "<input type='checkbox' value='$TheclassID-$rowCount-$colCount'  id='CheckValues' data-key='" .$Details[2]."'>" : "")."<div onclick='checkthevalue(this.parentNode)' class='" . ($seat ? "seat" : "nill") . "' " . ($seat ?  $newcolor : "") . ">" . ($seat ? $Details[1] : '___') . "</div></td>";
                    $temp++;
                    $colCount++;
                }
                echo "</tr>";
                $delay+=0.05;
                $rowCount++;
            }
            echo "<tr><td colspan='".($classroom['MaxColumn']+2)."' id='RowObject'><button style='width:100%;' onclick='addRow($TheclassID,this)'> +".($classroom['MaxRow']+1)."</button></td></tr>";
            echo "</table>";
            echo "</div>";
        }
    }

?>

