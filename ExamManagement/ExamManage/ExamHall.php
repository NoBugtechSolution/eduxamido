<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title>Examination</title>
    <link rel="stylesheet" href="m_e_pg3.css?v=<?=time()?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<?php

include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
if(!isset($_GET['ExamID'])||!isset($_GET['ExamDate'])){
    include('../../Common/ExaminationError.php'); 
}


    $ExamID = $_GET['ExamID'];
    $ExamDate = $_GET['ExamDate'];
    $qry = "SELECT 
    c.*, 
    a.a_exam_date, 
    i.*, 
    ms.MaxRow, 
    ms.MaxColumn,
    stu_count.TotalStudents
FROM 
    classroom c
INNER JOIN 
    assignment a ON a.ClassID = c.ClassID
INNER JOIN 
    invigilators i ON i.invid = a.inv_id
LEFT JOIN (
    SELECT 
        s.ClassID, 
        MAX(s.class_row) AS MaxRow, 
        MAX(s.class_col) AS MaxColumn
    FROM 
        exam_stu_seating s
    INNER JOIN 
        exam_students es ON es.exam_sub_stu_ID = s.exam_sub_stu_ID
    INNER JOIN 
        examsubjects exsub ON exsub.examsubjectsID = es.examsubjectsID
    WHERE 
        exsub.ExamDate = '$ExamDate'
    GROUP BY 
        s.ClassID
) ms ON ms.ClassID = c.ClassID
LEFT JOIN (
    SELECT 
        s.ClassID, 
        COUNT(DISTINCT es.exam_sub_stu_ID) AS TotalStudents
    FROM 
        exam_stu_seating s
    INNER JOIN 
        exam_students es ON es.exam_sub_stu_ID = s.exam_sub_stu_ID
    INNER JOIN 
        examsubjects exsub ON exsub.examsubjectsID = es.examsubjectsID
    INNER JOIN 
        examination ON examination.ExamID = exsub.ExamID
    INNER JOIN 
        assignment a2 ON a2.session = exsub.session AND a2.ClassID = s.ClassID
    WHERE 
        exsub.ExamDate = '$ExamDate' AND 
        examination.ExamID = $ExamID
    GROUP BY 
        s.ClassID
) stu_count ON stu_count.ClassID = c.ClassID
WHERE 
    a.a_exam_date = '$ExamDate'
    AND c.ClassID IN (
        SELECT DISTINCT s.ClassID
        FROM 
            exam_stu_seating s
        INNER JOIN 
            exam_students es ON es.exam_sub_stu_ID = s.exam_sub_stu_ID
        INNER JOIN 
            examsubjects exsub ON exsub.examsubjectsID = es.examsubjectsID AND exsub.session = a.session
        INNER JOIN 
            examination ON examination.ExamID = exsub.ExamID
        WHERE 
            exsub.ExamDate = '$ExamDate' AND 
            examination.ExamID = $ExamID
    );";
    // echo $qry;
    $result = mysqli_query($conn, $qry);
    $i=0;
?>
    <section id='header'>
        <a style='display:flex;gap:10px;width:300px;'href='ViewExamDates.php?ExamID=<?=$_GET['ExamID']?>'><ion-icon name="arrow-back-outline" id='back'></ion-icon></a>
        <h1 id='heading'><?=$ExamDate?></h1>
        <div style='display:flex;gap:10px;width:300px;'>
            <!-- <button id='add'>Add <ion-icon name="add-circle-outline"></ion-icon></button> -->
            <!-- <a href="../../pdf_works/Seating.php?ExamID=<?=$_GET['ExamID']?>&ExamDate=<?=$ExamDate?>&Class="><button id='download'>Download<ion-icon name="cloud-download-outline"></ion-icon></button></a> -->
        </div>
    </section>
    <div class="ExaminationTable">
    <table >
        <thead>
            <th>SI NO</th>
            <th>Hall Name</th>
            <th>Capacity</th>
            <th>No Of Students</th>
            <th>Invigilator</th>
            <th>Action</th>
        </thead>
        <tbody>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        $i += 1;
        ?>
        <tr >
            <td><?=$i?></td>
            <td ><?=$row['ClassName']?></td>
            <td><?=(($row['ClassRows']>$row['MaxRow'])?$row['ClassRows']:$row['MaxRow'])*(($row['ClassColumns']>$row['MaxColumn'])?$row['ClassColumns']:$row['MaxColumn'])?></td>
            <td ><?=$row['TotalStudents']?></td>
            <td ><?=$row['invi_name']?></td>
            <td style='width:300px'>
                <div style='display:flex;gap:10px;margin:0 auto;width:fit-content;'>
                    <!-- <a href='ClassRoomDetails.php?ExamID=<?=$ExamID?>&ExamDate=<?=$ExamDate?>&Class=<?=$row['ClassID']?>'>
                        <button id='view'>Seating <i class="fa-solid fa-users"></i></button>
                    </a> -->
                    <!-- <a href='../../pdf_works/Seating.php?ExamID=<?=$_GET['ExamID']?>&ExamDate=<?=$ExamDate?>&Class=<?=$row['ClassID']?>'>
                        <button id='view'>Download <ion-icon name="arrow-forward-circle-outline"></ion-icon></button>
                    </a> -->
                    <a href='ExamHallDetails.php?ExamID=<?=$_GET['ExamID']?>&ExamDate=<?=$ExamDate?>&Class=<?=$row['ClassID']?>'>
                        <button id='view'>Details <ion-icon name="arrow-forward-circle-outline"></ion-icon></button>
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
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>