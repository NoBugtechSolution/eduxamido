<?php
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
  exit;
}
include('../Common/Connections.php');

$scheme = null;
if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM schemes WHERE scheme_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $scheme = $result->fetch_assoc();
    $stmt->close();
}
if(!$scheme) {
    echo '<p style="color:red;">Scheme not found.</p>';
    echo '<a href="scheme_manage.php" class="btn btn-secondary">Back</a>';
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Scheme</title>
    <link rel="stylesheet" href="../homescreen/styles.css?v=<?=time()?>">
    <style>
        body { color: white;background-color:rgb(212, 242, 253);}
        .main-content { padding: 20px; display: flex; flex-direction: column; align-items: center;width:fit-content; margin:0 auto}
        .view-container { background:#2c8cc1; padding: 24px; border-radius: 8px;    }
        .view-row { margin-bottom: 14px; }
        .view-label { font-weight: bold; display: inline-block; width: 120px; }
        .btn { padding: 6px 16px; border: none; border-radius: 4px; background: #2272c8; color: #fff; cursor: pointer; text-decoration: none; }
        .btna { padding: 10px 26px; border: none; border-radius: 4px; background: #2272c8; color: #fff; cursor: pointer; text-decoration: none; transition: background 0.2s; }
        .btna:hover { background: #0056b3; }
        .btn:hover { background: #0056b3; }
        .btn-secondary { background:rgb(74, 94, 111); }
        .btn-secondary:hover { background: #495057; }
        th { padding: 12px; background:rgb(40, 106, 206); }
        .action-links { display: flex; gap: 8px; }
        .action-links a { margin: 8px; }
        td { padding: 12px;}
    </style>
</head>
<body>
    <header class="header">
        <h1 class="title">Scheme</h1>
    </header>
    <main class="main-content">
        <div style='background-color:#ffffff;box-shadow: 5px 5px 10px #00000066;padding:20px;border-radius: 14px;'>
            <p style='color:black;font-weight: 400;padding:10px;margin-bottom:10px;text-align:center;background-color:#2272c8;color:white;border-radius: 8px;'>Scheme Details</p>
            <div class="view-container">
                
                <div class="view-row"><span class="view-label">Scheme Name:</span> <?=htmlspecialchars($scheme['scheme_name'])?></div>
                <div class="view-row"><span class="view-label">Start Year:</span> <?=htmlspecialchars($scheme['start_year'])?></div>
                <div class="view-row"><span class="view-label">Description:</span> <?=nl2br(htmlspecialchars($scheme['description']))?></div>
            </div>
            <div style="display: flex; gap: 20px; margin: 32px 0 24px 0;justify-content:space-between;width:100%">
                <a href="view_courses.php?id=<?=urlencode($scheme['scheme_id'])?>" class="btna">COURSES</a>
                <a href="view_programme.php?id=<?=urlencode($scheme['scheme_id'])?>" class="btna">PROGRAMME</a>
            </div>
            <a href="scheme_manage.php" class="btn btn-secondary">Exit</a>
        </div>
    </main>
    <footer class="footer">
        <p>2025 eduxamido. All Rights Reserved.</p>
    </footer>
</body>
</html>
