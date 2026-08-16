<?php 
include("../Common/Connections.php");
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
}
if (isset($_POST['update'])) {
    $rollno = $_POST['rollno'];
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $programmes_id = $_POST['programmes_id'];
    $academicYear = $_POST['academic_year'];

    // Update student details
    $sql_update = "UPDATE students_details SET Name='$name', stud_dob='$dob', programmes_id='$programmes_id', AcademicYear='$academicYear' WHERE RollNo='$rollno'";
    mysqli_query($conn, $sql_update);
    header("Location: student_details.php?rollno=$rollno");
}

if (isset($_GET['rollno'])) {
    $rollno = $_GET['rollno'];
    $sql_student = "SELECT * FROM students_details WHERE RollNo = '$rollno'";
    $result_student = mysqli_query($conn, $sql_student);
    $student = mysqli_fetch_assoc($result_student);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Edit Student</title>
    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            margin-top: 50px;
        }
        .edit-wrapper {
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-update {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            padding: 10px 20px;
        }
        .btn-update:hover {
            background-color: #218838;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input,select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Edit Student Details</h1>
        <div class="edit-wrapper">
            <form method="POST" action="" onsubmit="return confirmUpdate()">
                <input type="hidden" name="rollno" value="<?php echo $student['RollNo']; ?>">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="name" value="<?php echo $student['Name']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Date of Birth:</label>
                    <input type="date" name="dob" value="<?php echo $student['stud_dob']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Program:</label>
                    <select name="programmes_id" id="" required>
                        <?php 
                            $outV=$conn->query("SELECT * FROM programmes");
                            while($row = $outV->fetch_assoc()){
                                $selected = ($row['programmes_id'] == $student['programmes_id']) ? 'selected' : '';
                                echo "<option value='{$row['programmes_id']}' $selected>{$row['programmes_name']}</option>";
                            }
                        ?>
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Academic Year:</label>
                    <input type="text" name="academic_year" value="<?php echo $student['AcademicYear']; ?>" required>
                </div>
                <button type=" submit" name="update" class="btn-update">Update</button>
            </form>
        </div>
    </div>
    <script>
        function confirmUpdate() {
            return confirm("Are you sure you want to update the student details?");
        }
    </script>
</body>
</html>