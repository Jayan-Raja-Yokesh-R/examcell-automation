<?php 





    if(isset($_POST['export'])){
            //Creating new Spreadsheet
            include('lib/phpexcel/Classes/PHPExcel/IOFactory.php');
            include('lib/phpexcel/Classes/PHPExcel.php');
            $file = new PHPExcel(); 
            $activesheet = $file -> getActiveSheet();

            $activesheet -> setCellValue('A1','Register Number');
			$activesheet -> setCellValue('B1','Dummy Number');
			$activesheet -> setCellValue('C1','Name');
			$activesheet -> setCellValue('D1','Department Name');
			$activesheet -> setCellValue('E1','Department Code');
            $activesheet -> setCellValue('F1','Actual Semester');
			$activesheet -> setCellValue('G1','Semester');
            $activesheet -> setCellValue('H1','Subject Code');
            $activesheet -> setCellValue('I1','Subject Name');
            $activesheet -> setCellValue('J1','Mark');
            $activesheet -> setCellValue('K1','Mark In Words');

			
			
			
            $activesheet->getStyle("A1:K1")->getFont()->setBold( true );
            $count = 2;
            include_once("db_connection.php");

            $stmt = $con -> prepare("SELECT * FROM student_details INNER JOIN exam_details ON student_details.regno=exam_details.regno;");

            $stmt -> execute();
            $result = $stmt -> get_result();
            
            foreach($result as $row) {
                $activesheet -> setCellValue('A'.$count,$row['regno']);
                $activesheet -> setCellValue('B'.$count,$row['dummyno']);
                $activesheet -> setCellValue('C'.$count,$row['name']);
                $activesheet -> setCellValue('D'.$count,$row['deptname']);
                $activesheet -> setCellValue('E'.$count,$row['deptcode']);
                $activesheet -> setCellValue('F'.$count,$row['actual_sem']);
				$activesheet -> setCellValue('G'.$count,$row['sem']);
                $activesheet -> setCellValue('H'.$count,$row['subcode']);
				$activesheet -> setCellValue('I'.$count,$row['subname']);
				$activesheet -> setCellValue('J'.$count,$row['mark']);
				$activesheet -> setCellValue('K'.$count,$row['markinwords']);
                ++$count;

            }
			
		
			

            $filename = 'Exam_data.xlsx';
            $writer = PHPExcel_IOFactory::createWriter($file,'Excel2007');
            $writer -> save($filename);
            

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Transfer-Encoding: Binary');
            header("Content-disposition: attachment; filename=".$filename."");
            readfile($filename);
            unlink($filename);
            $con -> close();
            $stmt -> close();
            exit;
    }



    if(isset($_POST['export_all'])){
        //Creating new Spreadsheet
            include('lib/phpexcel/Classes/PHPExcel/IOFactory.php');
            include('lib/phpexcel/Classes/PHPExcel.php');
            $file = new PHPExcel(); 

            $activesheet = $file -> getActiveSheet();

            $activesheet -> setCellValue('A1','Register Number');
            $activesheet -> setCellValue('B1','Dummy Number');
            $activesheet -> setCellValue('C1','Subject Code');
            $activesheet -> setCellValue('D1','Subject Name');
            $activesheet -> setCellValue('E1','Examdate');
            $activesheet -> setCellValue('F1','Session');
            $activesheet -> setCellValue('G1','Actual Semester');
            $activesheet -> setCellValue('H1','Semester');
            $activesheet -> setCellValue('I1','Mark');
            $activesheet -> setCellValue('J1','Mark In Words');
            $activesheet -> setCellValue('K1','Exam Start Month');
            $activesheet -> setCellValue('L1','Exam End Month');
            $activesheet -> setCellValue('M1','Year Of Exam');

            $activesheet->getStyle("A1:M1")->getFont()->setBold( true );
            $count = 2;
            include_once("db_connection.php");

            $stmt = $con -> prepare("SELECT * FROM exam_details;");

            $stmt -> execute();
            $result = $stmt -> get_result();

            foreach($result as $row) {
                $activesheet -> setCellValue('A'.$count,$row['regno']);
                $activesheet -> setCellValue('B'.$count,$row['dummyno']);
                $activesheet -> setCellValue('C'.$count,$row['subcode']);
                $activesheet -> setCellValue('D'.$count,$row['subname']);
                $activesheet -> setCellValue('E'.$count,$row['examdate']);
                $activesheet -> setCellValue('F'.$count,$row['session']);
                $activesheet -> setCellValue('G'.$count,$row['actual_sem']);
                $activesheet -> setCellValue('H'.$count,$row['sem']);
                $activesheet -> setCellValue('I'.$count,$row['mark']);
                $activesheet -> setCellValue('J'.$count,$row['markinwords']);
                $activesheet -> setCellValue('K'.$count,$row['exam_start_month']);
                $activesheet -> setCellValue('L'.$count,$row['exam_start_month']);
                $activesheet -> setCellValue('M'.$count,$row['year_of_exam']);
                ++$count;

            }

            $filename = 'Exam_data_all.xlsx';
            $writer = PHPExcel_IOFactory::createWriter($file,'Excel2007');
            $writer -> save($filename);


            header('Content-Type: application/vnd.ms-excel');
            header('Content-Transfer-Encoding: Binary');
            header("Content-disposition: attachment; filename=".$filename."");
            readfile($filename);
            unlink($filename);
            $con -> close();
            $stmt -> close();
            exit;
}


