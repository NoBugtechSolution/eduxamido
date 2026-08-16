<?php

include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
$Eid=$_GET['ExamID'];
if(isset($_GET['SubjectID'])){
    $sql="SELECT course_name FROM `examsubjects` INNER JOIN courses ON courses.course_id= examsubjects.course_id WHERE examsubjectsID='{$_GET['SubjectID']}'";
    $ExamName=$conn->query($sql)->fetch_assoc()['course_name'];
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
    <style>
body {
  font-family: Arial, sans-serif;
  background-color: #f0f2f5;
  color: #333;
  padding: 20px;
}
h1 {
  color: #2c3e50;
}
table {
  border-collapse: collapse;
  margin: 10px 0;
  width: 100%;
  max-width: 1000px;
}
th, td {
  border: 2px solid #ddd;
  padding: 8px;
  text-align: center;
  transition: background-color 0.3s ease;
}
th {
  background-color: #f2f2f2;
}
td {
  background-color: #fff;
  cursor: pointer;
}
td:hover {
  background-color: #f5f5f5 !important;
  color: black;
}
.seat {
  padding: 10px;
  background-color: #3498db;
  color: white;
  border-radius: 4px;
  transform: rotateX(90deg);
  animation: Loader .3s  ease-in-out forwards;
}
@keyframes Loader {
    from{
        transform: rotateX(80deg);
    }
    to{
        transform: rotateX(0deg);
    }
}
.nill {
  background-color: #e74c3c;
  color: white;
}
.classroom {
  margin-bottom: 20px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  padding: 20px;
}
button{
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #2980b9;
        }
        center>h1{
            color:green !important;
        }
        h2 .invs{
          font-size:33px;
          text-decoration:underline;
        }
        .headervalue{
            width: 100%;
            display:flex;
            justify-content: space-between;
            align-items:center;
        }
        .headervalue button{
            height: fit-content;
            padding: 10px 22px;
            font-size:14px;
            display:flex;
            justify-content: center;
            gap:5px;
            align-items:center;

        }
        
        .headervalue a{
            text-decoration:none;
        }
</style>
</head>
<body>
    <center><h1><u>
        <?=
        (!isset($_GET['ExamDate']))?"ClassRoom Seating Arrangements":((!isset($_GET['SubjectID']))?"Seating Arrangements on {$_GET['ExamDate']}":((!isset($_GET['Class']))?"Seating of students have '<b style='font-weight:800'>$ExamName</b>' Exam":"$ClassName <br>Seating of students have '<b style='font-weight:800'>$ExamName</b>' Exam"))
        ?>
    </u> </h1></center><hr>
    <a href="<?=
    (!isset($_GET['ExamDate']))?"../ExamManage/ViewExams.php":
    (((!isset($_GET['SubjectID']))&&isset($_GET['Class']))?"ExamHallDetails.php?ExamID=$Eid&ExamDate={$_GET['ExamDate']}&Class={$_GET['Class']}":
    ((!isset($_GET['SubjectID']))?"ViewExamDates.php?ExamID=$Eid":
    ((!isset($_GET['Class']))?"ExamSubjects.php?ExamID=$Eid&ExamDate={$_GET['ExamDate']}":
    "ExamSubjectsDetails.php?SubjectID={$_GET['SubjectID']}&ExamID=$Eid&ExamDate={$_GET['ExamDate']}")))?>">
        <button style='font-size:30px;display:grid;place-items:center;'><ion-icon name="arrow-back-outline" id='back'></ion-icon></button>
    </a>
<?php

$colors=['#3f98df','#2912a6','#5a00e6','#0c66e6','#437899','#08456a'];
$departmentDatav1=[];
        if(!isset($_GET['ExamDate'])){
            $selectdate="SELECT DISTINCT(ExamDate),session FROM `examsubjects` WHERE ExamID=$Eid ";
            $datedetails=$conn->query($selectdate);
            while($datedetailsrow=$datedetails->fetch_assoc()){
                // $departmentDatav1=[];
                $ExamDates=$datedetailsrow['ExamDate'];
                $Session=$datedetailsrow['session'];
                echo "<div class='headervalue'><h1>{$ExamDates} ".(($Session=="AM")?"Morning Session":"Afternoon Session")."</h1><a href='EditSeating.php?ExamID=$Eid&ExamDate=$ExamDates&Session=$Session'><button><ion-icon style='font-size:20px;' name='create-outline'></ion-icon><span>Edit Seating </span></button></a></div>";
                SeatsGet($ExamDates,$Session,$departmentDatav1,$Eid);
                
            }
        }else{
            $ExamDates=$_GET['ExamDate'];
            $sql="SELECT DISTINCT(ExamDate),session FROM `examsubjects` WHERE ExamID=$Eid AND ExamDate='$ExamDates'";
            // echo $sql;
            if(isset($_GET['Session'])){
                $sql.=" AND session='{$_GET['Session']}'";
            }
            $result=$conn->query($sql);
            while($row=$result->fetch_assoc()){
                $Session=$row['session'];
                echo "<div class='headervalue'><h1>{$ExamDates} ".(($Session=="AM")?"Morning Session":"Afternoon Session")."</h1><a href='EditSeating.php?ExamID=$Eid&ExamDate=$ExamDates&Session=$Session'><button><ion-icon style='font-size:20px;' name='create-outline'></ion-icon><span>Edit Seating </span></button></a></div>";
                // echo "<h1>Seating Arrangements on {$ExamDates}</h1>";
                SeatsGet($ExamDates,$Session,$departmentDatav1,$Eid);
            }
        }

?>
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
            echo "<table border='1' style='border-collapse: collapse;'>";
            $i=1;
            echo"<tr>";
            echo"<td></td>";
            $classnum++;
            while ((int)$classroom['MaxColumn']>=$i) {
                if($i%2==1&&$i!=1){
                    echo "<td style='border:none'></td>";
                }
                echo"<td> C$i</td>";
                $i++;

            }
                echo"</tr>";
            $j=1;
            $subjectsVise="";
            if(isset($_GET['SubjectID'])){
                $subjectsVise=" AND exam_students.examsubjectsID=".$_GET['SubjectID'];
            }
            $studentsSelect="SELECT exam_students.RollNo,students_details.programmes_id,exam_stu_seating.class_row,exam_stu_seating.class_col
            FROM `exam_stu_seating` 
            INNER JOIN exam_students on exam_students.exam_sub_stu_ID=exam_stu_seating.exam_sub_stu_ID
            INNER JOIN examsubjects on examsubjects.examsubjectsID=exam_students.examsubjectsID
            INNER JOIN students_details on students_details.RollNo=exam_students.RollNo
            INNER JOIN examination ON examination.ExamID=examsubjects.ExamID
            WHERE examsubjects.ExamDate='$ExamDates' AND exam_stu_seating.ClassID=$TheclassID AND examination.ExamID=$Eid AND examsubjects.session='$Session' $subjectsVise" ;
            // echo $studentsSelect."<br>";
            $studentsseats=$conn->query($studentsSelect);
            $classseatings=[];
            $classseatings=array_fill(0, $classroom['MaxRow'], array_fill(0, $classroom['MaxColumn'], ''));
            while ($seats=$studentsseats->fetch_assoc()) {
                // echo $seats['class_row']." : ".$seats['class_col']."  =  ".$seats['RollNo']."<br>";
                $classseatings[$seats['class_row']-1][$seats['class_col']-1]=$seats['programmes_id']."-".$seats['RollNo'];
            }
            foreach ($classseatings as $row) {
                echo "<tr>";
                echo"<td style='width:50px;'>R$j</td>";
                $j++;
                $temp=1;
                foreach ($row as $seat) {
                    $Details = explode('-', $seat);
                    $index = array_search($Details[0], $departmentDatav1);
                    if($index === false){
                        $departmentDatav1[] = $Details[0];
                        $index = array_search($Details[0], $departmentDatav1);
                    }
                    

                    if($temp%2==1&&$temp!=1){
                        echo "<td style='border:none'></td>";
                    }
                    $newcolor="style='background-color:".$colors[$index%(count($colors))]."; animation-delay: ".$delay."s;'";
                    echo "<td " . ($seat ?  $newcolor : "") . "  class='" . ($seat ? "seat" : "nill") . "'>" . ($seat ? $Details[1] : '___') . "</td>";
                    $temp++;
                }
                echo "</tr>";
                $delay+=0.05;
            }
            echo "</table>";
            echo "</div>";
        }
    }

?>

