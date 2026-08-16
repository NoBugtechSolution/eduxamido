<?php 
session_start();
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title>Date Selector</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional Custom CSS -->
    <link href="style.css" rel="stylesheet">
    <script>
        function addDateField() {
            var newField = document.createElement('div');
            newField.className = 'form-group';

            var label = document.createElement('label');
            label.textContent = 'Choose another date:';
            newField.appendChild(label);

            var dateInput = document.createElement('input');
            dateInput.type = 'date';
            dateInput.name = 'date[]';
            dateInput.className = 'form-control';
            dateInput.required = true;
            newField.appendChild(dateInput);

            document.getElementById('dateFields').appendChild(newField);
        }

        function showAssignmentButton() {
            document.getElementById('assignmentButton').style.display = 'block';
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1>Select Dates</h1>
        <form action="index.php" method="post">
            <div id="dateFields">
                <div class="form-group">
                    <label for="date">Choose a date:</label>
                    <input type="date" id="date" name="date[]" class="form-control" required>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addDateField()">Add Date</button>
            <button type="submit" class="btn btn-primary">Submit Dates</button>
        </form>

        <?php
        $conn = mysqli_connect("localhost", "root", "", "examido");

        if (!$conn) {
            die("Connection error: " . mysqli_connect_error());
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dates = $_POST['date'];
            $totalDates = count($dates);
            $datesArray = array_map('htmlspecialchars', $dates);

            $_SESSION["totalDates"] = $totalDates;
            $_SESSION["datesArray"] = $datesArray;
            
            // $sql="DELETE FROM assignment";
            // mysqli_query($conn,$sql);

            echo "<div class='alert alert-success mt-3' role='alert'>";
            echo "Total number of dates chosen: " . $totalDates . "<br>";
            echo "You selected the following dates:<br>";
            foreach ($datesArray as $date) {
                echo $date . "<br>";
            }
            echo "</div>";

            echo "<button id='assignmentButton' class='btn btn-info mt-3' style='display:none;' onclick='location.href=\"assignment.php\"'>View Assignment</button>";
            echo "<script>showAssignmentButton();</script>";
        }
        ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
