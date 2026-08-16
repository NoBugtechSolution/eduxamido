<?php
include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title>EXAM</title>
    <link rel="stylesheet" href="style.css?t=<?=time()?>">
</head>
<body>
    <h1>Examination Date</h1>
    <form  method="post" onsubmit='return checkOption()'>
        <label for="ExamName">Examination Name</label>
        <input type="text" id="ExamName" name="ExamName" placeholder="Examination Name" required>
        <label for="ExamName">Schemes</label>
        <select name="scheme" id="schemeData" required>
            <option value="0" disabled selected>Select an Option</option>
            <?php
            $schemesSql="SELECT * FROM `schemes`";
            $schemes=$conn->query($schemesSql);
            while($row=$schemes->fetch_assoc()){
                echo "<option value='".$row['scheme_id']."'>".$row['scheme_name']."</option>";
            }
            ?>
            
        </select>
        <label for="ExamName">Academic Year</label>
        <input type="text" id="AcademicYear" name="AcademicYear" placeholder="Ex: 2021-24" required>
        <input type="submit" value="Submit" name="Exams">
        <h6>or</h6>
        <a href="viewselected.php">View Created</a>
    </form>
    <script>
        function checkOption(){
            if((document.getElementById("schemeData").value)=="0"){
                alert("Select a scheme")
                return false;
            }
            return true;
        }
    </script>

    <?php
    if(isset($_POST['Exams'])){
        $ExName=$_POST['ExamName'];
        $AcYear=$_POST['AcademicYear'];
        $scheme=$_POST['scheme'];
        $examination="INSERT INTO `examination`( `ExaminationName`,`scheme_id`, `AcademicYear`,`Status`) VALUES ('$ExName','$scheme','$AcYear','-4')";
        $conn->query($examination);
        $selectExam="SELECT MAX(ExamID) as MaxData FROM examination";
        $examid=$conn->query($selectExam)->fetch_assoc();
        $_SESSION['ExaminationID']=$examid['MaxData'];
        header("Location: StudentsDetailsUpload.php");
    }
    ?>
</body>
</html>