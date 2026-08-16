<?php
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
  exit;
}
include('../Common/Connections.php');

$programme_id = isset($_GET['programme_id']) ? intval($_GET['programme_id']) : 0;
$scheme_id = isset($_GET['scheme_id']) ? intval($_GET['scheme_id']) : 0;

if($programme_id) {
    $stmt = $conn->prepare("DELETE FROM programmes WHERE programmes_id=?");
    $stmt->bind_param("i", $programme_id);
    $stmt->execute();
    $stmt->close();
}
header("Location: view_programme.php?id=" . urlencode($scheme_id));
exit;
