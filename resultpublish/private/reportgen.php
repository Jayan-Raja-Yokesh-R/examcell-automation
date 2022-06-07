<?php 
require_once '../../mpdf/vendor/autoload.php';
include_once("db_connection.php");
$table_header = "";
$subjects = array();
$dept_codes = array(103 => 'civil',104 => 'cse',105 => 'eee',106 => 'ece', 114 => 'mech',205 => 'it');
function generateTableHeader($sem,$deptcode) {
    global $con,$html_header,$subjects,$subject_map;

    $table_header = '
    
    <table>
    <tr>
        <td class="bold" style="width:140px;">Register Number</td>
        <td class="bold">Name</td>
    ';

    $dcode = $deptcode;
    $sem = $sem;
    $stmt_subcode = $con -> prepare("SELECT * FROM subject_reg WHERE deptcode=? AND sem=? ORDER BY subcode");
    $stmt_subcode -> bind_param("ii",$dcode,$sem);
    $stmt_subcode -> execute();
    $result = $stmt_subcode -> get_result();
    if($result -> num_rows < 1 ) {
        return null;
    }
    $index = 0;
    forEach($result as $subcode) {
        array_push($subjects,$subcode['subcode']);
        $table_header .= '<td class="bold">'.$subcode['subcode'].'</td>';
    }
    
    return $table_header.'</tr>';



}

function generateTableBody($sem,$deptcode) {
    global $dept_codes,$con,$subjects;
    
    $table_body = '';
    $stmt_for_regno = $con -> prepare("SELECT regno,name FROM student_details WHERE deptcode=?");
    $stmt_for_regno -> bind_param("i",$deptcode);
    $stmt_for_regno -> execute();
    $result = $stmt_for_regno -> get_result();
    $tablename = "dept_".$dept_codes[$deptcode];
    if($result -> num_rows < 1) {
        return null;
    }
    forEach($result as $row) {
        
        $regno = $row['regno'];
        $name = $row['name'];
        $stmt_for_grade = $con -> prepare("SELECT * FROM $tablename WHERE regno=? AND sem=? ORDER BY subcode");
        $stmt_for_grade -> bind_param("ii",$regno,$sem);

        $stmt_for_grade -> execute();
        
        $res = $stmt_for_grade -> get_result();
        
        
        if($res -> num_rows < 1 ) {
            return null;
        }
        $table_body .= '<tr>
        <td>'.$regno.'</td>
        <td>'.$name.'</td>
        ';
        $i = 0;
        $j = 0;

        $registered_subs= array();
        $grade = array();
        forEach($res as $r) {
            array_push($registered_subs,$r['subcode']);
            array_push($grade,$r['grade']);
        }

        while($i < count($registered_subs)) {

            if(strcmp($registered_subs[$i],$subjects[$j]) == 0) {
                $table_body .= '
                    <td>'.$grade[$i].'</td>
                ';
                ++$i;
            }  else {
                $table_body .= '
                <td>-</td>
            ';
            }
            ++$j;
        }
        while($j < count($subjects)) {
            $table_body .= '
            <td>-</td>
        ';
        ++$j;
        }
        $table_body .= '</tr>';
        $registered_subs = array();
        $grade = array();

    }
    $table_body .= '</table>';
    $subjects=array();
    return $table_body;
}


if(isset($_POST['report']) && $_POST['deptcode'] != 0) {

    $dept_codes_ex = array(103 => 'Civil Engineering',104 => 'Computer Science and Engineering',105 => 'Electrical and Electronic Engineering',106 => 'Electronics and Communication Engineering', 114 => 'Mechanical Engineering',205 => 'Information Technology');
    $mpdf = new mPDF('','A4-L');
    $mpdf -> setAutoTopMargin='stretch';
    $mpdf -> SetHTMLHeader('<header style="font-size:11px;font-weight:bold;text-align:center;
    border-bottom:1px solid black;">
    <div style="float:left;width:40px;height:40px;">
    <img src="../assets/images/logo.png" style="width:40px;height:40px;float:left;"/>
    </div>
    <div>
    <p style="line-height:1;">VELAMMAL COLLEGE OF ENGINEERING AND TECHNOLOGY</p>
    <p style="line-height:1;">OFFICE OF CONTROLLER OF EXAMINATION</p>
    <p style="line-height:1;">PROVISIONAL RESULTS</p>
    </div>
    </header>');


    $mpdf -> setFooter("Page {PAGENO} of {nb}");
    $mpdf->SetDisplayMode('fullpage');
    $deptcode = $_POST['deptcode'];


    $html_header = '<html>
    <head>
    <style>
    table,tr,td {
    
        border: 0.5px solid black;
    }
    table {
        font-size:12px;
        width: 100%;
        border-collapse: collapse;
    }
    .bold {
        font-weight: bold;
        }
    </style>
    </head>
    <body>
    <p class="bold" style="font-size:11px;">Branch Code:'.$deptcode.'-'.$dept_codes_ex[$deptcode].'</p>
    

    ';
    $html_footer = '</body></html>';
    for($sem=1;$sem<=8;$sem++){

        $tableHeader = generateTableHeader($sem,$deptcode);
        $tableBody = generateTableBody($sem,$deptcode);

        if($tableBody != null && $tableHeader != null) {
            $template = $html_header.'<p class="bold"  style="font-size:11px;">Sem: '.str_pad($sem,2,"0",STR_PAD_LEFT).'</p>'.$tableHeader.$tableBody.$html_footer;
            // echo $template;
            $mpdf -> Addpage();
            $mpdf->WriteHTML($template);
            
        } 
        
        
    }

    $filename = $deptcode.'_'.$dept_codes_ex[$deptcode].'_report.pdf';
    $mpdf->Output($filename,'D');
    exit;
} else {
    header('Location:index.php');
}





?>