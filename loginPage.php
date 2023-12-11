

<?php

    session_start();
    $error = ''; // IF LOGIN.PHP RETURNS A LOGIN ERROR, THIS WILL DISPLAY A MESSAGE

    if (isset($_POST['submit1'])) {
        // GETTING USERNAME AND PASSWORD
        $username = $_POST['username'];
        $password = $_POST['password'];

        // DATABASE LOGIN INFORMATION
        $connection = mysqli_connect("localhost", "ayanamala1", "ayanamala1", "ayanamala1");

        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        $username = htmlspecialchars($username);
        $password = htmlspecialchars($password);

        $result = mysqli_query($connection, "SELECT * FROM user WHERE BINARY uname = '$username'AND pword = md5('$password')");

        if (!$result) {
            print "Error - the query could not be executed";
            exit;
        }

        // INSERT LOGGING SESSION INFORMATION
        $num_rows = mysqli_num_rows($result);

        if ($result && $num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['login_MEMBERID'] = $row['MemberID'];
            $_SESSION['login_FIRSTNAME'] = $row['fname'];
            $_SESSION['login_LASTNAME'] = $row['lname'];
            $_SESSION['login_USERTYPE'] = $row['usertype'];

            header("Location: index.php"); // Redirect to the homepage or another page upon successful login
            exit();
        } 
        if(isset($_POST['username']) || isset($_POST['password'])){
        if (!$result || $num_rows == 0) {
           $error = "Invalid Username or Password";
          }
        }

        mysqli_close($connection);
    }
	?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Home - RentHub</title>
    <link href="css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="css/stylesheet.css" type="text/css" rel="stylesheet">
    <link rel="shortcut icon" href=" newlogo.ico">
    <style>
	body{
            background-color: #f5f5f5;
            background-image: url('image1.jpg');
            background-repeat: no-repeat;
            background-size: cover; 
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .navbar-default {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar {
            background-color: #fff; /* Adjust the background color of the navbar */
        }

        .login-form {
            max-width: 1000px; /* Adjust the max-width to increase or decrease the size */
            background-color: #eac2c2;
            margin-top: 10px;
            padding: 150px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                background-color: rgba(255, 255, 255, 0.8);
            margin-top: 10px;
        }

        .login-form .form-group {
            margin-bottom: 15px;
        }

        .login-form label {
            color: #333333;
            font-size: 16px;
            font-weight: bold;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .login-form input[type="submit"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #4caf50;
            color: #ffffff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .login-form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .login-form .text-danger {
            color: #ff0000;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="navbar-default">
        <div class="container">
            <div class="navbar-header nabar-left">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-bar-links">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">
                    <img src="logo.png" width="50" style="margin-top: -10px">
                </a>
                <div class="navbar-heading ml-2" style="padding: 10px; font-weight: bold; margin-top : -20px; margin-left:350px;"><h2>AMD HOME VISIONARIES</h2></div>
            
            </div>

            <div class="collapse navbar-collapse" id="nav-bar-links">
                <?php
				print '
						<form class="navbar-form navbar-right" style="border: none" onSubmit="return Validate()" method="post" action="">
							<div class="form-group">
								<input type="submit" class="btn btn-default" value="Sign In" name="submit1">
							</div>
						</form>
					';
					print '
					<form class="navbar-form navbar-right" style="border: none" onSubmit="return Validate()" method="post" action="https://codd.cs.gsu.edu/~ayanamala1/WP/PW/4/registration/index.php">
				 <div class="form-group" style="border: none;">
					<input type="submit" class="btn btn-default" value="Register" name="submit1">
				</div>
				</form>
				';
                ?>
            </div>
        </div>
    </div>

    <div class="login-form">
        <div class="form-heading"><b><h2>LOGIN FORM</h2></b></div>
        <br>


        <form class="form-horizontal" onSubmit="return Validate()" method="post" action="">
            <div class="form-group">
                <label for="username">User Name:</label>
                <input type="text" class="form-control" id="username" name="username" maxlength="20">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" maxlength="20">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Sign In" name="submit1">
            </div>
           <?php if (!empty($error)) { ?>
  <div class="alert alert-danger"><?php echo $error; ?></div>
<?php } ?>

        </form>
    </div>

    <script src="script/validateform.js"></script>
    <script src="script/jquery-1.11.2.min.js"></script>
    <script src="script/bootstrap.min.js"></script>
</body>
</html>
