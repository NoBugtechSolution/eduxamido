<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title>Students</title>
    <link rel="stylesheet" href="style.css">
    <?php
    if(!isset($_GET['ExamID'])){
        die("Somthing Missing");
    }
     $_SESSION['ExaminationID']=$_GET['ExamID'];
    ?>
</head>
<body>
<?php 
    ?>
    <h1>Students Data</h1>
    <form action="StudentsDetails.php" method="post" enctype="multipart/form-data">
        <label for="StudentsData">Students Data</label>
        <input type="file" id="StudentsData" name="file_upload"  required>
        <input type="submit" value="Submit">
    </form>
</body>
</html>