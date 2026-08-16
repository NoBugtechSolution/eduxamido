<?php
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home Screen</title>
    <link rel="stylesheet" href="styles.css?v=<?=time()?>" />
  </head>
  <body>
    <!-- Header Section -->
    <header class="header">
      <h1 class="title">Eduxamido</h1>
    </header>

    <!-- Main Content Section -->
    <main class="main-content">
      <div class="main-buttons">
        <div class="button-container">
          <a href="../ExamManagement/ExamManage/ViewExams.php"
            ><div class="button-content">
              <h2>Manage Examination</h2>
            </div>
          </a>
        </div>
        <div class="button-container">
          <a href="../classroomManagement/index.php">
            <div class="button-content">
              <h2>Class Manage</h2>
            </div>
          </a>
        </div>
        <div class="button-container">
          <a href="../student_details/student_data_list.php">
            <div class="button-content">
              <h2>Students Manage</h2>
            </div>
          </a>
        </div>
        <div class="button-container">
          <a href="../InvigilatorManagement">
          <div class="button-content">
            <h2>Manage Invigilator</h2>
          </div>
          </a>
        </div>
        <div class="button-container">
          <a href="../SchemeManagement/scheme_manage.php">
            <div class="button-content">
              <h2>Scheme Manage</h2>
            </div>
          </a>
        </div>
        <div class="button-container">
          <a href="../DepartmentManagement/department_manage.php">
            <div class="button-content">
              <h2>Department Manage</h2>
            </div>
          </a>
        </div>
        <!-- Add more buttons as needed -->
      </div>
    </main>

    <!-- Footer Section -->
    <footer class="footer">
      <p> 2025 eduxamido. All Rights Reserved.</p>
    </footer>
  </body>
</html>
