<?php
if(!isset($_POST['File'])){
    header("location:index.php");
}
include("../Common/Connections.php");
$sqlFilePath = $_POST['File'];
$insertQueries = TableNamesDetails($sqlFilePath);
$SelectedTables=array_keys($_POST);

function TableNamesDetails($filePath) {
    if (!file_exists($filePath)) {
        die("File not found.");
    }

    $sqlContent = file_get_contents($filePath);

    preg_match_all('/CREATE\s+TABLE\s+`?(\w+)`?.*?\;/is', $sqlContent, $matches, PREG_OFFSET_CAPTURE);
    $createTable=[];
    $MainQuery=[];
    $CREATETABLE=[];
    foreach ($matches[0] as $index => $match) {
        $fullQuery = $match[0];
        $tableName = $matches[1][$index][0];
        if (array_key_exists($tableName, $CREATETABLE)) {
            $CREATETABLE[$tableName] .= $fullQuery;
        } else {
            $CREATETABLE[$tableName] = $fullQuery;
        }
    }

    preg_match_all('/INSERT\s+INTO\s+`?(\w+)`?.*?;/is', $sqlContent, $matches, PREG_OFFSET_CAPTURE);

    $insertStatements = [];
    $InsertValue=[];
    
    foreach ($matches[0] as $index => $match) {
        $fullQuery = $match[0];
        $tableName = $matches[1][$index][0];
        if (array_key_exists($tableName, $MainQuery)) {
            $MainQuery[$tableName] .= $fullQuery;
        } else {
            $MainQuery[$tableName] = $fullQuery;
        }
    }
    
    preg_match_all('/ALTER\s+TABLE\s+`?(\w+)`?.*?\;/is', $sqlContent, $matches, PREG_OFFSET_CAPTURE);

    $FOREIGN_KEYS = [];  
    $ALTERS = [];       

    foreach ($matches[0] as $index => $match) {
        $fullQuery = trim($match[0]);
        $tableName = $matches[1][$index][0];
        if (stripos($fullQuery, 'FOREIGN KEY') !== false) {
            if (!isset($FOREIGN_KEYS[$tableName])) {
                $FOREIGN_KEYS[$tableName] = '';
            }
            $FOREIGN_KEYS[$tableName] .= $fullQuery . "\n";
        } else {
            if (!isset($ALTERS[$tableName])) {
                $ALTERS[$tableName] = '';
            }
            $ALTERS[$tableName] .= $fullQuery . "\n";
        }
    }

    $tablesName=array_keys($MainQuery);
    foreach($tablesName as $TablesDetails){
        $insertStatements[] = [
        'table' => $TablesDetails,
        'query' => $MainQuery[$TablesDetails],
        'constraint'=>$ALTERS[$TablesDetails],
        'createtable'=>$CREATETABLE[$TablesDetails],
        'FOREIGN_KEYS'=>(isset($FOREIGN_KEYS[$TablesDetails])?$FOREIGN_KEYS[$TablesDetails]:"")
    ];
    }

    return $insertStatements;
}
$conn->query("SET foreign_key_checks = 0;");
foreach ($SelectedTables as $tableName) {
    if($tableName!='File')
    $conn->query("DROP TABLE IF EXISTS `$tableName`");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
    <style>
        body{
            height: 100vh;
            display:flex;
            align-items:center;
            flex-direction:column;
            font-family: 'Poppins', sans-serif;
            padding: 0px;
            box-sizing: border-box;
        }
        table{
            height: fit-content;
        }
        th,td{
            padding: 5px 20px;
        }
        tr td:last-child{
            width:300px;
        }
        button{
            width:100%;
            padding: 10px 20px;
            background-color: #3b98db;
            color:white;
            border:none;
            border-radius:5px;
        }
    </style>
</head>
<body>
    <div style='padding:30px'>
        <h1>Creating Tables</h1>
        <table border=1>
            <thead>
                <th>SI</th>
                <th>Table Name</th>
                <th>Result</th>
                <th>Problem</th>
            </thead>
            <tbody>
                <?php
                $i=1;
                    foreach ($SelectedTables as $tableName) {
                        foreach ($insertQueries as $entry) {
                            if ($entry["table"] === $tableName) {
                                echo "<tr>";
                                echo "<td>$i</td>";
                                echo "<td> '$tableName' </td>";
                                // echo $entry["query"] . "<br><br>";
                                if($entry["createtable"]!=''){
                                    try{
                                        // $conn->multi_query($entry["query"]);
                                        if ($conn->multi_query($entry["createtable"])) {
                                            do {
                                                if ($result = $conn->store_result()) {
                                                    $result->free();
                                                }
                                            } while ($conn->more_results() && $conn->next_result());
                                        }
                                        echo "<td> <b style='color:green'>Successfully</b></td>";
                                        echo "<td>No Problems</td>";
                                    }catch(Exception $e){
                                        echo "<td> <b style='color:red'>Failed</b></td>";
                                        echo "<td>$e</td>";
                                    }
                                }else{
                                    echo "<td> <b style='color:gray'>No Changes</b></td>";
                                    echo "<td>No Problems</td>";
                                }
                                echo "</tr>";
                                $i++;
                            }
                        }
                    }
                    // $conn->query("SET foreign_key_checks = 1;");
                ?>
            </tbody>
        </table>
        <hr>
        <h1>Adding CONSTRAINTS</h1>
        <table border=1>
            <thead>
                <th>SI</th>
                <th>Table Name</th>
                <th>Result</th>
                <th>Problem</th>
            </thead>
            <tbody>
                <?php
                $i=1;
                    foreach ($SelectedTables as $tableName) {
                        foreach ($insertQueries as $entry) {
                            if ($entry["table"] === $tableName) {
                                echo "<tr>";
                                echo "<td>$i</td>";
                                echo "<td> '$tableName' </td>";
                                // echo $entry["constraint"] . "<br><br>";
                                if($entry["constraint"]!=''){
                                    try{
                                        // $conn->multi_query($entry["query"]);
                                        if ($conn->multi_query($entry["constraint"])) {
                                            do {
                                                if ($result = $conn->store_result()) {
                                                    $result->free();
                                                }
                                            } while ($conn->more_results() && $conn->next_result());
                                        }
                                        echo "<td> <b style='color:green'>Successfully</b></td>";
                                        echo "<td>No Problems</td>";
                                    }catch(Exception $e){
                                        echo "<td> <b style='color:red'>Failed</b></td>";
                                        echo "<td>$e</td>";
                                    }
                                }else{
                                    echo "<td> <b style='color:gray'>No Changes</b></td>";
                                    echo "<td>No Problems</td>";
                                }
                                echo "</tr>";
                                $i++;
                            }
                        }
                    }
                    // $conn->query("SET foreign_key_checks = 1;");
                ?>
            </tbody>
        </table>
        <hr>
        <h1>Adding FOREIGN KEYS</h1>
        <table border=1>
            <thead>
                <th>SI</th>
                <th>Table Name</th>
                <th>Result</th>
                <th>Problem</th>
            </thead>
            <tbody>
                <?php
                $i=1;
                    foreach ($SelectedTables as $tableName) {
                        foreach ($insertQueries as $entry) {
                            if ($entry["table"] === $tableName) {
                                echo "<tr>";
                                echo "<td>$i</td>";
                                echo "<td> '$tableName' </td>";
                                // echo $entry["FOREIGN_KEYS"] . "<br><br>";
                                if($entry["FOREIGN_KEYS"]!=''){
                                    try{
                                        // $conn->multi_query($entry["query"]);
                                        if ($conn->multi_query($entry["FOREIGN_KEYS"])) {
                                            do {
                                                if ($result = $conn->store_result()) {
                                                    $result->free();
                                                }
                                            } while ($conn->more_results() && $conn->next_result());
                                        }
                                        echo "<td> <b style='color:green'>Successfully</b></td>";
                                        echo "<td>No Problems</td>";
                                    }catch(Exception $e){
                                        echo "<td> <b style='color:red'>Failed</b></td>";
                                        echo "<td>$e</td>";
                                    }
                                }else{
                                    echo "<td> <b style='color:gray'>No Changes</b></td>";
                                    echo "<td>No Problems</td>";
                                }
                                echo "</tr>";
                                $i++;
                            }
                        }
                    }
                    // $conn->query("SET foreign_key_checks = 1;");
                ?>
            </tbody>
        </table>

        <hr>
        <h1>Adding Values to the table</h1>
        <table border=1>
            <thead>
                <th>SI</th>
                <th>Table Name</th>
                <th>Result</th>
                <th>Problem</th>
            </thead>
            <tbody>
                <?php
                $i=1;
                    foreach ($SelectedTables as $tableName) {
                        foreach ($insertQueries as $entry) {
                            if ($entry["table"] === $tableName) {
                                echo "<tr>";
                                echo "<td>$i</td>";
                                echo "<td> '$tableName' </td>";
                                // echo $entry["query"] . "<br><br>";
                                if($entry["query"]!=''){
                                    try{
                                        if ($conn->multi_query($entry["query"])) {
                                            do {
                                                if ($result = $conn->store_result()) {
                                                    $result->free();
                                                }
                                            } while ($conn->more_results() && $conn->next_result());
                                        }
                                        echo "<td> <b style='color:green'>Successfully</b></td>";
                                        echo "<td>No Problems</td>";
                                    }catch(Exception $e){
                                        echo "<td> <b style='color:red'>Failed</b></td>";
                                        echo "<td>$e</td>";
                                    }
                                }else{
                                    echo "<td> <b style='color:gray'>No Changes</b></td>";
                                    echo "<td>No Problems</td>";
                                }
                                echo "</tr>";
                                $i++;
                            }
                        }
                    }
                    $conn->query("SET foreign_key_checks = 1;");
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
