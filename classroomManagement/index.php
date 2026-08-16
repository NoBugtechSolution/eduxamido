<?php
include('../Common/Connections.php'); // Include your connection file
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../adminlogin/adminlogin.php');
}
// Fetch all classrooms
$sql = "SELECT * FROM classroom";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    
    <title>Classroom Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center text-primary">Classroom Management</h2>
        <a href="create.php" class="btn btn-success mb-3">Add New Classroom</a>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Class Name</th>
                    <th>Rows</th>
                    <th>Columns</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['ClassName']) ?></td>
                        <td><?= $row['ClassRows'] ?></td>
                        <td><?= $row['ClassColumns'] ?></td>
                        <td>
                            <a href="edit.php?id=<?= $row['ClassID'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete.php?id=<?= $row['ClassID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php $conn->close(); ?>