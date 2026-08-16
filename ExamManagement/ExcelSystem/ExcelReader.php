<?php
function ColumnDeatils($path){
    
    $reader = PHPExcel_IOFactory::createReaderForFile($path);
    $excel_Obj = $reader->load($path);
    $sheetCount = $excel_Obj->getSheetCount();
    for ($sheetIndex = 0; $sheetIndex < $sheetCount; $sheetIndex++) {
        $worksheet = $excel_Obj->getSheet($sheetIndex);
        $colomncount = $worksheet->getHighestDataColumn();
        $rowcount = $worksheet->getHighestRow();
        $colomncount_number = 5;

        $colsDetails=[];
        for ($col = 0; $col < $colomncount_number; $col++) {
            $header = $worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex($col) . '1')->getValue();                     
            $colsDetails[]=$header;             
        }
               
        foreach($neededRows as $RowsValue){
            $InBuild = strtolower(str_replace(' ', '', $RowsValue));
            echo $InBuild;
            $normalizedArray = array_map(fn($item) => is_string($item) ? strtolower(str_replace(' ', '', $item)) : '', $colsDetails);
            $found = in_array($InBuild, $normalizedArray);
            if (!$found) {
                echo("<h1>$RowsValue field not Found</h1>");
                die("<h4>$RowsValue Should be in first 5 Coulmn in the file</h4>");
            } 
        }
    }
}

function StudentsDeatils($path,$conn){
    $neededRows=["Roll No","Name","DOB","Program","Academic Year"];
    $Students=[];
    // $Students[0]=[]; //Roll NO
    // $Students[1]=[]; //Name
    // $Students[2]=[]; //DOB
    // $Students[3]=[]; //Program
    // $Students[4]=[]; //Academic Year



    $reader = PHPExcel_IOFactory::createReaderForFile($path);
    $excel_Obj = $reader->load($path);
    $sheetCount = $excel_Obj->getSheetCount();

    for ($sheetIndex = 0; $sheetIndex < $sheetCount; $sheetIndex++) {
        $worksheet = $excel_Obj->getSheet($sheetIndex);
        $colomncount = $worksheet->getHighestDataColumn();
        $rowcount = $worksheet->getHighestRow();
        $colomncount_number = 5;

        $colsDetails=[];
        for ($col = 0; $col < $colomncount_number; $col++) {
            $header = $worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex($col) . '1')->getValue();                     
            $colsDetails[]=$header;             
        }
               
        foreach($neededRows as $RowsValue){
            $InBuild = strtolower(str_replace(' ', '', $RowsValue));
            $normalizedArray = array_map(fn($item) => is_string($item) ? strtolower(str_replace(' ', '', $item)) : '', $colsDetails);
            $found = in_array($InBuild, $normalizedArray);
            if (!$found) {
                echo("<h1>$RowsValue field not Found</h1>");
                die("<h4>$RowsValue Should be in first 5 Coulmn in the file</h4>");
            } 
        }
        $colsDetails = array_map('strtolower', $colsDetails);
        $rowCounts=0;

        for ($row = 2; $row <= $rowcount; $row++) {
            $data++;
            $roll = $worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(array_search("roll no", $colsDetails)) . $row);
            if ($roll->isFormula()) {
                $roll = $roll->getCalculatedValue();
            }
                    // echo "(".$roll.",";
            if($roll!=''){
                $Name = $worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(array_search("name", $colsDetails)) . $row);
                        

                $DOB = $worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(array_search("dob", $colsDetails)) . $row)->getValue();
                $unixTimestamp = PHPExcel_Shared_Date::ExcelToPHP($DOB); 
                $DOB = date("Y-m-d", $unixTimestamp); 

                $Program = $worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(array_search("program", $colsDetails)) . $row);
                $AcademicYear = $worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(array_search("academic year", $colsDetails)) . $row);

                // $students_check="SELECT RollNo FROM students_details WHERE RollNo='$roll'";
                // $Presents=$conn->query($students_check)->num_rows;
                // if($Presents==0){
                //     $depID=0;
                //     $index = array_search($depart, $departmentsID);
                //     if ($index !== false) {
                //         $depID=$index;
                //     } else {
                //         header("location: NotFound.php?Unknown=$depart");
                //         return;
                //                 // $conn->query("INSERT INTO `departments`(`department_name`) VALUES ('$depart')");
                //                 // $DespData=$conn->query("SELECT * FROM `departments` WHERE department_name='$depart'")->fetch_assoc();
                //                 // $departmentsID[$DespData['department_id']]=$depart;
                //                 // $index = array_search($depart, $departmentsID);
                //                 // $depID=$index;
                //     }
                //     $values.= "(".$roll.","."'".$Name."',"."'".$depID."',"."'".$AcademicYear."','$DOB'),";
                //             // echo "(".$roll.","."'".$Name."',"."'".$depID."',"."'".$AcademicYear."','$DOB'),<br>";
                //     $total_data++;
                // }
                // if (!in_array($depart, $department)) {
                //     $department[] = $depart;
                // }
                // $values2.= "$roll,";
                    
                $Students[]=[
                    'RollNo'=>$roll,
                    'Name'=>$Name,
                    'DOB'=>$DOB,
                    'Program'=>$Program,
                    'AcademicYear'=>$AcademicYear
                ];
               
            }
        }
    // echo '</table>';
    }
    return $Students;
}
?>