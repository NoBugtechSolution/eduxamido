<?php
include('../../../Common/Connections.php');
include('../../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../../adminlogin/adminlogin.php');
}
if(!isset($_SESSION['ExaminationID'])){
    include('../../../Common/ExaminationError.php'); 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Details</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>

    <?php
    $departmentsID=[];
    $DespGet=$conn->query("SELECT * FROM `programmes` ORDER BY programmes_id");
    while($dep=$DespGet->fetch_assoc()){
        $departmentsID[$dep['programmes_id']]=$dep['programmes_scode'];
    }
    
    $insert="INSERT INTO students_details( `RollNo`, `Name`, `programmes_id`,`AcademicYear`,`stud_dob`) VALUES ";
    $values="";
    $values2="";
    $total_data=0;
    $department=[];
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

    if (isset($_FILES['file_upload'])) {
        require_once "../../Classes/PHPExcel.php";
        $DataFields=['rollno','name','Program'];

        $dir = '../../uploads/';
        $file_name = $_FILES['file_upload']['name'];
        $tmp_file_name = $_FILES['file_upload']['tmp_name'];

        if (move_uploaded_file($tmp_file_name, $dir . $file_name)) {
            ?>
           
            <?php
            $data=0;
            $path = $dir . $file_name;
            include_once "../../ExcelSystem/ExcelReader.php";
            // ColumnDeatils($path);
            $neededRows=["Roll No","Name","DOB","Program","Academic Year"];
            $studentsData=StudentsDeatils($path,$conn);
            echo "<center><h3>Students Details</h3></center>";
            echo "<table class='studentsdetails'>";
            echo '<tr>';
            foreach( $neededRows as $header){
                echo '<th>' . $header . '</th>'; 
            }
            echo '<th>Exist</th>';
            echo '</tr>';
            foreach($studentsData as $student){
                $data++;
                $roll=$student['RollNo'];
                $students_check="SELECT RollNo FROM students_details WHERE RollNo='$roll'";
                $Presents=$conn->query($students_check)->num_rows;
                $Program=$student['Program'];
                echo '<tr>';
                echo "<td>$roll</td>";
                echo "<td>".$student['Name']."</td>";
                echo "<td>".$student['DOB']."</td>";
                echo "<td>".$Program."</td>";
                echo "<td>".$student['AcademicYear']."</td>";
                echo "<td>";
                echo '<input type="checkbox" disabled ';
                echo ($Presents==1)? "checked >":">";
                echo "</td>";
                echo '</tr>';

                if($Presents==0){
                    $depID=0;
                    $index = array_search($Program, $departmentsID);
                    if ($index !== false) {
                        $depID=$index;
                    } else {
                        header("location: NotFound.php?Unknown=$Program");
                        return;
                                // $conn->query("INSERT INTO `departments`(`department_name`) VALUES ('$depart')");
                                // $DespData=$conn->query("SELECT * FROM `departments` WHERE department_name='$depart'")->fetch_assoc();
                                // $departmentsID[$DespData['department_id']]=$depart;
                                // $index = array_search($depart, $departmentsID);
                                // $depID=$index;
                    }
                    $values.= "(".$roll.","."'".$student['Name']."',"."'".$depID."',"."'".$student['AcademicYear']."','".$student['DOB']."'),";
                            // echo "(".$roll.","."'".$Name."',"."'".$depID."',"."'".$AcademicYear."','$DOB'),<br>";
                    $total_data++;
                }
                if (!in_array($Program, $department)) {
                    $department[] = $Program;
                }
                $values2.= "$roll,";
            }


            echo '</table>';
            
        } else {
            echo '<center>Error uploading file.</center>';
        }
    } else {
        echo '<center>No file selected.</center>';
    }
    $query=0;
    if($total_data!=0){
        $values=substr($values, 0, -1);
        $query1= $insert.$values;
        $conn->query($query1);
    }
    $values2=substr($values2, 0, -1);
    $query=$values2;
    // echo $values;
    ?>
     <form method="post" action="ExaminationDates.php" >
     <?php foreach($department as $data1){?>
     <input type="hidden" value="<?php echo $data1; ?>" name="departs[]">
     <?php }?>
        <textarea style='display:none;'name="DatabaseEntry" ><?php echo $query; ?></textarea>
            <center>File uploaded successfully &nbsp;<button name='students_data' <?php echo ($data!=0)?"type='submit'": "type='reset'"; ?> style="background-color:#2980b9;padding:7px;font-size:18;border:none;border-radius:4px;cursor:pointer; color:white;">NEXT</button></center>
    </form>
    <?php
    // if(isset($_POST['students_data'])){
    //     $query=$_POST['DatabaseEntry'];
    //     if($query!=0){
    //         $conn->query($query);
    //     }
    //     // $query1=$_POST['DatabaseEntry2'];
    //     // $conn->query($query1);
    //     header("Location: ExamPage2.php");
    // }
    
    ?>
</body>

</html>