<?php
include('../Common/Connections.php'); // Include connection
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../adminlogin/adminlogin.php');
}
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM classroom WHERE ClassID = $id");
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $className = $_POST['ClassName'];
    $classRows = $_POST['ClassRows'];
    $classColumns = $_POST['ClassColumns'];

    $sql = "UPDATE classroom SET ClassName='$className', ClassRows='$classRows', ClassColumns='$classColumns' WHERE ClassID = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Classroom</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center text-warning">Edit Classroom</h2>
        <form method="POST" class="card p-4 bg-white shadow">
            <div class="mb-3">
                <label class="form-label">Class Name</label>
                <input type="text" name="ClassName" class="form-control" value="<?= htmlspecialchars($row['ClassName']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Rows</label>
                <input type="number" name="ClassRows" class="form-control" value="<?= $row['ClassRows'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Columns</label>
                <input type="number" name="ClassColumns" class="form-control" value="<?= $row['ClassColumns'] ?>" required>
            </div>
            <button type="submit" class="btn btn-warning">Update Classroom</button>
        </form>
    </div>
</body>

</html>