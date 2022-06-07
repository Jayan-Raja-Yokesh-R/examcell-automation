<?php 
            require_once '../mpdf/vendor/autoload.php';

            function convertNumberToWord($number) {

                $numberInWord = '';
               
                
        
                    
                    $numbers = array('Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine');
                    
                    if($number == 0) {
                        return 'Zero';
                    }
                      while($number != 0) {

                        $rem = intval($number % 10);

                        $numberInWord = ' '.$numbers[$rem].$numberInWord;
                        $number = intval($number / 10);
         
                      }
                  
                    return $numberInWord;
        
        
        
                }
    if(isset($_POST['print'])) {


        include_once("db_connection.php");
        $start = $_POST['dummystart'];
        $end = $_POST['dummyend'];
        $subcode = $_POST['subcode'];


        $totmarks = 0;
      //  $mpdf = new mPDF([
       //         'mode' => 'utf-8',
       //         'format' => 'A4',
        //        'orientation' => 'P',
	    //        'setAutoTopMargin' => false
        //]);
		$mpdf = new mPDF();
        $mpdf->SetMargins(6, 6, 6, 6);

        
        $stm = $con -> prepare("SELECT ename FROM external WHERE subcode=? AND s_dummyno=? AND e_dummyno=? LIMIT 1;");
        $stm -> bind_param("sii",$subcode,$start,$end);

        if($stm -> execute()) {
            
            $result = $stm -> get_result();
            $numrow = $result -> num_rows;
            if($numrow > 0){
                
                $external = $result -> fetch_assoc();
                $ename = $external['ename'];
            } else {
                $stm -> close();
                header("location: view_marks.php");
                
            }
           
        }
    
        

        $body = '';
        $stmt = $con -> prepare("SELECT * FROM exam_details WHERE dummyno=? AND subcode = ? ;");
        $srno = 1;
        $subname = '';
        
        for($num=$start;$num<=$end;$num++) {

            $stmt -> bind_param('is',$num,$subcode);

           
            if($stmt -> execute()) {
                
                $result = $stmt -> get_result();
                $numrow = $result -> num_rows;
                if($numrow > 0) {

                    
                    $row = $result -> fetch_assoc();

                    $subname = $row['subname'];
                    $body .= '<tr>
                    <td>'.$srno.'</td>
                    <td>'.str_pad($row['dummyno'],6,"0",STR_PAD_LEFT).'</td>
                    <td>'.str_pad($row['mark'],2,0,STR_PAD_LEFT).'</td>
                    <td>'.$row['markinwords'].'</td>
                
                    </tr>';
                    $totmarks += intval($row['mark']);
                }  else {

                }
                ++$srno;
                
            }
            
            


        }

        $header = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Mark Template</title>
        
            <style>
            .logo {
                width:70px;
                height:70px;
            }
            .bold {
                font-weight: bold;
            }
        
            .table1,.table2,.table3 {
                width: 100%;
            }
            .table2,.table2 td {
                border:1px solid black;
                border-collapse: collapse;
            }
            .table2 {
                text-align: center;
                margin-top: 15px;
            }
            .table2 td {
                height: 29px;
            }
            .table2 tr td:first-child {

                max-width: 50px;
        
            }
            .table3 {
                text-align: center;
                margin-top: 60px;
            }
            .footer td {
        
                height: 40px;
            }
            
            </style>
        </head>
        <body>
            <div class="container">

                
                
                <table class="table table1" >


                    <tr>
                        <th style="border-right: 0px;width: 110px;"> <img src="assets/images/vcet-logo.jpg" class="logo" style="margin-left:20px" /></th>
                       
                           <th colspan="3">
                            <p style="font-weight: bold;">VELAMMAL COLLEGE OF ENGINEERING AND TECHNOLOGY</p>
                            
                            <p>(Autonomous)</p>
                      
                            <p>MADURAI - 625 009</p>
                              
                            <p>OFFICE OF CONTROLLER OF EXAMINATION</p>
                        
                            <p>End Semester Examination Result</p>
        
                        
                        </th>
            
                     
                       
                    </tr>
        
                
                <tr>
                    <td colspan="2"><p><span class="bold">Subject Code:</span>'.$subcode.'</p></td>
                    
                </tr>

                <tr>
                    <td colspan="2"><p><span class="bold">Subject Name:</span>'.$subname.'</p></td>
                  
                </tr>
                </table>
        
            
                    
            

            
                <table class="table2" >
                    <tr >
                        <td class="bold" style="width:50px;">Sr. No.</td>
                        <td class="bold" style="width:110px;">
                            Dummy Number
                        </td>
                    
                        <td class="bold">
                            Mark
                        </td>
                        <td class="bold">
                            Mark in Words
                        </td>
                    </tr>';
        $body .= ' 
        
        <tr>
            <td colspan="2"><span class="bold">Total Marks: </span></td><td>'.$totmarks.'</td><td>'.convertNumberToWord($totmarks).'</td>
        </tr>
        </table>
		
		<table class="table3 bold">
			   <tr class="footer">
                <td >
                    <p>'.$ename.'</p>
                    Signature of Examiner
                </td>
                <td >
                    Signature of Chairman
                </td>
            </tr>
		</table>




            </div>
        </body>
        </html>';

        echo $html;
        $template = $header.$body;
        $mpdf->WriteHTML($template);
        $mpdf->Output($start.'_'.$end.'_'.$subcode.'.pdf','D');

    }
?>
