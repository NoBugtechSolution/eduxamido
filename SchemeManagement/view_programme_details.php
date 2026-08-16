<?php
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
  exit;
}
include('../Common/Connections.php');

// Get programme_id and scheme_id from query
if(!isset($_GET['programme_id']) || !isset($_GET['id'])) {
    echo '<p style="color:red;">Invalid request.</p>';
    echo '<a href="scheme_manage.php" class="btn btn-secondary">Back</a>';
    exit;
}
$programme_id = intval($_GET['programme_id']);
$scheme_id = intval($_GET['id']);

// Fetch programme details
$stmt = $conn->prepare("SELECT * FROM programmes WHERE programmes_id = ? AND scheme_id = ?");
$stmt->bind_param("ii", $programme_id, $scheme_id);
$stmt->execute();
$result = $stmt->get_result();
$programme = $result->fetch_assoc();
$stmt->close();

if(!$programme) {
    echo '<p style="color:red;">Programme not found.</p>';
    echo '<a href="view_programme.php?id='.urlencode($scheme_id).'" class="btn btn-secondary">Back</a>';
    exit;
}

// Fetch scheme name
$stmt = $conn->prepare("SELECT scheme_name FROM schemes WHERE scheme_id = ?");
$stmt->bind_param("i", $scheme_id);
$stmt->execute();
$stmt->bind_result($scheme_name);
$stmt->fetch();
$stmt->close();

// Fetch department name
$dept_name = 'Unknown';
if($programme['department_id']) {
    $stmt = $conn->prepare("SELECT department_name FROM departments WHERE department_id = ?");
    $stmt->bind_param("i", $programme['department_id']);
    $stmt->execute();
    $stmt->bind_result($dept_name);
    $stmt->fetch();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Programme Details</title>
    <link rel="stylesheet" href="../homescreen/styles.css?v=<?=time()?>">
    <style>
        body { color: white; background-color:rgb(212, 242, 253);}
        .main-content { padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .view-container { background-color:white;color:black; padding: 28px 32px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px #eee; min-width: 340px; }
        .view-row { margin-bottom: 18px; }
        .view-label { font-weight: bold; display: inline-block; width: 160px; }
        .btn { padding: 6px 16px; border: none; border-radius: 4px; background: #007bff; color: #fff; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #0056b3; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #495057; }
    </style>
</head>
<body>
    <header class="header">
        <h1 class="title">Programme Details</h1>
    </header>
    <main class="main-content">
        <div class="view-container">
            <div class="view-row"><span class="view-label">Programme Code:</span> <?=htmlspecialchars($programme['programmes_scode'])?></div>
            <div class="view-row"><span class="view-label">Programme Name:</span> <?=htmlspecialchars($programme['programmes_name'])?></div>
            <div class="view-row"><span class="view-label">Department:</span> <?=htmlspecialchars($dept_name)?></div>
            <div class="view-row"><span class="view-label">Scheme:</span> <?=htmlspecialchars($scheme_name)?></div>
            <a href="view_programme.php?id=<?=urlencode($scheme_id)?>" class="btn btn-secondary">Back to Programmes</a>
        </div>
    </main>
    <footer class="footer">
        <p>2025 eduxamido. All Rights Reserved.</p>
    </footer>
</body>
</html>
