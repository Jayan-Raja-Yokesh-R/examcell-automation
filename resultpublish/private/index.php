<?php
if ( ! ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1') ) {
    header("HTTP/1.1 404 Not Found");   
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>

    <link rel="stylesheet" href="../assets/stylings/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="../css/import.css">
</head>
<body>
   <div class="container d-flex align-items-center justify-content-center">
        <div class="cont bg-light  d-flex  justify-content-center align-items-center">


                
            <div class="sub-container jumbotron p-3 border">
                <h4>ADMIN</h4>
                <div class="form-container ">

                    <form action="handleimport.php" enctype="multipart/form-data" method="POST">
                        <label for="s_uploaded_file">Upload Student Information:</label>
                        <input type="file" name="s_uploaded_file" id="s_uploaded_file" class="uploaded_file form-control" required  />
                        <input type="submit" value="Upload" id="s_data_upload" class="upload btn btn-outline-success" name="s_data_upload"  />
                    </form>

                </div>
                <div class="form-container">

                    <form action="handleimport.php" method="POST" enctype="multipart/form-data">

                        <label for="excel-file">Upload Student Exam Details:</label>
                        <input type="file" name="e_uploaded_file" id="e_uploaded_file" class="uploaded_file form-control" required  />
                        <h6 class="text-danger">[File Name Eg: deptcode_***.xlsv]</h6>
                        <input type="submit" value="Upload" name="e_data_upload" id="e_data_upload" class="e_data_upload btn btn-outline-success" />
                    
                    </form>

                </div>

        </div>

<div class="sub-container jumbotron p-3 border report">
    <h4>REPORT GENERATION</h4>
    <div class="form-container">

            <form action="reportgen.php" method="POST">

                <select name="deptcode" class="form-control">

                    <option value="0">--Select dept--</option>
                    <option value="103">CIVIL</option>
                    <option value="104">CSE</option>
                    <option value="105">EEE</option>
                    <option value="106">ECE</option>
                    <option value="114">MECH</option>
                    <option value="205">IT</option>

                </select>
                <input type="submit" value="Generate" name="report" id="report" class="report btn btn-outline-success" />

            </form>

            </div>
        
        <div class="form-container" >
            <form class="d-flex" action="reportgensem.php" method="POST">
            <select name="deptcode" class="form-control">

                    <option value="0">--Select dept--</option>
                    <option value="103">CIVIL</option>
                    <option value="104">CSE</option>
                    <option value="105">EEE</option>
                    <option value="106">ECE</option>
                    <option value="114">MECH</option>
                    <option value="205">IT</option>

            </select>
            <select name="sem" class="form-control">

                    <option value="0">--Select Semester--</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">6</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>

            </select>
            <input type="submit" value="Generate" name="reportsem" id="reportsem" class="report btn btn-outline-success" />
            </form>
        </div>
       
        <div class="form-container">
            <form action="reportgenall.php" method="POST">
                <input type="submit" value="Generate Report For All Dept" name="report_all" id="report_all" class="reportall btn btn-outline-warning" />
            </form>
        </div>
        
</div>


</div>
</div>
</body>
</html>