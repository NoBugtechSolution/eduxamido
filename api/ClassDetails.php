<?php
include_once('api.php');
include_once('../Common/Connections.php');
if(!isset($_GET['Mode']) || !isset($_GET['ClassID'])||!isset($_GET['Date'])){
    $conn->close();
    echo json_encode(["status" => "error", "message" => "Missing Values"]);
    exit;
}

if($_GET['Mode']==0){
    $classID=$_GET['ClassID'];
    $Date=$_GET['Date'];

    $SQL="SELECT c.*, 
       es.StudentsCount, 
       (es.MaxRow * es.MaxCol) AS TotalCapacity
            FROM classroom c
            LEFT JOIN (
                SELECT COUNT(*) AS StudentsCount, 
                    MAX(class_row) AS MaxRow, 
                    MAX(class_col) AS MaxCol
                FROM exam_stu_seating 
                INNER JOIN exam_students ON exam_stu_seating.exam_sub_stu_ID = exam_students.exam_sub_stu_ID
                INNER JOIN examsubjects ON examsubjects.examsubjectsID = exam_students.examsubjectsID
                WHERE examsubjects.ExamDate = '$Date' 
                AND exam_stu_seating.ClassID = $classID
            ) es ON c.ClassID = $classID
            WHERE c.ClassID = $classID;";
    $result = $conn->query($SQL);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(["error" => "No data found"]);
    }
    $conn->close();

}elseif($_GET['Mode']==1){
    $classID=$_GET['ClassID'];
    $Date=$_GET['Date'];
    $sql="SELECT programmes.programmes_scode AS Depart, 
       COUNT(*) AS StudentCount
        FROM students_details 
        INNER JOIN exam_students ON exam_students.RollNo = students_details.RollNo
        INNER JOIN examsubjects ON examsubjects.examsubjectsID = exam_students.examsubjectsID
        INNER JOIN exam_stu_seating ON exam_stu_seating.exam_sub_stu_ID = exam_students.exam_sub_stu_ID
        INNER JOIN programmes ON programmes.programmes_id=students_details.programmes_id
        WHERE examsubjects.ExamDate = '$Date' 
        AND exam_stu_seating.ClassID = $classID
        GROUP BY students_details.programmes_id;";

    $result = $conn->query($sql);
    
    $data = [];
    
    if ($result->num_rows > 0) {
        $i=1;
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                "index"=>$i,
                "Depart" => $row["Depart"],
                "StudentCount" =>$row["StudentCount"]
            ];
            $i++;
        }
    }
    echo json_encode($data, JSON_PRETTY_PRINT);
    $conn->close();
}else{
    $conn->close();
    echo json_encode(["status" => "error", "message" => "Unknown Error"]);
    exit;
}

?>