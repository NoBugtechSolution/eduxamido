<?php
include('../Common/Connections.php'); // Include your connection file
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../adminlogin/adminlogin.php');
}
$sql = "SELECT * FROM invigilators";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css?v=<?=time()?>">
    <title>Invigilator Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="mainBox">
        <h2 class="text-center text-primary" style='display:flex;align-items:center;justify-content:space-between;'>
            <a href="../homescreen"><ion-icon name="arrow-back-outline"></ion-icon></a>
            <span>Invigilator Management</span>
            <ion-icon style='opacity:0'name="arrow-forward-outline"></ion-icon>
        </h2>
        <a href="create.php" class="btn btn-success mb-3">Add New Invigilator</a>
        <table >
            <thead class="table-dark" >
                <tr>
                    <th>SI</th>
                    <th>Invigilator Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Highest Qualification</th>
                    <th>Post</th>
                    <th>Duty Count</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                
                <?php $i=1; while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td class='<?=($row['invi_status']==1)?"Ready":"notReady"?>'><?=$i++;?></td>
                        <td><?= htmlspecialchars($row['invi_name']) ?></td>
                        <td><?= $row['inviemail'] ?></td>
                        <td><?= $row['invi_address'] ?></td>
                        <td><?= $row['invi_highest_qualification'] ?></td>
                        <td><?= $row['invi_post'] ?></td>
                        <td><?= $row['invi_duty_count'] ?></td>
                        <td>
                            <a href="edit.php?id=<?= $row['invid'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete.php?id=<?= $row['invid'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>

<?php $conn->close(); ?>