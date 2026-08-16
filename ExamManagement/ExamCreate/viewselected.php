<?php
include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
$selectExam="SELECT * FROM examination";
    $examdetails=$conn->query($selectExam);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title>EXAM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            color: #2c3e50;
        }
        form {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        select,input{
            display: block;
            margin: 10px 0;
            width: 100%;
        }
        select{
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s ease;
        }
        select:focus {
            border-color: #3498db;
            outline: none;
        }
        input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <h1>Examination Date</h1>
    <form method="post">
        <select name="selectexamination" id="">
            <?php
            while($data=$examdetails->fetch_assoc()){
            ?>
            <option value="<?php echo $data['ExamID'] ?>"><?php echo $data['ExaminationName'] ?></option>

            <?php
            }
            ?>
        </select>
        <input type="submit" value="Submit">
    </form>
    <?php
    if(isset($_POST['selectexamination'])){
        $Eid=$_POST['selectexamination'];
        $_SESSION['ExaminationID']=$Eid;
        $selectExam="SELECT * FROM examination WHERE ExamID=$Eid";
        $examdetails=$conn->query($selectExam)->fetch_assoc();
        if($examdetails['Status']==-4){
            header("Location: StudentsDetailsUpload.php");
        }elseif($examdetails['Status']==-2){
            header("Location: ClassSelect.php");
        }elseif($examdetails['Status']==-1){
            header("Location: ExaminationSeatings.php");
        }else{
            header("Location: finaloutput.php");
        }
    }
    ?>
</body>
</html>