<?php
include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
if(!isset($_SESSION['ExaminationID'])){
    include('../../Common/ExaminationError.php'); 
}

$classsql="SELECT * FROM classroom";
    $classData=$conn->query($classsql);
$classValues=[];
    while($temp=$classData->fetch_assoc()){
        $classValues[]=$temp;
    }

    $Eid=$_SESSION['ExaminationID'];
        

    $dategetsql="SELECT DISTINCT(ExamDate),session FROM `examsubjects` WHERE ExamID=$Eid";
    $ExamDates=$conn->query($dategetsql);


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
            max-width: fit-content;
            width: 100%;
            margin-bottom: 20px;
            display:grid;
            place-items:center;
            gap:50px;
            <?php if($ExamDates->num_rows>3){$val=3;}else{$val=$ExamDates->num_rows;} echo "grid-template-columns: repeat($val,1fr);";?>
            
            place-items:center;
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
            grid-column: span <?php echo $val; ?>;
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
        .classdetails{
            height: fit-content;
            gap:15px;
            display:grid;
            place-items:center;
            grid-template-columns: 1fr 1fr 1fr;
            padding-bottom:10px;
        }
        .classdetails>div{
            width: 120px;
            height: fit-content;
            background-color:#eeeeee;
            text-align:center ;
            font-size:22px;
            padding: 4px;
            padding-top:20px;
            padding-bottom:20px;
            border-radius:10px;
            
            border:3px solid transparent;
            cursor: pointer;
            transform:scale(1);
            transition:.3s;
        }
        .classdetails>div:hover{
            background-color:#dedede;
            transform:scale(1.03);
        }
        .classdetails>.active{
            border:3px solid #333333;
            background-color:#3f98df !important;
            color:white;
        }
        h5,h6{
            margin:0;
        }
        h6{
            margin-top:6px;
            font-size:13px;
        }
        .classheader{
            display:flex;
            justify-content:space-between;
        }
        .Selects{
            border:2px solid black;
            padding:16px;
            border-radius:10px;
        }
    </style>
</head>
<body>
    <?php
