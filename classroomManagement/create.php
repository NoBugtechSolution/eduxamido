<?php
include('../Common/Connections.php'); // Include connection
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../adminlogin/adminlogin.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $className = $_POST['ClassName'];
    $classRows = $_POST['ClassRows'];
    $classColumns = $_POST['ClassColumns'];

    $sql = "INSERT INTO classroom (ClassName, ClassRows, ClassColumns) VALUES ('$className', '$classRows', '$classColumns')";
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
    <title>Add Classroom</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center text-primary">Add New Classroom</h2>
        <form method="POST" class="card p-4 bg-white shadow">
            <div class="mb-3">
                <label class="form-label">Class Name</label>
                <input type="text" name="ClassName" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Rows</label>
                <input type="number" name="ClassRows" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Columns</label>
                <input type="number" name="ClassColumns" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Add Classroom</button>
        </form>
    </div>
</body>

</html>