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
    $stmt = $conn->prepare("SELECT c.*, d.department_name FROM courses c LEFT JOIN departments d ON c.department_id = d.department_id WHERE c.course_id=?");
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Course</title>
    <link rel="stylesheet" href="../homescreen/styles.css?v=<?=time()?>">
    <style>
        body{background-color:rgb(212, 242, 253);}
        .main-content { padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .view-container { background: #fff; padding: 24px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px #eee; min-width: 320px; }
        .view-row { margin-bottom: 14px; }
        .view-label { font-weight: bold; display: inline-block; width: 140px; }
        .btn { padding: 6px 16px; border: none; border-radius: 4px; background: #007bff; color: #fff; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #0056b3; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #495057; }
    </style>
</head>
<body>
    <header class="header">
        <h1 class="title">View Course</h1>
    </header>
    <main class="main-content">
        <div class="view-container">
            <div class="view-row"><span class="view-label">Department:</span> <?=htmlspecialchars($course['department_name'])?></div>
            <div class="view-row"><span class="view-label">Course Code:</span> <?=htmlspecialchars($course['course_code'])?></div>
            <div class="view-row"><span class="view-label">Course Name:</span> <?=htmlspecialchars($course['course_name'])?></div>
            <div class="view-row"><span class="view-label">Credits:</span> <?=htmlspecialchars($course['credits'])?></div>
            <div class="view-row"><span class="view-label">Semester:</span> <?=htmlspecialchars($course['semester'])?></div>
            <div class="view-row"><span class="view-label">Type:</span> <?=htmlspecialchars($course['course_type'])?></div>
             <a href="javascript:history.back()" class="btn btn-secondary">Back</a>
        </div>
       
    </main>
    <!-- <footer class="footer">
        <p>2025 eduxamido. All Rights Reserved.</p>
    </footer> -->
</body>
</html>
