<?php
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
  exit;
}
include('../Common/Connections.php');

$course = null;
if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM courses WHERE course_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();
    $stmt->close();
}
if(!$course) {
    echo '<p style="color:red;">Course not found.</p>';
    echo '<a href="scheme_manage.php" class="btn btn-secondary">Back</a>';
    exit;
}

// Fetch all departments for dropdown
$departments = [];
$res = $conn->query("SELECT * FROM departments ORDER BY department_name ASC");
while($row = $res->fetch_assoc()) {
    $departments[] = $row;
}

// Handle update
if(isset($_POST['update_course'])) {
    $course_id = intval($_POST['course_id']);
    $department_id = intval($_POST['department_id']);
    $course_code = trim($_POST['course_code']);
    $course_name = trim($_POST['course_name']);
    $credits = intval($_POST['credits']);
    $semester = intval($_POST['semester']);
    $course_type = trim($_POST['course_type']);
    if($department_id && $course_code && $course_name && $credits && $semester && $course_type) {
        $stmt = $conn->prepare("UPDATE courses SET department_id=?, course_code=?, course_name=?, credits=?, semester=?, course_type=? WHERE course_id=?");
        $stmt->bind_param("ississi", $department_id, $course_code, $course_name, $credits, $semester, $course_type, $course_id);
        $stmt->execute();
        $stmt->close();
        // Try to get scheme_id from GET or POST for redirect
        $scheme_id = isset($_GET['scheme_id']) ? intval($_GET['scheme_id']) : 0;
        if (!$scheme_id && isset($_POST['scheme_id'])) {
            $scheme_id = intval($_POST['scheme_id']);
        }
        if ($scheme_id) {
            header("Location: view_scheme.php?id=" . urlencode($scheme_id));
        } else {
            header("Location: scheme_manage.php");
        }
        exit;
    }
    $error = "All fields are required.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Course</title>
    <link rel="stylesheet" href="../homescreen/styles.css?v=<?=time()?>">
    <style>
        body{background-color:rgb(212, 242, 253);}
        .main-content { padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .form-container { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px #eee; min-width: 350px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 4px; }
        .form-group input, .form-group select { width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { padding: 6px 16px; border: none; border-radius: 4px; background: #007bff; color: #fff; cursor: pointer; width:150px }
        .btn:hover { background: #0056b3; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #495057; }
        .error { color: #dc3545; margin-bottom: 10px; }
    </style>
</head>
<body>
    <header class="header">
        <h1 class="title">Edit Course</h1>
    </header>
    <main class="main-content">
        <div class="form-container">
            <?php if(isset($error)): ?>
                <div class="error"><?=htmlspecialchars($error)?></div>
            <?php endif; ?>
            <form method="post">
                <input type="hidden" name="course_id" value="<?=htmlspecialchars($course['course_id'])?>">
                <div class="form-group">
                    <label>Department</label>
                    <select name="department_id" required>
                        <option value="">Select Department</option>
                        <?php foreach($departments as $dept): ?>
                            <option value="<?=$dept['department_id']?>" <?=($course['department_id']==$dept['department_id'])?'selected':''?>><?=htmlspecialchars($dept['department_name'])?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Course Code</label>
                    <input type="text" name="course_code" required value="<?=htmlspecialchars($course['course_code'])?>">
                </div>
                <div class="form-group">
                    <label>Course Name</label>
                    <input type="text" name="course_name" required value="<?=htmlspecialchars($course['course_name'])?>">
                </div>
                <div class="form-group">
                    <label>Credits</label>
                    <input type="number" name="credits" min="1" required value="<?=htmlspecialchars($course['credits'])?>">
                </div>
                <div class="form-group">
                    <label>Semester</label>
                    <input type="number" name="semester" min="1" required value="<?=htmlspecialchars($course['semester'])?>">
                </div>
                <div class="form-group">
                    <label>Course Type</label>
                    <select name="course_type" required>
                        <option value="">Select Type</option>
                        <option value="Core" <?=($course['course_type']==='Core')?'selected':''?>>Core</option>
                        <option value="Elective" <?=($course['course_type']==='Elective')?'selected':''?>>Elective</option>
                        <option value="Lab" <?=($course['course_type']==='Lab')?'selected':''?>>Lab</option>
                        <option value="Other" <?=($course['course_type']==='Other')?'selected':''?>>Other</option>
                    </select>
                </div>
                <div style='display:flex;gap:30px'>
                    <a href="javascript:history.back()" class="btn btn-secondary">Cancel</a>
                    <button type="submit" name="update_course" class="btn">Update Course</button>
                </div>
            </form>
        </div>
    </main>
    <footer class="footer">
        <p>2025 eduxamido. All Rights Reserved.</p>
    </footer>
</body>
</html>
