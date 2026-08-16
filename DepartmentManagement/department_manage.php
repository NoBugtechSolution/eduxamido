<?php
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
  exit;
}
include('../Common/Connections.php');

// Handle Create
if(isset($_POST['add_department'])) {
    $department_name = trim($_POST['department_name']);
    $department_scode = trim($_POST['department_scode']);
    if($department_name !== '') {
        $stmt = $conn->prepare("INSERT INTO departments (department_name, department_scode) VALUES (?, ?)");
        $stmt->bind_param("ss", $department_name, $department_scode);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: department_manage.php");
    exit;
}

// Handle Delete
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM departments WHERE department_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: department_manage.php");
    exit;
}

// Handle Edit
$edit_department = null;
if(isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM departments WHERE department_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_department = $result->fetch_assoc();
    $stmt->close();
}

// Handle Update
if(isset($_POST['update_department'])) {
    $id = intval($_POST['department_id']);
    $department_name = trim($_POST['department_name']);
    $department_scode = trim($_POST['department_scode']);
    if($department_name !== '') {
        $stmt = $conn->prepare("UPDATE departments SET department_name=?, department_scode=? WHERE department_id=?");
        $stmt->bind_param("ssi", $department_name, $department_scode, $id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: department_manage.php");
    exit;
}

// Fetch all departments
$departments = [];
$sql = "SELECT * FROM departments ORDER BY department_name ASC";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    $departments[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Department Management</title>
    <link rel="stylesheet" href="../homescreen/styles.css?v=<?=time()?>">
    <style>
        .main-content { padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .department-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .department-table th, .department-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .department-table th { background: #f5f5f5; }
        .form-container { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px #eee; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 4px; }
        .form-group input, .form-group select { width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { padding: 6px 16px; border: none; border-radius: 4px; background: #007bff; color: #fff; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #a71d2a; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #495057; }
        .action-links a { margin-right: 8px; }
    </style>
</head>
<body>
    <header class="header">
        <h1 class="title">Department Management</h1>
    </header>
    <main class="main-content">
        <div class="form-container">
            <?php if($edit_department): ?>
                <h2>Edit Department</h2>
                <form method="post">
                    <input type="hidden" name="department_id" value="<?=htmlspecialchars($edit_department['department_id'])?>">
                    <div class="form-group">
                        <label>Department Name</label>
                        <input type="text" name="department_name" required value="<?=htmlspecialchars($edit_department['department_name'])?>">
                    </div>
                    <div class="form-group">
                        <label>Department Code</label>
                        <input type="text" name="department_scode" value="<?=isset($edit_department['department_scode']) ? htmlspecialchars($edit_department['department_scode']) : ''?>">
                    </div>
                    <button type="submit" name="update_department" class="btn">Update</button>
                    <a href="department_manage.php" class="btn btn-secondary">Cancel</a>
                </form>
            <?php else: ?>
                <h2>Add New Department</h2>
                <form method="post">
                    <div class="form-group">
                        <label>Department Name</label>
                        <input type="text" name="department_name" required>
                    </div>
                    <div class="form-group">
                        <label>Department Code</label>
                        <input type="text" name="department_scode">
                    </div>
                    <button type="submit" name="add_department" class="btn">Add Department</button>
                </form>
            <?php endif; ?>
        </div>
        <h2>All Departments</h2>
        <table class="department-table">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Department Name</th>
                    <th>Department Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($departments) === 0): ?>
                    <tr><td colspan="4">No departments found.</td></tr>
                <?php else: $sl=1; foreach($departments as $department): ?>
                    <tr>
                        <td><?= $sl++ ?></td>
                        <td><?=htmlspecialchars($department['department_name'])?></td>
                        <td><?=htmlspecialchars($department['department_scode'] ?? '')?></td>
                        <td class="action-links">
                            <a href="?edit=<?=$department['department_id']?>" class="btn btn-secondary">Edit</a>
                            <a href="?delete=<?=$department['department_id']?>" class="btn btn-danger" onclick="return confirm('Delete this department?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
        <br>
        <a href="../homescreen/index.php" class="btn btn-secondary">Back to Home</a>
    </main>
    <footer class="footer">
        <p>2025 eduxamido. All Rights Reserved.</p>
    </footer>
</body>
</html>
