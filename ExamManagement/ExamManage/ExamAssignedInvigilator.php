<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title>Invigilator</title>
    <link rel="stylesheet" href="inv_pg1.css?v=<?=time()?>">
</head>
<body>
<?php

include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
   /* 
    
    $qry = 'SELECT * FROM examsubjects WHERE ExamID = "'.$ExamID.'" AND ExamDate = "'.$ExamDate.'"';
    $result = mysqli_query($conn, $qry);

   */
  if(!isset($_GET['ExamID'])||!isset($_GET['ExamDate'])){
    include('../../Common/ExaminationError.php'); 
}

    $ExamID = $_GET['ExamID'];
    $ExamDate = $_GET['ExamDate'];

    ?>
    <section id='header'>
    <a href='ViewExamDates.php?ExamID=<?=$_GET['ExamID']?>'><ion-icon name="arrow-back-outline" id='back'></ion-icon></a>
    <h1 id='heading'>Invigilator</h1>
    <button id='download'>Download <ion-icon name="cloud-download-outline"></ion-icon></button>
    </section>
<?php
    // echo "<div id='topbox'>";
    //     echo "<h4 id='Sl'>Sl</h4>";
    //     echo "<h4 id='invname'>Name</h4>";
    //     echo "<h4 id='cls'>ClassRoom</h4>";
    //     echo "<h4 id='invid'>InvID</h4>";
    //     echo "<h4 id='action'>Action</h4>";
    // echo "</div>";

    $sql = "SELECT ASIG.assignment_id,INVS.invid,INVS.invi_name,INVS.invi_duty_count,CLASS.ClassID,CLASS.ClassName ,ASIG.session,
    (SELECT count(*) FROM `examsubjects` 
INNER JOIN exam_students ON exam_students.examsubjectsID=examsubjects.examsubjectsID
INNER JOIN exam_stu_seating ON exam_students.exam_sub_stu_ID=exam_stu_seating.exam_sub_stu_ID
INNER JOIN classroom ON classroom.ClassID=CLASS.ClassID AND classroom.ClassID=exam_stu_seating.ClassID
WHERE examsubjects.ExamDate='$ExamDate') AS TotalStudents
    FROM assignment as ASIG
INNER JOIN invigilators as INVS on ASIG.inv_id=INVS.invid
INNER JOIN classroom as CLASS on CLASS.ClassID=ASIG.ClassID
WHERE ASIG.a_exam_date='$ExamDate' ORDER BY INVS.invi_duty_count DESC" ;
// echo $sql;
    $result = mysqli_query($conn, $sql);
    ?>
    <div class="ExaminationTable">
    <table >
        <thead>
            <th>SI NO</th>
            <th class='Names'> Name</th>
            <th>ClassRoom</th>
            <th>Total Students</th>
            <th>Session</th>
            <th>Duty Count</th>
            <th>Action</th>
        </thead>
        <tbody>
    <?php
    $i = 0;
    while($row = mysqli_fetch_assoc($result)){
        $i += 1;
        ?>
        <tr >
            <td ><?= $i?></td>
            <td  class='Names'><?=$row['invi_name']?></td>
            <td ><?=$row['ClassName']?></td>
            <td><?=$row['TotalStudents']?></td>
            <td><?=($row['session']=="AM")?"Morning":"Afternoon"?></td>
            <td ><?=$row['invi_duty_count']?></td>
            <td><a href="changeInvigilator.php?Inv=<?=$row['invid']?>&assign=<?=$row['assignment_id']?>&ExamID=<?=$ExamID?>&ExamDate=<?=$ExamDate?>" style='text-decoration: none;'><button id='change'><ion-icon name="create-outline"></ion-icon><span>Change</span> </button></a></td>
        </tr>
        <?php
    }
?>
    </tbody>
    </table>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>