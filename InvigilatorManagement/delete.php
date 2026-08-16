<?php
include('../Common/Connections.php'); // Include connection
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../adminlogin/adminlogin.php');
}
$id = $_GET['id'];
$conn->query("DELETE FROM `invigilators` WHERE `invid` = $id");

header("Location: index.php");

$conn->close();
