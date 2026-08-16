<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <title>Not Found</title>
    <style>
        body{
            display: flex;
            align-items:center;
            flex-direction:column;
            justify-content: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        h1{
            padding: 10px;
            background-color:red;
            color: white;
            border-radius: 10px;
            margin:0;
        }
    </style>
</head>
<body>
    <h1>Unknown Program Name: '<?=$_GET['Unknown']?>'</h1>
    <a href="http://localhost/Examido/Demo/DepartmentManagement/department_manage.php"><p>Please Create it and try again</p></a>
</body>
</html>