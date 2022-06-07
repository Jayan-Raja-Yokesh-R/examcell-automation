<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data</title>
    <link rel="stylesheet" href="assets/stylings/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/export_details.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            margin: 10px;
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
               <li class="nav-item"><a href="view_result.php" class="nav-link">View Result</a></li>
               <li class="nav-item rounded-3 bg-white"><a href="#" class="nav-link text-dark">Export</a></li>
               <li class="nav-item"><a href="edit_entries.php" class="nav-link">Edit</a></li>
           
            </ul>

    </nav>
    <div class="container">
        <div class="form-container">
            <h4>Export Exam Data:</h4>
            <form action="handleexportexcel.php" method="POST">
                
                <input type="submit" value="Export Data(Without exam timings)" class="export btn btn-outline-success" name="export" />
                <input type="submit" value="Export Data(With exam timings)" class="export btn btn-outline-primary" name="export_all" />
                <input type="submit" value="Export Student Details" class="export btn btn-outline-primary" name="export_student" />

            </form>
            <h4>Department wise export:</h4>
            <form action="handleexportexcel.php" method="POST">
                <label for="deptname">Department</label>
                <input type="text" name="deptname" id="deptname" class="txtbox form-control" placeholder="Enter the department name" />
                <label for="sem">Semester</label>
                <input type="text" name="sem" id="sem" class="txtbox form-control" placeholder="Enter semester" />
                <input type="submit" value="Export" class="export btn btn-outline-primary" name="export_deptwise" />
            </form>

        </div>
    </div>
</body>
</html>