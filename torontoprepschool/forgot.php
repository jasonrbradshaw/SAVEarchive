<?php

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email = "";
$email_err = "";
$token = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){


    // Validate new password
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email";     
    } elseif(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format";
    } else{
        // Initiate empty array for email results
        $email_arr = array();
        // Grab entered email
        $email = trim($_POST["email"]);
        // Populate array
        $query = "SELECT email FROM users";
        $result = mysqli_query($conn, $query);
        while($row = mysqli_fetch_array($result)){
                $email_arr[] = $row['email'];
                
            }
    

        // in_array() checks if email is in database
        if (in_array($email, $email_arr, TRUE)){ 

            // Grab user token
            $query = "SELECT token FROM users WHERE email='$email'";
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_array($result)){
                $token = $row['token'];
            }
            
            // Write some code here to send email
            $to = $email;
            $subject = "Reset Password";

            $message = "
      <html>
            <head>
            <title>Forgot Password</title>
                <style>
                    .container {
                        width: 60%;
                        border: 4px solid #666666;
                        border-radius: 6px;
                        display: inline-block;
                        text-align: left;
                    }

                    .navigation {
                        width: 100%;
                        height: 65px;
                        position: relative;
                        top: 0;
                        left: 0;
                        background-color: red;
                        overflow: hidden;
                        padding-bottom: 10px;

                    }

                    .footer {
                        position: relative;
                        background-color: #666666;
                        width: 100%;
                        height: 120px;
                        bottom: 0;
                        left: 0;
                        padding-top: 10px;
                        color: white;
                        text-align: center
                    }

                    .logo { 
                        height: 75px;
                        width: auto;
                        position: relative;
                        left: 2%;
                        top: 4px;
                    }

                    @media screen and (max-width: 450px) {
                    .logo {
                      display: block;
                      margin-left: auto;
                      margin-right: auto;
                      width: auto;
                    }
                    }
                </style>
            </head>
            <body>
            <div style='text-align:center;background-color: #efefef'>
                <div class='container'>
                    <div class='navigation'>
                        <img src='https://msgboard.org/TPS/TPS_logo.png' class='logo'>
                    </div>
                        <div style='text-align: left; padding: 10px; background-color: white'>
                            <p>Hello,</p>
                            <p>You are receiving this message because a request has been made to retrieve a forgotten password for the Toronto Prep School (TPS) online application. If you did not make this request, please alert TPS Admin by responding to this email. Thank you.</p>
                            <p> Click <a href='https://msgboard.org/TPS/reset.php?token=$token'>here</a> to reset your password.</p><br>
                        </div>

                        <div class='footer'>
                            
                            <p>250 Davisville Avenue, Suite 200<br>

                            Toronto, Ontario M4S 1H2<br>

                            phone (416) 545-1020<br>

                            fax (416) 545-1456</p>
                            <p style='font-size: 12px'>2020 © <a href='https://torontoprepschool.com/'>Toronto Prep School</a></p>

                        </div>
                </div>
            </div>
            </body>
            </html>
            ";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: Toronto Prep School Online Admissions<webmaster@torontoprepschool.com>' . "\r\n";
            // $headers .= 'Cc: myboss@example.com' . "\r\n";

            mail($to,$subject,$message,$headers);

            $email_err = "Email has been sent successfully.";
        }else{ 
            $email_err = "Email does not exist in our database.";
        } 
    
    }

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
    <h1>Forgot Password</h1>
</div>
<div class="login-box">
        <p>Please enter your email to receive a link to reset your password.</p>
        <form class="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <input type="text" name="email" class="form-control" placeholder="EMAIL" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary login-button" value="Submit">
            </div>
            <br><a href="login.php">Return to login</a>
        </form>
    </div>    
</div>

    </div>
        <footer class="footer"><p class="information">2020 &#169 MSGBoard.org</p></footer>
    </div>
</body>
</html>