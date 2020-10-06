<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
}

if(isset($_POST['submit'])) {
require_once "config.php";
	
	// Parent statement variables
	$par_first = mysqli_real_escape_string($conn,$_POST['parenfirst']);
	$par_surname = mysqli_real_escape_string($conn,$_POST['parenlast']);
	$par_la1 = mysqli_real_escape_string($conn,$_POST['pq1']);
	$par_la2 = mysqli_real_escape_string($conn,$_POST['pq2']);
	$par_la3 = mysqli_real_escape_string($conn,$_POST['pq3']);
	$par_la4 = mysqli_real_escape_string($conn,$_POST['pq4']);
	$par_la5 = mysqli_real_escape_string($conn,$_POST['pq5']);
	$asses_ans = mysqli_real_escape_string($conn,$_POST['assesment']);
	$asses_doc = $_FILES['assesment_file']['name'];
	$par_la6 = mysqli_real_escape_string($conn,$_POST['pq6']);
	$par_la7 = mysqli_real_escape_string($conn,$_POST['pq7']);
	$par_la8 = mysqli_real_escape_string($conn,$_POST['pq8']);
	$par_la9 = mysqli_real_escape_string($conn,$_POST['pq9']);
	

	// User Id acts as foreign key for all tables
	$user_id = $_SESSION["id"];


	// Prepare an insert statement for parentstmt table
    $sql = "INSERT INTO parentstmt (id, par_first, par_surname, par_la1, par_la2, par_la3, par_la4, par_la5, asses_ans, asses_doc, par_la6, par_la7, par_la8, par_la9) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE id=VALUES(id), par_first=VALUES(par_first), par_surname=VALUES(par_surname), par_la1=VALUES(par_la1), par_la2=VALUES(par_la2), par_la3=VALUES(par_la3), par_la4=VALUES(par_la4), par_la5=VALUES(par_la5), asses_ans=VALUES(asses_ans), par_la6=VALUES(par_la6), par_la7=VALUES(par_la7), par_la8=VALUES(par_la8), par_la9=VALUES(par_la9)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssssss", $user_id, $par_first, $par_surname, $par_la1, $par_la2, $par_la3, $par_la4, $par_la5, $asses_ans, $asses_doc, $par_la6, $par_la7, $par_la8, $par_la9);

            // Make document directory if it does not already exist
            if (!file_exists('files/' . $_SESSION["username"] . '/documents/')) {
  				mkdir('files/' . $_SESSION["username"] . '/documents/', 0777, TRUE);
  			}

            // Document file directory
  			$doc_target = "files/" . $_SESSION["username"] . '/documents/' . basename($asses_doc);


  			move_uploaded_file($_FILES['assesment_file']['tmp_name'], $doc_target);


  			// Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to next page
                header("location: candidate-statement.php");
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
	<title>Parent Statment</title>
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
	  <a class="active" href="parent-statement.php">Parent Statement</a>
	</div>
	<div class="navbox">
	  <a href="candidate-statement.php">Candidate Statement</a>
	</div>
	<div class="navbox">
	  <a href="referral.php">Referrals</a>
	</div>
	<div class="navbox">
	  <a href="confirmation.php">Confirmation</a>
	</div>
</div>

<div class="top">
	<h1>Parent Statement</h1>
</div>

