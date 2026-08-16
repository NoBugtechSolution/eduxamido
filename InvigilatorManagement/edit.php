<?php
include('../Common/Connections.php'); // Include connection
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../adminlogin/adminlogin.php');
}
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM invigilators WHERE invid = $id");
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Name = $_POST['InvName'];
    $dutycount=$_POST['duty_count'];
    $Email = $_POST['Email'];
    $Address = $_POST['Address'];
    $HQ = $_POST['HQ'];
    $Post = $_POST['Post'];
    $status = $_POST['status'];


    $sql = "UPDATE `invigilators` SET `invi_name`='$Name',`invi_duty_count`='$dutycount',`inviemail`='$Email',`invi_address`='$Address',`invi_highest_qualification`='$HQ',`invi_post`='$Post',`invi_status`='$status' WHERE `invid`='$id'";
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
    <title>Edit Invigilator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center text-warning" style='display:flex;align-items:center;justify-content:space-between;'>
            <a href="index.php"><ion-icon name="arrow-back-outline"></ion-icon></a>
            <span>Edit Invigilator</span>
            <ion-icon style='opacity:0'name="arrow-forward-outline"></ion-icon>
        </h2>
        <form method="POST" class="card p-4 bg-white shadow">
        <div class="mb-3">
                <label class="form-label"> Name</label>
                <input placeholder="Enter the Name of the Invigilator" value='<?=$row['invi_name']?>'  type="text" name="InvName" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Duty Count</label>
                <input type="number" placeholder="Enter the Duty Count of the Invigilator" value='<?=$row['invi_duty_count']?>' name="duty_count" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" placeholder="Enter the Email of the Invigilator" value='<?=$row['inviemail']?>' name="Email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class='form-control' placeholder="Enter the Address of the Invigilator" name="Address" id="" rows="5" required><?=$row['invi_address']?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Highest Qualification</label>
                <textarea class='form-control' placeholder="Enter the highest Qualification of Invigilator"  name="HQ" id="" rows="3" required><?=$row['invi_highest_qualification']?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Post</label>
                <input type="text" placeholder="Enter the Post of the Invigilator" value='<?=$row['invi_post']?>' name="Post" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Current Status</label>
                <select name="status" class='form-control' id="">
                    <option value="1" <?=($row['invi_status']==1)?"selected":"";?>>Active</option>
                    <option value="0" <?=($row['invi_status']==0)?"selected":"";?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">Update Invigilator Details</button>
        </form>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>