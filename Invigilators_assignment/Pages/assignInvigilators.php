<?php
            include('../../Common/Connections.php');
            include('../../Common/Session.php');
            if(!isset($_SESSION['User'])){
                header('location:../../adminlogin/adminlogin.php');
            }
            if(!isset($_SESSION['ExaminationID'])){
                include('../../Common/ExaminationError.php'); 
            }
            $Eid=$_SESSION['ExaminationID'];
            $datesArray=[];
            $selectdate="SELECT DISTINCT(ExamDate),session FROM `examsubjects` WHERE ExamID=$Eid ";
            $datedetails=$conn->query($selectdate);
            
            
        
            
            
            // Update assignments
            $invc = "SELECT * FROM invigilators WHERE invi_status = 1";
            $invcount = mysqli_query($conn, $invc);
            while($datedetailsrow=$datedetails->fetch_assoc()){
                $date=$datedetailsrow['ExamDate'];
                $Session=$datedetailsrow['session'];
                $classselect="SELECT * FROM classroom WHERE ClassID IN (SELECT DISTINCT(exam_stu_seating.ClassID) FROM `exam_stu_seating` 
                INNER JOIN exam_students on exam_students.exam_sub_stu_ID=exam_stu_seating.exam_sub_stu_ID
                INNER JOIN examsubjects on examsubjects.examsubjectsID=exam_students.examsubjectsID
                INNER JOIN examination ON examination.ExamID=examsubjects.ExamID
                WHERE examsubjects.ExamDate='$date' AND examsubjects.session='$Session' AND examination.ExamID=$Eid)";
                $classrooms=$conn->query($classselect);
                $exp="";
                while ($classroom=$classrooms->fetch_assoc()) {
                    $class=$classroom['ClassID'];
                    // Fetch invigilators for the current date
                    if($exp==''){
                        $sql = "SELECT * FROM invigilators WHERE invi_status = 1  ORDER BY invi_duty_count ASC LIMIT 1";
                    }else{
                        $tr=substr($exp, 0, -1);
                        $sql = "SELECT * FROM invigilators WHERE invi_status = 1 AND invid not in ($tr) ORDER BY invi_duty_count ASC LIMIT 1";

                    }
                    $result = mysqli_query($conn, $sql);
                    
                    if($classrooms->num_rows>$invcount->num_rows || $invcount->num_rows==0){
                        echo "<center style='font-size:25px;'>Not enough invigilators</center>";
                        return;
                    }
                    
                    if (!$result) {
                        die("Error fetching invigilators: " . mysqli_error($conn));
                    }

                    $invigilator = mysqli_fetch_assoc($result);

                    $check1="SELECT * FROM assignment WHERE ClassID='$class' AND inv_id='$inv_id' AND a_exam_date='$date' AND session='$Session'";
                    $checkValu=$conn->query($check1);
                    if($checkValu->num_rows==0){

                        if ($invigilator) {
                            $inv_id = $invigilator["invid"];
                            $name = $invigilator["invi_name"];
                            $exp.="$inv_id,";

                            // Update duty count
                            $update_sql = "UPDATE invigilators SET invi_duty_count = invi_duty_count + 1 WHERE invid = $inv_id";
                            $conn->query($update_sql);
                            $checksql="SELECT * FROM assignment WHERE ClassID='$class' AND a_exam_date='$date' AND session='$Session'";
                            $checkassign=$conn->query($checksql);
                            // Insert assignment
                            if($checkassign->num_rows==0){
                                $insert_sql = "INSERT INTO assignment (ClassID, inv_id, a_exam_date,session) VALUES ($class, $inv_id, '$date','$Session')";
                                $conn->query($insert_sql);
                            }else{
                                $assignDetails=$checkassign->fetch_assoc();
                                $invy=$assignDetails['inv_id'];
                                $update_in_sql = "UPDATE invigilators SET invi_duty_count = invi_duty_count - 1 WHERE invid = $invy";
                                $conn->query($update_in_sql);
                                $assignment_id=$assignDetails['assignment_id'];
                                $update_assign_sql = "UPDATE assignment SET inv_id = $inv_id WHERE assignment_id = $assignment_id";
                                $conn->query($update_assign_sql);
                            }
                        }
                    }
                }
            }
            $updatestatus="UPDATE `examination` SET `Status`='0' WHERE ExamID=$Eid";
            $conn->query($updatestatus);
            header("Location: assignment.php");
?>