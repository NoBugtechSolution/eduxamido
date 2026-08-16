<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <title>File Select</title>
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
        select{
            padding: 5px 10px ;
        }
    </style>
</head>
<body>
    <form action="dbManage.php" method='POST'>
    <?php
        $sqlFiles = glob('./*.sql');
    ?>

        <table >
            <thead>
                <th colspan='2'>Select The file</th>
            </thead>
            <tbody>
                <tr>
                    <td>File Name</td>
                    <td>
                        <select name="File" id="">
                            <?php
                            foreach ($sqlFiles as $file) {
                                echo "<option value='".basename($file) . "'>".basename($file) . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan='3'><button type="submit">Edit Tables</button></td>
                </tr>

            </tbody>
        </table>
    </form>
</body>
</html>