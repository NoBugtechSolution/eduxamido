<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--  -->
    <title>Examination</title>
    <link rel="stylesheet" href="m_e_pg4.css?v=<?=time()?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php

include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
if(!isset($_GET['SubjectID'])){
    include('../../Common/ExaminationError.php'); 
}

    $SubjectID = $_GET['SubjectID'];
    $qry = "SELECT class.ClassID,class.ClassName,(SELECT COUNT(*) FROM exam_students 
            INNER JOIN exam_stu_seating on exam_students.exam_sub_stu_ID=exam_stu_seating.exam_sub_stu_ID
            WHERE exam_students.examsubjectsID=$SubjectID AND exam_stu_seating.ClassID=class.ClassID) as StudentsCount,Subject.session
            FROM classroom as class INNER JOIN examsubjects as Subject on Subject.examsubjectsID=$SubjectID
            WHERE ClassID IN (SELECT DISTINCT(exam_stu_seating.ClassID) FROM exam_stu_seating 
            INNER JOIN exam_students on exam_students.exam_sub_stu_ID=exam_stu_seating.exam_sub_stu_ID
            INNER JOIN examsubjects as Subject on Subject.examsubjectsID=exam_students.examsubjectsID
            WHERE Subject.examsubjectsID=$SubjectID)";
            // echo $qry;
    $result = mysqli_query($conn, $qry);
    $sub=$conn->query("SELECT course_name FROM `examsubjects` INNER JOIN courses ON courses.course_id= examsubjects.course_id WHERE examsubjectsID=$SubjectID")->fetch_assoc();
    ?>

    <section id='header'>
        <a href='ExamSubjects.php?ExamID=<?=$_GET['ExamID']?>&ExamDate=<?=$_GET['ExamDate']?>' style='display:flex;gap:10px;width:300px;'><ion-icon name="arrow-back-outline" id='back'></ion-icon></a>
        <h1 id='heading'><?= $sub['course_name']?></h1>
        <div style='display:flex;gap:10px;width:300px;'>
            <button id='edit'>Edit<ion-icon name="create-outline"></ion-icon></button>
            <button id='download'>Download<ion-icon name="cloud-download-outline"></ion-icon></button>
        </div>
    </section>

    <div id='subdiv'>
        <h4 id='halldetails'>Exam Hall Details</h4>
    </div>
    <div class="ExaminationTable">
    <table >
        <thead>
            <th>SI NO</th>
            <th class='Hall'> Exam Hall</th>
            <th>No. of Students</th>
            <th>Action</th>
        </thead>
        <tbody>
    <?php
    $i = 0;
    while($row = mysqli_fetch_assoc($result)){
        $i += 1;
        ?>
        <tr>
            <td><?=$i?></td>
            <td class='Hall'><?=$row['ClassName']?></td>
            <td><?=$row['StudentsCount']?></td>
            <td style='width:300px'>
                <div style='display:flex;gap:10px;margin:0 auto;width:fit-content;'>
                    <button id='download1'>Download<ion-icon name="cloud-download-outline"></ion-icon></button>
                    <a href="ClassRoomDetails.php?SubjectID=<?=$_GET['SubjectID']?>&ExamID=<?=$_GET['ExamID']?>&ExamDate=<?=$_GET['ExamDate']?>&Class=<?=$row['ClassID']?>&Session=<?=$row['session']?>">
                        <button id='view'>Seating<i class="fa-solid fa-users"></i> </button>
                    </a>
                </div>
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
    </table>
</div>
<div id='subdiv1'>
    <h4 id='halldetails'>Student Details</h4>
    <button id='add'>Add<ion-icon name="add-circle-outline"></ion-icon></button>
</div>
    <div class="ExaminationTable">
    <table >
        <thead>
            <th>SI NO</th>
            <th class='Std'> PRN</th>
            <th class='Std'>Name</th>
            <th>DOB</th>
            <th>Program</th>
            <th>Status</th>
            <th>Action</th>
        </thead>
        <tbody>
    <?php
    $studentsSQL="SELECT * FROM `exam_students` 
        inner join students_details on students_details.RollNo=exam_students.RollNo
        INNER JOIN programmes ON programmes.programmes_id=students_details.programmes_id
        WHERE examsubjectsid=$SubjectID";
    $studentsqu=$conn->query($studentsSQL);
    $no=1;
    while($data=$studentsqu->fetch_assoc()){
        ?>
        <tr>
            <td><?=$no?></td>
            <td class='Std'><?=$data['RollNo']?></td>
            <td class='Std'><?=$data['Name']?></td>
            <td><?=$data['stud_dob']?></td>
            <td><?=$data['programmes_name']?></td>
            <td><?=($data['exam_status']==0)?"Regular":"Supply"?></td>
            <td ><button id='download1' class="detl">Delete <ion-icon name="trash-outline"></ion-icon></button></td></tr>
        <?php
        $no++;
    }
?>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>