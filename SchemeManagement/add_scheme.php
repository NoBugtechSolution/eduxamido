<?php
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
  exit;
}
include('../Common/Connections.php');

// Handle Create
if(isset($_POST['add_scheme'])) {
    $name = trim($_POST['scheme_name']);
    $year = intval($_POST['start_year']);
    $desc = trim($_POST['description']);
    if($name !== '') {
        $stmt = $conn->prepare("INSERT INTO schemes (scheme_name, start_year, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $name, $year, $desc);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: scheme_manage.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Scheme</title>
    <link rel="stylesheet" href="../homescreen/styles.css?v=<?=time()?>">
    <style>
        .main-content { padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .form-container { background:rgb(9 120 181);color:white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px #eee; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 4px; }
        .form-group input, .form-group textarea { width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px; }
        .btn {background-color: #3498db;min-width: 150px;border: 2px solid white;color: white;border-radius: 6px;box-shadow: 2px 2px 5px rgba(255, 255, 255, 0.277);font-size: 18px;padding: 6px 14px;display: flex;align-items: center;justify-content: center;gap: 10px;}
        .btn:hover { background: #0056b3; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #495057; }
        .editValue{display: flex;gap: 50px;width: 100%;justify-content: space-between;}
    </style>
</head>
<body>
    <header class="header">
        <h1 class="title">Add New Scheme</h1>
    </header>
    <main class="main-content">
        <div class="form-container">
            <form method="post">
                <div class="form-group">
                    <label>Scheme Name</label>
                    <input type="text" name="scheme_name"placeholder='Enter the Scheme name' required>
                </div>
                <div class="form-group">
                    <label>Start Year</label>
                    <input type="number" name="start_year" placeholder='Enter the Year it started'>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" placeholder='Enter Description about the Scheme'></textarea>
                </div>
                <div class='editValue'>
                    <a href="scheme_manage.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" name="add_scheme" class="btn">Add Scheme</button>
                </div>
            </form>
        </div>
    </main>
    <footer class="footer">
        <p>2025 eduxamido. All Rights Reserved.</p>
    </footer>
</body>
</html>
