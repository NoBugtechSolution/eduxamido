
<?php
include('../../../Common/Connections.php');
include('../../../Common/Session.php');
if(!isset($_SESSION['User'])){
    header('location:../../../adminlogin/adminlogin.php');
}
if(!isset($_SESSION['ExaminationID'])){
    include('../../../Common/ExaminationError.php'); 
}
$ElectiveOpenData=$conn->query("SELECT * FROM `courses` WHERE course_type IN ('Elective','OpenCourse')");
$OtherCourses=[];
while($dataR=$ElectiveOpenData->fetch_assoc()){
    $OtherCourses[]=$dataR['course_id'];
}
if(isset($_POST['DataCreate'])){
    $count_data=0;
    $Eid=$_SESSION['ExaminationID'];
    $Students=$_POST['DatabaseEntry'];
    $updatestatus="UPDATE `examination` SET `Status`='-2' WHERE ExamID=$Eid";
    $datevalues='';
    $subjectID=1;
    foreach ($_POST['ExamDate'] as $date) {

        echo "<br>".$date."<br>";

        $depidsvalue=$date.'departsSubject';
        $subjnameiddate=$date.'SubjectName';
        $session=$date.'session';
        $subjnameidsvalue=$_POST[$subjnameiddate];
        $sessionData=$_POST[$session];
        $count_data=0;
        
        
        foreach ($_POST[$depidsvalue] as $depart) {
            $d1=$subjnameidsvalue[$count_data];
            // echo "<br><br><br>SubjectCode: ".$subjcodeidsvalue[$count_data]."<-->      SubjectName: ".$subjnameidsvalue[$count_data]."   <--> Departs:  ".$depart."<br><br><br>";
            $srt="INSERT INTO `examsubjects`(`ExamID`, `course_id`, `examHour`, `Qp_code`, `ExamDate`, `ExamStatus`, `session`) VALUES ('$Eid','".$subjnameidsvalue[$count_data]."','3','0','$date','0','".$sessionData[$count_data]."')";
            $conn->query($srt);

            $maxsubjectsql="SELECT MAX(examsubjectsID) as MAXID FROM `examsubjects` ";
            $maxsubjectq=$conn->query($maxsubjectsql)->fetch_assoc();
            $subjectID=$maxsubjectq['MAXID'];
            $index=in_array($subjnameidsvalue[$count_data], $OtherCourses);
            if($index>-1 || $depart=='Others'){
                $count_data++;
               continue;
            }
            
            $DeaprtmentData = explode(',', $depart);
            foreach($DeaprtmentData as $qwe){
                echo "<br>Department: $qwe<br>";
                $DespData=$conn->query("SELECT * FROM `programmes` WHERE programmes_scode='$qwe'")->fetch_assoc();
                $DespDataID=$DespData['programmes_id'];
                // $dpinsert="INSERT INTO `subject_for_programmes`( `examsubjectsID`, `programmes_id`) VALUES ('$subjectID','$DespDataID')";
                // $conn->query($dpinsert);
                $StudentsDetails=explode(',', $Students);
                // echo "$dpinsert<br>";
                foreach( $StudentsDetails as $details){
                    $SelectstudSQL="SELECT * FROM students_details WHERE RollNo='$details' AND programmes_id='$DespDataID'";
                    $Selected=$conn->query($SelectstudSQL);
                    if($Selected->num_rows==0){
                        continue;
                    }
                    $InsertStudentsql="INSERT INTO `exam_students`( `RollNo`, `examsubjectsID`, `exam_status`) VALUES ('$details','$subjectID','0')";
                    // echo "<br><br>$InsertStudentsql<br><br>";
                    $conn->query($InsertStudentsql);
                }
                
            }
            $count_data++;
            
            // $conn->query($srt);
            $subjectID++;
        }
        echo $datevalues;
        
    }
    $datevalues=substr($datevalues, 0, -1);
    $conn->query($updatestatus);
    header("Location: SpecialExam.php");
}

$semNo=$conn->query("SELECT MAX(semester) AS MaxSem FROM `courses`")->fetch_assoc()['MaxSem'];
$subjectsData=$conn->query("SELECT * FROM `courses` INNER JOIN departments ON departments.department_id=courses.department_id ORDER BY department_name,course_type");

?>
<script>
    dateValue=[];
    examsdepdata=[];
    department=[];
    i=0;
    maxsemNo=0;
    SubjectsOfSem=[];
    SubjectsCodeOfSem=[];
    SubjectProvider=[];
    OtherCourses=[];
