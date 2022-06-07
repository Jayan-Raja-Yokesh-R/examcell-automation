<?php 

set_time_limit(0);

function getDateFormat($date) {

    if($date == '') {
        return $date;
    }else if(strpos($date,'/')) {

        return date("d-m-Y",strtotime(str_replace("/","-",$date)));

    } else if(strpos($date,'-')){
        return date("d-m-Y",strtotime(str_replace("/","-",$date)));
    } else {
        $excel_date = (int)$date; 
        $unix_date = ($excel_date - 25569) * 86400;
        $excel_date = 25569 + ($unix_date / 86400);
        $unix_date = ($excel_date - 25569) * 86400;
        return gmdate("d-m-Y", $unix_date);
    }
    
}

//handling student data
if(isset($_POST['s_data_upload'])) {
   


    $supported_file_types = array('csv','xlsx','xls');
    if(isset($_FILES['s_uploaded_file']['name'])) {
   
        $fileName = $_FILES['s_uploaded_file']['name'];
        $fileExt = explode(".",$fileName);
        $fileExtension = $fileExt[1];

        if(in_array($fileExtension,$supported_file_types)) {


                $absoluteFilePath = $_FILES['s_uploaded_file']['tmp_name'];



                include_once("db_connection.php");
                include('lib/phpexcel/Classes/PHPExcel/IOFactory.php');

                $isFileUploaded = true;
                try {

                    $spreadSheet = PHPExcel_IOFactory::load($absoluteFilePath);
                    $activeSheet = $spreadSheet -> getSheet(0);
                    $maxRows = $activeSheet -> getHighestRow();
                    $maxColumns = $activeSheet -> getHighestColumn();
                    $maxColumnIndex = PHPExcel_Cell::columnIndexFromString($maxColumns);

                    $query = "INSERT IGNORE INTO  student_details VALUES(?,?,?,?,?,?,?,?)";

                    $stmt = $con -> prepare($query);

                    

                    for($row = 2;$row <= $maxRows;++$row) {
                        $rowData = '';
                        for($col = 0;$col < $maxColumnIndex;++$col) {

            
                            $rowData .= $activeSheet -> getCellByColumnAndRow($col,$row) -> getFormattedValue().'#';
                        }
                      
                        $cellValue = explode('#',$rowData);
                        $regno = $cellValue[0];
                        $name = $cellValue[1];
                        $dob = getDateFormat($cellValue[2]);
                        $gender = $cellValue[3];
                        $deptcode = intval($cellValue[4]);
                        $deptname = $cellValue[5];
                        $course = $cellValue[6];
                        $regulation = intval($cellValue[7]);

                        $stmt -> bind_param('isssissi',$regno,$name,$dob,$gender,$deptcode,$deptname,$course,$regulation);

                        if(!$stmt -> execute()) {
                            $isFileUploaded = false;
                        }
                    }


                    if($isFileUploaded) {
                        
                       echo "<script>alert('File uploaded successfully!')
                       window.location.href='index.php'</script>";

                    } else {
                        echo "<script>alert('Error in uploading file!')</script>";
                    }
                
                
                } catch(Exception $e) {
                    die('Error uploading file "' . pathinfo($absoluteFilePath, PATHINFO_BASENAME). '": ' . $e->getMessage());

                } 
        

        } else {
            
            echo "<script>alert('File format not supported!')
            window.location.href='index.php'</script>";
            
            
        }
        

    }


} 
//handling exam data
else if(isset($_POST['e_data_upload'])) {
   


    $supported_file_types = array('csv','xlsx','xls');
    $dept_codes = array(103 => 'civil',104 => 'cse',105 => 'eee',106 => 'ece', 114 => 'mech',205 => 'it');

    if(isset($_FILES['e_uploaded_file']['name'])) {
   
        $fileName = $_FILES['e_uploaded_file']['name'];
        $fileExt = explode(".",$fileName);
        $fileExtension = $fileExt[1];
        $deptcode = intval(explode("_",$fileName)[0]);


        if(in_array($fileExtension,$supported_file_types)) {


                $absoluteFilePath = $_FILES['e_uploaded_file']['tmp_name'];



                include_once("db_connection.php");
                include('lib/phpexcel/Classes/PHPExcel/IOFactory.php');

                $isFileUploaded = true;
                try {

                    $spreadSheet = PHPExcel_IOFactory::load($absoluteFilePath);
                    $activeSheet = $spreadSheet -> getSheet(0);
                    $maxRows = $activeSheet -> getHighestRow();
                    $maxColumns = $activeSheet -> getHighestColumn();
                    $maxColumnIndex = PHPExcel_Cell::columnIndexFromString($maxColumns);

                    $tablename = 'dept_'.$dept_codes[$deptcode];
                    $query = "INSERT INTO $tablename VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
                    
                    $stmt = $con -> prepare($query);

                    

                    for($row = 2;$row <= $maxRows;++$row) {
                        $rowData = '';
                        for($col = 0;$col < $maxColumnIndex;++$col) {

							
                            $rowData .= $activeSheet -> getCellByColumnAndRow($col,$row) -> getFormattedValue().'#';
                        }
                    //    echo $rowData.'<br>';
                        $cellValue = explode('#',$rowData);
                        $regno = $cellValue[0];
                        $subcode = $cellValue[2];
                        $subname = $cellValue[3];
                        $actualsem = intval($cellValue[4]);
                        $sem = intval($cellValue[5]);
                        $internalmark = intval($cellValue[6]);
						$externalmark = intval($cellValue[7]);
                        $exam_start_month = $cellValue[8];
                        $exam_end_month = $cellValue[9];
                        $year_of_exam = intval($cellValue[10]);


						$total = round(($internalmark/2)+($externalmark/2));
						
						if($total >=91 && $total <=100)
						{     $grade = 'O';    }
						else if($total >=81 && $total <=90)
						{     $grade = 'A+';   }
						else if($total >=71 && $total <=80)
						{     $grade = 'A';    }
						else if($total >=61 && $total <=70)
						{     $grade = 'B+';    }
						else if($total >=51 && $total <=60)
						{     $grade = 'B';    }
						else
						{     $grade = 'RA';   }
						//$imark = inva
                        //$grade = 'NA';
                        
                        $stmt -> bind_param('issiiiiisssi',$regno,$subcode,$subname,$actualsem,$sem,$internalmark,$externalmark,$total,$grade,$exam_start_month,$exam_end_month,$year_of_exam);

                        if(!$stmt -> execute()) {
                            $isFileUploaded = false;
                        } else {
                            $stmt_subcode = $con -> prepare("SELECT subcode FROM subject_reg WHERE subcode=? AND deptcode=? AND sem=? AND year_of_exam=?");
                            $stmt_subcode -> bind_param("siii",$subcode,$deptcode,$sem,$year_of_exam);
                            $stmt_subcode -> execute();
                            $result = $stmt_subcode -> get_result();
                            if($result -> num_rows <= 0) {

                                $stmt_subcode_insert = $con -> prepare("INSERT  INTO subject_reg(subcode,deptcode,sem,year_of_exam) VALUES(?,?,?,?)");
                                $stmt_subcode_insert -> bind_param("siii",$subcode,$deptcode,$sem,$year_of_exam);
                                $stmt_subcode_insert -> execute();
                                $stmt_subcode_insert -> close();
                            }

                         

                        }
                    }


                    if($isFileUploaded) {
                        
                       echo "<script>alert('File uploaded successfully!')
                       window.location.href='index.php'</script>";

                    } else {
                        echo "<script>alert('Error in uploading file!')</script>";
                    }
                
                
                } catch(Exception $e) {
                    die('Error uploading file "' . pathinfo($absoluteFilePath, PATHINFO_BASENAME). '": ' . $e->getMessage());

                } finally {
                    $con -> close();
                    $stmt -> close();
                }
        

        } else {
            
            echo "<script>alert('File format not supported!')
            window.location.href='index.php'</script>";
            
            
        }
        

    }


} 
else {
    header("Location:index.php");
}

?>