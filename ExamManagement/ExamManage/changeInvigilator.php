


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title>Change Invigilator</title>
    <link rel="stylesheet" href="inv_pg1.css?v=<?=time()?>">
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

    $invid=$_GET['Inv'];
    $assign=$_GET['assign'];
    $selectInv="SELECT * FROM `invigilators` 
    LEFT JOIN assignment 
    ON assignment.inv_id = invigilators.invid 
    AND assignment.a_exam_date = '$ExamDate'
    WHERE assignment.inv_id IS NULL OR invigilators.invid='$invid';";
    $Invs=$conn->query($selectInv);
?>
<form action="saveData.php" method="post">
    <section id='header'>
    <a href='ExamAssignedInvigilator.php?ExamID=<?=$ExamID?>&ExamDate=<?=$ExamDate?>'><ion-icon name="arrow-back-outline" id='back'></ion-icon></a>
    <h1 id='heading'> Change Invigilator</h1>
    <button id='download' type='submit'>Save <ion-icon name="cloud-upload-outline"></ion-icon></ion-icon></button>
    </section>
    
        <input type="hidden" name='assign' value='<?=$assign?>'>
        <input type="hidden" name='ExamID' value='<?=$ExamID?>'>
        <input type="hidden" name='ExamDate' value='<?=$ExamDate?>'>
        <input type="hidden" name='OldInv' value='<?=$invid?>'>
    <div class="ExaminationTable">
    <table >
        <thead>
            <th>SI NO</th>
            <th style='text-align:left;'> Name</th>
            <th style='text-align:left;'>Email</th>
            <th>Post</th>
            <th>Duty Count</th>
            <th>Status</th>
            <th>Action</th>
        </thead>
        <tbody>
    <?php
    $i = 0;
    while($row = $Invs->fetch_assoc()){
        $i += 1;
        ?>
        <tr class='<?=($row['invid']==$invid)?"selected":""?>'>
            <td style='padding:20px'><?= $i?></td>
            <td  style='text-align:left;'><?=$row['invi_name']?></td>
            <td style='text-align:left;'><?=$row['inviemail']?></td>
            <td><?=$row['invi_post']?></td>
            <td><?=$row['invi_duty_count']?></td>
            <td ><?=($row['invi_status']==1)?"<span class='rdy green'>Ready</span>":"<span class='rdy red'>Not Ready</span>"?></td>
            <td><input <?=($row['invid']==$invid)?"checked":""?> style='transform: scale(1.5);' onchange='selectAct(this)' value='<?=$row['invid']?>' type="radio" name="Inv" id=""></td>
        </tr>
        <?php
    }
?>
    </tbody>
    </table>
    </div>
    </form>
    <script>
        function selectAct(obj){
            document.querySelectorAll(".selected").forEach(element => {
                element.classList.remove("selected")
            });
            obj.parentNode.parentNode.classList.add("selected")
        }
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>