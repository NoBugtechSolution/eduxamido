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
    $qry = "SELECT *,(SELECT COUNT(*) FROM exam_students WHERE examsubjectsID=Subject.examsubjectsID) as StudentCount FROM examsubjects as Subject INNER JOIN courses ON courses.course_id=Subject.course_id WHERE ExamID = $ExamID AND ExamDate = '$ExamDate'";
    $result = mysqli_query($conn, $qry);
    $i=0;
?>
    <section id='header'>
        <a style='display:flex;gap:10px;width:300px;'href='ViewExamDates.php?ExamID=<?=$_GET['ExamID']?>'><ion-icon name="arrow-back-outline" id='back'></ion-icon></a>
        <h1 id='heading'><?=$ExamDate?></h1>
        <div style='display:flex;gap:10px;width:300px;'>
            <button id='add'>Add <ion-icon name="add-circle-outline"></ion-icon></button>
            <a href="../../pdf_works/Seating.php?ExamID=<?=$_GET['ExamID']?>&ExamDate=<?=$ExamDate?>"><button id='download'>Download<ion-icon name="cloud-download-outline"></ion-icon></button></a>
        </div>
    </section>
    <div class="ExaminationTable">
    <table >
        <thead>
            <th>SI NO</th>
            <th class='SubjectName'>Subject Code</th>
            <th class='SubjectName'>Subject Name</th>
            <th>No. of Students</th>
            <th>Action</th>
        </thead>
        <tbody>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        $i += 1;
        ?>
        <tr >
            <td><?=$i?></td>
            <td class='SubjectName'><?=$row['course_code']?></td>
            <td class='SubjectName'><?=$row['course_name']?></td>
            <td><?=$row['StudentCount']?></td>
            <td style='width:300px'>
                <div style='display:flex;gap:10px;margin:0 auto;width:fit-content;'>
                    <a href='ClassRoomDetails.php?SubjectID=<?=$row['examsubjectsID']?>&ExamID=<?=$_GET['ExamID']?>&ExamDate=<?=$row['ExamDate']?>&Session=<?=$row['session']?>'>
                        <button id='view'>Seating <i class="fa-solid fa-users"></i></button>
                    </a>
                    <a href='ExamSubjectsDetails.php?SubjectID=<?=$row['examsubjectsID']?>&ExamID=<?=$_GET['ExamID']?>&ExamDate=<?=$row['ExamDate']?>'>
                        <button id='view'>View <ion-icon name="arrow-forward-circle-outline"></ion-icon></button>
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