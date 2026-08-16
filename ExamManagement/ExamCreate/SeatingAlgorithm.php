
<?php
include('../../Common/Connections.php');
include('../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../adminlogin/adminlogin.php');
}
if(!isset($_SESSION['ExaminationID'])){
    include('../../Common/ExaminationError.php'); 
}

// define('ROWS', 7);
// define('COLS', 5);
// define('MAX_CLASS_STUDENT', ROWS * COLS);


class Classroom {
    public $roomNum;
    public $assignedNum = 0;
    public $seat = [];
    public $ROW=0;
    public $COLUMN=0;
    public $Name;
    public $ClassID=0;

    public function __construct($roomNum,$Row,$Column,$ClassName,$ClID) {
        $this->ROW=$Row;
        $this->COLUMN=$Column;
        $this->ClassID=$ClID;
        $this->Name=$ClassName;
        $this->roomNum = $roomNum + 1;
        $this->seat = array_fill(0, $Row, array_fill(0, $Column, ''));
    }
}

function assignSeats($classrooms, $studentsBySubject,$suppliesStudents) {
    $subjectKeys = array_keys($studentsBySubject);
    $SupplysubjectKeys = array_keys($suppliesStudents);
    $totalSubjects = count($subjectKeys);
    $totalStudentsAssigned = 0;
    $totalStudents = array_sum(array_map('count', $studentsBySubject));

    // Initialize student indices for each subject
    $studentIndices = array_fill(0, $totalSubjects, 0);

    // Track current classroom index and subject index
    $classroomIndex = 0;
    $subjectIndex = 0;
    while ($totalStudentsAssigned < $totalStudents) {
        $currentClassroom = $classrooms[$classroomIndex];
        $currentColumn = 0;
        $MAX_CLASS_STUDENT=$currentClassroom->ROW*$currentClassroom->COLUMN;

        // Fill the current classroom completely before moving to the next
        while ($currentColumn < $currentClassroom->COLUMN && $currentClassroom->assignedNum < $MAX_CLASS_STUDENT) {
            
            $subject = $subjectKeys[$subjectIndex % $totalSubjects];
            
            $students = $studentsBySubject[$subject];
            $studentCount = count($students);

            for ($rowIndex = 0; $rowIndex < $currentClassroom->ROW; $rowIndex++) {
                // echo "<br>".$subjectKeys[$subjectIndex % $totalSubjects]."  :  ".$studentIndices[$subjectIndex % $totalSubjects]."            $studentCount";
                if ($studentIndices[$subjectIndex % $totalSubjects] >= $studentCount) {
                //    echo" Stopped<br>";
                    unset($studentIndices[$subjectIndex % $totalSubjects]);
                    unset($subjectKeys[$subjectIndex % $totalSubjects]);

                    $studentIndices = array_values($studentIndices);
                    $subjectKeys = array_values($subjectKeys);

                    $totalSubjects-=1;
                    if($totalSubjects==0){
                        if(count($SupplysubjectKeys)==0){
                            break 2;
                        }else{
                            $totalSubjects=count($SupplysubjectKeys);
                            $subjectKeys=$SupplysubjectKeys;
                            $studentIndices = array_fill(0, $totalSubjects, 0);
                            $subjectIndex=0;
                            $subject=$SupplysubjectKeys[$subjectIndex % $totalSubjects];
                            $SupplysubjectKeys=[];
                            $studentsBySubject=$suppliesStudents;
                            $totalStudentsAssigned=0;
                            // $totalStudents = array_sum(array_map('count', $studentsBySubject));
                            
                        }
                    }
                    $subject = $subjectKeys[$subjectIndex % $totalSubjects];

                    $students = $studentsBySubject[$subject];
                    $studentCount = count($students);
                    // echo "<br>COntinue ".$subjectKeys[$subjectIndex % $totalSubjects]."  :  ".$studentIndices[$subjectIndex % $totalSubjects]."            $studentCount";
                    $rowIndex--;
                    continue;
                }
                $temp=$currentClassroom->seat[$rowIndex][$currentColumn] = $students[$studentIndices[$subjectIndex % $totalSubjects]];
                // echo "CLASS: ".$classroomIndex." ROW: ".$rowIndex." Colu: ".$currentColumn." Num: ".$temp."   ll".$studentIndices[$subjectIndex % $totalSubjects]."<br>";
                // echo $temp."<br>";
                $currentClassroom->assignedNum++;
                $studentIndices[$subjectIndex % $totalSubjects]++;
            
                $totalStudentsAssigned++;
                

                if ($currentClassroom->assignedNum >= $MAX_CLASS_STUDENT) break;
            }

            $currentColumn++;
            $subjectIndex++;
        }

        // Move to the next classroom if the current one is filled

        // if ($currentClassroom->assignedNum >= MAX_CLASS_STUDENT) {
            $classroomIndex++;
        // }

        // Ensure we break the loop if there are no more classrooms left
        if ($classroomIndex >= count($classrooms)) break;
    }
}
    $Eid=$_SESSION['ExaminationID'];

        if(isset($_POST['classes'])){
        $selectdate="SELECT DISTINCT(ExamDate),session FROM `examsubjects` WHERE ExamID=$Eid ";
        $datedetails=$conn->query($selectdate);
        $classids=$_POST['classes'];
        $classdate=0;
        
        while($datedetailsrow=$datedetails->fetch_assoc()){
            $dateID=$datedetailsrow['ExamDate'];
            $Session=$datedetailsrow['session'];
            $getsubsql="SELECT DISTINCT(programmes_id) FROM `students_details` 
            INNER JOIN exam_students ON exam_students.RollNo=students_details.RollNo 
            INNER JOIN examsubjects ON exam_students.examsubjectsID=examsubjects.examsubjectsID
            WHERE examsubjects.ExamDate='$dateID' AND examsubjects.ExamID=$Eid AND examsubjects.session='$Session'";
            $getval=$conn->query($getsubsql);
            $departarrayv1="";
            echo $getsubsql;
            while($subjectsDate=$getval->fetch_assoc()){
                $departarrayv1.=$subjectsDate['programmes_id'].",";
            }
            // echo $getsubsql;
            $departarrayv1=substr($departarrayv1, 0, -1);
            // echo $departarrayv1;
            $DeaprtmentData = explode(',', $departarrayv1);
            // echo count($DeaprtmentData);
            $datevalue=$datedetailsrow['ExamDate'];
            $classValues=$classids[$classdate];
            $classdate++;
        if (count($DeaprtmentData)>0) {
            $subjectStudentsArr = [];
            $suppliesStudents=[];
            $totalStudents = 0;
            
            foreach ($DeaprtmentData as $departdata){
                $subjectCode=$departdata;
                $subjectStudentsArr[$subjectCode] = [];
                $subject_select="SELECT * FROM `examsubjects` 
                INNER JOIN exam_students on exam_students.examsubjectsID=examsubjects.examsubjectsID
                INNER JOIN students_details on students_details.RollNo=exam_students.RollNo
                INNER JOIN programmes ON programmes.programmes_id=students_details.programmes_id
                WHERE students_details.programmes_id='$subjectCode' AND examsubjects.ExamID=$Eid AND examsubjects.ExamDate='$dateID' AND examsubjects.session='$Session'";
                // echo $subject_select;

                $subject_select_q=$conn->query($subject_select);
                while($row2=$subject_select_q->fetch_assoc()){
                    if($row2['exam_status']!=-1){
                        $subjectStudentsArr[$subjectCode][] = $row2['programmes_scode']."-".$row2['RollNo']."-".$row2['exam_sub_stu_ID'];
                    }else{
                        $suppliesStudents[$subjectCode][]=$row2['programmes_scode']."-".$row2['RollNo']."-".$row2['exam_sub_stu_ID'];
                    }
                }
                // echo $subject_select_q->num_rows."<br>";
                $totalStudents += intval($subject_select_q->num_rows);
            }

            $class_data=getclass($totalStudents,$classValues);
            $totalClasses=count($class_data);
            $classrooms = array_map(function ($i)use ($class_data) {
                return new Classroom($i,$class_data[$i]['ClassRows'],$class_data[$i]['ClassColumns'],$class_data[$i]['ClassName'],$class_data[$i]['ClassID']);
            }, range(0, $totalClasses - 1));
            $departmentDatav1 = array_keys($subjectStudentsArr);
            assignSeats($classrooms, $subjectStudentsArr,$suppliesStudents);

            foreach ($classrooms as $classroom) {
                $class_row=1;
                foreach ($classroom->seat as $row) {
                    $ClassID=$classroom->ClassID;
                    $class_col=1;
                    foreach ($row as $seat) {
                        if($seat){
                            $Details = explode('-', $seat);
                            // $rollnum=$Details[0];
                            $stu_ID=$Details[2];
                            $CheckSeatsql="SELECT * FROM exam_stu_seating WHERE exam_sub_stu_ID='$stu_ID'";
                            $checkseat=$conn->query($CheckSeatsql);
                            if($checkseat->num_rows>0){
                                $seatingdata="UPDATE `exam_stu_seating` SET `ClassID`='$ClassID',`class_row`='$class_row',`class_col`='$class_col' WHERE `exam_sub_stu_ID`='$stu_ID'";
                                // echo $seatingdata;
                            }else{
                                $seatingdata="INSERT INTO `exam_stu_seating`(`exam_sub_stu_ID`, `ClassID`, `class_row`, `class_col`) VALUES ('$stu_ID','$ClassID','$class_row','$class_col')";
                            }
                            $conn->query($seatingdata);
                            $class_col++;
                        }
                    }
                    $class_row++;
                }

            }
            
        } else {
            echo "<h1>Error: Invalid input</h1>";
            echo "<p>Please ensure the number of subjects and corresponding details are provided correctly.</p>";
        }
    }
}

