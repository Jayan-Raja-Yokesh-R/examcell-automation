<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Entry</title>
    <link rel="stylesheet" href="assets/stylings/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/edit_entries.css">
</head>
<body>
<header>

<nav class="navbar navbar-expand justify-content-around navbar-dark bg-dark">
        <a class="navbar-brand" style="margin-left:0px;">MARK ENTRY</a>
   
            <ul class="navbar-nav">

                <li class="nav-item"><a href="#" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="mark_entry.php" class="nav-link">Mark Entry</a></li>
                <li class="nav-item"><a href="view_marks.php" class="nav-link">View</a></li>
                <li class="nav-item"><a href="view_result.php" class="nav-link">View Result</a></li>
                <li class="nav-item"><a href="export_details.php" class="nav-link">Export</a></li>
                <li class="nav-item rounded-3 bg-white"><a href="edit_entries.php" class="nav-link text-dark">Edit</a></li>
            </ul>

</nav>

</header>
    <div class="container">

    <div class="form-container">
            <form action="edit_entries.php" method="POST" >

                <label for="dummystart">From</label>
                <input type="text" name="dummystart" class="dummystart form-control" id="dummystart" placeholder="Enter the dummy number" required />
                <label for="dummyend">To</label>
                <input type="text" name="dummyend" class="dummyend form-control" id="dummyend" placeholder="Enter the dummy number" required />
                <label for="subcode">Subject Code</label>
                <input type="text" name="subcode" id="subcode" class="subcode form-control" placeholder="Enter subject code" required />
                <input type="submit" name="display" value="Display" id="display" class="display btn btn-outline-success" >

            </form>
        </div>




        <?php
            if(isset($_POST['display'])) {
             
                
                include_once("db_connection.php");
                $start = intval($_POST['dummystart']);
                $end = intval($_POST['dummyend']);
                $subcode = $_POST['subcode'];
                $subname = '';
                $body = "";
                

                $stmt = $con -> prepare("SELECT * FROM exam_details WHERE dummyno BETWEEN ? AND ? AND subcode=?;");
                $srno = 1;


                $stmt -> bind_param("iis",$start,$end,$subcode);

                if($stmt -> execute()) {
                    $result = $stmt -> get_result();
                    $srno = 1;
                    if($result -> num_rows > 0) {
                        
                        foreach($result as $row) {

                            $regno = $row['regno'];
                            $dummyno = $row['dummyno'];
                            $subname = $row['subname'];
                            $date = $row['examdate'];
                            $session = $row['session'];
                            $sem = $row['sem'];
                            $mark = $row['mark'];
                            $markinwords = $row['markinwords'];
                            $body .= "<tr>
                            
                                <td>$srno</td>
                                <td><input type='text' name='regnum[]' readonly value='$regno' class='form-control' /></td>
                                <td><input type='text' name='dummynum[]' value='$dummyno'  class='form-control' /></td>
                                <td><input type='text' name='subcode[]' value='$subcode'  class='form-control' /></td>
                                <td><input type='text' name='subname[]' value='$subname'  class='form-control' /></td>
                                <td><input type='text' name='examdate[]' value='$date'  class='form-control' /></td>
                                <td><input type='text' name='session[]' value='$session'  class='form-control' /></td>
                                <td><input type='text' name='sem[]' value='$sem'  class='form-control' /></td>
                                <td><input type='number' name='mark[]' value='$mark' min='0' max='100' class='marks form-control' required /></td>
                                <td><input type='text' name='markinwords[]' value='$markinwords' class='form-control' /></td>
    
                            
                            </tr>";
                            ++$srno;
    
                        }

                        $header = '
                            <form method="POST" action="store_marks.php" class="entryform">
                        
                            <table class="table table-bordered">
                                    
                                    <tr class="bg-dark">
                                        <td class="text-light">Sr. No.</td>
                                        <td class="text-light">Register Number</td>
                                        <td class="text-light">Dummy Number</td>
                                        <td class="text-light">Subject Code</td>
                                        <td class="text-light">Subject Name</td>
                                        <td class="text-light">Exam date</td>
                                        <td class="text-light">Session</td>
                                        <td class="text-light">Semester</td>
                                        <td class="text-light">Mark</td>
                                        <td class="text-light">Mark In Words</td>
                                    </tr>
                                    
                            ';
                            echo $header.$body."
                            <tr><td colspan=''><input type='submit' value='Update' name='update_entries' id='update_entries' class='btn  btn-success' /></td>
                               </td> 
                            
                            </tr>
                             </table>
                            
                             </form>";

                    } else {

                        echo "<h3>--NO DATA FOUND FOR GIVEN DETAILS--</h3>";
                        exit;
                    }
                    
                }
     

                
                
            }
        ?>

    </div>

    <script>

        
var marks = document.querySelectorAll('.marks')

marks.forEach(mark=> {
    mark.addEventListener('blur',myfun);
});

function myfun(e) {


    let index = this.parentElement.parentElement.rowIndex - 1;
    let marks = document.getElementsByName('mark[]');
    let markinwords = document.getElementsByName('markinwords[]');
   
   
    markinwords[index].value =  convertNumberToWord(marks[index].value);

}



function convertNumberToWord(number) {

let numberInWord = '';


    
    let numbers = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];

      if(number != 0 && number != '' && number <= 100){
        let temp = number;
        if(parseInt(temp) >=0 && parseInt(temp) <10) {
            numberInWord = 'Zero ' + numbers[parseInt(temp)];
        } else {
            while(temp != 0) {
            

            numberInWord = numbers[temp % 10] +' '+numberInWord;
            temp = parseInt(temp / 10);
        }
        }
        
      

    } else if(number == 0 && number != ''){
        numberInWord = 'Zero Zero';
    } else {
        return '';
    }
  
    return numberInWord;



}
</script>
</body>
</html>