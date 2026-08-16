<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--  -->
    <title>Examination</title>
    <link rel="stylesheet" href="m_e_pg2.css?t=<?=time()?>">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<?php

include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_GET['ExamID'])){
    include('../../Common/ExaminationError.php'); 
}
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}

    $ExamID = $_GET['ExamID'];
    $qry = 'SELECT * FROM examination WHERE ExamID = "'.$ExamID.'"';
    $result = mysqli_query($conn, $qry);
    $row = mysqli_fetch_assoc($result);
?>
    <section id='header'>
        <a style='width:100px;'href='ViewExams.php'><ion-icon name="arrow-back-outline" id='back'></ion-icon></a>
        <h1 id='heading'><?=$row['ExaminationName']?></h1>
        <a href="EditData/StudentsDetailsUpload.php?ExamID=<?=$ExamID?>"><button  id='create'>ADD<ion-icon name="add-circle-outline"></ion-icon></button></a>
    </section>
<?php
    $sql = "SELECT DISTINCT(ExamDate) FROM examsubjects WHERE ExamID=$ExamID";
    $result1 = mysqli_query($conn, $sql);
    $ku=1;
    if($result1->num_rows==0){
        echo "<center><h1 style='color:red;'>No Dates Found</h1></center>";
        die;
    }
    echo "<section id='grid'>";
    while($row=$result1->fetch_assoc()){
        ?>
        <div id='box'>
            <div class='headerDetails'>
                <h4 id='examsubid'><?=$ku?>.</h4>
                <h4 id='examdate'><?=$row['ExamDate']?></h4>
            </div>
            <div class='dateOptions'>
                <a href='ExamAssignedInvigilator.php?ExamID=<?=$ExamID?>&ExamDate=<?=$row['ExamDate']?>'><button id='inv'><ion-icon name="people-outline"></ion-icon><span>Invigilator</span></button></a>
                <a href='ExamSubjects.php?ExamID=<?=$ExamID?>&ExamDate=<?=$row['ExamDate']?>'><button id='view'><ion-icon name="book-outline"></ion-icon><span>Subjects</span></button></a>
                <a href='ClassRoomDetails.php?ExamID=<?=$ExamID?>&ExamDate=<?=$row['ExamDate']?>'><button id='view'><i class="fa-solid fa-users"></i><span>Seatings</span></button></a>
                <a href='ExamHall.php?ExamID=<?=$ExamID?>&ExamDate=<?=$row['ExamDate']?>'><button id='view'><i class="fa-solid fa-chalkboard"></i><span>ExamHall</span></button></a>
            </div>
        </div>
        <?php
        $ku++;
    }
    echo "</section>";
?>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>