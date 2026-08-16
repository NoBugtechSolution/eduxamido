<?php
include('../Common/Connections.php'); // Include connection
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../adminlogin/adminlogin.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Name = $_POST['InvName'];
    $Email = $_POST['Email'];
    $Address = $_POST['Address'];
    $HQ = $_POST['HQ'];
    $Post = $_POST['Post'];
    $status = $_POST['status'];

    $sql = "INSERT INTO `invigilators`( `invi_name`, `inviemail`, `invi_address`, `invi_highest_qualification`, `invi_post`, `invi_status`) VALUES ('$Name','$Email','$Address','$HQ','$Post','$status')";
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
<style>
    input,select,textarea{
        background-color:#e3e2e2  !important;
        resize: horizontal;
    }
</style>
<head>
    <title>Add Invigilator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center text-primary" style='display:flex;align-items:center;justify-content:space-between;'>
            <a href="index.php"><ion-icon name="arrow-back-outline"></ion-icon></a>
            <span>Add New Invigilator</span>
            <ion-icon style='opacity:0'name="arrow-forward-outline"></ion-icon>
        </h2>
        <form method="POST" class="card p-4 bg-white shadow">
            <div class="mb-3">
                <label class="form-label"> Name</label>
                <input placeholder="Enter the Name of the Invigilator"  type="text" name="InvName" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" placeholder="Enter the Email of the Invigilator" name="Email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class='form-control' placeholder="Enter the Address of the Invigilator" name="Address" id="" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Highest Qualification</label>
                <textarea class='form-control' placeholder="Enter the highest Qualification of Invigilator" name="HQ" id="" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Post</label>
                <input type="text" placeholder="Enter the Post of the Invigilator" name="Post" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Current Status</label>
                <select name="status" class='form-control' id="">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Add Invigilator</button>
        </form>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>