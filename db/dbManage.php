<?php
if(!isset($_POST['File'])){
    header("location:index.php");
}


function TableNamesDetails($filePath) {
    if (!file_exists($filePath)) {
        die("File not found.");
    }

    $sqlContent = file_get_contents($filePath);

    preg_match_all('/INSERT\s+INTO\s+`?(\w+)`?.*?;/is', $sqlContent, $matches, PREG_OFFSET_CAPTURE);

    $insertStatements = [];
    foreach ($matches[0] as $index => $match) {
        $fullQuery = $match[0];
        $tableName = $matches[1][$index][0];

        $insertStatements[] = [
            'table' => $tableName,
            'query' => $fullQuery
        ];
    }

    return $insertStatements;
}

$sqlFilePath = $_POST['File'];
$insertQueries = TableNamesDetails($sqlFilePath);


    // echo "<h1>{$item['table']}</h1>\n";
    // echo "<pre>{$item['query']}</pre>\n\n<br><hr><br>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <title>Update</title>
    <style>
        body{
            height: 100vh;
            display:flex;
            align-items:center;
            flex-direction:column;
            font-family: 'Poppins', sans-serif;
        }
        table{
            height: fit-content;
        }
        th,td{
            padding: 5px 20px;
        }
        tr td:last-child{
            margin: 0 auto;
        }
        button{
            width:100%;
            padding: 10px 20px;
            background-color: #3b98db;
            color:white;
            border:none;
            border-radius:5px;
        }
        tr:last-child td{
            padding:0;
        }
    </style>
</head>
<body>
    <h1>Select Tables To Update</h1>
    
        <table border='1'>
            <thead>
                <th>SI</th>
                <th>Table Name</th>
                <th><input type="checkbox" checked name="" onclick="selectAll(this)" id="All"></th>
            </thead>
            <tbody>
                <form action="Updates.php" method="post" onsubmit='return (confirm("This Process will delete existing data"))'>
                    <input type="hidden" name="File" value='<?=$_POST['File']?>'>
                    <?php $i=1; foreach ($insertQueries as $item) {?>
                    <tr>
                        <td><?=$i++?></td>
                        <td><?=$item['table']?></td>
                        <td><input type="checkbox" checked onclick="selectIt()" name="<?=$item['table']?>" id="tablesD"></td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan='3'><button type="submit">Edit Tables</button></td>
                    </tr>
                </form>
            </tbody>
        </table>
        <script>
            function selectIt(){
                let flag=0;
                document.querySelectorAll("#tablesD").forEach(element => {
                    if(!element.checked){
                        flag=1;
                    }
                });
                if(flag==1){
                    document.getElementById("All").checked=false
                }else{
                    document.getElementById("All").checked=true
                }
            }
            function selectAll(object){
                document.querySelectorAll("#tablesD").forEach(element => {
                    element.checked=object.checked
                });
            }
            
        </script>
</body>
</html>
