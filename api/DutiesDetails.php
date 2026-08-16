<?php
    include_once('api.php');
    include_once('../Common/Connections.php');
    if(!isset($_GET['Invid'])){
        echo json_encode(["status" => "error", "message" => "Missing Values"]);
        exit;
    }
    $id=$_GET['Invid'];
    $sql = "SELECT * FROM `assignment` INNER JOIN classroom ON classroom.ClassID=assignment.ClassID WHERE inv_id=$id ORDER BY a_exam_date DESC";
    $result = $conn->query($sql);
    
    $data = [];
    
    if ($result->num_rows > 0) {
        $i=1;
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                "index"=>$i,
                "MainID" => $row["assignment_id"],
                "ClassID" =>$row["ClassID"],
                "Date" => $row["a_exam_date"],
                "ClassName"=>$row['ClassName'],
                "ClassID"=>$row['ClassID']
            ];
            $i++;
        }
    }
    
    echo json_encode($data, JSON_PRETTY_PRINT);
    $conn->close();
    
?>