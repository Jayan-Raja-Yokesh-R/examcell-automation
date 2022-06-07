<?php 
$total_pass_students = 0;
$registered_subs = 0;
$semester = 0;
$examination='';
$year=0;

$dept_codes = array(103 => 'civil',104 => 'cse',105 => 'eee',106 => 'ece', 114 => 'mech',205 => 'it');
$dept_codes_ex = array(103 => 'Civil Engineering',104 => 'Computer Science and Engineering',105 => 'Electrical and Electronic Engineering',106 => 'Electronics and Communication Engineering', 114 => 'Mechanical Engineering',205 => 'Information Technology');
require_once __DIR__ . '/vendor/autoload.php';
//SELECT subcode,COUNT( * ) AS SUBJECT_COUNT FROM dept_civil GROUP BY 

//SELECT subcode,COUNT( * )  AS SUBJECT_COUNT FROM dept_civil  WHERE total >= 50 GROUP BY subcode
function generateTempalteRow($row) {
    global $registered_subs,$year,$examination;
    $examination = $row['exam_start_month'].'/ '.$row['exam_end_month'].', '.$year;
    $table_row = '
        <tr>

            <td style="height:30px;">'.$registered_subs.'</td>
            <td>'.$row['subcode'].'</td>
            <td>'.$row['subname'].'</td>
            <td>'.$row['total_students'].'</td>
            <td>'.$row['student_passed'].'</td>
            <td>'.$row['student_failed'].'</td>
            <td>'.$row['pass_percent'].'</td>

        </tr>
    ';
    return $table_row;

}
function getSubjectDetails($row,$tablename,$con) {
    global $total_pass_students ,$registered_subs;

    $query_for_sub_details = "SELECT subcode,subname,exam_start_month,exam_end_month,COUNT(*) as total_students,
        COUNT(case WHEN total >= 50 then total end) as student_passed,
        COUNT(case WHEN total < 50 then total end) as student_failed,

        CONCAT( ROUND((COUNT(CASE WHEN total >=50 THEN total END ) / COUNT( * ) *100  ),2),'%') as pass_percent


    FROM $tablename 
    WHERE sem=? AND subcode=? AND year_of_exam=?" ;

    $stmt_for_sub_details = $con -> prepare($query_for_sub_details);

    $stmt_for_sub_details -> bind_param("isi",$row['sem'],$row['subcode'],$row['year_of_exam']);

    if($stmt_for_sub_details -> execute()) {
        $result = $stmt_for_sub_details -> get_result();
        $subdetail = $result -> fetch_assoc();

        $total_pass_students += $subdetail['student_passed'];
        ++$registered_subs;

        return generateTempalteRow($subdetail);



        





        
    } else {
        header("Location:index.php");
    }
    

}
function generatePdf($table,$filename,$deptcode) {

    global $dept_codes_ex,$semester,$examination;
    $header = '
    <html>
    <head>
    <link rel="stylesheet" href="css/style.css"></link>
    </head>
    <body>
    <h4>Semester:'.$semester.'</h4>
    <h4>Date:'.date("d-m-Y").'</h4>
    <h4>Examination: '.$examination.'</h4>
    ';
    $footer = '
       <centre> <table class="footer">
            <tr>
                <td>Class in-charge</td>
                <td>HOD/Principal</td>
            </tr>
        </table>
        </centre>
        </body>
        </html>
    ';
    $mpdf = new mPDF();
    $mpdf->SetMargins(6, 6, 6, 6);
    $mpdf -> setAutoTopMargin='stretch';
    $mpdf -> SetHTMLHeader('<header style="font-size:11px;font-weight:bold;text-align:center;
    border-bottom:1px solid black;">
    <div style="float:left;width:40px;height:40px;">
    <img src="assets/images/logo.png" style="width:40px;height:40px;float:left;"/>
    </div>
    <div>
    <p style="line-height:1;">VELAMMAL COLLEGE OF ENGINEERING AND TECHNOLOGY</p>
    <p style="line-height:1;">Department Of '.$dept_codes_ex[$deptcode].'</p>
    <p style="line-height:1;">Result Analysis</p>
    </div>
    </header>');
    $template = $header.$table.$footer;
    $mpdf -> writeHTML($template);
    $mpdf -> Output($filename,'D');
    exit;

}
if(isset($_POST['print'])) {
  
    $deptcode = $_POST['dept'];
    $sem = $_POST['semester'];
    $year_of_exam = $_POST['year'];

    $semester = $sem;
    $year = $year_of_exam;
    $table = '<table style="border:1px solid black;">
    <tr>
        <td class="bold">Sr.No</td>
        <td class="bold">Subject Code</td>
        <td class="bold">Subject Name</td>
        <td class="bold">No of Students Appeared</td>
        <td class="bold">No of Students Passed</td>
        <td class="bold">No of Students Failed</td>
        <td class="bold">Pass Percent</td>
    </tr>';
    include_once("db_connection.php");

    $tablename = 'dept_'.$dept_codes[$deptcode];

    $query_for_sub_reg = "SELECT * FROM subject_reg 
    WHERE sem=? AND deptcode=? AND year_of_exam=?
    ORDER BY subcode";

    $stmt_for_sub_reg = $con -> prepare($query_for_sub_reg);

    $stmt_for_sub_reg -> bind_param("isi",$sem,$deptcode,$year_of_exam);

    $stmt_for_sub_reg -> execute();

    $result = $stmt_for_sub_reg -> get_result();

    foreach($result as $row) {


        $table .= getSubjectDetails($row,$tablename,$con);

    }
    $table .= '</table>';
    $filename ='result_analysis_'.$dept_codes[$deptcode].'_sem_'.$sem.'.pdf';
    generatePdf($table,$filename,$deptcode,$sem);



}

?>