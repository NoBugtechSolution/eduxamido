<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--  -->
    <title>Examination</title>
    <link rel="stylesheet" href="m_e_pg4.css?v=<?=time()?>">
    <style>
        .Hall{
            text-align:left !important;
            padding-left: 20px !important;
        }
        a,button{
            cursor: pointer !important;
            text-decoration:none !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php

include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
if(!isset($_GET['ExamID'])){
    include('../../Common/ExaminationError.php'); 
}

    $SubjectID = $_GET['SubjectID']=10;
    $ClassID=$_GET['Class'];
    $ExamID=$_GET['ExamID'];
    $ExamDate=$_GET['ExamDate'];
    $qry = "SELECT DISTINCT 
    TheStudent.programmes_id,programmes.programmes_scode, 
        examsubjects.examsubjectsID,
        courses.course_id,
        courses.course_name,
        (
            SELECT COUNT(*) 
            FROM exam_students 
            INNER JOIN exam_stu_seating AS InnerSeating 
            ON InnerSeating.exam_sub_stu_ID = exam_students.exam_sub_stu_ID
            INNER JOIN students_details ON students_details.RollNo=exam_students.RollNo
            WHERE 
            exam_students.examsubjectsID = examsubjects.examsubjectsID 
            AND InnerSeating.ClassID = $ClassID
            AND students_details.programmes_id=TheStudent.programmes_id
        ) AS StudentsCount
        FROM examsubjects
        INNER JOIN exam_students AS Students 
        ON Students.examsubjectsID = examsubjects.examsubjectsID
        INNER JOIN exam_stu_seating AS Seating 
        ON Seating.exam_sub_stu_ID = Students.exam_sub_stu_ID
        INNER JOIN courses 
        ON examsubjects.course_id = courses.course_id
        INNER JOIN students_details AS TheStudent ON TheStudent.RollNo=Students.RollNo 
        INNER JOIN programmes ON programmes.programmes_id=TheStudent.programmes_id
        WHERE 
        examsubjects.ExamDate = '$ExamDate' 
        AND Seating.ClassID = $ClassID 
        AND examsubjects.ExamID = $ExamID;";
            // echo $qry;
    $result = mysqli_query($conn, $qry);
    $sub=$conn->query("SELECT * FROM `classroom` WHERE ClassID=$ClassID")->fetch_assoc();
    ?>

    <section id='header'>
        <a href='ExamHall.php?ExamID=<?=$_GET['ExamID']?>&ExamDate=<?=$_GET['ExamDate']?>' style='display:flex;gap:10px;width:400px;'><ion-icon name="arrow-back-outline" id='back'></ion-icon></a>
        <h1 id='heading'><?= $sub['ClassName']?></h1>
        <div  style='display:flex;gap:10px;width:400px;justify-content:end;'>
            <a href='ClassRoomDetails.php?ExamID=<?=$ExamID?>&ExamDate=<?=$ExamDate?>&Class=<?=$ClassID?>'><button id='view'>View Seating <i class="fa-solid fa-users"></i></button></a>
            <a href="../../pdf_works/Seating.php?ExamID=<?=$_GET['ExamID']?>&ExamDate=<?=$ExamDate?>&Class=<?=$ClassID?>"><button id='download'>Seating<ion-icon name="cloud-download-outline"></ion-icon></button></a>
        </div>
    </section>

    <div id='subdiv'>
        <h4 id='halldetails'>Course Details</h4>
    </div>
    <div class="ExaminationTable">
    <table >
        <thead>
            <th>SI NO</th>
            <th class='Hall'> Programme</th>
            <th class='Hall'> Course</th>
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
            <td class='Hall'><?=$row['programmes_scode']?></td>
            <td class='Hall'><?=$row['course_name']?></td>
            <td><?=$row['StudentsCount']?></td>
            <td style='width:300px'>
                <a href='../../pdf_works/pdf1.php?Class=<?=$ClassID?>&Course=<?=$row['examsubjectsID']?>&Programme=<?=$row['programmes_id']?>' style='display:flex;gap:10px;margin:0 auto;width:fit-content;'>
                    <button id='download1'>Download<ion-icon name="cloud-download-outline"></ion-icon></button>
                </a>
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
    $studentsSQL="SELECT Students.RollNo,Students.Name,Students.stud_dob,programmes.programmes_scode,exam_students.exam_status FROM `examsubjects` 
        INNER JOIN exam_students ON exam_students.examsubjectsID=examsubjects.examsubjectsID
        INNER JOIN students_details AS Students ON Students.RollNo=exam_students.RollNo 
        INNER JOIN exam_stu_seating AS Seating ON Seating.exam_sub_stu_ID=exam_students.exam_sub_stu_ID
        INNER JOIN programmes ON Students.programmes_id=programmes.programmes_id
        WHERE ExamID=$ExamID AND ExamDate= '$ExamDate' AND Seating.ClassID=$ClassID ";
    $studentsqu=$conn->query($studentsSQL);
    $no=1;
    while($data=$studentsqu->fetch_assoc()){
        ?>
        <tr>
            <td><?=$no?></td>
            <td class='Std'><?=$data['RollNo']?></td>
            <td class='Std'><?=$data['Name']?></td>
            <td><?=$data['stud_dob']?></td>
            <td><?=$data['programmes_scode']?></td>
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