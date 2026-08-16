<?php
include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
$data=$_POST['changes'];
$ExamID=$_POST['ExamID'];
$ExamDate=$_POST['ExamDate'];
$Session=$_POST['Session'];
// echo "ExamID: $ExamID    ExamDate: $ExamDate    Session: $Session <br>";
$newSeating=explode(",",$data);
foreach($newSeating as $Student){
    $details=explode("-",$Student);
    $studID=$details[0];
    $ClassID=$details[1];
    $row=$details[2];
    $column=$details[3];
    echo "ID: $studID    Class: $ClassID    Row: $row    Column: $column <br>";
    $update="UPDATE `exam_stu_seating` SET `ClassID`='$ClassID',`class_row`='$row',`class_col`='$column' WHERE `exam_sub_stu_ID`='$studID'";
    $conn->query($update);
    header("location: EditSeating.php?ExamID=$ExamID&ExamDate=$ExamDate&Session=$Session");
}


?>