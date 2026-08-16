<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <!--  -->
    <title>Examination</title>
    <link rel="stylesheet" href="m_e_pg1.css?v=<?=time()?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<?php


include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
    

    $sql = "SELECT * FROM examination ORDER BY ExamID DESC";
    $result = mysqli_query($conn, $sql);
    ?>
    <section id='header'>
        <a href="../../homescreen/"><ion-icon name="arrow-back-outline" id='back'></ion-icon></a>
        <h1 id='heading'>Examinations</h1>
        <a href='../ExamCreate/ExaminationDetails.php'><button id='create'>CREATE</button></a>
    </section>
    <div class="ExaminationTable">
    <table >
        <thead>
            <th>SI NO</th>
            <th class='ExamName'>Examination Name</th>
            <th>Action</th>
        </thead>
        <tbody>
    <?php
    $i=1;
    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
            ?>
            <tr>
                <td><?=$i?></td>
                <td class='ExamName'><?=$row['ExaminationName']?></td>
                <td style='width:300px;'>
                    <div style='width:fit-content;display:flex;gap:10px;margin:0 auto;'>
                        
                        <a href='ClassRoomDetails.php?ExamID=<?=$row['ExamID']?>'>
                            <button id='view'>Seating<i class="fa-solid fa-users"></i> </button>
                        </a>
                        <a href='ViewExamDates.php?ExamID=<?=$row['ExamID']?>'>
                            <button id='view'>View<ion-icon name="arrow-forward-circle-outline"></ion-icon></button>
                        </a>
                    </div>
                </td>
            </tr>
            <?php
            $i++;
        }
    }else{
        echo"<tr><td colspan='3'>No Data</td></tr>";
    }
    ?>
    </tbody>
    </table>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>