<?php
// Initialize the session
session_start();

// Include config file
require_once "config.php";

// Validate token here
if(isset($_GET['token'])){
    $token = trim($_GET['token']);

    // Empty variables
    $id = "";
    $username = "";
    $user_type = "";
    $exists = "";

    // Grab user variables
    $query = "SELECT * FROM users WHERE token='$token'";
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_array($result)){
        $id = $row['id'];
        $username = $row['username'];
        $user_type = $row['user_type'];
        $exists = $row['token'];
    }
    
    // Verify token exists
    if($exists == NULL){
        header("location: login.php");
    } else {
        // Store data in session variables
        $_SESSION["loggedin"] = true;
        $_SESSION["id"] = $id;
        $_SESSION["username"] = $username;     
        $_SESSION["user_type"] = $user_type; 
    }

}

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 

 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have at least 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
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
    <h1>Reset Password</h1>
</div>
<div class="login-box">
        <p>Please fill out this form to reset your password.</p>
        <form class="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <input type="password" name="new_password" class="form-control" placeholder="New Password" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary login-button" value="Submit">
                <a class="btn btn-link" href="login.php">Cancel</a>
            </div>
        </form>
    </div>    
</div>

    </div>
        <footer class="footer"><p class="information">2020 &#169 MSGBoard.org</p></footer>
    </div>
</body>
</html>