<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Result Analysis</title>
    <link rel="stylesheet" href="assets/stylings/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <div class="container">

        <div class="form-container">

                
            <form action="generatepdf.php" method="POST">
            <!-- $dept_codes = array(103 => 'civil',104 => 'cse',105 => 'eee',106 => 'ece', 114 => 'mech',205 => 'it'); -->
                        <label for="dept">Department</label>
                        <select name="dept" id="dept" class="form-control dept">
                            <option value="0" selected>--select departemnt--</option>
                            <option value="103">Civil Engineering</option>
                            <option value="104">Computer Science And Engineering</option>
                            <option value="105">Electrical And Electronics Engineering</option>
                            <option value="106">Electronics And Communication Engineering</option>
                            <option value="114">Mechanical Engineering</option>
                            <option value="205">Information Technology</option>
                        </select>
                        <label for="year">Year Of Examination</label>
                        <input type="number" min="2000"  required name="year" id="year" class="year form-control" />
                        
                        <label for="semester">Semester</label>
                        <select name="semester" id="semester" class="form-control semester">
                            <option value="0" selected >--select semester--</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                        <input type="submit" value="Print" name="print" class="btn btn-outline-primary" />

            </form>

        </div>

    </div>
    <script>
        var year_field = document.querySelectorAll('.year');
        year_field[0].max = new Date().getFullYear();
        year_field[0].value = new Date().getFullYear();
       
    </script>
    
</body>
</html>