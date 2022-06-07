<?php 




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

    if(isset($_POST['upload'])) {


        $supported_file_types = array('csv','xlsx','xls');
        if(isset($_FILES['excel-file']['name'])) {
           
            $fileName = $_FILES['excel-file']['name'];
			$fileExt = explode(".",$fileName);
            $fileExtension = $fileExt[1];
            if(in_array($fileExtension,$supported_file_types)) {
            
                $absoluteFilePath = $_FILES['excel-file']['tmp_name'];
                $isFileExist = false;
                if(file_exists('uploads/'.$fileName)) {
                    $isFileExist = true;
                }

                if(!$isFileExist) {

                    include_once("db_connection.php");
                    include('lib/phpexcel/Classes/PHPExcel/IOFactory.php');


                    $query = "INSERT INTO exam_details VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    
                    $isUploadSuccessful = true;

                                                    
                    try {
                        $stmt = $con -> prepare($query);

                        $spreadSheet = PHPExcel_IOFactory::load($absoluteFilePath);
                        $activeSheet = $spreadSheet -> getSheet(0);
                        $maxRows = $activeSheet -> getHighestRow();
                        $maxColumns = $activeSheet -> getHighestColumn();
                        $maxColumnIndex = PHPExcel_Cell::columnIndexFromString($maxColumns);



                        for($row = 2;$row <= $maxRows;++$row) {

                            $rowData = '';
                            for($col = 0;$col < $maxColumnIndex;++$col) {

                                $rowData .= $activeSheet -> getCellByColumnAndRow($col,$row) -> getFormattedValue().'#';

                            }

                        
                            $cellValue = explode('#',$rowData);
                            $regno = (int)$cellValue[0];
                            $dummyno = (int)$cellValue[1];
                            $subcode = $cellValue[2];
                            $subname = $cellValue[3];
                            $examdate = getDateFormat($cellValue[4]);
                            $session = $cellValue[5];
                            $actualsem = (int)$cellValue[6];
                            $sem = (int)$cellValue[7];
                            $mark = 0;
                            $markinwords = 'NA';
                            $exam_start_month = $cellValue[10];
                            $exam_end_month = $cellValue[11];
                            $year_of_exam = (int)$cellValue[12];

                            // echo $exam_start_month.$exam_end_month.$year_of_exam;
                            // echo '<br>';
                            $stmt->bind_param("iissssiiisssi",$regno,$dummyno,$subcode,$subname,$examdate,$session,$actualsem,$sem,$mark,$markinwords,$exam_start_month,$exam_end_month,$year_of_exam);
                            
                            
                             
                            
                            if(!$stmt -> execute()) {
                                $isFileUploaded = false;
                            }

                         


                       
                        }

                    } catch(Exception $e) {
                        die('Error uploading file "' . pathinfo($absoluteFilePath, PATHINFO_BASENAME). '": ' . $e->getMessage());
                    }

                        if($isUploadSuccessful) {
                            $con->close();
                            // move_uploaded_file($_FILES['excel-file']['tmp_name'],'uploads/'.$fileName);
                           echo "<script>alert('Upload Successful');window.location.href='index.php'</script>";
                        } else {
                            $con->close();
                            echo "<script>alert('Upload failed! Please check and try again');
                            window.location.href='index.php';</script>";
                            header("Location:index.php");
                        }


                } else {
                    echo "<script>alert('File with same name already exists! Try again with different and content.');
                    window.location.href='index.php';</script>";
                }


               

            } else {
                echo "<script>alert('Unsupported file format!');
                window.location.href='index.php';</script>";
            }
        }


    }




    if(isset($_POST['s_data_upload'])) {
       
        $supported_file_types = array('csv','xlsx','xls');
        if(isset($_FILES['s_uploaded_file']['name'])) {

            $fileName = $_FILES['s_uploaded_file']['name'];

           $fileExt = explode(".",$fileName);
            $fileExtension = $fileExt[1];
	
            if(in_array($fileExtension,$supported_file_types)) {


                include_once("db_connection.php");

                $isFileexists = true;
                $absoluteFilePath = $_FILES['s_uploaded_file']['tmp_name'];

                if(file_exists('uploads/'.$_FILES['s_uploaded_file']['name'])) {
                    $isFileexists = false;
                }
                if($isFileexists) {

                
                                

                                include('lib/phpexcel/Classes/PHPExcel/IOFactory.php');
                                
                                $uploaded = true;
                                $stmt = $con -> prepare("INSERT IGNORE INTO student_details VALUES(?,?,?,?,?,?,?,?);");
                                
                                
                                try {
                                    $spreadSheet = PHPExcel_IOFactory::load($absoluteFilePath);
                                    $activeSheet = $spreadSheet -> getSheet(0);
                                    $maxRows = $activeSheet -> getHighestRow();
                                    $maxColumns = $activeSheet -> getHighestColumn();
                                    $maxColumnIndex = PHPExcel_Cell::columnIndexFromString($maxColumns);



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

                                } catch(Exception $e) {
                                    die('Error uploading file "' . pathinfo($absoluteFilePath, PATHINFO_BASENAME). '": ' . $e->getMessage());
                                }


                                if($uploaded) {
                                    $con->close();
                                    move_uploaded_file($_FILES['s_uploaded_file']['tmp_name'],'uploads/'.$fileName);
                                    echo "<script>alert('File Upload Successful!')
                                    window.location.href='index.php';
                                    </script>";
                                    
                                } else {
                                    echo "<script>alert('File Upload Unsuccessful! Check and try again!')
                                    window.location.href='index.php';</script>";
                                }

                    } else {
                        $con->close();
                        echo "<script>alert('File already uploaded.Check with".
                        " file contents and name of the file and try again');window.location.href='index.php'</script>";
                    }
            } else {
                echo "<script>alert('File format not supported');
                window.location.href='index.php'</script>";
            }

        }
    }

?>