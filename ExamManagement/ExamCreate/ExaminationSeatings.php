<?php
include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../../adminlogin/adminlogin.php');
}
// $tempdelete="DELETE FROM `exam_stu_seating` WHERE 1";
// $conn->query($tempdelete);
if(!isset($_SESSION['ExaminationID'])){
    include('../../Common/ExaminationError.php'); 
}
$Eid=$_SESSION['ExaminationID'];
$colors=['#3f98df','#2912a6','#5a00e6','#0c66e6','#437899','#08456a'];
$departmentDatav1=[];

?>
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<!-- <meta name='viewport' content='width=device-width, initial-scale=1.0'> -->
<title>Seating Arrangement</title>
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
</style>
</head>
<body>


<a href='ClassSelect.php'><button>Reselect Classes</button></a>
<a href='../../Invigilators_assignment/Pages/assignInvigilators.php'><button>NEXT</button></a>
<?php
    $selectdate="SELECT DISTINCT(ExamDate),session FROM `examsubjects` WHERE ExamID=$Eid ";
    $datedetails=$conn->query($selectdate);
    while($datedetailsrow=$datedetails->fetch_assoc()){
        $departmentDatav1=[];
        $ExamDates=$datedetailsrow['ExamDate'];
        $Session=$datedetailsrow['session'];
        echo "<h1>Seating Arrangements on {$ExamDates} ".(($Session=="AM")?"Morning Session":"Afternoon Session")."</h1>";
        $classselect="SELECT * FROM classroom WHERE ClassID IN (SELECT DISTINCT(exam_stu_seating.ClassID) FROM `exam_stu_seating` 
        INNER JOIN exam_students on exam_students.exam_sub_stu_ID=exam_stu_seating.exam_sub_stu_ID
        INNER JOIN examsubjects on examsubjects.examsubjectsID=exam_students.examsubjectsID
        INNER JOIN examination ON examination.ExamID=examsubjects.ExamID
        WHERE examsubjects.ExamDate='$ExamDates' AND examsubjects.session='$Session' AND examination.ExamID=$Eid)";
        // echo $classselect;
        $classrooms=$conn->query($classselect);
        $classnum=1;
        while ($classroom=$classrooms->fetch_assoc()) {
            $delay=0.0;
            $TheclassID=$classroom['ClassID'];
            echo "<div class='classroom'>";
            echo "<h2>Classroom {$classnum} ({$classroom['ClassName']})<b style='font-size:17px;'>(Columns: {$classroom['ClassColumns']}, Rows: {$classroom['ClassRows']})</b></h2>";
            echo "<table border='1' style='border-collapse: collapse;'>";
            $i=1;
            $classnum++;
            echo"<tr>";
            echo"<td></td>";
            while ((int)$classroom['ClassColumns']>=$i) {
                if($i%2==1&&$i!=1){
                    echo "<td style='border:none'></td>";
                }
                echo"<td> C$i</td>";
                $i++;

            }
                echo"</tr>";
            $j=1;
            $studentsSelect="SELECT exam_students.RollNo,students_details.programmes_id,exam_stu_seating.class_row,exam_stu_seating.class_col
            FROM `exam_stu_seating` 
            INNER JOIN exam_students on exam_students.exam_sub_stu_ID=exam_stu_seating.exam_sub_stu_ID
            INNER JOIN examsubjects on examsubjects.examsubjectsID=exam_students.examsubjectsID
            INNER JOIN students_details on students_details.RollNo=exam_students.RollNo
            INNER JOIN examination ON examination.ExamID=examsubjects.ExamID
            WHERE examsubjects.ExamDate='$ExamDates' AND exam_stu_seating.ClassID=$TheclassID  AND examsubjects.session='$Session' AND examination.ExamID=$Eid";
            $studentsseats=$conn->query($studentsSelect);
            $classseatings=[];
            $classseatings=array_fill(0, $classroom['ClassRows'], array_fill(0, $classroom['ClassColumns'], ''));;
            while ($seats=$studentsseats->fetch_assoc()) {
                // echo $seats['class_row']." : ".$seats['class_col']."  =  ".$seats['RollNo']."<br>";
                $classseatings[$seats['class_row']-1][$seats['class_col']-1]=$seats['programmes_id']."-".$seats['RollNo'];
            }
            foreach ($classseatings as $row) {
                echo "<tr>";
                echo"<td>R$j</td>";
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
</body>
</html>