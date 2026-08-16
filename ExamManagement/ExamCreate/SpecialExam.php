<?php
include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
if(!isset($_SESSION['ExaminationID'])){
    include('../../Common/ExaminationError.php'); 
  }
$Eid=$_SESSION['ExaminationID'];
$neededRows=["Roll No","Name","DOB","Program","Academic Year"];
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if(isset($_POST['DataCreate'])){
    $departmentsID=[];
    $DespGet=$conn->query("SELECT * FROM `programmes` ORDER BY programmes_id");
    while($dep=$DespGet->fetch_assoc()){
        $departmentsID[$dep['programmes_id']]=$dep['programmes_scode'];
    }
    
    $dategetsql="SELECT DISTINCT(ExamDate),session FROM `examsubjects` WHERE ExamID=$Eid";
    $ExamDates=$conn->query($dategetsql);
    while($datesData=$ExamDates->fetch_assoc()){
        $dateID=$datesData['ExamDate'];
        $session=$datesData['session'];
        $getsubsql="SELECT * FROM `examsubjects` WHERE ExamDate='$dateID' AND ExamID=$Eid AND session='$session'";
        // echo $getsubsql;
        $subjects=$conn->query($getsubsql);
            while($subject=$subjects->fetch_assoc()){
                $subID=$subject['examsubjectsID'];
                // echo $subID;
                error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
                $insert="INSERT INTO students_details( `RollNo`, `Name`, `programmes_id`,`AcademicYear`,`stud_dob`) VALUES ";
                $StudentsDat1="INSERT INTO `exam_students`( `RollNo`, `examsubjectsID`, `exam_status`) VALUES ";
                $values="";
                $values2="";
                $total_data=0;
                $StudentsDatav=0;
                if(isset($_FILES["$subID,$dateID"])){
                    require_once "../Classes/PHPExcel.php";
                    $dir = '../uploads/';
                    
                    $file_name = $_FILES["$subID,$dateID"]['name'];
                    $tmp_file_name = $_FILES["$subID,$dateID"]['tmp_name'];
                    if (move_uploaded_file($tmp_file_name, $dir . $file_name)) {
                        include_once "../ExcelSystem/ExcelReader.php";
                        $neededRows=["Roll No","Name","DOB","Program","Academic Year"];
                        $path = $dir . $file_name;
                        $studentsData=StudentsDeatils($path,$conn);
                        $data=0;
                        
                        foreach($studentsData as $student){
                            $data++;
                            $roll=$student['RollNo'];
                            $students_check="SELECT RollNo FROM students_details WHERE RollNo='$roll'";
                            $Presents=$conn->query($students_check)->num_rows;
                            $Program=$student['Program'];

                            if($Presents==0){
                                $depID=0;
                                $index = array_search($Program, $departmentsID);
                                if ($index !== false) {
                                    $depID=$index;
                                } else {
                                    header("location: NotFound.php?Unknown=$Program");
                                    return;
                                }
                                $values.= "(".$roll.","."'".$student['Name']."',"."'".$depID."',"."'".$student['AcademicYear']."','".$student['DOB']."'),";
                                        // echo "(".$roll.","."'".$Name."',"."'".$depID."',"."'".$AcademicYear."','$DOB'),<br>";
                                $total_data++;
                            }
                            $students_check2="SELECT RollNo FROM exam_students WHERE RollNo='$roll' AND examsubjectsID=$subID";
                            $Presents2=$conn->query($students_check2)->num_rows;
                            if($Presents2==0){
                                $values2.= "($roll,$subID,0),"; 
                                $StudentsDatav++;   
                            }
                        }
                        if($total_data!=0){
                            $values=substr($values, 0, -1);
                            $query1= $insert.$values;
                            // echo $query1;
                            $conn->query($query1);
                        }
                        if($StudentsDatav!=0){
                            $values2=substr($values2, 0, -1);
                            $query2=$StudentsDat1.$values2;
                            // echo $query2;
                            $conn->query($query2);
                        }
                        // echo $values2;
                        
                    } 
                }
                // echo $_POST["$subID,$dateID"];
            }
        
    }
    header("Location: SupplySelect.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title>Special Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        
        

        $dategetsql="SELECT DISTINCT(ExamDate),session FROM `examsubjects` WHERE ExamID=$Eid";
        $ExamDates=$conn->query($dategetsql);
    ?>
    <h1>Special Details</h1>
    <form  method="post" enctype="multipart/form-data">
        <table class='supply-Table'>
        <tbody>
        <?php
        $Outer=0;
            while($datesData=$ExamDates->fetch_assoc()){
                $dateID=$datesData['ExamDate'];
                $session=$datesData['session'];
                $getsubsql="SELECT * FROM `examsubjects` 
                INNER JOIN courses ON courses.course_id=examsubjects.course_id 
                WHERE ExamDate='$dateID' AND ExamID=$Eid AND session='$session' AND courses.course_type IN ('Elective','OpenCourse')";
                // echo $getsubsql;
                $subjects=$conn->query($getsubsql);
                if($subjects->num_rows==0){
                    continue;
                }
                echo "<tr><td style='padding:0px; padding-top:30px;background-color: #fff;' colspan='3'><b>$dateID (".(($session=="AM")?"Morning":"Afternoon").")</b><hr></td></tr>"
                ?>
                
                    
                    <?php
                    $i=1;
                    while($subject=$subjects->fetch_assoc()){
                         $Outer++;
                        $od=($i%2==1)?"class='odds'":"";
                        echo "<tr $od>";
                        echo "<td>".$subject['course_code'] ."</td>";
                        echo "<td>".$subject['course_name'] ."</td>";
                        echo "<td style='width:200px;'>";?>
                        <input type='file' name='<?php echo $subject['examsubjectsID'].",".$dateID;?>' required></td>
                        <?php 
                        echo "</tr>";
                        $i++;
                    }
                    ?>
                   
                <?php
                
            }
            if($Outer==0){
                header("Location: SupplySelect.php");
            }
        ?>
         </tbody>
         </table>
         <input type="submit" name="DataCreate" value="Next">
    </form>
</body>
</html>