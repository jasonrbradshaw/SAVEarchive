<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$email = $username = $password = $confirm_password = "";
$email_err = $username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate email
    if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format";
    }

    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a valid email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $email);
            
            // Set parameters
            $email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already in our database.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
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
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($email_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err)){


        // Prepare an insert statement
        $sql = "INSERT INTO users (email, username, password, token) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $email, $param_username, $param_password, $token);
            
            // Set parameters
            $email = $email;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            // Create unique token
            $token = bin2hex(openssl_random_pseudo_bytes(16));
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<head>
	<title>Create Account</title>
	<link rel="stylesheet" href="style.css">
	<link rel="icon" href="tps_favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .information {
          text-align: center;
          line-height: 50px;
        }

        h1, h2, h3, p, a, form, input{
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
    </style>
</head>
<body>

<div class="navigation">
	<img src="TPS_logo.png" class="logo">
</div> 

<div class="top">
  <h1>Toronto Prep School Online Admission</h1>
</div>

    <div class="container login-box">
    <h2>Sign Up</h2>
    <p>Please fill this form to create an account.</p>
            <form class="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <!-- Email, should this be validated with type="email"?? -->
                    <input type="text" name="email" class="form-control" placeholder="EMAIL" value="<?php echo $email; ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>    
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <!-- Username -->
                    <input type="text" name="username" class="form-control" placeholder="USERNAME" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>    
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <!-- Password -->
                    <input type="password" name="password" class="form-control" placeholder="PASSWORD" value="<?php echo $password; ?>">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <!-- Confirm Password -->
                    <input type="password" name="confirm_password" class="form-control" placeholder="CONFIRM PASSWORD" value="<?php echo $confirm_password; ?>">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div><br>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary login-button" value="Submit">
                    <input type="reset" class="btn btn-default login-button" value="Reset">
                </div>
                <p>Already have an account? <a href="login.php">Login here</a>.</p>
            </form>
    </div>

    </div>
        <footer class="footer"><p class="information">2020 &#169 MSGBoard.org</p></footer>
    </div>
</body>
</html>
