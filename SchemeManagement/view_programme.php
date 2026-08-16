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

// Fetch all programmes for this scheme
$programmes = [];
$sql = "SELECT * FROM programmes WHERE scheme_id = ? ORDER BY programmes_id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $scheme['scheme_id']);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()) {
    $programmes[] = $row;
}
$stmt->close();

// Fetch all department names for mapping
$departments = [];
$res = $conn->query("SELECT department_id, department_name FROM departments");
while($row = $res->fetch_assoc()) {
    $departments[$row['department_id']] = $row['department_name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Programmes in Scheme</title>
    <link rel="stylesheet" href="programes.css?v=<?=time()?>">
    <!-- <style>
        body { color: white; }
        .main-content { padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .view-container { background:rgb(122, 184, 250); padding: 24px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px #eee; min-width: 320px; }
        .view-row { margin-bottom: 14px; }
        .view-label { font-weight: bold; display: inline-block; width: 120px; }
        .btn { padding: 6px 16px; border: none; border-radius: 4px; background: #007bff; color: #fff; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #0056b3; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #495057; }
        th { padding: 12px; background:rgb(40, 106, 206); }
        .action-links { display: flex; gap: 8px; }
        .action-links a { margin: 8px; }
        td { padding: 12px;}
    </style> -->
</head>
<body>
    <section id='header'>
        <div><a href="view_scheme.php?id=<?=urlencode($scheme['scheme_id'])?>"><ion-icon name="arrow-back-outline" id='back'></ion-icon></a></div>
        <h1 id='heading'>Programmes in <?=htmlspecialchars($scheme['scheme_name'])?></h1>
        <div><a href='add_programme.php?scheme_id=<?=urlencode($scheme['scheme_id'])?>'><button id='create'>Add Programme</button></a></div>
    </section>
    <main class="main-content">
        <table class="scheme-table" style="margin-bottom:24px; border-radius: 8px; ">
<?php
// Handle add programme form submission
if(isset($_POST['add_programme'])) {
    $scode = trim($_POST['programmes_scode']);
    $name = trim($_POST['programmes_name']);
    $dept_id = intval($_POST['department_id']);
    if($scode && $name && $dept_id && $scheme) {
        $stmt = $conn->prepare("INSERT INTO programmes (scheme_id, programmes_scode, programmes_name, department_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $scheme['scheme_id'], $scode, $name, $dept_id);
        $stmt->execute();
        $stmt->close();
        // Refresh to show new programme
        header("Location: view_programme.php?id=" . urlencode($scheme['scheme_id']));
        exit;
    }
}
?>
            <thead>
                <tr>
                    <th style='min-width:80px'>Sl No.</th>
                    <th>Programme Code</th>
                    <th>Programme Name</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($programmes) === 0): ?>
                    <tr><td colspan="5">No programmes found for this scheme.</td></tr>
                <?php else: $sl=1; foreach($programmes as $programme): ?>
                    <tr >
                        <td><?= $sl++ ?></td>
                        <td><?=htmlspecialchars($programme['programmes_scode'])?></td>
                        <td><?=htmlspecialchars($programme['programmes_name'])?></td>
                        <td><?=htmlspecialchars(isset($departments[$programme['department_id']]) ? $departments[$programme['department_id']] : 'Unknown')?></td>
                        <td class="action-links">
                            <div class='tableController'>
                                <a href="view_programme_details.php?programme_id=<?=urlencode($programme['programmes_id'])?>&id=<?=urlencode($scheme['scheme_id'])?>" class="btn">View</a>
                                <a href="edit_programme.php?programme_id=<?=urlencode($programme['programmes_id'])?>&scheme_id=<?=urlencode($scheme['scheme_id'])?>" class="btn btn-secondary">Edit</a>
                                <a href="delete_programme.php?programme_id=<?=urlencode($programme['programmes_id'])?>&scheme_id=<?=urlencode($scheme['scheme_id'])?>" class="btn" style="background:#dc3545;" onclick="return confirm('Delete this programme?');">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
        <!-- <a href="view_scheme.php?id=<?=urlencode($scheme['scheme_id'])?>" class="btn btn-secondary">Back</a> -->
    </main>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