if(isset($_POST['export_student'])) {

    include('lib/phpexcel/Classes/PHPExcel/IOFactory.php');
    include('lib/phpexcel/Classes/PHPExcel.php');
    $file = new PHPExcel(); 
    $activesheet = $file -> getActiveSheet();


    $activesheet -> setCellValue('A1',"Register Number");
    $activesheet -> setCellValue('B1',"Name");
    $activesheet -> setCellValue('C1',"DOB");
    $activesheet -> setCellValue('D1',"Gender");
    $activesheet -> setCellValue('E1',"Dept Code");
    $activesheet -> setCellValue('F1',"Dept Name");
    $activesheet -> setCellValue('G1',"Course");
    $activesheet -> setCellValue('H1',"Regulation");


    $activesheet->getStyle("A1:H1")->getFont()->setBold( true );
    $count = 2;
    include_once("db_connection.php");

    $stmt = $con -> prepare("SELECT * FROM student_details;");

    if($stmt -> execute()) {
        $result = $stmt -> get_result();

        foreach($result as $row) {
            $activesheet -> setCellValue('A'.$count,$row['regno']);
            $activesheet -> setCellValue('B'.$count,$row['name']);
            $activesheet -> setCellValue('C'.$count,$row['dob']);
            $activesheet -> setCellValue('D'.$count,$row['gender']);
            $activesheet -> setCellValue('E'.$count,$row['deptcode']);
            $activesheet -> setCellValue('F'.$count,$row['deptname']);
            $activesheet -> setCellValue('G'.$count,$row['course']);
            $activesheet -> setCellValue('H'.$count,$row['regulation']);
            ++$count;



        }

            $filename = 'Student_data_all.xlsx';
            $writer = PHPExcel_IOFactory::createWriter($file,'Excel2007');
            $writer -> save($filename);


            header('Content-Type: application/vnd.ms-excel');
            header('Content-Transfer-Encoding: Binary');
            header("Content-disposition: attachment; filename=".$filename."");
            readfile($filename);
            unlink($filename);
            $con -> close();
            $stmt -> close();
            exit;
    }




}

if(isset($_POST['export_deptwise'])) {
    $deptname = $_POST['deptname'];
    $sem = $_POST['sem'];

    include('lib/phpexcel/Classes/PHPExcel/IOFactory.php');
    include('lib/phpexcel/Classes/PHPExcel.php');
    $file = new PHPExcel(); 
    $activesheet = $file -> getActiveSheet();

    $activesheet -> setCellValue('A1','Register Number');
    $activesheet -> setCellValue('B1','Name');
    $activesheet -> setCellValue('C1','Actual Semester');
    $activesheet -> setCellValue('D1','Semester');
    $activesheet -> setCellValue('E1','Subject Code');
    $activesheet -> setCellValue('F1','Subject Name');
    $activesheet -> setCellValue('G1','Mark');
    $activesheet -> setCellValue('H1','Exam Start Month');
    $activesheet -> setCellValue('I1','Exam End Month');
    $activesheet -> setCellValue('J1','Year Of Exam');
    


    
    
    
    $activesheet->getStyle("A1:J1")->getFont()->setBold( true );
    $count = 2;
    include_once("db_connection.php");

    $stmt = $con -> prepare("SELECT * FROM student_details LEFT JOIN exam_details ON student_details.regno=exam_details.regno WHERE exam_details.sem=? AND student_details.deptname=?;");

    $stmt -> bind_param("is",$sem,$deptname);
    $stmt -> execute();
    $result = $stmt -> get_result();
    
    foreach($result as $row) {
        $activesheet -> setCellValue('A'.$count,$row['regno']);
        $activesheet -> setCellValue('B'.$count,$row['name']);
        $activesheet -> setCellValue('C'.$count,$row['actual_sem']);
        $activesheet -> setCellValue('D'.$count,$row['sem']);
        $activesheet -> setCellValue('E'.$count,$row['subcode']);
        $activesheet -> setCellValue('F'.$count,$row['subname']);
        $activesheet -> setCellValue('G'.$count,$row['mark']);
        $activesheet -> setCellValue('H'.$count,$row['exam_start_month']);
        $activesheet -> setCellValue('I'.$count,$row['exam_end_month']);
        $activesheet -> setCellValue('J'.$count,$row['year_of_exam']);

        ++$count;

    }
    
    

    

    $filename = $deptname.'_sem_'.$sem.'.xlsx';
    $writer = PHPExcel_IOFactory::createWriter($file,'Excel2007');
    $writer -> save($filename);
    

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Transfer-Encoding: Binary');
    header("Content-disposition: attachment; filename=".$filename."");
    readfile($filename);
    unlink($filename);
    $con -> close();
    $stmt -> close();
    exit;
}

    

?>