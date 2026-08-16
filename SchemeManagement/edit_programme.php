<?php
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
  exit;
}
include('../Common/Connections.php');

$programme_id = isset($_GET['programme_id']) ? intval($_GET['programme_id']) : 0;
$scheme_id = isset($_GET['scheme_id']) ? intval($_GET['scheme_id']) : 0;

// Fetch all departments
$departments = [];
$res = $conn->query("SELECT department_id, department_name FROM departments");
while($row = $res->fetch_assoc()) {
    $departments[$row['department_id']] = $row['department_name'];
}

// Fetch programme details
$programme = null;
if($programme_id) {
    $stmt = $conn->prepare("SELECT * FROM programmes WHERE programmes_id=?");
    $stmt->bind_param("i", $programme_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $programme = $result->fetch_assoc();
    $stmt->close();
    if($programme) {
        $scheme_id = $programme['scheme_id'];
    }
}
if(!$programme) {
    echo '<p style="color:red;">Programme not found.</p>';
    echo '<a href="view_programme.php?id=' . urlencode($scheme_id) . '" class="btn btn-secondary">Back</a>';
    exit;
}

// Handle form submission
if(isset($_POST['update_programme'])) {
    $scode = trim($_POST['programmes_scode']);
    $name = trim($_POST['programmes_name']);
    $dept_id = intval($_POST['department_id']);
    if($scode && $name && $dept_id) {
        $stmt = $conn->prepare("UPDATE programmes SET programmes_scode=?, programmes_name=?, department_id=? WHERE programmes_id=?");
        $stmt->bind_param("ssii", $scode, $name, $dept_id, $programme_id);
        $stmt->execute();
        $stmt->close();
        header("Location: view_programme.php?id=" . urlencode($scheme_id));
        exit;
    }
    $error = "All fields are required.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Programme</title>
    <link rel="stylesheet" href="../homescreen/styles.css?v=<?=time()?>">
    <style>
        body{background-color:rgb(212, 242, 253);}
        .main-content { padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .form-container { background:rgb(246, 246, 246); padding: 24px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px #eee; min-width: 350px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 4px; }
        .form-group input, .form-group select { width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { padding: 6px 16px; border: none; border-radius: 4px; background: #007bff; color: #fff; cursor: pointer; text-decoration: none;width:150px; }
        .btn:hover { background: #0056b3; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #495057; }
        .error { color: #dc3545; margin-bottom: 10px; }
    </style>
</head>
<body>
    <header class="header">
        <h1 class="title">Edit Programme</h1>
    </header>
    <main class="main-content">
        <div class="form-container">
            <?php if(isset($error)): ?>
                <div class="error"><?=htmlspecialchars($error)?></div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label>Programme Code</label>
                    <input type="text" name="programmes_scode" required value="<?=htmlspecialchars($programme['programmes_scode'])?>">
                </div>
                <div class="form-group">
                    <label>Programme Name</label>
                    <input type="text" name="programmes_name" required value="<?=htmlspecialchars($programme['programmes_name'])?>">
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <select name="department_id" required>
                        <option value="">Select Department</option>
                        <?php foreach($departments as $dept_id => $dept_name): ?>
                            <option value="<?=htmlspecialchars($dept_id)?>" <?=($programme['department_id'] == $dept_id) ? 'selected' : ''?>><?=htmlspecialchars($dept_name)?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style='display:flex;gap:30px'>
                    <a href="view_programme.php?id=<?=urlencode($scheme_id)?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" name="update_programme" class="btn">Update Programme</button>
                </div>
            </form>
        </div>
    </main>
    <footer class="footer">
        <p>2025 eduxamido. All Rights Reserved.</p>
    </footer>
</body>
</html>
