<?php
include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
  exit;
}
include('../Common/Connections.php');

$scheme_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$scheme = null;
if($scheme_id) {
    $stmt = $conn->prepare("SELECT * FROM schemes WHERE scheme_id=?");
    $stmt->bind_param("i", $scheme_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $scheme = $result->fetch_assoc();
    $stmt->close();
}
if(!$scheme) {
    echo '<p style="color:red;">Scheme not found.</p>';
    echo '<a href="scheme_manage.php" class="btn btn-secondary">Back</a>';
    exit;
}
// Handle search and filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_department = isset($_GET['department_id']) ? intval($_GET['department_id']) : 0;

// Build SQL with optional search and filter
$query = "SELECT c.*, d.department_name FROM courses c LEFT JOIN departments d ON c.department_id = d.department_id WHERE c.scheme_id = ?";
$params = [$scheme_id];
$types = "i";
if($search !== '') {
    $query .= " AND (c.course_code LIKE ? OR c.course_name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
}
if($filter_department) {
    $query .= " AND c.department_id = ?";
    $params[] = $filter_department;
    $types .= "i";
}
$query .= " ORDER BY c.course_id ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$courses = [];
while($row = $result->fetch_assoc()) {
    $courses[] = $row;
}
$stmt->close();

// Fetch all departments for filter dropdown
$departments = [];
$res = $conn->query("SELECT department_id, department_name FROM departments ORDER BY department_name ASC");
while($row = $res->fetch_assoc()) {
    $departments[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Courses in Scheme</title>
    <link rel="stylesheet" href="courses.css?v=<?=time()?>">
    <!-- <style>
        body { color: white; }
        .main-content { padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .view-container { background:rgb(122, 184, 250); padding: 24px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px #eee; min-width: 320px; }
        .view-row { margin-bottom: 14px; }
        .view-label { font-weight: bold; display: inline-block; width: 120px; }
        .btn { padding: 6px 16px; border: none; border-radius: 4px; background: #007bff; color: #fff; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #0056b3; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #495057; }
        th { padding: 12px; background:rgb(40, 106, 206); }
        .action-links { display: flex; gap: 8px; }
        .action-links a { margin: 8px; }
        td { padding: 12px;}
    </style> -->
</head>
<body>
    <section id='header'>
        <div><a href="view_scheme.php?id=<?=urlencode($scheme['scheme_id'])?>"><ion-icon name="arrow-back-outline" id='back'></ion-icon></a></div>
        <h1 id='heading'>Courses in <?=htmlspecialchars($scheme['scheme_name'])?></h1>
        <div><a href='add_course.php?scheme_id=<?=urlencode($scheme['scheme_id'])?>'><button id='create'>Add New Course</button></a></div>
    </section>
    <main class="main-content">
        <form method="get" style="margin-bottom: 18px; width: 90%; display: flex; gap: 16px; align-items: center;">
            <input type="hidden" name="id" value="<?=htmlspecialchars($scheme['scheme_id'])?>">
            <input type="text" name="search" placeholder="Search by code or name" value="<?=htmlspecialchars($search)?>" style="padding: 8px; border-radius: 4px; border: 1px solid #ccc; min-width: 180px;">
            <select name="department_id" style="padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                <option value="0">All Departments</option>
                <?php foreach($departments as $dept): ?>
                    <option value="<?=htmlspecialchars($dept['department_id'])?>" <?=($filter_department == $dept['department_id']) ? 'selected' : ''?>><?=htmlspecialchars($dept['department_name'])?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn">Search/Filter</button>
            <?php if($search !== '' || $filter_department): ?>
                <a href="view_courses.php?id=<?=urlencode($scheme['scheme_id'])?>" class="btn btn-secondary">Reset</a>
            <?php endif; ?>
        </form>
        <table class="scheme-table" style="margin-bottom:24px; border-radius: 8px;">
            <thead>
                <tr>
                    <th style='min-width:80px'>Sl No.</th>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Department</th>
                    <th>Credits</th>
                    <th>Semester</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($courses) === 0): ?>
                    <tr><td colspan="8">No courses found for this scheme.</td></tr>
                <?php else: $sl=1; foreach($courses as $course): ?>
                    <tr >
                        <td><?= $sl++ ?></td>
                        <td><?=htmlspecialchars($course['course_code'])?></td>
                        <td><?=htmlspecialchars($course['course_name'])?></td>
                        <td><?=htmlspecialchars($course['department_name'])?></td>
                        <td><?=htmlspecialchars($course['credits'])?></td>
                        <td><?=htmlspecialchars($course['semester'])?></td>
                        <td><?=htmlspecialchars($course['course_type'])?></td>
                        <td class="action-links ">
                            <div class='tableController'>
                                <a href="view_course.php?id=<?=urlencode($course['course_id'])?>" class="btn">View</a>
                                <a href="edit_course.php?id=<?=urlencode($course['course_id'])?>&scheme_id=<?=urlencode($scheme['scheme_id'])?>" class="btn btn-secondary">Edit</a>
                                <a href="delete_course.php?id=<?=urlencode($course['course_id'])?>&scheme_id=<?=urlencode($scheme['scheme_id'])?>" class="btn btn-danger" style="background:#dc3545;" onclick="return confirm('Delete this course?');">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
        <!-- <a href="add_course.php?scheme_id=<?=urlencode($scheme['scheme_id'])?>" class="btn" style="margin-bottom:16px;">Add New Course</a> -->
        <!-- <a href="view_scheme.php?id=<?=urlencode($scheme['scheme_id'])?>" class="btn btn-secondary">Back</a> -->
    </main>
    <!-- <footer class="footer">
        <p>2025 eduxamido. All Rights Reserved.</p>
    </footer> -->
     <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