function getclass($totalStudents,$classValues){
    global $conn;
    $class_strength=0;
    $class_id=1;
    $class=0;
    $class_data=[];
    $ClassVa = explode(',', $classValues);
    sort($ClassVa);
    while($class_strength<$totalStudents){
        
        if($classValues==0||count($ClassVa)==0||(!$ClassVa[$class])){
            $sql = "SELECT * FROM classroom WHERE ClassID = $class_id";
            $MainClassID=$class_id;
        }else{
            $sql = "SELECT * FROM classroom WHERE ClassID = ".$ClassVa[$class];
            $MainClassID=$ClassVa[$class];
        }

        
        $result = $conn->query($sql);
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            $class_strength+= $row['ClassRows']*$row['ClassColumns'];
            $class_data[$class]=[
                'ClassID'=>$MainClassID,
                'ClassName'=>$row['ClassName'],
                'ClassColumns'=>$row['ClassColumns'],
                'ClassRows'=>$row['ClassRows'],
            ];
        }else{
            $class_data[$class]=[
                'ClassID'=>$class_id,
                'ClassName'=>"UNKNOWN",
                'ClassColumns'=>5,
                'ClassRows'=>7,
            ];
            $class_strength+= 5*7;
        }
        $class++;
        $class_id++;
    }
    return $class_data;
}
$updatestatus="UPDATE `examination` SET `Status`='-1' WHERE ExamID=$Eid";
$conn->query($updatestatus);
header("Location: ExaminationSeatings.php");
?>