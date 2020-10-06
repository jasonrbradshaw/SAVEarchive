<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
}

if(isset($_POST['submit'])) {
require_once "config.php";

	// Referral Variables
	$teacher = 'teacher';
	$community = 'community';
	$teachFirst = mysqli_real_escape_string($conn, $_POST['teachFirst']);
	$teachLast = mysqli_real_escape_string($conn, $_POST['teachLast']);
	$teachEmail = mysqli_real_escape_string($conn, $_POST['teachEmail']);
	$commFirst = mysqli_real_escape_string($conn, $_POST['commFirst']);
	$commLast = mysqli_real_escape_string($conn, $_POST['commLast']);
	$commEmail = mysqli_real_escape_string($conn, $_POST['commEmail']);

	
	// User Id acts as foreign key for all tables
	$user_id = $_SESSION["id"];

	// Prepare an insert statement for teacher referral
    $sql = "INSERT INTO referral (id, ref_type, first_name, last_name, email) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE id=VALUES(id), ref_type=VALUES(ref_type), first_name=VALUES(first_name), last_name=VALUES(last_name), email=VALUES(email)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $user_id, $teacher, $teachFirst, $teachLast, $teachEmail);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: confirmation.php");
            } else{
            	// Populate user error message here when done
                die('Could not enter data: ' . mysqli_error($conn));
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
        }


    // Prepare an insert statement for community referral
    $sql = "INSERT INTO referral (id, ref_type, first_name, last_name, email) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE id=VALUES(id), ref_type=VALUES(ref_type), first_name=VALUES(first_name), last_name=VALUES(last_name), email=VALUES(email)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $user_id, $community, $commFirst, $commLast, $commEmail);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: confirmation.php");
            } else{
            	// Populate user error message here when done
                die('Could not enter data: ' . mysqli_error($conn));
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
        }


    // Send emails 
    $token = "";

    // Grab user token
            $query = "SELECT token FROM users WHERE id=$user_id";
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_array($result)){
                $token = $row['token'];
            }

    // Grab user name
    $can_first = "";
    $can_last = "";

    // Sanitize referral information
    $teachFirst = htmlspecialchars($teachFirst);
    $teachLast = htmlspecialchars($teachLast);
    $teachEmail = htmlspecialchars($teachEmail);
    $commFirst = htmlspecialchars($commFirst);
    $commLast = htmlspecialchars($commLast);
    $commEmail = htmlspecialchars($commEmail);

            $query = "SELECT can_name, can_surname FROM application WHERE id=$user_id";
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_array($result)){
                $can_first = htmlspecialchars($row['can_name']);
                $can_last = htmlspecialchars($row['can_surname']);
            }

 
            // Teacher Referral
            $to = $teachEmail;
            $subject = "Teacher Referral";

            $message = "
            <html>
            <head>
            <title>Teacher Referral</title>
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
                            <p>Hello $teachFirst $teachLast,</p>
                            <p>You are receiving this message because a request has been made by $can_first $can_last for you to fill an online referral for Toronto Prep School (TPS). If you have any questions please contact Toronto Prep School Head of Admissions, <a href='mailto:ftsimikalis@torontoprepschool.com'>Fouli Tsimikalis</a>.</p>
                            <p>If you did not make this request, please alert TPS Admin by responding to this email. Thank you for your time.</p>
                            <p> Click <a href='https://msgboard.org/TPS/teacher-referral.php?token=$token'>here</a> to start the application.</p><br>
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


            // Community Referral
            $to = $commEmail;
            $subject = "Community Referral";

            $message = "
              <html>
            <head>
            <title>Teacher Referral</title>
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
            <div style='text-align:center; background-color: #efefef'>
                <div class='container'>
                    <div class='navigation'>
                        <img src='https://msgboard.org/TPS/TPS_logo.png' class='logo'>
                    </div>
                        <div style='text-align: left; padding: 10px; background-color: white'>
                            <p>Hello $commFirst $commLast,</p>
                            <p>You are receiving this message because a request has been made by $can_first $can_last for you to fill an online referral for Toronto Prep School (TPS). If you have any questions please contact Toronto Prep School Head of Admissions, <a href='mailto:ftsimikalis@torontoprepschool.com'>Fouli Tsimikalis</a>.</p>
                            <p>If you did not make this request, please alert TPS Admin by responding to this email. Thank you for your time.</p>
                            <p> Click <a href='https://msgboard.org/TPS/community-referral.php?token=$token'>here</a> to start the application.</p><br>
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

    // Make document directory if it does not already exist
    if (!file_exists('files/' . $_SESSION["username"] . '/additional_documents/')) {
  		mkdir('files/' . $_SESSION["username"] . '/additional_documents/', 0777, TRUE);
  		}


	$docs = array_filter($_FILES['addDocuments']['name']); //something like that to be used before processing files.

	foreach($docs as $doc){
		// Prepare an insert statement for additional docs
	    $sql = "INSERT IGNORE INTO additional (id, additional_name) VALUES (?, ?)";
	         
	        if($stmt = mysqli_prepare($conn, $sql)){
	            // Bind variables to the prepared statement as parameters
	            mysqli_stmt_bind_param($stmt, "ss", $user_id, $doc);
	            
	            // Attempt to execute the prepared statement
	            if(mysqli_stmt_execute($stmt)){
	                // Redirect to login page
	                header("location: confirmation.php");
	            } else{
	            	// Populate user error message here when done
	                die('Could not enter data: ' . mysqli_error($conn));
	            }
	            
	            // Close statement
	            mysqli_stmt_close($stmt);
	        }
	    }

	// Count # of uploaded files in array
	$total = count($_FILES['addDocuments']['name']);

	// Loop through each file
	for( $i=0 ; $i < $total ; $i++ ) {

	  //Get the temp file path
	  $tmpFilePath = $_FILES['addDocuments']['tmp_name'][$i];

	  //Make sure we have a file path
	  if ($tmpFilePath != ""){
	    //Setup our new file path
	    $newFilePath = "files/" . $_SESSION["username"] . '/additional_documents/' . $_FILES['addDocuments']['name'][$i];

	    //Upload the file into the temp dir
	    if(move_uploaded_file($tmpFilePath, $newFilePath)) {

	    	// Redirect to next page
        	header("location: confirmation.php");

	    }
	  }
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Referrals</title>
	<link rel="stylesheet" href="style.css">
	<link rel="icon" href="tps_favicon.png">
	<script src="scripts/jquery.min.js"></script>
	<script src="scripts/max_char.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
        .information {
          text-align: center;
          line-height: 50px;
        }

        /* Full-width inputs */
		input[type=text], input[type=password], input[type=email] {
		  width: 100%;
		  padding: 12px 20px;
		  margin: 8px 0;
		  display: inline-block;
		  border: 1px solid #ccc;
		  box-sizing: border-box;
		}

        #searchingimageDiv {
          width: 100%;
          height: 100%;
          top: 0;
          left: 0;
          position: fixed;
          display: block;
          opacity: 0.7;
          background-color: #666b6c;
          z-index: 99;
          text-align: center;
        }

        #searchingimage1 {
          position: absolute;
          top: 50%;
          left: 50%;
          width: 250px;
          height: 250px;
          margin-top: -125px; /* Half the height */
          margin-left: -125px; /* Half the width */
          z-index: 100;
        }

        .navbox {
          display: inline-block;
          border-radius: 0px 0px 10px 10px;
          background-color: #ffcccb;
          color: white;
        }

        .topnav a {
          float: left;
          color: #f2f2f2;
          text-align: center;
          padding: 14px 16px;
          text-decoration: none;
          font-size: 17px;
          border-radius: 0px 0px 10px 10px;
        }

        h1, h2, h3, p, a, form, input{
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

    </style>
    <script>
    function showHide(){ 
    //create an object reference to the div containing images 
    var oimageDiv=document.getElementById('searchingimageDiv') 
    //set display to inline if currently none, otherwise to none 
    oimageDiv.style.display=(oimageDiv.style.display=='none')?'inline':'none' 
    } 
    </script>
    </style>
</head>
<body>

    <!-- Loader bar on click submit -->
<div id="searchingimageDiv" style="display:none"> 
    <img id="searchingimage1" src="loadnew.gif" alt="" /> 
</div>


<div class="navigation">
	<img src="TPS_logo.png" class="logo">
	<div class="greeting">
		<p>Hello, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></p>
	    <p>
	        <a href="reset.php" class="btn btn-warning">Reset Your Password</a><br>
	        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
	    </p>
	</div>
</div>

<div class="topnav">
	<div class="navbox">
	  <a href="instructions.php">Instructions</a>
	</div>
	<div class="navbox">
	  <a href="application.php">Application</a>
	</div>
	<div class="navbox">
	  <a href="parent-statement.php">Parent Statement</a>
	</div>
	<div class="navbox">
	  <a href="candidate-statement.php">Candidate Statement</a>
	</div>
	<div class="navbox">
	  <a class="active" href="referral.php">Referrals</a>
	</div>
	<div class="navbox">
	  <a href="confirmation.php">Confirmation</a>
	</div>
</div>

<div class="top">
	<h1>Referrals</h1>
</div>

<div class="body">
		<div class="container">
			<form method="POST" action="<?php $_PHP_SELF ?>" enctype="multipart/form-data">
			<div class="container">
		<p class="center"> Your referral's will be emailed a link to complete an online form from the browser. Ensure that you enter their information correctly in the spaces provided. Please inform them that they have been sent a referral form and to check their junk mail inbox if they cannot find it.</p></br>

		<h2>Teacher Referral</h2>
		First Name <br>
 	 	<input type="text" name="teachFirst" 
 	 	<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM referral WHERE id=$user_id AND ref_type='teacher'";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['first_name']));
				echo '"';
			}
		?>
 	 	required><br><br>
	 	Last Name <br>
	  	<input type="text" name="teachLast" 
	  	<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM referral WHERE id=$user_id AND ref_type='teacher'";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['last_name']));
				echo '"';
			}
		?>
	  	required><br><br>
		Email <br>
	 	<input type="email" name="teachEmail" 
	 	<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM referral WHERE id=$user_id AND ref_type='teacher'";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['email']));
				echo '"';
			}
		?>
	 	required><br><br>

		<h2>Community Referral</h2>
		First Name <br>
 	 	<input type="text" name="commFirst" 
 	 	<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM referral WHERE id=$user_id AND ref_type='community'";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['first_name']));
				echo '"';
			}
		?>
 	 	required><br><br>
 	 	Last Name <br>
 	 	<input type="text" name="commLast" 
 	 	<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM referral WHERE id=$user_id AND ref_type='community'";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['last_name']));
				echo '"';
			}
		?>
 	 	required><br><br>
		Email <br>
 	 	<input type="email" name="commEmail" 
 	 	<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM referral WHERE id=$user_id AND ref_type='community'";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['email']));
				echo '"';
			}
		?>
 	 	required><br><br>

 	 	Upload any other referrals or documentation you feel will assist the Admissions Committee in making a more informed decision<br>
 	 	<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT * FROM additional WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo "✅" . htmlspecialchars($row['additional_name']) . " uploaded<br>";
			}
			echo "<br>";
		?>
  		<input type="file" id="documents" name="addDocuments[]" multiple><br><br>


		<button type="submit" name="submit" class="instr" onclick='showHide()'>Save & Continue</button>
	</div>
	</div>
	</form>
</div>

	</div>
        <footer class="footer"><p class="information">2020 &#169 MSGBoard.org</p></footer>
    </div>

</body>
</html>