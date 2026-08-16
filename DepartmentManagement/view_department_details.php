<?php
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
  exit;
}
include('../Common/Connections.php');

$department = null;
if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM departments WHERE department_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $department = $result->fetch_assoc();
    $stmt->close();
}
if(!$department) {
    echo '<p style="color:red;">Department not found.</p>';
    echo '<a href="department_manage.php" class="btn btn-secondary">Back</a>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Department Details</title>
    <link rel="stylesheet" href="../homescreen/styles.css?v=<?=time()?>">
    <style>
        body { color: white; }
        .main-content { padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .view-container { background:rgb(122, 184, 250); padding: 24px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px #eee; min-width: 320px; }
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
        <h1 class="title">Department Details</h1>
    </header>
    <main class="main-content">
        <div class="view-container">
            <div class="view-row"><span class="view-label">Department Name:</span> <?=htmlspecialchars($department['department_name'])?></div>
            <div class="view-row"><span class="view-label">Department Code:</span> <?=htmlspecialchars($department['department_scode'])?></div>
        </div>
        <a href="department_manage.php" class="btn btn-secondary">Back</a>
    </main>
    <footer class="footer">
        <p>2025 eduxamido. All Rights Reserved.</p>
    </footer>
</body>
</html>
