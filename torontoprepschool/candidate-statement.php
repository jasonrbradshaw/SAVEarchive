<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
}

if(isset($_POST['submit'])) {
require_once "config.php";
	
	// Candidate statement variables
	$sq1 = mysqli_real_escape_string($conn,$_POST['sq1']);
	$sq2 = mysqli_real_escape_string($conn,$_POST['sq2']);
	$subject1 = mysqli_real_escape_string($conn,$_POST['subject1']);
	$subject2 = mysqli_real_escape_string($conn,$_POST['subject2']);
	$subject3 = mysqli_real_escape_string($conn,$_POST['subject3']);
	$sq3 = mysqli_real_escape_string($conn,$_POST['sq3']);
	$sq4 = mysqli_real_escape_string($conn,$_POST['sq4']);
	$sq5 = mysqli_real_escape_string($conn,$_POST['sq5']);
	$sq6 = mysqli_real_escape_string($conn,$_POST['sq6']);
	$sq7 = mysqli_real_escape_string($conn,$_POST['sq7']);
	$sq8 = mysqli_real_escape_string($conn,$_POST['sq8']);
	$sq9 = mysqli_real_escape_string($conn,$_POST['sq9']);
	$sq10 = mysqli_real_escape_string($conn,$_POST['sq10']);
	

	// User Id acts as foreign key for all tables
	$user_id = $_SESSION["id"];


	// Prepare an insert statement for parentstmt table
    $sql = "INSERT INTO candidatestmt (id, stu_la1, stu_la2, subj1, subj2, subj3, stu_la3, stu_la4, stu_la5, stu_la6, stu_la7, stu_la8, stu_la9, stu_la10) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE id=VALUES(id), stu_la1=VALUES(stu_la1), stu_la2=VALUES(stu_la2), subj1=VALUES(subj1), subj2=VALUES(subj2), subj3=VALUES(subj3), stu_la3=VALUES(stu_la3), stu_la4=VALUES(stu_la4), stu_la5=VALUES(stu_la5), stu_la6=VALUES(stu_la6), stu_la7=VALUES(stu_la7), stu_la8=VALUES(stu_la8), stu_la9=VALUES(stu_la9), stu_la10=VALUES(stu_la10)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssssss", $user_id, $sq1, $sq2, $subject1, $subject2, $subject3, $sq3, $sq4, $sq5, $sq6, $sq7, $sq8, $sq9, $sq10);
  			// Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: referral.php");
            } else{
            	// Populate user error message here when done
                die('Could not enter data: ' . mysqli_error($conn));
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
        }

mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Candidate Statment</title>
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
	  <a class="active" href="candidate-statement.php">Candidate Statement</a>
	</div>
	<div class="navbox">
	  <a href="referral.php">Referrals</a>
	</div>
	<div class="navbox">
	  <a href="confirmation.php">Confirmation</a>
	</div>
</div>

<div class="top">
	<h1>Candidate Statement</h1>
</div>

<div class="body">
	<form method="POST" action="<?php $_PHP_SELF ?>" enctype="multipart/form-data">
		<div class="container">

		The traits I like most about me are . . .<br>
 	 	<textarea id="sq1" name="sq1" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM candidatestmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['stu_la1'])) ;
			}
		?></textarea><br>
 	 	<div id="scounter">Characters remaining (2000)</div><br>

 	 	The things I like most about school are . . . <br>
 	 	<textarea id="sq2" name="sq2" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM candidatestmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['stu_la2'])) ;
			}
		?></textarea><br>
 	 	<div id="scounter2">Characters remaining (2000)</div><br>

 	 	In order of preference, list the three subjects you are the most excited about studying at school. <br>
 	 	<input type="text" name="subject1" placeholder="Subject One"
 	 	<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM candidatestmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['subj1']));
				echo '"';
			}
		?>
		><br>
		<input type="text" name="subject2" placeholder="Subject Two"
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM candidatestmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['subj2']));
				echo '"';
			}
		?>
		><br>
		<input type="text" name="subject3" placeholder="Subject Three"
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM candidatestmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['subj3']));
				echo '"';
			}
		?>
		><br><br>

		List the extracurricular programs and any clubs or organizations you have been involved with most recently (sports, music, dance, community service etc.)  <br>
 	 	<textarea id="sq3" name="sq3" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM candidatestmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['stu_la3'])) ;
			}
		?></textarea><br>
 	 	<div id="scounter3">Characters remaining (2000)</div><br>

 	 	Describe your favourite memory and why it is special for you. <br>
 	 	<textarea id="sq4" name="sq4" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM candidatestmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['stu_la4']));
			}
		?></textarea><br>
 	 	<div id="scounter4">Characters remaining (2000)</div><br>

 	 	The things I do best are . . . <br>
 	 	<textarea id="sq5" name="sq5" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM candidatestmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['stu_la5']));
			}
		?></textarea><br>
 	 	<div id="scounter5">Characters remaining (2000)</div><br>

 	 	I have difficulty with . . .<br>
 	 	<textarea id="sq6" name="sq6" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM candidatestmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['stu_la6']));
			}
		?></textarea><br>
 	 	<div id="scounter6">Characters remaining (2000)</div><br>

 	 	Who do you respect the most in your life and why? <br>
 	 	<textarea id="sq7" name="sq7" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM candidatestmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['stu_la7']));
			}
		?></textarea><br>
 	 	<div id="scounter7">Characters remaining (2000)</div><br>

 	 	If you could visit any country in the world, where would you go and why? <br>
 	 	<textarea id="sq8" name="sq8" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM candidatestmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['stu_la8']));
			}
		?></textarea><br>
 	 	<div id="scounter8">Characters remaining (2000)</div><br>

 	 	Describe the personal traits needed to be a good friend. <br>
 	 	<textarea id="sq9" name="sq9" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM candidatestmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['stu_la9']));
			}
		?></textarea><br>
 	 	<div id="scounter9">Characters remaining (2000)</div><br>

 	 	What do you expect from Toronto Prep School? <br>
 	 	<textarea id="sq10" name="sq10" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM candidatestmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['stu_la10']));
			}
		?></textarea><br>
 	 	<div id="scounter10">Characters remaining (2000)</div><br>

		<button type="submit" name="submit" class="instr" onclick='showHide()'>Save & Continue</button>
	</div>
	</form>
</div>

	</div>
        <footer class="footer"><p class="information">2020 &#169 MSGBoard.org</p></footer>
    </div>

</body>
</html>