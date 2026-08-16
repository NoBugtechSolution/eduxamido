<?php
include("../Common/Connections.php");
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
}
// Check if rollno is provided
if (isset($_GET['rollno'])) {
    $rollno = $_GET['rollno'];

    // Delete the student record
    $sql_delete = "DELETE FROM students_details WHERE RollNo = '$rollno'";
    if (mysqli_query($conn, $sql_delete)) {
        // Redirect back to the student details page or a confirmation page
        header("Location: student_details.php?message=Student+deleted+successfully");
        exit();
    } else {
        // Handle the error
        header("Location: student_details.php?message=Error+deleting+student");
        exit();
    }
} else {
    // Redirect if rollno is not provided
    header("Location: student_details.php?message=Invalid+request");
    exit();
}
?>