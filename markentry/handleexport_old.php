<?php 

require_once 'lib/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;



    if(isset($_POST['export'])){
            //Creating new Spreadsheet
            $file = new Spreadsheet();
            $activesheet = $file -> getActiveSheet();

            $activesheet -> setCellValue('A1','Register Number');
            $activesheet -> setCellValue('B1','Dummy Number');
            $activesheet -> setCellValue('C1','Subject Code');
            $activesheet -> setCellValue('D1','Subject Name');
            $activesheet -> setCellValue('E1','Mark');
            $activesheet -> setCellValue('F1','Mark In Words');
            
            $activesheet->getStyle("A1:F1")->getFont()->setBold( true );
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
                $activesheet -> setCellValue('E'.$count,$row['mark']);
                $activesheet -> setCellValue('F'.$count,$row['markinwords']);
                ++$count;

            }

            $filename = 'Exam_data.xlsx';
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file,'Xlsx');
            $writer -> save($filename);
            

            header('Content-Type: application/x-www-form-urlencoded');
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
            $file = new Spreadsheet();
            $activesheet = $file -> getActiveSheet();

            $activesheet -> setCellValue('A1','Register Number');
            $activesheet -> setCellValue('B1','Dummy Number');
            $activesheet -> setCellValue('C1','Subject Code');
            $activesheet -> setCellValue('D1','Subject Name');
            $activesheet -> setCellValue('E1','Examdate');
            $activesheet -> setCellValue('F1','Session');
            $activesheet -> setCellValue('G1','Semester');
            $activesheet -> setCellValue('H1','Mark');
            $activesheet -> setCellValue('I1','Mark In Words');

            $activesheet->getStyle("A1:I1")->getFont()->setBold( true );
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
                $activesheet -> setCellValue('G'.$count,$row['sem']);
                $activesheet -> setCellValue('H'.$count,$row['mark']);
                $activesheet -> setCellValue('I'.$count,$row['markinwords']);
                ++$count;

            }

            $filename = 'Exam_data_all.xlsx';
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file,'Xlsx');
            $writer -> save($filename);


            header('Content-Type: application/x-www-form-urlencoded');
            header('Content-Transfer-Encoding: Binary');
            header("Content-disposition: attachment; filename=".$filename."");
            readfile($filename);
            unlink($filename);
            $con -> close();
            $stmt -> close();
            exit;
}


if(isset($_POST['export_student'])) {
    $file = new Spreadsheet();
    $activesheet = $file -> getActiveSheet();


    $activesheet -> setCellValue('A1',"Register Number");
    $activesheet -> setCellValue('B1',"Name");
    $activesheet -> setCellValue('C1',"DOB");
    $activesheet -> setCellValue('D1',"Dept Code");
    $activesheet -> setCellValue('E1',"Dept Name");
    $activesheet -> setCellValue('F1',"Course");
    $activesheet -> setCellValue('G1',"Regulation");


    $activesheet->getStyle("A1:G1")->getFont()->setBold( true );
    $count = 2;
    include_once("db_connection.php");

    $stmt = $con -> prepare("SELECT * FROM student_details;");

    if($stmt -> execute()) {
        $result = $stmt -> get_result();

        foreach($result as $row) {
            $activesheet -> setCellValue('A'.$count,$row['regno']);
            $activesheet -> setCellValue('B'.$count,$row['name']);
            $activesheet -> setCellValue('C'.$count,$row['dob']);
            $activesheet -> setCellValue('D'.$count,$row['deptcode']);
            $activesheet -> setCellValue('E'.$count,$row['deptname']);
            $activesheet -> setCellValue('F'.$count,$row['course']);
            $activesheet -> setCellValue('G'.$count,$row['regulation']);
            ++$count;



        }

            $filename = 'Student_data_all.xlsx';
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file,'Xlsx');
            $writer -> save($filename);


            header('Content-Type: application/x-www-form-urlencoded');
            header('Content-Transfer-Encoding: Binary');
            header("Content-disposition: attachment; filename=".$filename."");
            readfile($filename);
            unlink($filename);
            $con -> close();
            $stmt -> close();
            exit;
    }



}

    

?>