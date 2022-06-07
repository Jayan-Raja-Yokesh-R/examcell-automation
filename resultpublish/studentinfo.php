<?php 
    session_start();
    if(isset($_SESSION['user'])) {
        unset($_SESSION['user']);
    
    } else {
        header("Location:index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Info</title>
    <link rel="stylesheet" href="assets/stylings/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="css/studentinfo.css">
</head>
<body>
    <header class="bg-dark text-light header">
        <img src="assets/images/logo.png" alt="vcet_logo" class="logo" />
        <h2>VELAMMAL COLLEGE OF ENGINEERING AND TECHNOLOGY</h2>

    </header>
    <div class="container">

    
    <div class="table-container">
    <?php 

        if(isset($_POST['login'])) {


                 if($_POST['registerno'] <= 0 || $_POST['semester'] <= 0) {
                     header("Location:index.php");
                 }
                $regno = $_POST['registerno'];
                $sem = $_POST['semester'];
                $dob = $_POST['dob'];
                
              

                $_POST = array();
                include_once("private/db_connection.php");

                $dept_codes = array(103 => 'civil',104 => 'cse',105 => 'eee',106 => 'ece', 114 => 'mech',205 => 'it');
                $dept_code_query = "SELECT * FROM student_details WHERE regno=? AND dob=?";
                $dept_stmt = $con -> prepare($dept_code_query);

                $dept_stmt -> bind_param("is",$regno,$dob);
				$dept_stmt -> execute();
				$result = $dept_stmt -> get_result();
                if($result -> num_rows > 0) {

					
                    $row = $result -> fetch_assoc();
		
                    $name = $row['name'];
                    $branch = $row['course'].' '.$row['deptname'];

                    $deptcode = intval($row['deptcode']);
                    $tablename = "dept_".$dept_codes[$deptcode];

                    $exam_query = "SELECT * FROM student_details s INNER JOIN $tablename d
                    ON s.regno=d.regno
                    WHERE s.regno=? AND s.dob=? AND deptcode=? AND sem=?";

                    $exam_stmt = $con -> prepare($exam_query);
                    $exam_stmt -> bind_param("isii",$regno,$dob,$deptcode,$sem);

                    if($exam_stmt -> execute()) {

                        $result = $exam_stmt -> get_result();
                        if($result -> num_rows < 1) {
                            $con -> close();
                            $dept_stmt -> close();
                            $exam_stmt -> close();
                            echo "<script>alert('No data found for given details')
                            window.location.href = 'index.php';
                            </script>";
                            
                        }
                        echo '<div class="student-table-container">
                        <table class="table table-striped student-table">
                                    <tr>
                                        <th>Register Number</th>
                                        <td>'.$regno.'</td>
                                        <td><a href="logout.php" class="btn btn-dark">Log out</a></td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td colspan="2">'.$name.'</td>
                                    </tr>
                                    <tr>
                                        <th>Branch</th>
                                        <td colspan="2">'.$branch.'</td>
                                    </tr>
                                    <tr>
                                        <th>Semester</th>
                                        <td colspan="2">'.$sem.'</td>
                                    </tr>
                                </table>
                                </div>
                        
                                <table class="table table-striped table-bordered mark-table">
                        
                        
                                <thead>
                                    <tr>
                                        <th>Semester</th>
                                        <th>Subject Code</th>
										<th>Total</th>
                                        <th>Grade</th>
                                        <th>Result</th>
                                    </tr>
                                </thead>
                                <tbody>

                            ';
                        foreach($result as $row) {
                
                            $result = $row['total'] < 50 ? 'FAIL' : 'PASS';
                            echo '<tr>

                                    <td>'.str_pad($row['actual_sem'],2,"0",STR_PAD_LEFT).'</td>
                                    <td>'.$row['subcode'].'</td>
									<td>'.$row['total'].'</td>
                                    <td>'.$row['grade'].'</td>
                                    <td>'.$result.'</td>

                                </tr>';
                        }
                        $con -> close();
                        $dept_stmt -> close();
                        $exam_stmt -> close();
                        echo '
                        </tbody>
                        </table>';

                

                    }

                } else {
                    $con -> close();
                    $dept_stmt -> close();
     
                    echo "<script>alert('No data found')
                    window.location.href='index.php';</script>";
                   
                }

        } else {
            header("Location:index.php");
        }


?>

    </div>

    </div>
    <script>

        setTimeout(function() {
            alert('Session timedout you will be redirected to the home page')
            window.location.href="index.php"
        },900000)

    </script>
</body>
</html>