// if(isset($_POST['classes'])){
//     $i=0;
//     $classare=$_POST['classes'];
//     foreach ($_POST['dates'] as $datesv) {
//         $updateClass="UPDATE `exam_date` SET Classes='".$classare[$i]."' WHERE Date='$datesv'";
//         $conn->query($updateClass);
//         $i++;
//     }
//     // header("Location: ExamPage4.php");
// }

    echo"<script>
    datearray1=[];
    datecollection=[];
    capacities=[];
    classdetails=[];
    ";
    $datesv=[];
    while($newdate=$ExamDates->fetch_assoc()){
        
        $tempDate=$newdate['ExamDate'].$newdate['session'];
        

        $dateID=$newdate['ExamDate'];
        
        
        $terCount=0;
            $Tsql="SELECT Count(*) as Totals FROM `examsubjects` 
            INNER JOIN exam_students on exam_students.examsubjectsID=examsubjects.examsubjectsID
            INNER JOIN students_details on students_details.RollNo=exam_students.RollNo
            WHERE  examsubjects.ExamID=$Eid AND examsubjects.ExamDate='$dateID' AND session='".$newdate['session']."'";
            // echo "console.log(` $Tsql`)";
        $countsql=$conn->query($Tsql)->fetch_assoc();
        $terCount+= $countsql['Totals'];
        $newdate['Totals']="(0/$terCount)";
        $newdate['Totalval']=$terCount;
        $datesv[]=$newdate;
        echo"datearray1['$tempDate']=[];
        datearray1['$tempDate'][0]=0;
        datearray1['$tempDate'][1]=$terCount;
        datecollection.push('$tempDate');
        capacities.push(false);
        ";
    }
    echo "</script>";
    ?>
    <h3>Select Class</h3>
    <form  method="post"  onsubmit="return checkData()" action='SeatingAlgorithm.php'>
        <?php 
        
        
        foreach($datesv as $newdate){ $qwe=0;
            $AlreadyClass="SELECT DISTINCT(exam_stu_seating.ClassID) FROM examsubjects
        INNER JOIN exam_students ON exam_students.examsubjectsID=examsubjects.examsubjectsID
        INNER JOIN exam_stu_seating ON exam_stu_seating.exam_sub_stu_ID=exam_students.exam_sub_stu_ID
        WHERE NOT examsubjects.ExamID=$Eid AND examsubjects.ExamDate='".$newdate['ExamDate']."' AND examsubjects.session='".$newdate['session']."'";
        // echo$AlreadyClass;
        $ClassesNot=$conn->query($AlreadyClass);
        $NotclassValues=[];
        while($temp=$ClassesNot->fetch_assoc()){
                $NotclassValues[]=$temp['ClassID'];
        }
        echo "<script>classdetails['".$newdate['ExamDate'].$newdate['session']."']=[];</script> ";
            ?>
        
        <div id='S<?php echo $newdate['ExamDate'].$newdate['session'] ?>' class="Selects">
        <input  type="hidden" name="classes[]" id="IN<?php echo $newdate['ExamDate'].$newdate['session'] ?>">
        <input  type="hidden" name="dates[]" value="<?php echo $newdate['ExamDate'].$newdate['session'] ?>">
        <h6 class='classheader' style="margin-left:10px;margin-bottom:10px; font-size:16px;"><b><?php echo $newdate['ExamDate'].(($newdate['session']=="AM")?" Morning Session":" Afternoon Session") ?></b><b style="color:red;" id="To<?php echo $newdate['ExamDate'].$newdate['session'] ?>"><?php echo $newdate['Totals'];?></b></h6>
            <div class="classdetails">
                <?php foreach($classValues as $rows){
                    if(in_array($rows['ClassID'],$NotclassValues)){
                        ?>
                        <div style='background-color:red;color:white'>
                            <h5><?php echo $rows['ClassName'] ?></h5>
                            <h6><b >Not Available</b></h6>
                        </div>
                        <?php
                        continue;
                    }
                    ?>
                <div onclick="select(this,'<?php echo $newdate['ExamDate'].$newdate['session'] ?>',<?php echo $rows['ClassID'] ?>)"  <?php  if($qwe<$newdate['Totalval']){echo"class='active'";}?>>
                    <h5><?php echo $rows['ClassName'] ?></h5>
                    <?php if($qwe<$newdate['Totalval']){
                        echo "<script>classdetails['".$newdate['ExamDate'].$newdate['session']."'].push(".$rows['ClassID'].")</script> ";
                        $qwe+=$rows['ClassRows']*$rows['ClassColumns'];
                    }?>
                    <h6>Total Capacity: <b><?php echo $rows['ClassRows']*$rows['ClassColumns']; ?></b></h6>
                    <!-- <input  <?php  if($qwe<$newdate['Totalval']){echo"checked";}?> type="checkbox" name="" id=""> -->
                </div>
                <?php }
                    echo"<script> 
                    datearray1['".$newdate['ExamDate'].$newdate['session']."'][0]=$qwe;";
                    echo"</script>";
                ?>
            </div>
        </div>
        <?php }?>
        
        
        <input type="submit" value="Submit">
    </form>
    <script>
        
        function select(object,Date,ClassID){
            collectvalue=datecollection.indexOf(Date);
            capb=object.querySelectorAll('b');
            console.log(classdetails)
            capacity=capb[0].innerHTML;
            if(object.classList.contains('active')){
                object.classList.remove('active');
                datearray1[Date][0]-=parseInt(capacity);

                const index = classdetails[Date].indexOf(ClassID);
                if (index > -1) {
                    classdetails[Date].splice(index, 1);
                }
            }else{
                object.classList.add('active');
                datearray1[Date][0]+=parseInt(capacity);
                classdetails[Date].push(ClassID);
            }
           
            totoldisplay=document.getElementById("To"+Date);
            if(datearray1[Date][0]<datearray1[Date][1]){
                totoldisplay.style.color="red"
                capacities[collectvalue]=false;
            }else{
                totoldisplay.style.color="green"
                capacities[collectvalue]=true
            }
            inputDate(Date)
            totoldisplay.innerHTML=`(${datearray1[Date][0]}/${datearray1[Date][1]})`;
        }
        function checkData(){
            for(i=0;i<capacities.length;i++){
                if(capacities[i]==false){
                    console.log(capacities[i])
                    return false;
                }
            }
            
            return true;
        }
        activevalue();
        function activevalue(){
            for (let Date of datecollection) {
                collectvalue=datecollection.indexOf(Date);
                totoldisplay=document.getElementById("To"+Date);
                if(datearray1[Date][0]<datearray1[Date][1]){
                totoldisplay.style.color="red"
                capacities[collectvalue]=false;
            }else{
                totoldisplay.style.color="green"
                capacities[collectvalue]=true
            }
            totoldisplay.innerHTML=`(${datearray1[Date][0]}/${datearray1[Date][1]})`;
            }
        }

        function inputDate(Date){
            inputbox=document.getElementById("IN"+Date);
            inputbox.value=classdetails[Date];
        }
    </script>
</body>
</html>