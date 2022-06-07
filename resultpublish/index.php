<?php 
      session_start();
      $_SESSION['user'] = 1;
      
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="assets/stylings/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
<header class="bg-dark text-light header">
        <img src="assets/images/logo.png" alt="vcet_logo" class="logo" />
        <h2>VELAMMAL COLLEGE OF ENGINEERING AND TECHNOLOGY</h2>

    </header>
    <div class="container">

        <div class="form-container">

                <form action="studentinfo.php" method="POST" id="studentloginform">
                    <div class="login-box">
                    <h2 class="form-header">Student Login</h2>
                    <label for="registerno">Student Register Number</label>
                    <input type="text" name="registerno" id="registerno" class="form-control registerno">
                     <label>Date of Birth</label>
                     <input type="text" name="dob" id="dob" class="dob" placeholder="Eg. dd-mm-yyyy"/>
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
                    <input type="submit" value="Login" name="login" id="login" class="btn text-light login"/>
                    </div>
                </form>

        </div>

    </div>
</body>
</html>