<div class="body">

	<form method="POST" action="<?php $_PHP_SELF ?>" enctype="multipart/form-data">
		<div class="container">
		Name of person completing this form<br>
  		<input type="text" name="parenfirst" placeholder="First Name"
  		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM parentstmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['par_first']));
				echo '"';
			}
		?>
		required><br>
  		<input type="text" name="parenlast" placeholder="Last Name"
  		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM parentstmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['par_surname']));
				echo '"';
			}
		?>
		required><br><br>
  		
  		Briefly describe your child’s character and personality. <br>
 	 	<textarea id="pq1" name="pq1" rows="10" maxlength="2000" required><?php 	// Ensure php statement happens on these lines to avoid trailing and leading space
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM parentstmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['par_la1']));
			}
		?></textarea><br>
 	 	<div id="counter">Characters remaining (2000)</div><br>

 	 	What qualities do you admire in your child? <br>
 	 	<textarea id="pq2" name="pq2" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM parentstmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['par_la2'])) ;
			}
		?></textarea><br>
 	 	<div id="counter2">Characters remaining (2000)</div><br>

 	 	What do you consider to be your child’s greatest area of need, socially and/or academically? <br>
 	 	<textarea id="pq3" name="pq3" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM parentstmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['par_la3'])) ;
			}
		?></textarea><br>
 	 	<div id="counter3">Characters remaining (2000)</div><br>

 	 	Is there anything we should know about the history of your child’s education? Has your child ever skipped or repeated a grade? <br>
 	 	<textarea id="pq4" name="pq4" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM parentstmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['par_la4'])) ;
			}
		?></textarea><br>
 	 	<div id="counter4">Characters remaining (2000)</div><br>

 	 	Has your child ever been asked to withdraw from school, been suspended, put on probation, or missed school for an extended period of time? Please explain. <br>
 	 	<textarea id="pq5" name="pq5" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM parentstmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['par_la5'])) ;
			}
		?></textarea><br>
 	 	<div id="counter5">Characters remaining (2000)</div><br>

 	 	Does your child have a psychological or educational assessment? <br>
  		<input type="radio" id="yes" name="assesment" value="yes" 
  		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT * FROM parentstmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['asses_ans'] == 'yes'){
					echo "checked";
				}
			}
		?>
  		required>
		<label for="yes">Yes</label><br>
		<input type="radio" id="no" name="assesment" value="no"
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT * FROM parentstmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['asses_ans'] == 'no'){
					echo "checked";
				}
			}
		?>
		>
		<label for="no">No</label><br><br>
  		If yes, please upload a PDF copy of the assessment
  		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT * FROM parentstmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo "<br>✅" . htmlspecialchars($row['asses_doc']) . " uploaded<br><br>";
			}
		?>
  		<input type="file" name="assesment_file" accept="pdf"><br><br>

  		Is your child currently seeing a counsellor, psychologist and/or psychiatrist? If yes, explain. <br>
 	 	<textarea id="pq6" name="pq6" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM parentstmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['par_la6'])) ;
			}
		?></textarea><br>
 	 	<div id="counter6">Characters remaining (2000)</div><br>

 	 	Presently, does your child have any physical or mental restrictions affecting his or her ability to participate in school sports or other activities? If yes, please describe the nature of the restriction and any reasonable accommodation you feel may be necessary for your child’s participation. <br>
 	 	<textarea id="pq7" name="pq7" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM parentstmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['par_la7'])) ;
			}
		?></textarea><br>
 	 	<div id="counter7">Characters remaining (2000)</div><br>

 	 	Are there any family circumstances that might affect your child’s performance at school? If yes, explain. <br>
 	 	<textarea id="pq8" name="pq8" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM parentstmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['par_la8'])) ;
			}
		?></textarea><br>
 	 	<div id="counter8">Characters remaining (2000)</div><br>

 	 	How did you find out about Toronto Prep School? <br>
 	 	<textarea id="pq9" name="pq9" rows="10" maxlength="2000" required><?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT * FROM parentstmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo stripcslashes(htmlspecialchars($row['par_la9'])) ;
			}
		?></textarea><br>
 	 	<div id="counter9">Characters remaining (2000)</div><br>
 	 	
 	 	<button type="submit" name="submit" class="instr" onclick='showHide()'>Save & Continue</button>
  		</div>
	</form>
</div>

	</div>
        <footer class="footer"><p class="information">2020 &#169 MSGBoard.org</p></footer>
    </div>

</body>
</html>
