<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>

    <link rel="stylesheet" href="assets/stylings/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <header>

        <nav class="navbar navbar-expand justify-content-around navbar-dark bg-dark">
        <a class="navbar-brand" style="margin-left:0px;">MARK ENTRY</a>
                    <ul class="navbar-nav">

                        <li class="nav-item rounded-3 bg-white"><a href="#" class="nav-link text-dark">Home</a></li>
                        <li class="nav-item"><a href="mark_entry.php" class="nav-link">Mark Entry</a></li>
                        <li class="nav-item"><a href="view_marks.php" class="nav-link">View</a></li>
                        <li class="nav-item"><a href="view_result.php" class="nav-link">View Result</a></li>
                        <li class="nav-item"><a href="export_details.php" class="nav-link">Export</a></li>
                        <li class="nav-item"><a href="edit_entries.php" class="nav-link">Edit</a></li>
                    </ul>
  
        </nav>

    </header>
    <div class="container bg-light  d-flex flex-column justify-content-center align-items-center">


        <div class="sub-container jumbotron p-3 border">
        <div class="form-container ">

                <form action="handleimportexcel.php" enctype="multipart/form-data" method="POST">
                    <label for="s_uploaded_file">Upload Student Information:</label>
                    <input type="file" name="s_uploaded_file" id="s_uploaded_file" class="uploaded_file form-control" required  />
                    <input type="submit" value="Upload" id="s_data_upload" class="upload btn btn-outline-success" name="s_data_upload"  />
                </form>

        </div>
        <div class="form-container">

                <form action="handleimportexcel.php" method="POST" enctype="multipart/form-data">

                    <label for="excel-file">Upload Student Exam Details:</label>
                    <input type="file" name="excel-file" id="excel-file" class="excel-file form-control" required >
                    <input type="submit" value="Upload" name="upload" id="upload" class="upload btn btn-outline-success" />
                
                </form>

        </div>
        </div>
       

    </div>
</body>
</html>