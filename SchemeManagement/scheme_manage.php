<?php
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
  exit;
}
include('../Common/Connections.php'); // Database connection

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

// Handle Delete
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM schemes WHERE scheme_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: scheme_manage.php");
    exit;
}

// Handle Edit
$edit_scheme = null;
if(isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM schemes WHERE scheme_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_scheme = $result->fetch_assoc();
    $stmt->close();
}

// Handle Update
if(isset($_POST['update_scheme'])) {
    $id = intval($_POST['scheme_id']);
    $name = trim($_POST['scheme_name']);
    $year = intval($_POST['start_year']);
    $desc = trim($_POST['description']);
    if($name !== '') {
        $stmt = $conn->prepare("UPDATE schemes SET scheme_name=?, start_year=?, description=? WHERE scheme_id=?");
        $stmt->bind_param("sisi", $name, $year, $desc, $id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: scheme_manage.php");
    exit;
}

// Fetch all schemes
$schemes = [];
$result = $conn->query("SELECT * FROM schemes ORDER BY start_year DESC");
while($row = $result->fetch_assoc()) {
    $schemes[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scheme Management</title>
    <link rel="stylesheet" href="scheme_manage.css?v=<?=time()?>">
    <style>
        
        .form-container { background:rgb(9 120 181);color:white; padding: 24px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px #eee; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 4px; }
        .form-group input, .form-group textarea { width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;}
        /* .btn { padding: 6px 16px; border: none; border-radius: 4px; background: #007bff; color: #fff; cursor: pointer; text-decoration: none; } */
        /* .btn:hover { background: #0056b3; } */
        .btn-danger { background: #dc3545; }
        /* .btn-danger:hover { background: #a71d2a; } */
        .btn-secondary { background: #6c757d; }
        /* .btn-secondary:hover { background: #495057; } */
        /* .action-links { display: flex; gap: 8px; }
        .action-links a { margin: 8px; } */
    </style>
</head>
<body>
    <section id='header'>
        <div><a href="../homescreen/index.php"><ion-icon name="arrow-back-outline" id='back'></ion-icon></a></div>
        <h1 id='heading'>Schemes</h1>
        <div><a href='add_scheme.php'><button id='create'>CREATE</button></a></div>
    </section>
    <main class="main-content">
        
            <?php if($edit_scheme): ?>
                <div class="form-container">
                <h2>Edit Scheme</h2>
                <form method="post">
                    <input type="hidden" name="scheme_id" value="<?=htmlspecialchars($edit_scheme['scheme_id'])?>">
                    <div class="form-group">
                        <label>Scheme Name</label>
                        <input type="text" name="scheme_name" required value="<?=htmlspecialchars($edit_scheme['scheme_name'])?>">
                    </div>
                    <div class="form-group">
                        <label>Start Year</label>
                        <input type="number" name="start_year" value="<?=htmlspecialchars($edit_scheme['start_year'])?>">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description"><?=htmlspecialchars($edit_scheme['description'])?></textarea>
                    </div>
                    <div class='editValue'>
                        <a href="scheme_manage.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="update_scheme" class="btn">Update</button>
                    </div>
                </form>
                </div>
                <!-- <a href="add_scheme.php" class="btn">Add New Scheme</a> -->
            <?php endif; ?>
        
        <h2>All Schemes</h2>
        <table class="scheme-table">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Scheme Name</th>
                    <th>Start Year</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($schemes) === 0): ?>
                    <tr><td colspan="5">No schemes found.</td></tr>
                <?php else: $sl=1; foreach($schemes as $scheme): ?>
                    <tr>
                        <td><?= $sl++ ?></td>
                        <td><?=htmlspecialchars($scheme['scheme_name'])?></td>
                        <td><?=htmlspecialchars($scheme['start_year'])?></td>
                        <td><?=htmlspecialchars($scheme['description'])?></td>
                        <td class="action-links ">
                            <div class='tableController'>
                                <a href="view_scheme.php?id=<?=urlencode($scheme['scheme_id'])?>" class="btn">View</a>
                                <a href="?edit=<?=$scheme['scheme_id']?>" class="btn btn-secondary">Edit</a>
                                <a href="?delete=<?=$scheme['scheme_id']?>" class="btn btn-danger" onclick="return confirm('Delete this scheme?');">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
        <br>
        <!-- <a href="../homescreen/index.php" class="btn btn-secondary">Back to Home</a> -->
    </main>
    <!-- <footer class="footer">
        <p>2025 eduxamido. All Rights Reserved.</p>
    </footer> -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>