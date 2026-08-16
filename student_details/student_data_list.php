<?php 
   include("../Common/Connections.php");
   include('../Common/Session.php');
if(!isset($_SESSION['User'])){
  header('location:../adminlogin/adminlogin.php');
}
   $sql="SELECT * FROM students_details";
   $result=mysqli_query($conn,$sql);
   
   $count=0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Student list</title>
    <style>
        <style>
         body {
            background-color: #f7f7f7;
        }
        .table-wrapper {
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            border-collapse: separate;
            border-spacing: 0 10px;
        }
        thead {
            background-color: #4CAF50;
            color: white;
        }
        thead th {
            padding: 12px;
        }
        tbody tr {
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        tbody td {
            padding: 12px;
        }
        tbody tr:last-child {
            border-bottom: none;
        }  
        .form-control {
            width: 300px;
            margin-top: 30px;
            height: 40px;
        }
         .form-group{
            display: flex;
            flex-grow: 0
         }
         .btn{
            margin-left: 10px;
            margin-top: 30px;
            height: 40px;
        }
        .text-center{
            margin-top: 50px;
            font-size: 50px;
        }
        #view{
            width: 40px;
        }
        .btnvw {
            margin-left: 10px;
            margin-top: 0px;
            height: 40px;
            width: 80px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        select, input, .btn {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .btnvw:hover {
            background-color: #0056b3;
        }
        #search{
            margin-left: -110px;
        }
    </style>
    </style>
</head>
<body>
    <h1 class="text-center">STUDENT DETAILS</h1>
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-12">
                <!-- Combine both forms into a single form -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                    <div class="row">
                        <div class="col-md-4">
                            <select id="department" name="programmes_id" class="form-control">
                                <option value="">All Departments</option>
                                <?php 
                                $sql_dept = "SELECT programmes.programmes_id, programmes.programmes_name FROM programmes ORDER BY programmes.programmes_name ASC";
                                $result_dept = mysqli_query($conn, $sql_dept);
                                while($row_dept = mysqli_fetch_assoc($result_dept)) {
                                    $selected = (isset($_GET['programmes_id']) && $_GET['programmes_id'] == $row_dept['programmes_id']) ? 'selected' : '';
                                    echo '<option value="' . $row_dept['programmes_id'] . '" ' . $selected . '>' . $row_dept['programmes_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select style="margin-left: -110px;" id="academic_year" name="academic_year" class="form-control">
                                <option value="">All Academic Years</option>
                                <?php
                                $sql_year = "SELECT DISTINCT AcademicYear FROM students_details WHERE AcademicYear IS NOT NULL AND AcademicYear != '' ORDER BY AcademicYear DESC";
                                $result_year = mysqli_query($conn, $sql_year);
                                while($row_year = mysqli_fetch_assoc($result_year)) {
                                    $year_val = trim($row_year['AcademicYear']);
                                    // Show if it looks like a year or year range (e.g., 2023, 2023-24, 2023/24), or fallback to any non-empty value
                                    if (preg_match('/^\d{4}([-/]?(\d{2,4}))?$/', $year_val) || strlen($year_val) > 0) {
                                        $selected = (isset($_GET['academic_year']) && $_GET['academic_year'] == $year_val) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($year_val) . '" ' . $selected . '>' . htmlspecialchars($year_val) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input id="search" type="text" name="search" class="form-control" placeholder="Search by name or roll no" value="<?php if(isset($_GET['search'])) echo htmlspecialchars($_GET['search']); ?>">
                        </div>
                        <div class="col-md-2">
                            <button id="filter" type="submit" class="btn btn-primary w-100">Filter/Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>RollNo</th>
                        <th>Name</th>
                        <th>Programmes</th>
                        <th>AcademicYear</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $programmes_id = isset($_GET['programmes_id']) ? $_GET['programmes_id'] : '';
                    $academic_year = isset($_GET['academic_year']) ? $_GET['academic_year'] : '';
                    $search = isset($_GET['search']) ? $_GET['search'] : '';

                    $where = [];
                    $params = [];
                    $types = '';

                    if ($programmes_id !== '') {
                        $where[] = 'students_details.programmes_id = ?';
                        $params[] = $programmes_id;
                        $types .= 's';
                    }
                    if ($academic_year !== '') {
                        $where[] = 'students_details.AcademicYear = ?';
                        $params[] = $academic_year;
                        $types .= 's';
                    }
                    if ($search !== '') {
                        $where[] = '(students_details.RollNo LIKE ? OR students_details.Name LIKE ?)';
                        $params[] = '%' . $search . '%';
                        $params[] = '%' . $search . '%';
                        $types .= 'ss';
                    }

                    $sql = "SELECT * FROM students_details INNER JOIN programmes ON programmes.programmes_id=students_details.programmes_id";
                    if (count($where) > 0) {
                        $sql .= " WHERE " . implode(' AND ', $where);
                    }

                    $stmt = mysqli_prepare($conn, $sql);
                    if (count($params) > 0) {
                        mysqli_stmt_bind_param($stmt, $types, ...$params);
                    }
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?php echo ++$count;?></td>
                        <td><?php echo $row['RollNo'];?></td>
                        <td><?php echo $row['Name'];?></td>
                        <td><?php echo $row['programmes_name'];?></td>
                        <td><?php echo $row['AcademicYear'];?></td>
                        <td id="view">
                        <button type="button" class="btnvw" onclick="location.href='student_details.php?rollno=<?php echo $row['RollNo']; ?>';">View</button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
