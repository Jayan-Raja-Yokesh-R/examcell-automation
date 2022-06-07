<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Result</title>
    <link rel="stylesheet" href="css/view_marks.css">
    <link rel="stylesheet" href="assets/stylings/bootstrap/css/bootstrap.min.css">
    <style>
        .sub-cont {
          display: flex;
          align-items: center;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand justify-content-around navbar-dark bg-dark">
        <a class="navbar-brand" style="margin-left:0px;">MARK ENTRY</a>
           <ul class="navbar-nav">

               <li class="nav-item active"><a href="index.php" class="nav-link">Home</a></li>
               <li class="nav-item"><a href="mark_entry.php" class="nav-link">Mark Entry</a></li>
               <li class="nav-item"><a href="view_marks.php" class="nav-link">View</a></li>
               <li class="nav-item rounded-3 bg-white"><a href="#" class="nav-link text-dark">View Result</a></li>
               <li class="nav-item"><a href="export_details.php" class="nav-link">Export</a></li>
               <li class="nav-item"><a href="edit_entries.php" class="nav-link">Edit</a></li>
           </ul>

</nav>
<div class="container">

        
    <div class="form-container">
        <form action="view_result.php" method="POST" class="form">

            
            <label for="subcode">Subject Code</label>
            <input type="text" name="subcode" id="subcode" class="subcode form-control" placeholder="Enter subject code" required />
           
            <lable for="branchname">Branch Name</lable>
            <input type="text" name="branchname" id="branchname" class="form-control" placeholder="Enter branch name" />
            <div class="sub-cont">
                <label for="dummystart">From</label>
                <input type="text" name="dummystart" class="dummystart form-control" id="dummystart" placeholder="Enter the dummy number" required />
                <label for="dummyend">To</label>
                <input type="text" name="dummyend" class="dummyend form-control" id="dummyend" placeholder="Enter the dummy number" required />
            </div>
            <input type="submit" name="view" value="View" id="view" class="view btn btn-outline-success" />
            

        </form>

        
    </div>

    <?php 
    
        if(isset($_POST['view'])) {
            

            include_once('db_connection.php');
            $subcode = $_POST['subcode'];
            $branchname = $_POST['branchname'];
            $start = $_POST['dummystart'];
            $end = $_POST['dummyend'];

            unset($_POST['subcode']);
            unset($_POST['branchname']);
            unset($_POST['dummystart']);
            unset($_POST['dummyend']);
            unset($_POST['view']);
            echo "
               
                <table class='table table-bordered'>
                        
                        <tr class='bg-dark'>
                            <td class='text-light'>Sr. No.</td>
                            <td class='text-light'>Dummy Number</td>
                            <td class='text-light'>Subject Code</td>
                            <td class='text-light'>Subject Name</td>
                            <td class='text-light'>Mark</td>
                            <td class='text-light'>Mark In Words</td>
                        </tr>
                
                ";


            // $stmt = $con -> prepare("SELECT * FROM exam_details WHERE subcode=? AND deptname=?;");
            $query = "SELECT * FROM exam_details WHERE dummyno BETWEEN ? AND ? AND subcode = ? AND regno IN (SELECT regno from student_details WHERE deptname= ?);";

            $stmt = $con -> prepare($query);
            $stmt -> bind_param("iiss",$start,$end,$subcode,$branchname);

            if($stmt->execute()) {


                $sno = 1;
                $result = $stmt -> get_result();
                while($row = $result->fetch_assoc()) {

                    $dummyno = $row['dummyno'];
                    $subcode = $row['subcode'];
                    $subname = $row['subname'];
                    $mark = $row['mark'];
                    $markinwords = $row['markinwords'];
                    echo "<tr>
                        
                         
                            <td>$sno</td>
                            <td>$dummyno</td>
                            <td>$subcode</td>
                            <td>$subname</td>
                            <td>$mark</td>
                            <td>$markinwords</td>

                        
                        </tr>";
                        ++$sno;
                }

            }


           

        } else {
            echo "<h2>---</h2>";
        }
    
    ?>
</div>


</body>
</html>