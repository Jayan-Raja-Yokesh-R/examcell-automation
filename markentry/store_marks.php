<?php

if(isset($_POST['uploadmarks'])) {
    // echo "Form submitted";
    
    // print_r(count($_POST['marks']));
    // print_r($_POST['markinwords']);

    $marks = $_POST['marks'];
    $markinwords = $_POST['markinwords'];
    $dummynums = $_POST['dummynums'];
    $subcode = $_POST['subcode'];
    $externalname = $_POST['externalname'];

    $s_dummyno = $dummynums[0];
    $e_dummyno = $dummynums[count($dummynums)-1];
    include_once("db_connection.php");

    $successful = true;
    $stmt = $con -> prepare("UPDATE exam_details SET mark=?,markinwords=? WHERE dummyno=?;");
    for($index=0;$index<count($marks);$index++) {

        $submark = intval($marks[$index]);
        $submarkinword = $markinwords[$index];
        $dummyno = intval($dummynums[$index]);


        $stmt -> bind_param("isi",$submark,$submarkinword,$dummyno);

        if(!$stmt -> execute()) {
         $successful = false;
         break;
        } 
            
        

    }

    if($successful) {

        // $stm1 = $con -> prepare("SELECT * FROM external WHERE subcode=?");
        // $stm1 -> bind_param("s",$subcode);
        // $stm1 -> execute();
        // $rows = $stm1 -> get_result() -> num_rows;
        // $stm1 -> close();
        // if( $rows > 0) {
        //     $smt = $con -> prepare("UPDATE external SET ename=? WHERE subcode=?;");
        //     $smt -> bind_param("ss",$externalname,$subcode);
        //     if($smt -> execute()) {
        //         echo "<script>alert('Updation Successful')
        //         window.location.href = 'mark_entry.php';</script>";
        //         $smt -> close();
        //         $con -> close();
        //     }
  
        // } 
        // else {
        //     $smt1 = $con -> prepare("INSERT INTO external(ename,subcode) VALUES(?,?);");
        //     $smt1 -> bind_param("ss",$externalname,$subcode);
        //     if($smt1 -> execute()) {
                // echo "<script>alert('Updation Successful')
                // window.location.href = 'mark_entry.php';</script>";
                // $smt1 -> close();
                // $con -> close();
        //     }
        // }
        $stmt = $con -> prepare("INSERT INTO external(ename,subcode,s_dummyno,e_dummyno) VALUES(?,?,?,?);");
        $stmt -> bind_param("ssii",$externalname,$subcode,$s_dummyno,$e_dummyno);

        if($stmt -> execute()) {
            echo "<script>alert('Updation Successful')
            window.location.href = 'mark_entry.php';</script>";
            $stmt -> close();
            $con -> close();
        } 
       
        
    } else {
        echo "<script>alert('Error Occurred in updating marks')
        window.location.href = 'mark_entry.php'</script>";
    }

}

if(isset($_POST['update_entries'])) {
    $regno = $_POST['regnum'];
    $dummyno = $_POST['dummynum'];
    $subcode = $_POST['subcode'];
    $subname = $_POST['subname'];
    $date = $_POST['examdate'];
    $session = $_POST['session'];
    $semester = $_POST['sem'];
    $mark = $_POST['mark'];
    $markinwords = $_POST['markinwords'];

    include_once("db_connection.php");

    $stmt = $con -> prepare("UPDATE `exam_details` SET `regno`=?,`dummyno`=?,`subcode`=?,`subname`=?,`examdate`=?,`session`=?,`sem`=?,`mark`=?,`markinwords`=? WHERE dummyno=?");

    $successful = true;
    for($index=0;$index<count($dummyno);$index++) {

        // $submark = intval($marks[$index]);
        // $submarkinword = $markinwords[$index];
        // $dummyno = intval($dummynums[$index]);

        $rno = intval($regno[$index]);
        $dno = intval($dummyno[$index]);
        $scode = $subcode[$index];
        $sname = $subname[$index];
        $dt = $date[$index];
        $ses = $session[$index];
        $sem = $semester[$index];
        $mk = $mark[$index];
        $mkw = $markinwords[$index];

        $stmt -> bind_param("iissssiisi",$rno,$dno,$scode,$sname,$dt,$ses,$sem,$mk,$mkw,$dno);

        if(!$stmt -> execute()) {
         $successful = false;
         break;
        } 
            
        

    }
    if($successful) {
        echo "<script>alert('Updation Successful')
            window.location.href = 'edit_entries.php';</script>";
    }


}

?>