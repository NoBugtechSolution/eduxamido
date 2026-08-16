<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title>EXAM</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Exam Dates</h1>
    <form action="SubjectsSelect.php" method="POST" id="" class='noflex'>
        
        <?php
        include('../../../Common/Session.php');
        if(!isset($_SESSION['User'])){
            header('location:../../../adminlogin/adminlogin.php');
        }
         if(!isset($_POST['departs'])){
            die("Some Deatils went missing please <a href='StudentsDetailsUpload.php'>try again</a>");
        } foreach($_POST['departs'] as $data1){?>
            <input type="hidden" value="<?php echo $data1; ?>" name="departs[]">
        <?php }?>
        <textarea name="DatabaseEntry" style="display:none;"><?php echo $_POST['DatabaseEntry']; ?></textarea>
        <div id="DateInput" class="date-inputs"></div>
        <button type="button" class="add-date-btn"  onclick="Create_Dates()">Add Date</button>
        <input type="submit" value="Submit">
    </form>

    <script>
        const DateInputsDiv = document.getElementById('DateInput');
        let DateCount = 1;

        function Create_Dates() {
            const div = document.createElement('div');
            Input_boxs = `<div>
                <label for="Date${DateCount}">Exam Date${DateCount}</label>
        <div style="display:flex; gap:20px;align-items:center;" ><input style="width:100%" type="date" id="Date${DateCount}" name="ExamDate[]" required>
            `;
            if(DateCount>1){
                Input_boxs+=`<button onclick='removeInput(this)' type='button' style="height:35px;background-color:red;color:white;border:none;border-radius: 4px;" >REMOVE</button></div></div>`;
            }else{
                Input_boxs+=`</div></div>`;
            }
            div.innerHTML=Input_boxs
            DateInputsDiv.appendChild(div);
            DateCount++;
        };
        function removeInput(object){
            Parent_data=object.parentNode.parentNode;
            dataP=Parent_data.parentNode.parentNode;
            Parent_data.remove();
            DateCount--;
            labels=dataP.querySelectorAll('label');
            i=1;
            labels.forEach(function(label) {
                label.textContent="Exam Date"+i; 
                i++;
            });
        }
        Create_Dates();
    </script>
</body>
</html>