<?php
    include_once('api.php');
    include_once('../Common/Connections.php');
    if(!isset($_GET['Mode'])){
        $conn->close();
        echo json_encode(["status" => "error", "message" => "Missing Values"]);
        exit;
    }
    if($_GET['Mode']==0){
        if(!isset($_GET['User'])|| !isset($_GET['Pass'])){
            echo json_encode(["status" => "error", "message" => "Missing Values"]);
            exit;
        }
        $UserName=$_GET['User'];
        $password=$_GET['Pass'];
        $stmt = $conn->prepare("SELECT invid FROM `invigilators` WHERE inviemail=? AND invi_pass= ?");
        $stmt->bind_param("ss", $UserName, $password);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($invid);
        $stmt->fetch();
        if ($stmt->num_rows > 0) {
            echo json_encode(["status" => "success", "Log" => true,"invid"=>$invid]);
        } else {
            echo json_encode(["status" => "success", "Log" => false,"invid"=>null]);
        }
        $conn->close();
    }else if($_GET['Mode']==1){
        if(!isset($_GET['Invid'])){
            echo json_encode(["status" => "error", "message" => "Missing Values"]);
            exit;
        }
        $Invid=$_GET['Invid'];
        $stmt = $conn->prepare("SELECT invi_name FROM `invigilators` WHERE invid=?");
        $stmt->bind_param("s", $Invid);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($invi_name);
        $stmt->fetch();
        $conn->close();
        echo json_encode(["status" => "success", "UserName" => $invi_name]);
    }else{
        $conn->close();
        echo json_encode(["status" => "error", "message" => "Unknown Error"]);
        exit;
    }
    

?>