<?php
$departmentarray=[];
foreach($_POST['departs'] as $data){
    $departmentarray[]=$data;
    ?>
department.push('<?php echo preg_replace("/[^a-zA-Z0-9 ]/", "", $data); ?>');
    <?php
}
foreach($OtherCourses as $Other){
    echo "OtherCourses.push(`$Other`);";
}

foreach ($_POST['ExamDate'] as $date) {
    ?>
    dateValue[i+"AM"]=[department[0]];
    dateValue[i+"PM"]=[];
    examsdepdata[i]=[];
    examsdepdata[i][0]=[department[0]];
    
    i++;
    
    <?php
}
for($i=1;$i<=$semNo;$i++){
    echo"SubjectsOfSem[$i]=[];";
    echo"SubjectsCodeOfSem[$i]=[];";
    echo"SubjectProvider[$i]=[];";
}
while($dataRow=$subjectsData->fetch_assoc()){
    echo "SubjectsOfSem[".$dataRow['semester']."].push(`".$dataRow['course_name']." - ".$dataRow['course_type']."`);";
    echo "SubjectsCodeOfSem[".$dataRow['semester']."].push(".$dataRow['course_id'].");";
    echo "SubjectProvider[".$dataRow['semester']."].push(`".$dataRow['department_name']."`);";
}

echo "maxsemNo=$semNo;";

