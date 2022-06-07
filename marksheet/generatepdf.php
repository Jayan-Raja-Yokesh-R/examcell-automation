<?php 
$exam_start_month = '';
$exam_end_month = '';
$year = 0;
$registeredSubjects = 0;
$dept_codes = array(103 => 'civil',104 => 'cse',105 => 'eee',106 => 'ece', 114 => 'mech',205 => 'it');
require_once '../mpdf/vendor/autoload.php';
function generateTemplateHeader($result) {

    $row = $result -> fetch_assoc();
    $exam = $row['exam_start_month'].'. / '.$row['exam_end_month'].'. Examination, '.$row['year_of_exam'];
    $course = $row['course'].' '.$row['deptname'];
    $header = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Template 1</title>
        <link rel="stylesheet" href="css/gradesheet.css"></link>  
    </head>
    <body>
        <div id="container">
    
        <table class="table table1" >
    
            <tr>
                <th style="border-right: 0px;width: 110px;"> <img src="assets/images/logo.png" class="logo" style="margin-left:20px" alt="vcet-logo" /></th>
               
                   <th colspan="3" style="text-align: center;width: 650px;">
                    <p style="font-weight: bold;">VELAMMAL COLLEGE OF ENGINEERING AND TECHNOLOGY</p>
                    
                    <p>(Autonomous)</p>
              
                    <p>MADURAI - 625 009</p>
                      
                    <p> UNIVERSITY EXAMINATIONS - '.$exam.'</p>
                    <p>PROVISIONAL GRADE SHEET</p>
                   </th>
    
               
               
            </tr>
    
            <tr>
                <td class="bold" style="width:115px;">Register Number</td>
                <td>'.$row['regno'].'</td>
                <td class="bold">Semester</td>
                <td>'.str_pad($row['sem'],2,"0",STR_PAD_LEFT).'</td>
            </tr>
    
            <tr>
                <td class="bold">Name</td>
                <td>'.$row['name'].'</td>
                <td class="bold">D.O.B</td>
                <td>'.$row['dob'].'</td>
            </tr>
    
            <tr>
                <td class="bold">Degree & Branch</td>
                <td colspan="">'.$course.'</td>
                <td class="bold">REGULATIONS</td>
                <td>'.$row['regulation'].'</td>
            </tr>
    
            <tr>
                <td class="bold">COLLEGE OF STUDY </td>
                <td colspan="3">VELAMMAL COLLEGE OF ENGINEERING AND TECHNOLOGY</td>
                
            </tr>
            
    
      
           
    
        </table>';

        return $header;
}
function generateEmptyTemplateRows($srno) {

    $empty_rows = '</table>';
    for($count=$srno;$count<=16;$count++) {

        $empty_rows = '   <tr class="subjects">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>

    </tr>
'.$empty_rows;

    }
   
    return $empty_rows;
    
}
function generateTemplateBody($result) {

    global $registeredSubjects;
    $body = '    <table class="table table2">

       

           
            
                  
    <tr class="subjects bold" style="border:0.5px solid black;">

        <td class="bold">Sr.no</td>
        <td class="bold sem">Sem</td>
        <td class="bold subcode">Sub Code</td>
        <td class="bold subtitle">Subject Title</td>
        <td class="bold grade">Grade</td>
        <td class="bold result">Result</td>


    </tr>';
    $srno = 1;

    foreach($result as $row) {
        
        $result = $row['total'] < 50 ? 'FAIL' : 'PASS';
        $body .= '
        <tr class="subjects">
        <td>'.$srno++.'</td>
        <td>'.$row['actual_sem'].'</td>
        <td>'.$row['subcode'].'</td>
        <td>'.$row['subname'].'</td>
        <td>'.$row['grade'].'</td>
        <td>'.$result.'</td>

    </tr>
        ';
    }
    $registeredSubjects = --$srno;
    if($srno < 16) {
        return  $body.generateEmptyTemplateRows($srno);
    } else {
        return $body.'</table>';
    }
   
}
function generateTemplateSubBody() {
    $subBody = '<table class="mid-table" style="width: 800px;">
    <tr >
        <td class="bold">
            GRADE POINTS AVERAGE (GPA)
        </td>
        <td style="width: 80px;">NIL</td>
        <td class="bold">
            CUMULATIVE GRADE POINT AVERAGE (CGPA) 
        </td>
        <td style="width: 77px;">
            NIL
        </td>
    </tr>
</table>';
return $subBody;
}
function generateTemplateFooter() {

    global $registeredSubjects;
    $footer = '<table class="table table3">
	<tr>
            <td  colspan="3" class="bold" style="height:30px;">No of Subjects Registered: '.$registeredSubjects.'</td>
        </tr>
        <tr>

            <td class="footer">Signature of the Candidate</td>
            <td class="footer">Signature of the Principal with seal</td>
            <td class="footer">Controller of Examinations</td>
        </tr>
		
</table>';
return $footer;
}
function generatePdf($html,$filename) {
    $mpdf = new mPDF();
    $mpdf->SetMargins(6, 6, 6, 6);
    $mpdf -> writeHTML($html);
    $mpdf -> Output($filename.'_grade_sheet.pdf','D');
} 
if(isset($_POST['print'])) {
    


$regno = $_POST['regno'];
$yearOfExam = $_POST['year'];
$sem = $_POST['semester'];

include_once("db_connection.php");

$query = "SELECT deptcode FROM student_details WHERE regno=?";

$stmt_for_tabel_name = $con -> prepare($query);
$stmt_for_tabel_name -> bind_param("i",$regno);

if($stmt_for_tabel_name -> execute()) {
    $result = $stmt_for_tabel_name -> get_result();
    if($result -> num_rows > 0){

        $row = $result -> fetch_assoc();
        $tablename = 'dept_'.$dept_codes[$row['deptcode']];
        
        $query = "SELECT * FROM student_details s 
        INNER JOIN $tablename d 
        ON s.regno=d.regno
        WHERE s.regno=? AND d.sem=? AND d.year_of_exam=?
        ORDER BY d.subcode";
        $stmt_for_mark = $con -> prepare($query);
        $stmt_for_mark -> bind_param("iii",$regno,$sem,$yearOfExam);

        if($stmt_for_mark -> execute()) {
            $result = $stmt_for_mark -> get_result();
            $template = generateTemplateHeader($result).generateTemplateBody($result).generateTemplateSubBody().generateTemplateFooter();
            
            generatePdf($template,$regno);
            $con -> close();
            
        } else {
            $con -> close();
            header("Location:index.php");
            // exit;
        }

    } else {
        $con -> close();
        header("Location:index.php");
    }
} else {
    $con -> close();
    header("Location:index.php");
}



 

}



