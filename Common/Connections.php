<?php
$server_name = 'localhost';
$user = 'root';
$password = '';
$database = 'eduxamido';

$conn = new mysqli($server_name, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// $server_name='localhost';
// $user='u278136182_team_dev';
// $password='Cc!0oBeG:';
// $database='u278136182_eduxamido';

// $conn = new mysqli($server_name, $user, $password, $database);
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }


?>
