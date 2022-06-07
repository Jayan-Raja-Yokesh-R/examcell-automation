<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Marks</title>
    <link rel="stylesheet" href="css/view_marks.css">
    <link rel="stylesheet" href="assets/stylings/bootstrap/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand justify-content-around navbar-dark bg-dark">
        <a class="navbar-brand" style="margin-left:0px;">MARK ENTRY</a>
           <ul class="navbar-nav">

               <li class="nav-item active"><a href="index.php" class="nav-link">Home</a></li>
               <li class="nav-item"><a href="mark_entry.php" class="nav-link">Mark Entry</a></li>
               <li class="nav-item rounded-3 bg-white"><a href="#" class="nav-link text-dark">View</a></li>
               <li class="nav-item"><a href="view_result.php" class="nav-link">View Result</a></li>
               <li class="nav-item"><a href="export_details.php" class="nav-link">Export</a></li>
               <li class="nav-item"><a href="edit_entries.php" class="nav-link">Edit</a></li>
           </ul>

</nav>
    <div class="container">

        
        <div class="form-container">
            <form action="view_marks.php" method="POST" class="form">

                <label for="dummystart">From</label>
                <input type="text" name="dummystart" class="dummystart form-control" id="dummystart" placeholder="Enter dummy number" required />
                <label for="dummyend">To</label>
                <input type="text" name="dummyend" class="dummyend form-control" id="dummyend" placeholder="Enter dummy number" required />
                <label for="subcode">Subject Code</label>
                <input type="text" name="subcode" id="subcode" class="subcode form-control" placeholder="Enter subject code" required />
                <input type="submit" name="view" value="View" id="view" class="view btn btn-outline-success" />
                <input type='submit' value='Print' name='print' id='print' class="print btn btn-outline-primary" formaction="print_pdf.php" />

            </form>
        </div>



        <?php
            if(isset($_POST['view'])) {


                
                include_once("db_connection.php");
                $start = intval($_POST['dummystart']);
                $end = intval($_POST['dummyend']);
                $subcode = $_POST['subcode'];

                $stmt = $con -> prepare("SELECT dummyno,subcode,subname,mark,markinwords FROM exam_details WHERE dummyno=? AND subcode=?;");
                $srno = 1;
                $body = "";
                for($dummyno=$start;$dummyno<=$end;$dummyno++) {


                    $stmt->bind_param("is",$dummyno,$subcode);
                    if($stmt -> execute()) {
                        $result = $stmt -> get_result();
                        if(!$result -> num_rows > 0) {
                            exit;
                        }
                        $row = $result -> fetch_assoc();
                        $subname = $row['subname'];
                        $mark = $row['mark'];
                        $markinwords = $row['markinwords'];
                        $body .=  "<tr>
                        
                            <td>$srno</td>
                            <td>".str_pad($dummyno,6,'0',STR_PAD_LEFT)."</td>

                            <td>".str_pad($mark,2,0,STR_PAD_LEFT)."</td>
                            <td>$markinwords</td>

                        
                        </tr>";

                    }
                    $srno++;
                    

                }
                $header =  "
                <h5>Subject Code: ".$subcode."</h5>
                <h5>Subject Name: ".$subname."</h5>
                <table class='table table-bordered'>
                        
                        <tr class='bg-dark'>
                            <td class='text-light'>Sr. No.</td>
                            <td class='text-light'>Dummy Number</td>
                 
                            <td class='text-light'>Mark</td>
                            <td class='text-light'>Mark In Words</td>
                        </tr>
                
                ";
                echo $header.$body."
                <tr></tr>
                 </table>";
            }


        ?>

    </div>
</body>
</html>