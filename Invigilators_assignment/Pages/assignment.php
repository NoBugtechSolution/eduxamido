<?php 
include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
if(!isset($_SESSION['ExaminationID'])){
    include('../../Common/ExaminationError.php');  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title>Assignments</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
            color: #343a40;
        }
        .date-heading {
            font-size: 1.8rem;
            color: #495057;
            font-weight: bold;
            text-align: left;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        .class-box {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            padding: 20px;
            transition: all 0.3s ease;
            text-align: center;
            /* margin-bottom: 30px; */
        }
        .class-box:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        .class-id {
            font-size: 1.5rem;
            font-weight: 600;
            color: #007bff;
            margin-bottom: 10px;
        }
        .invigilator-id {
            font-size: 1.2rem;
            font-weight: 500;
            color: #6c757d;
        }
        .invigilator-name {
            font-size: 1.2rem;
            font-weight: 500;
            color: #495057;
        }
        .no-invigilator {
            font-size: 1.2rem;
            color: #dc3545;
            font-weight: bold;
        }
        .datedata{
            display:grid;
            width:100%;
            grid-template-columns: 1fr 1fr;
            gap:10px;
            margin-bottom:20px;
        }
        button{
            padding: 3px 6px 3px 6px;
            border-radius: 4px;
            transition: border-color 0.3s ease;
            border-color: #3498db;
            outline: none;
        }
        button {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Assignments for the Examination</h1>
        
            <?php
            $Eid=$_SESSION['ExaminationID'];
            $datesArray=[];
            $selectdate="SELECT DISTINCT(ExamDate),session FROM `examsubjects` WHERE ExamID=$Eid ";
            $datedetails=$conn->query($selectdate);

            echo "<span style='color:green;'>Assignments updated successfully for all dates.</span> ";
            echo "<a href='../../ExamManagement/ExamCreate/FinalOutput.php'> <button type='button' >Next</button></a>";


            // Display assignments
            while($datedetailsrow=$datedetails->fetch_assoc()){
                $date=$datedetailsrow['ExamDate'];
                $Session=$datedetailsrow['session'];
                $classselect="SELECT * FROM classroom WHERE ClassID IN (SELECT DISTINCT(exam_stu_seating.ClassID) FROM `exam_stu_seating` 
                INNER JOIN exam_students on exam_students.exam_sub_stu_ID=exam_stu_seating.exam_sub_stu_ID
                INNER JOIN examsubjects on examsubjects.examsubjectsID=exam_students.examsubjectsID
                INNER JOIN examination ON examination.ExamID=examsubjects.ExamID
                WHERE examsubjects.ExamDate='$date' AND examsubjects.session='$Session' AND examination.ExamID=$Eid)";
                $classrooms=$conn->query($classselect);
               
                echo "<div class='date-heading'> $date  ".(($Session=="AM")?"Morning Session":"Afternoon Session")."</div><div class='datedata'>";

                while ($classroom=$classrooms->fetch_assoc()) {
                    $class=$classroom['ClassID'];
                    $sql = "SELECT a.ClassID, a.inv_id, i.invi_name 
                            FROM assignment a 
                            JOIN invigilators i ON a.inv_id = i.invid 
                            WHERE a.a_exam_date = '$date' AND a.ClassID = $class
                            AND a.session='$Session'
                            LIMIT 1";
                    $result = $conn->query($sql);

                    if (!$result) {
                        die("Error fetching assignments: " . mysqli_error($conn));
                    }

                    $assignment = $result->fetch_assoc();

                    echo "<div class='class-box'>";
                    echo "<div class='class-id'>Classroom: {$classroom['ClassName']}</div>";

                    if ($assignment) {
                        echo "<div class='invigilator-id'>Invigilator ID: " . $assignment['inv_id'] . "</div>";
                        echo "<div class='invigilator-name'>Name: " . $assignment['invi_name'] . "</div>";
                    } else {
                        echo "<div class='no-invigilator'>No invigilator assigned</div>";
                    }

                    echo "</div>";
                }
                echo "</div>";
            }
        
        ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
