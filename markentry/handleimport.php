<?php 

        //Make composer to load the phpSpreadsheet automatically
        //require_once 'lib/vendor/autoload.php';
    
        //Importing packages
        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Reader\Csv;
        use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
		use PhpOffice\PhpSpreadsheet\IOFactory;


        function getDateFormat($date) {

            if($date == '') {
                return $date;
            }else if(strpos($date,'/')) {
    
                return str_replace("/","-",$date);
       
            } else if(strpos($date,'-')){
                return $date;
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
                echo "File accepted";
                $isFileExist = false;
                if(file_exists('uploads/'.$fileName)) {
                    $isFileExist = true;
                }

                if(!$isFileExist) {

                    include_once("db_connection.php");

                    $factory = new IOFactory();
					$reader = $factory -> createReader(ucwords($fileExtension));

                    $spreadsheet = $reader -> load($_FILES['excel-file']['tmp_name']);

                    $sheetData = $spreadsheet -> getActiveSheet() -> toArray();

                    $query = "INSERT INTO exam_details VALUES(?,?,?,?,?,?,?,?,?)";
                    
                    $isUploadSuccessful = true;
                    for($row=1; $row<count($sheetData); $row++) {
                       

                        $stmt = $con->prepare($query);

                     
                        $regno = intval($sheetData[$row][0]);
                        $dummyno = (int)$sheetData[$row][1];
                        $subcode = $sheetData[$row][2];
                        $subname = $sheetData[$row][3];
                        $examdate = getDateFormat($sheetData[$row][4]);
                        $session = $sheetData[$row][5];
                        $sem = (int)$sheetData[$row][6];
                        $mark = 'NA';
                        $markinwords = 'NA';

                        $stmt->bind_param("iissssiis",$regno,$dummyno,$subcode,$subname,$examdate,$session,$sem,$mark,$markinwords);
                       
                        if(!$stmt->execute()) {
                            $isUploadSuccessful = false;
                            break;
                        }
                        
                    }

                    
                        if($isUploadSuccessful) {
                            $con->close();
                            move_uploaded_file($_FILES['excel-file']['tmp_name'],'uploads/'.$fileName);
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

                if(file_exists('uploads/'.$_FILES['s_uploaded_file']['name'])) {
                    $isFileexists = false;
                }
                if($isFileexists) {

                
                                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(ucwords($fileExtension));
                                $spreadsheet = $reader->load($_FILES['s_uploaded_file']['tmp_name']);
                                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                                $sheetCount = $spreadsheet->getSheetCount();

                                $uploaded = true;
                                $stmt = $con -> prepare("INSERT IGNORE INTO student_details VALUES(?,?,?,?,?,?,?);");
                                for($i=1;$i<count($sheetData);++$i) {
                                    
                                    $regno =(int) $sheetData[$i][0];
                                    $name = $sheetData[$i][1];
                                    $dob = getDateFormat($sheetData[$i][2]);
                                    $deptcode = (int)$sheetData[$i][3];
                                    $deptname = $sheetData[$i][4];
                                    $course = $sheetData[$i][5];
                                    $regulation = (int)$sheetData[$i][6];
                                    
                                    
                                    $stmt -> bind_param("ississi",$regno,$name,$dob,$deptcode,$deptname,$course,$regulation);
                                  
                                    
                                  if(!$stmt->execute()) {

                                        $uploaded = false;
                                        $stmt -> close();
                                        break;
                                     } 
                                    //else {
                                    //     if($stmt -> num_rows() == 0 ){
                                    //         echo "<script>alert('Error inserting data! Please check the file and try again')</script>";
                                    //     }
                                        
                                    // } 



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