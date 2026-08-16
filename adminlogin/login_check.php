<?php
include('../Common/Connections.php');
    if(isset($_POST['email'])){
        $email=$_POST['email'];
        $pass=$_POST['password'];
        $SQL="SELECT * FROM `admin_data` WHERE Admin_email='$email' AND Admin_password='$pass'";
        $values=$conn->query($SQL);
        if($values->num_rows>0){
            session_start();
            $_SESSION['User']=$values->fetch_assoc()['Admin_name'];
            header('location:../homescreen');
        }else{
            header("location:adminlogin.php?error=Wrong email or password");
        }
    }
?>