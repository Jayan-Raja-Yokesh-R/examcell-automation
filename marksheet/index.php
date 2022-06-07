<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Grade Sheet</title>
    <link rel="stylesheet" href="assets/stylings/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            
                <h3>Single Generate</h3>
                <form action="generatepdf.php" method="POST" >

                
                        <label for="regno">Student Register Number</label>
                        <input type="text" required name="regno" id="regno" class="regno form-control" placeholder="Enter register number" />

                        <label for="year">Year Of Examination</label>
                        <input type="number" required min="2000"  name="year" id="year" class="year form-control" />
                        
                        <label for="semester">Semester</label>
                        <select name="semester" id="semester" class="form-control semester">
                            <option value="0" selected>--select semester--</option>
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

        <div class="form-container">
            <h3>Bulk Generate</h3>
            <form action="generatepdf.php" method="POST">

              
                        <label for="regnofrom">From</label>
                        <input type="text" required name="regnofrom" id="regnofrom" class="regno form-control" placeholder="Enter register number" />
                        <label for="regnoto">To</label>
                        <input type="text" required name="regnoto" id="regnoto" class="regno form-control" placeholder="Enter register number" />

                        <label for="year">Year Of Examination</label>
                        <input type="number" min="2000"  required name="year" id="year" class="year form-control" />
                        
                        <label for="semester">Semester</label>
                        <select name="semester" id="semester" class="form-control semester">
                            <option value="0" selected>--select semester--</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>

                       <input type="submit" value="Print" name="printall" class="btn btn-outline-danger" />
   


            </form>

        </div>
        
    </div>
    <script>

        var year_field = document.querySelectorAll('.year');
        year_field[0].max = new Date().getFullYear();
        year_field[0].value = new Date().getFullYear();
        year_field[1].max = new Date().getFullYear();
        year_field[1].value = new Date().getFullYear();
        
    </script>
</body>
</html>