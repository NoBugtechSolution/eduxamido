<?php
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
  exit;
}
include('../Common/Connections.php');

// Get scheme_id from query string
$scheme_id = isset($_GET['scheme_id']) ? intval($_GET['scheme_id']) : 0;

// Fetch all departments (for dropdown)
$departments = [];
$res = $conn->query("SELECT * FROM departments ORDER BY department_name ASC");
while($row = $res->fetch_assoc()) {
    $departments[] = $row;
}

// Handle form submission
if(isset($_POST['add_course'])) {
    $department_id = intval($_POST['department_id']);
    $course_code = trim($_POST['course_code']);
    $course_name = trim($_POST['course_name']);
    $credits = intval($_POST['credits']);
    $semester = intval($_POST['semester']);
    $course_type = trim($_POST['course_type']);
    if($department_id && $course_code && $course_name && $credits && $semester && $course_type && $scheme_id) {
        $stmt = $conn->prepare("INSERT INTO courses (scheme_id, department_id, course_code, course_name, credits, semester, course_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iississ", $scheme_id, $department_id, $course_code, $course_name, $credits, $semester, $course_type);
        $stmt->execute();
        $stmt->close();
        header("Location: view_courses.php?id=" . urlencode($scheme_id));
        exit;
    }
    $error = "All fields are required.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Course</title>
    <link rel="stylesheet" href="../homescreen/styles.css?v=<?=time()?>">
    <style>
        body{background-color:rgb(212, 242, 253);}
        .main-content { padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .form-container { background:rgb(246, 246, 246); padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 8px #eee; min-width: 350px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 4px; }
        .form-group input, .form-group select { width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { padding: 6px 16px; border: none; border-radius: 4px; background:#0056b3; color: #fff; cursor: pointer; width:150px;}
        .btn:hover { background: #0056b3; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #495057; }
        .error { color: #dc3545; margin-bottom: 10px; }
    </style>
</head>
<body>
    <header class="header">
        <h1 class="title">Add New Course</h1>
    </header>
    <main class="main-content">
        <div class="form-container">
            <!-- <h3 style='text-align:center;margin:0px;font-size:20px'>New Course Details</h3> -->
            <?php if(isset($error)): ?>
                <div class="error"><?=htmlspecialchars($error)?></div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label>Department</label>
                    <select name="department_id" required>
                        <option value="">Select Department</option>
                        <?php foreach($departments as $dept): ?>
                            <option value="<?=$dept['department_id']?>" <?=($scheme_id && isset($_GET['department_id']) && $_GET['department_id'] == $dept['department_id']) ? 'selected' : ''?>><?=htmlspecialchars($dept['department_name'])?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Course Code</label>
                    <input type="text" placeholder='Enter the Course Code' name="course_code" required>
                </div>
                <div class="form-group">
                    <label>Course Name</label>
                    <input type="text" placeholder='Enter the Course Name' name="course_name" required>
                </div>
                <div class="form-group">
                    <label>Credits</label>
                    <input type="number" placeholder='Enter the Credits of the Course' name="credits" min="1" required>
                </div>
                <div class="form-group">
                    <label>Semester</label>
                    <input type="number" placeholder='Enter the Semester of the Course' name="semester" min="1" required>
                </div>
                <div class="form-group">
                    <label>Course Type</label>
                    <select name="course_type" required>
                        <option value="">Select Type</option>
                        <option value="Core">Core</option>
                        <option value="Elective">Elective</option>
                        <option value="Lab">Lab</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div style='display:flex;gap:30px'>
                    <a href="view_courses.php?id=<?=urlencode($scheme_id)?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" name="add_course" class="btn">Add Course</button>
                </div>
            </form>
        </div>
    </main>
    <footer class="footer">
        <p>2025 eduxamido. All Rights Reserved.</p>
    </footer>
</body>
</html>
