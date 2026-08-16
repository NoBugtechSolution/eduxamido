<?php
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
  exit;
}
include('../Common/Connections.php');

if(isset($_GET['id'])) {
    $course_id = intval($_GET['id']);
    // Optionally, get scheme_id for redirect
    $scheme_id = isset($_GET['scheme_id']) ? intval($_GET['scheme_id']) : 0;
    $stmt = $conn->prepare("DELETE FROM courses WHERE course_id=?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $stmt->close();
    // Redirect back to the scheme view
    if($scheme_id) {
        header("Location: view_courses.php?id=" . urlencode($scheme_id));
    } else {
        header("Location: scheme_manage.php");
    }
    exit;
} else {
    echo '<p style="color:red;">Invalid course ID.</p>';
    echo '<a href="scheme_manage.php" class="btn btn-secondary">Back</a>';
}