if(isset($_POST['printall'])) {
    
    $regnostart = $_POST['regnofrom'];
    $regnoend = $_POST['regnoto'];
    $sem = $_POST['semester'];
    $yearOfExam = $_POST['year'];
    $mpdf = new mPDF();
    $mpdf->SetMargins(6, 6, 6, 6);
    include_once("db_connection.php");
    for($regno=$regnostart;$regno<=$regnoend;++$regno) {


        $query = "SELECT deptcode FROM student_details WHERE regno=?";

            $stmt_for_tabel_name = $con -> prepare($query);
            $stmt_for_tabel_name -> bind_param("i",$regno);

            if($stmt_for_tabel_name -> execute()) {
                $result = $stmt_for_tabel_name -> get_result();
                if($result -> num_rows > 0){

                    $row = $result -> fetch_assoc();
                    $tablename = 'dept_'.$dept_codes[$row['deptcode']];
                    
                    $query = "SELECT * FROM student_details s 
                    INNER JOIN $tablename d 
                    ON s.regno=d.regno
                    WHERE s.regno=? AND d.sem=? AND d.year_of_exam=?
                    ORDER BY d.subcode";
                    $stmt_for_mark = $con -> prepare($query);
                    $stmt_for_mark -> bind_param("iii",$regno,$sem,$yearOfExam);

                    if($stmt_for_mark -> execute()) {
                        $result = $stmt_for_mark -> get_result();
                        $template = generateTemplateHeader($result).generateTemplateBody($result).generateTemplateSubBody().generateTemplateFooter();
                        
                        $mpdf -> writeHTML($template);
                        if($regno < $regnoend) {
                            $mpdf -> addPage();
                        }
                       
                        
                    } else {
                        $con -> close();
                        header("Location:index.php");
                        // exit;
                    }

                } else {
                    $con -> close();
                    header("Location:index.php");
                }
            } else {
                $con -> close();
                header("Location:index.php");
            }


    }

    $mpdf -> Output($regnostart.'_'.$regnoend.'_grade_sheets.pdf','D');


}

?>