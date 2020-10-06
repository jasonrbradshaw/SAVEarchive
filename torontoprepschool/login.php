<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["user_type"] == NULL){
    header("location: instructions.php");
    exit;
}

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["user_type"] == 'admin'){
    header("location: TPSadmin.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password, user_type FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $user_type);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;     
                            $_SESSION["user_type"] = $user_type;                      
                            
                            if($user_type == 'admin'){
                                // Redirect to admin page
                                header("location: TPSadmin.php");
                            }else{
                                // Redirect user to welcome page
                                header("location: instructions.php");
                            }

                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
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
<html>
<head>
	<title>TPS Online Admission</title>
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
    <h2>Login</h2>
            <p>Please fill in your credentials to login.</p>
            <form class="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <!-- Username -->
                    <input type="text" name="username" class="form-control" placeholder="USERNAME" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>    
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <!-- Password -->
                    <input type="password" name="password" placeholder="PASSWORD" class="form-control">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div><br>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary login-button" value="Login">
                </div>
                <p>Don't have an account? <a href="create.php">Sign up now</a>.</p>
                <p><a href="forgot.php">Forgot Password?</a></p>
            </form>
    </div>

    </div>
        <footer class="footer"><p class="information">2020 &#169 MSGBoard.org</p></footer>
    </div>

</body>
</html>
 
