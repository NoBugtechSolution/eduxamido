<?php 
include("../Common/Connections.php");
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
}
if (isset($_GET['rollno'])) {
    $rollno = $_GET['rollno'];
    // Fetch student details
    $sql_student = "SELECT * FROM students_details INNER JOIN programmes ON programmes.programmes_id=students_details.programmes_id WHERE RollNo = '$rollno'";
    $result_student = mysqli_query($conn, $sql_student);
    $student = mysqli_fetch_assoc($result_student);
    
    // Fetch attended exams
    $sql_exams = "SELECT Subject.*,classroom.ClassName,Seat.class_row,Seat.class_col,examination.ExaminationName FROM exam_students as Student
                INNER JOIN examsubjects as Subject on Subject.examsubjectsID=Student.examsubjectsID
                INNER JOIN exam_stu_seating as Seat on Seat.exam_sub_stu_ID=Student.exam_sub_stu_ID
                INNER JOIN classroom ON classroom.ClassID=Seat.ClassID
                INNER JOIN examination ON examination.ExamID=Subject.ExamID
                WHERE Student.RollNo = '$rollno'";
    $result_exams = mysqli_query($conn, $sql_exams);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Student Details</title>
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

.container {
    margin-top: 50px;
    width: 80%;
    max-width: 1200px;
}

.details-wrapper, .exams-wrapper {
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 100%;
}

.btn-delete, .btn-edit {
    padding: 8px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
    border: none;
    cursor: pointer;
    margin-right: 10px;
}

.btn-delete {
    background-color: #dc3545;
    color: white;
}

.btn-edit {
    background-color: #28a745;
    color: white;
}

.btn-delete:hover {
    background-color: #c82333;
}

.btn-edit:hover {
    background-color: #218838;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

table th, table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
}

table th {
    background-color: #8282dd;
    color: white;
}

table tr:nth-child(odd) {
    background-color: #eaeaea;
}

table tr:hover {
    background-color: #ddd;
}

.text-center {
    text-align: center;
}

button {
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #2980b9;
}

input[type="text"], input[type="number"], input[type="file"], input[type="date"] {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    transition: border-color 0.3s ease;
    width: 100%;
    margin: 10px 0;
}

input[type="text"]:focus, input[type="number"]:focus, input[type="file"]:focus, input[type="date"]:focus {
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

.add-date-btn {
    background-color: #2ecc71;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    display: block;
    margin: 10px 0;
}

.add-date-btn:hover {
    background-color: #27ae60;
}

.supply-Table {
    width: 100%;
}

.supply-Table tr {
    background-color: #f3f3f3;
}

.supply-Table td {
    padding: 0px 30px 0px 20px;
}

.odds {
    background-color: #eeeeee !important;
}
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Student Details</h1>
        
        <?php if (isset($student)): ?>
            <div class="details-wrapper">
                <h2><?php echo $student['Name']; ?></h2>
                <p><strong>Roll No:</strong> <?php echo $student['RollNo']; ?></p>
                <p><strong>Date of Birth:</strong> <?php echo $student['stud_dob']; ?></p>
                <p><strong>Program:</strong> <?php echo $student['programmes_name']; ?></p>
                <p><strong>Academic Year:</strong> <?php echo $student['AcademicYear']; ?></p>
                <button class="btn-edit" onclick="editStudent('<?php echo $student['RollNo']; ?>')">Edit</button>
                <button class="btn-delete" onclick="deleteStudent('<?php echo $student['RollNo']; ?>')">Delete</button>
            </div>

            <div class="exams-wrapper">
                <h3>Attended Exams</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Exam Name</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_exams) > 0): ?>
                            <?php while ($exam = mysqli_fetch_assoc($result_exams)): ?>
                                <?php if ($exam['ExamStatus'] == 1): ?>
                                    <tr>
                                        <td><?php echo $exam['ExamName']; ?></td>
                                        <td><?php echo $exam['ExamDate']; ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center">No exams yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No student found.</p>
        <?php endif; ?>
    </div>

    <script>
        function deleteStudent(rollno) {
            if (confirm('Are you sure you want to delete this student?')) {
                window.location.href = 'delete_student.php?rollno=' + rollno;
            }
        }

        function editStudent(rollno) {
            window.location.href = 'edit_student.php?rollno=' + rollno;
        }
    </script>
</body>
</html>