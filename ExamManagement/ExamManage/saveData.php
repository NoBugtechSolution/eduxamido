<?php
include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
  if(!isset($_POST['ExamID'])||!isset($_POST['ExamDate'])){
    include('../../Common/ExaminationError.php'); 
}
    $assignID=$_POST['assign'];
    $ExamID=$_POST['ExamID'];
    $ExamDate=$_POST['ExamDate'];
    $Inv=$_POST['Inv'];
    $OldInv=$_POST['OldInv'];

    $update="UPDATE `assignment` SET `inv_id`='$Inv' WHERE `assignment_id`='$assignID'";
    $increCount="UPDATE `invigilators` SET `invi_duty_count`=`invi_duty_count`+1 WHERE `invid`='$Inv'";
    $decreCount="UPDATE `invigilators` SET `invi_duty_count`=`invi_duty_count`-1 WHERE `invid`='$OldInv'";
    $conn->query($update);
    $conn->query($increCount);
    $conn->query($decreCount);
    header("location: ExamAssignedInvigilator.php?ExamID=$ExamID&ExamDate=$ExamDate")


?>