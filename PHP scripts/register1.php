<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $address = $salary632 = $username = $password ="";
$name_err = $address_err = $salary632_err = $username_err =$password_err ="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
    // Validate salary
    $input_salary632 = trim($_POST["salary632"]);
    if(empty($input_salary632)){
        $salary632_err = "Please enter the salary amount.";     
    } elseif(!ctype_digit($input_salary632)){
        $salary632_err = "Please enter a positive integer value.";
    } else{
        $salary632 = $input_salary632;
    }

    // Validate username
if(empty(trim($_POST["username"]))){
    $username_err = "Please enter a username.";
} elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
    $username_err = "Username can only contain letters, numbers, and underscores.";
} else{
    // Prepare a select statement
    $sql = "SELECT id FROM employees WHERE username = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        
        // Set parameters
        $param_username = trim($_POST["username"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);
            
            if(mysqli_stmt_num_rows($stmt) == 1){
                $username_err = "This username is already taken.";
            } else{
                $username = trim($_POST["username"]);
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Validate confirm password
if(empty(trim($_POST["password"]))){
    $password_err = "Please confirm password.";     
} else{
    $password = trim($_POST["password"]);
    if(empty($password_err) && ($password != $password)){
        $password_err = "Password did not match.";
    }
}
 
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($salary632_err)&& empty($username_err)&& empty($password_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO employees (name, address, salary632, username, password) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssiss", $param_name, $param_address, $param_salary632, $param_username, $param_password);
            
            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_salary632 = $salary632;
            $param_username = $username;
            $param_password = $password;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport"    content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<title>Initio - Free, multipurpose html5 template</title>
	<link rel="shortcut icon" href="assets/images/gt_favicon.png">
	
	<!-- Bootstrap -->
	<link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.no-icons.min.css" rel="stylesheet">
	<!-- Icons -->
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<!-- Fonts -->
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Alice|Open+Sans:400,300,700">
	<!-- Custom styles -->
	<link rel="stylesheet" href="assets/css/styles.css">

	<!--[if lt IE 9]> <script src="assets/js/html5shiv.js"></script> <![endif]-->
</head>
<body class="home">

<header id="header">
	<div id="head" class="parallax" parallax-speed="2">
		<h1 id="logo" class="text-center">
			<img class="img-circle" src="images/logo.png" alt="">
			<span class="title">Welcome to Luca's Loaves</span>
	
		</h1>
	</div>

	<nav class="navbar navbar-default navbar-sticky">
		<div class="container-fluid">
			
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
			</div>
			
			<div class="navbar-collapse collapse">
				
				<ul class="nav navbar-nav">
                <li class="active"><a href="index.html">Home</a></li>
					<li><a href="about.html">About</a></li>
					<li><a href="upload.html">Careers </a></li>
					<li><a href="orderonline.php">Order online</a></li>
					<li><a href="contactus.html">Contact Us</a></li>
					<li class="active"><a href="register1.php">Register</a></li>
				</ul>
			
			</div><!--/.nav-collapse -->			
		</div>	
	</nav>
</header>

<h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
					<form  class="centered"action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"id="form">
                        <div class="form-group">
                            <label>Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="text" style="width:300px;height:30px"name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span></label>
                        </div>
                        <div class="form-group">
                            <label>Address:&nbsp;&nbsp;&nbsp;
                            <textarea name="address"style="width:300px;height:30px" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err;?></span></label>
                        </div>
                        <div class="form-group">
                            <label>Salary:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="text" style="width:300px;height:30px"name="salary632" class="form-control <?php echo (!empty($salary632_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salary632; ?>">
                            <span class="invalid-feedback"><?php echo $salary632_err;?></span></label>
                        </div>
                        <div class="form-group">
                            <label>Username:
                            <input type="text" style="width:300px;height:30px"name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err;?></span></label>
                        </div>
                        <div class="form-group">
                            <label>Password:
                            <input type="text" style="width:300px;height:30px"name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                            <span class="invalid-feedback"><?php echo $password_err;?></span></label>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit"style="width:100px;height:60px">
                        
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</form>


</main>

<footer id="footer">
	<div class="container">
		<div class="row">
			<div class="col-md-3 widget">
				<h3 class="widget-title">Contact</h3>
				<div class="widget-body">
		
						<a href="mailto:#">15565156@qq.com</a><br>
						<br>
						English name:Lay
					</p>	
					<p>
						<br>
						Chinese name:LiangAnyuan
					</p>	
				</div>
			</div>

			<div class="col-md-3 widget">
				<h3 class="widget-title">Follow me</h3>
				<div class="widget-body">
					<p class="follow-me-icons">
						<a href=""><i class="fa fa-twitter fa-2"></i></a>
						<a href=""><i class="fa fa-dribbble fa-2"></i></a>
						<a href=""><i class="fa fa-github fa-2"></i></a>
						<a href=""><i class="fa fa-facebook fa-2"></i></a>
					</p>
				</div>
			</div>





		</div> <!-- /row of widgets -->
	</div>
</footer>

<footer id="underfooter">
	<div class="container">
		<div class="row">
			
			<div class="col-md-6 widget">
				<div class="widget-body">
					<p>Student ID :203190632</p>
				</div>
			</div>



		</div> <!-- /row of widgets -->
	</div>
</footer>



<!-- JavaScript libs are placed at the end of the document so the pages load faster -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="assets/js/template.js"></script>
</body>
</html>