?>
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title>EXAM</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            color: #2c3e50;
        }
        
        label, input,select {
            display: block;
            margin: 10px 0;
            width: 100%;
        }
        input[type="text"], select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,select:focus {
            border-color: #3498db;
            outline: none;
        }
        input[type="submit"],input[type="button"] {
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover,input[type="button"]:hover {
            background-color: #2980b9;
        }
        .depart-inputs {
            margin: 10px 0;
        }
        .add-depart-btn {
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            margin: 10px 0;
            
        }
        .add-subject-btn:hover {
            background-color: #27ae60;
        }
        h4{
            text-align:center;
            margin:5px
        }

        .remove_data{
            height:35px;
            background-color:red;
            color:white;
            border:none;
            border-radius: 4px;
            cursor: pointer;
        }
        .radiosselect{
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap:10px;
        }
        .radiosselect>label>input{
            display:none;
        }
        .radiosselect>h4{
            grid-column:span 3;
            margin:0;
            width: 100%;
        }
        .radiosselect>label{
            padding: 0;
            height:44px;
            display: grid; 
            place-content: center;
            gap: 0px;
            background-color:#aeaeae;
            border-radius:10px;
            color:white;
            cursor: pointer;
            transition: background-color .1s ease-in-out;
        }
        .radiosselect>label:hover{
            background-color:#7173cf;
        }
        .radiosselect>.active{
            background-color:#3f98df !important;
        }
        .radiosselect>label>input{
            margin:0;
            height:20px;
            padding: 0;
        }
        .the-space{
            display:grid;
            <?php if(count($_POST['ExamDate'])>3){$val=3;}else{$val=count($_POST['ExamDate']);} echo "grid-template-columns: repeat($val,1fr);";?>
            gap:14px;
        }
        .thediffdates {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            height:fit-content;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .selections{
            display:flex;
            flex-direction:column;
            row-gap:20px;
        }
        .selections>div{
            background-color:#f4f4f4;
            padding:20px;
            border-radius:10px;
        }
        .selections>div>h6{
            margin:0;
        }.selections>h4{
            text-decoration:underline;
        }
    </style>
</head>
<body>
    <h1>Subjects For Exam</h1>
    <form method="post" id="" onsubmit='return checkSubjectIn()'>
    <textarea name="DatabaseEntry" style='display:none;' ><?=$_POST['DatabaseEntry']; ?></textarea>
    <div class="the-space">
        <?php $co=0;    foreach ($_POST['ExamDate'] as $date) {?>
            <input type="hidden" name="ExamDate[]" value="<?=$date;?>">
        <div class="thediffdates" style="background-color:#efefef;padding:5px;border-radius:6px;" class="depart-inputs">
            <div id="P<?=$co;?>" class="selections">
                <h4><?=$date;?></h4>
                <div>
                <h6>Subject 1</h6>
                <hr>
                    <div style="display:flex; flex-direction:column;  gap:0px;align-items:center;">
                        <input type="text" name="<?=$date; ?>departsSubject[]" value="<?=$_POST['departs'][0]; ?>" id="inp<?=$co;?>:0" readonly required>
                        <select  id="" required onchange='DisplayCourse(this)'>
                            <option value="0" selected disabled>Select The Semester</option>
                            <?php
                            for($i=1;$i<=$semNo;$i++){
                                echo "<option value='$i'>$i</option>";
                            }
                            ?>
                        </select>
                         <select name="<?=$date; ?>SubjectName[]" id="SubjectNameSelect" required onchange="ChangeCourse(this,<?=$co;?>,0)">
                            <option value="0" selected disabled>First Select The Semester</option>
                        </select>
                        <select name="<?=$date;?>session[]" id="session" onchange="ChangeSession(this,<?=$co;?>,0)">
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                    <div class="radiosselect" id="Se<?=$co;?>">
                        <h4>Programs having this subject as exam</h4>
                        <?php
                        $i=0;
                        foreach($_POST['departs'] as $data){
                            $da="";
                            $cl="";
                            if($i==0){
                                $da="checked";
                                $cl="class='active'";
                                $i++;
                            }
                            ?>
                        <label id="<?=preg_replace("/[^a-zA-Z0-9 ]/", "", $data);?>" <?=$cl;?> >
                            <input  type='checkbox' onclick="ChangeRadioSelect(this,<?=$co;?>,0)" value='<?=preg_replace("/[^a-zA-Z0-9 ]/", "", $data);?>' <?=$da; ?>> <?=preg_replace("/[^a-zA-Z0-9 ]/", "", $data); ?>
                        </label>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php if(count($departmentarray)!=0){?>
            <button type="button" class="add-depart-btn" id="addDepartBtn<?=$co;?>" onclick="add_Subject('P<?=$co;?>','Se<?=$co;?>','<?=$co;?>','<?=$date;?>')">Add New Subject</button>
            <?php }?>
        </div>
        <?php $co++; }?>
        </div>
        <input type="<?php echo (count($departmentarray)!=0)?'submit':'button'?>" name="DataCreate" value="Create Subject">

    </form>

    <script>
        var invaluesdatac=0;
        function checkSubjectIn(){
            flag=0;
            InputBoxs=document.querySelectorAll("#SubjectNameSelect")
            for(let j=0;j<InputBoxs.length;j++){
                if(InputBoxs[j].value=='0'){
                    alert("Selection Pending")
                    flag=1;
                    return false;
                }
            }
            
            return true;
        }
        function DisplayCourse(obj){
            values=obj.value;
            parent=obj.parentNode;
            courseObj=parent.querySelector("#SubjectNameSelect")
            newOption=``;
            let label=``;
            SubjectsCodeOfSem[values].forEach((element,index) => {
                if(label==``){
                    newOption+=`<optgroup label="${SubjectProvider[values][index]}">`;
                    label=SubjectProvider[values][index];
                }else{
                    if(label!=SubjectProvider[values][index]){
                        newOption+=`</optgroup>`;
                        label=SubjectProvider[values][index];
                        newOption+=`<optgroup label="${SubjectProvider[values][index]}">`;
                    }
                }
                newOption+=`<option value="${SubjectsCodeOfSem[values][index]}" >${SubjectsOfSem[values][index]}</option>`
            });
            courseObj.innerHTML=`
                <option value="0" selected disabled>Select Course name</option>
                ${newOption}
            `;
        }
        function add_Subject(object,objectid,number,date) {
            invaluesdatac++;
            let Selectsession="AM";
            dateValue[number+Selectsession].length==department.length?Selectsession="PM":Selectsession="AM";
            DepartInputsDiv = document.getElementById(object);
            const div = document.createElement('div');
             htmldata= `
                <h6>Subject ${(invaluesdatac+1)}</h6>
                <hr>
                    <div style="display:flex;  flex-direction:column;  gap:0px;align-items:center;">
                        <input type="text" name="${date}departsSubject[]" id="inp${number}:${invaluesdatac}" required readonly>

                        <select  id="" required onchange='DisplayCourse(this)'>
                            <option value="0" selected disabled>Select The Semester</option>
                            <?php
                            for($i=1;$i<=$semNo;$i++){
                                echo "<option value='$i'>$i</option>";
                            }
                            ?>
                        </select>
                        <select name="${date}SubjectName[]" id="SubjectNameSelect" required onchange="ChangeCourse(this,${number},${invaluesdatac})"><option value="0" selected disabled>First Select The Semester</option></select>
                        <select name="${date}session[]" id="session" onchange="ChangeSession(this,${number},${invaluesdatac})">
                            <option value="AM" ${(Selectsession=='AM')?"selected":""}>AM</option>
                            <option value="PM" ${(Selectsession=='PM')?"selected":""}>PM</option>
                        </select>
                        <button style='width:100%;margin-bottom:10px;' onclick='removeInput(this,${number},${invaluesdatac})' type='button' class='remove_data' >REMOVE</button>
                    </div>
                    <div class="radiosselect" id="${objectid}">
                        <h4>Programs having this subject as exam</h4>`
            departTemp='';
        department.forEach(function(element) {

             indexV = dateValue[number+Selectsession].indexOf(element);
            if (indexV == -1) {
                checks='';
                clasq='';
                if(departTemp==''){
                    departTemp=element;
                    checks="checked";
                    clasq="class='active'"
                }
                htmldata+=`
                        <label id='${element}' ${clasq}>
                            <input  onclick="ChangeRadioSelect(this,${number},${invaluesdatac})" type='checkbox' value='${element}' ${checks}> ${element}
                        </label>`
            }else{
                htmldata+=`
                        <label id='${element}' style='display:none;' >
                            <input  onclick="ChangeRadioSelect(this,${number},${invaluesdatac})" type='checkbox' value='${element}' > ${element}
                        </label>`
            }
        });
        

        htmldata+=`</div></div>`;

            div.innerHTML=htmldata;
            DepartInputsDiv.appendChild(div);
            if(departTemp!=''){
                dateValue[number+Selectsession].push(departTemp);
            }
            if(dateValue[number+Selectsession].length>=department.length){
                document.getElementById('addDepartBtn'+number).style.display='none';
            }
            allinputs=DepartInputsDiv.querySelectorAll('#'+departTemp);
            let thisSelector=div.querySelector("#session");
            DepartInputsDiv.querySelectorAll('#session').forEach(function(element){
                if(thisSelector!=element){
                    if(element.value==thisSelector.value){
                        let ogbody=(element.parentNode.parentNode)
                        ogbody.querySelector('#'+departTemp).style.display='none';
                    }
                }
            })
            examsdepdata[number][invaluesdatac]=[departTemp];
            document.getElementById("inp"+number+":"+invaluesdatac).value=examsdepdata[number][invaluesdatac];
        };


        function ChangeSession(obj,number,invaluesdatac){
            

            let selectedSession=obj.value;
            let Parent_data=obj.parentNode.parentNode;
            let prevCollection=[];
            let prevSession=(selectedSession=='AM')?'PM':'AM';

            dateValue[number+prevSession]=[];

            Parent_data.querySelectorAll('input[type="checkbox"]').forEach(function(element){
                if(element.checked){
                    prevCollection.push(element.value);
                }else{
                    element.parentNode.style.display='grid';
                }
            })
            Parent_data.parentNode.querySelectorAll('#session').forEach(function(element){
                if(element!=obj){
                    if(element.value!=selectedSession){
                        let ogbody=(element.parentNode.parentNode)
                        prevCollection.forEach(element => {
                            ogbody.querySelector('#'+element).style.display='grid';
                        });
                    }
                }
            })
            let remover=[];
            Parent_data.parentNode.querySelectorAll('#session').forEach(function(element){
                if(element!=obj){
                    if(element.value==selectedSession){
                        let ogbody=(element.parentNode.parentNode)
                        ogbody.querySelectorAll('input[type="checkbox"]').forEach(function(element){
                            if(element.checked){
                                remover.push(element.value);
                            }
                        })
                    }
                }
            });

            remover.forEach(element => {
                let removed=Parent_data.querySelector('#'+element);
                removed.style.display='none';
                removed.classList.remove('active');
                removed.querySelector('input[type="checkbox"]').checked=false;
            });
            let InputVal=obj.parentNode.parentNode.querySelector('input');
            let op=0;
            let newarry=[];
            Parent_data.querySelectorAll('input[type="checkbox"]').forEach(function(element){
                if(element.checked){
                    newarry.push(element.value);
                }
            });
            if(InputVal.value!="Others"){
                InputVal.value=newarry;
            }
            examsdepdata[number][invaluesdatac]=[];
            newarry.forEach(function(element){
                dateValue[number+selectedSession].push(element);
            });
            examsdepdata[number][invaluesdatac]=newarry;
            examsdepdata[number][invaluesdatac].forEach(values => {
                let ore=Parent_data.parentNode.querySelectorAll('#'+values)
                ore.forEach(element => {
                    let TheobjMain=element.parentNode.parentNode
                    if(TheobjMain!=Parent_data){
                        let ses=TheobjMain.querySelector('#session');
                        if(ses.value==selectedSession){
                        element.style.display='none'
                        }
                    }
                });
                
            });
            
            
        }
        function removeInput(object,number,thevalue){
            invaluesdatac--;
            let Parent_data=object.parentNode.parentNode;
            let obj=Parent_data.querySelector('#session');
            let selectedSession=obj.value;
            let Recovers=[];
            Parent_data.querySelectorAll('input[type="checkbox"]').forEach(function(element){
                if(element.checked){
                    dateValue[number+selectedSession]= dateValue[number+selectedSession].filter(item => item !== element.value);
                    Recovers.push(element.value)
                }
            });
            Parent_data.parentNode.querySelectorAll('#session').forEach(function(element){
                if(element!=obj){
                    if(element.value==selectedSession){
                        let ogbody=(element.parentNode.parentNode)
                        Recovers.forEach(element => {
                            ogbody.querySelector('#'+element).style.display='grid';
                        });
                    }
                }
            });
            examsdepdata[number][thevalue]=[];
            
            document.getElementById('addDepartBtn'+number).style.display='grid';
            Parent_data.remove();
        }

        function ChangeRadioSelect(object,data,newnumber){
            if(object.parentNode.classList.contains("active")){
                object.parentNode.classList.remove("active");
                object.checked=false;
            }else{
                object.parentNode.classList.add("active");
                object.checked=true;
            }
            selectedvalue=object.value;
            Parent_data=object.parentNode.parentNode.parentNode.parentNode;
            inputlabels=[];
            boxparent=object.parentNode.parentNode.parentNode;
            let SelectedSession=boxparent.querySelector('#session');
            Parent_data.querySelectorAll('#session').forEach(function(element){
                if(SelectedSession!=element){
                    if(element.value==SelectedSession.value){
                        let ogbody=(element.parentNode.parentNode)
                        inputlabels.push(ogbody.querySelector('#'+selectedvalue));
                    }
                }
            })
            let selectorValue=SelectedSession.value;
            if(object.checked){
                
                Mode=1;
                examsdepdata[data][newnumber].push(selectedvalue)
                dateValue[data+selectorValue].push(selectedvalue);
            }else{
                
                Mode=0;
                if (dateValue[data+selectorValue] && dateValue[data+selectorValue].includes(selectedvalue)) {
                    const index = dateValue[data+selectorValue].indexOf(selectedvalue);
                    if (index > -1) {
                        dateValue[data+selectorValue].splice(index, 1);
                    }
                }

                if (examsdepdata[data] && examsdepdata[data][newnumber].includes(selectedvalue)) {
                    const index = examsdepdata[data][newnumber].indexOf(selectedvalue);
                    if (index > -1) {
                        examsdepdata[data][newnumber].splice(index, 1);
                    }
                }
            }
            inputlabels.forEach(function(inp){
                if(Mode==1){
                    if(inp!=object.parentNode){
                        inp.style.display='none';
                    }
                }else{
                    if(inp!=object.parentNode){
                        inp.style.display='grid';
                    }
                }
            })
            if(dateValue[data+"PM"].length==department.length && dateValue[data+"AM"].length==department.length){
                document.getElementById('addDepartBtn'+data).style.display='none';
            }else{
                document.getElementById('addDepartBtn'+data).style.display='grid';
            }
            document.getElementById("inp"+data+":"+newnumber).value=examsdepdata[data][newnumber];
        }


        function ChangeCourse(obj,number,invaluesdatac){
            let corseId=obj.value;
            let bodyv=obj.parentNode.parentNode;
            let InputVal=bodyv.querySelector('input');
            if(OtherCourses.indexOf(corseId) != -1){
                bodyv.querySelector('#Se'+number).style.display='none';
                let sessionValue=bodyv.querySelector('#session').value;
                let toRemove=[];
                bodyv.querySelectorAll('input[type="checkbox"]').forEach(function(element){
                    element.checked=false;
                    toRemove.push(element.value);
                    element.parentNode.classList.remove('active');
                });
                examsdepdata[number][invaluesdatac].forEach(element => {
                    if (dateValue[number+sessionValue] && dateValue[number+sessionValue].includes(element)) {
                        const index = dateValue[number+sessionValue].indexOf(element);
                        if (index > -1) {
                            dateValue[number+sessionValue].splice(index, 1);
                        }
                    }
                });
                InputVal.value="Others"
            }else{
                if(InputVal.value=="Others"){
                    bodyv.querySelector('#Se'+number).style.display='grid';
                    InputVal.value=examsdepdata[number][invaluesdatac];
                }
            }
        }

    </script>
</body>
</html>