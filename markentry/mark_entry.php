<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Entry</title>
    <link rel="stylesheet" href="assets/stylings/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/mark_entry.css">
</head>
<body>

<header>

<nav class="navbar navbar-expand justify-content-around navbar-dark bg-dark">
        <a class="navbar-brand" style="margin-left:0px;">MARK ENTRY</a>
           
           <ul class="navbar-nav">

               <li class="nav-item active"><a href="index.php" class="nav-link">Home</a></li>
               <li class="nav-item rounded-3 bg-white"><a href="#" class="nav-link text-dark">Mark Entry</a></li>
               <li class="nav-item"><a href="view_marks.php" class="nav-link">View</a></li>
               <li class="nav-item"><a href="view_result.php" class="nav-link">View Result</a></li>
               <li class="nav-item"><a href="export_details.php" class="nav-link">Export</a></li>
               <li class="nav-item"><a href="edit_entries.php" class="nav-link">Edit</a></li>
           </ul>

</nav>

    </header>
    <div class="container ">


        <div class="form-container">
            <form action="mark_entry.php" method="POST" >

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
                

                $stmt = $con -> prepare("SELECT dummyno,subcode,subname FROM exam_details WHERE dummyno=? AND subcode=?;");
                $srno = 1;

                for($dummyno=$start;$dummyno<=$end;$dummyno++) {


                    $stmt->bind_param("is",$dummyno,$subcode);
                    if($stmt -> execute()) {
                        $result = $stmt -> get_result();
						if(!$result -> num_rows > 0) {
							exit;
						}
                        $row = $result -> fetch_assoc();
                        $subname = $row['subname'];
                        $no = str_pad($dummyno,6,'0',STR_PAD_LEFT);
                        $body .= "<tr>
                        
                            <td>$srno</td>
                            <td><input type='text' name='dummynums[]' value='$no' readonly class='form-control' /></td>
    
                            <td><input type='number' name='marks[]' value='' min='0' max='100' class='marks form-control' required /></td>
                            <td><input type='text' name='markinwords[]' value='' class='form-control' /></td>

                        
                        </tr>";
                        $srno++;

                    }
                    

                }

                $header = '
                <form method="POST" action="store_marks.php" class="entryform">
                <h5>Subject Code: '.$subcode.'</h5>
                <h5>Subject Name: '.$subname.'</h5>
                <table class="table table-bordered">
                        
                        <tr class="bg-dark">
                            <td class="text-light">Sr. No.</td>
                            <td class="text-light">Dummy Number</td>

                            <td class="text-light">Mark</td>
                            <td class="text-light">Mark In Words</td>
                        </tr>
                        
                ';
                echo $header.$body."
                <tr><td colspan='1'><input type='submit' value='Upload' name='uploadmarks' id='uploadmarks' class='btn  btn-success' /></td>
                   <td colspan='3'><input type='text' name='externalname'  id='externalname' style='width:300px;background-color: aquamarine;' class='form-control externalname' placeholder='Enter the external name' required /></td> 
                
                </tr>
                 </table>
                 <input type='hidden' name='subcode' value='$subcode' />
                 </form>";
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
            let marks = document.getElementsByName('marks[]');
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