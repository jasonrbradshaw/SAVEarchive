<?php

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
}

if(isset($_POST['submit'])) {
require_once "config.php";
	
	// Application variables
	$can_surname = mysqli_real_escape_string($conn, $_POST['can_surname']);
	$can_name = mysqli_real_escape_string($conn, $_POST['can_name']);
	$can_prename = mysqli_real_escape_string($conn, $_POST['can_prename']);
	$can_address = mysqli_real_escape_string($conn, $_POST['can_address']);
	$can_homeph = mysqli_real_escape_string($conn, $_POST['can_homeph']);
	$can_cellph = mysqli_real_escape_string($conn, $_POST['can_cellph']);
	$can_birth = mysqli_real_escape_string($conn, $_POST['can_birth']);
	$can_gender = mysqli_real_escape_string($conn, $_POST['can_gender']);
	$can_image = $_FILES['can_image']['name'];
	$can_birthcert = $_FILES['birth_cert']['name'];
	$can_entry = mysqli_real_escape_string($conn, $_POST['can_entry']);
	$can_canres = mysqli_real_escape_string($conn, $_POST['can_canres']);
	$can_cit = mysqli_real_escape_string($conn, $_POST['can_cit']);
	$can_entrydate = mysqli_real_escape_string($conn, $_POST['can_entrydate']);
	$can_lang = mysqli_real_escape_string($conn, $_POST['can_lang']);
	$can_school = mysqli_real_escape_string($conn, $_POST['can_school']);

	// User Id acts as foreign key for all tables
	$user_id = $_SESSION["id"];


	// Prepare an insert statement for application table
    $sql = "INSERT INTO application (id, can_surname, can_name, can_prename, can_address, can_homeph, can_cellph, can_birth, can_gender, can_image, can_birthcert, can_entry, can_canres, can_cit, can_entrydate, can_lang, can_school) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE id=VALUES(id), can_surname=VALUES(can_surname), can_name=VALUES(can_name), can_prename=VALUES(can_prename), can_address=VALUES(can_address), can_homeph=VALUES(can_homeph), can_cellph=VALUES(can_cellph), can_birth=VALUES(can_birth), can_gender=VALUES(can_gender), can_entry=VALUES(can_entry), can_canres=VALUES(can_canres), can_school=VALUES(can_school)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssssssssssss", $user_id, $can_surname, $can_name, $can_prename, $can_address, $can_homeph, $can_cellph, $can_birth, $can_gender, $can_image, $can_birthcert, $can_entry, $can_canres, $can_cit, $can_entrydate, $can_lang, $can_school);


            // Create image directory for user if it does not exist
            if (!file_exists('files/' . $_SESSION["username"] . '/images/')) {
  				mkdir('files/' . $_SESSION["username"] . '/images/', 0777, TRUE);
  			}

            // image file directory
  			$image_target1 = "files/" . $_SESSION["username"] . '/images/' . basename($can_image);

  			// Move candidate photo to temp folder
            move_uploaded_file($_FILES['can_image']['tmp_name'], $image_target1);

  			// image file directory
  			$image_target2 = "files/" . $_SESSION["username"] . '/images/' . basename($can_birthcert);

            // Move candidate photo to temp folder
            move_uploaded_file($_FILES['birth_cert']['tmp_name'], $image_target2);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: parent-statement.php");
            } else{
            	// Populate user error message here when done
                die('Could not enter data: ' . mysqli_error($conn));
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
        }

     // Create document directory for user if it does not exist
            if (!file_exists('files/' . $_SESSION["username"] . '/documents/')) {
  				mkdir('files/' . $_SESSION["username"] . '/documents/', 0777, TRUE);
  			}

		$docs = array_filter($_FILES['addReports']['name']); //something like that to be used before processing files.

		foreach($docs as $doc){
			// Prepare an insert statement for additional docs
		    $sql = "INSERT IGNORE INTO reports (id, report_name) VALUES (?, ?)";
		         
		        if($stmt = mysqli_prepare($conn, $sql)){
		            // Bind variables to the prepared statement as parameters
		            mysqli_stmt_bind_param($stmt, "ss", $user_id, $doc);
		            
		           // Attempt to execute the prepared statement
	            if(mysqli_stmt_execute($stmt)){
	                // Redirect to login page
	                header("location: parent-statement.php");
	            } else{
	            	// Populate user error message here when done
	                die('Could not enter data: ' . mysqli_error($conn));
	            }
	            
	            // Close statement
	            mysqli_stmt_close($stmt);
		        }
		    }

		// Count # of uploaded files in array
		$total = count($_FILES['addReports']['name']);

		// Loop through each file
		for( $i=0 ; $i < $total ; $i++ ) {

		  //Get the temp file path
		  $tmpFilePath = $_FILES['addReports']['tmp_name'][$i];

		  //Make sure we have a file path
		  if ($tmpFilePath != ""){
		    //Setup our new file path
		    $newFilePath = "files/" . $_SESSION["username"] . '/documents/' . $_FILES['addReports']['name'][$i];

		    //Upload the file into the temp dir
		    if(move_uploaded_file($tmpFilePath, $newFilePath)) {

		    	// Redirect to next page
	        	header("location: parent-statement.php");

		    }
		  }
		}



    // Candidate resides with
    // Variable
        $resides_with = array('motherRes', 'fatherRes', 'stepfatherRes', 'stepmotherRes', 'gaurdianRes', 'otherRes');	

		  	foreach($resides_with as $resides){
		    $resides = mysqli_real_escape_string($conn, $_POST[$resides]);

		    if($resides == TRUE){
			    $query = "INSERT IGNORE INTO resides (id, resides_with) VALUES (?, ?)";

			   		if($query = mysqli_prepare($conn, $query)){
			            // Bind variables to the prepared statement as parameters
			            mysqli_stmt_bind_param($query, "ss", $user_id, $resides);

			        // Attempt to execute the prepared statement
			    	mysqli_stmt_execute($query);

			    	// Close statement
			        mysqli_stmt_close($query);
				    }
				}
			}


	// Correspondence sent to
    // Variable
        $sent_to = array('bothCor', 'motherCor', 'fatherCor', 'gaurdianCor', 'otherCor');	

		  	foreach($sent_to as $send){
		    $send = mysqli_real_escape_string($conn, $_POST[$send]);

		    if($send == TRUE){
			    $query = "INSERT IGNORE INTO corr (id, sent_to) VALUES (?, ?)";

			   		if($query = mysqli_prepare($conn, $query)){
			            // Bind variables to the prepared statement as parameters
			            mysqli_stmt_bind_param($query, "ss", $user_id, $send);

			        // Attempt to execute the prepared statement
			    	mysqli_stmt_execute($query);

			    	// Close statement
			        mysqli_stmt_close($query);
				    }
				}
			}

}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Application</title>
	<link rel="stylesheet" href="style.css">
	<link rel="icon" href="tps_favicon.png">
	<script src="scripts/jquery.min.js"></script>
	<script src="scripts/addField.js"></script>
	<script src="scripts/disabled.js"></script>
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
</head>
<body>

<!-- Loader bar on click submit -->
<div id="searchingimageDiv" style="display:none"> 
	<img id="searchingimage1" src="loadnew.gif" alt="" /> 
</div>

<div class="navigation">
	<img src="TPS_logo.png" class="logo" alt="Toronto Prep School Logo">
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
	  <a class="active" href="application.php">Application</a>
	</div>
	<div class="navbox">
	  <a href="parent-statement.php">Parent Statement</a>
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
	<h1>Application</h1>
</div>

<div class="body">
	<form method="POST" action="<?php $_PHP_SELF ?>" enctype="multipart/form-data">
		<div class="container">
		<label for="can_surname">Candidate's Surname<br></label>
  		<input type="text" name="can_surname" id="can_surname" 
  		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];

  		$query = "SELECT can_surname FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['can_surname']));
				echo '"';
			}
		?>
  		required><br><br>
  		
  		<label for="can_name">Candidate's Given name(s)<br></label>
 	 	<input type="text" name="can_name" id="can_name" 
 	 	<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT can_name FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['can_name']));
				echo '"';
			}
		?>
		required><br><br>

 	 	<label for="can_prename">Candidate's Preferred name<br></label>
 	 	<input type="text" name="can_prename" id="can_prename"
 	 	<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT can_prename FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['can_prename']));
				echo '"';
			}
		?>
		><br><br>

 	 	<label for="home_add">Home Address<br></label>
 	 	<input type="text" name="can_address" id="home_add"
 	 	<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT can_address FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['can_address']));
				echo '"';
			}
		?>
		required><br><br>

 	 	<label for="can_homeph">Home Telephone<br></label>
 	 	<input type="text" name="can_homeph" id="can_homeph"
 	 	<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT can_homeph FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['can_homeph']));
				echo '"';
			}
		?>
		><br><br>

 	 	<label for="can_cellph">Candidate's Cell Phone<br></label>
 	 	<input type="text" name="can_cellph" id="can_cellph"
 	 	<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT can_cellph FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['can_cellph']));
				echo '"';
			}
		?>
		><br><br>

 	 	<label for="can_birth">Candidate's Date of Birth<br></label>
 	 	<input type="date" name="can_birth" id="can_birth"
 	 	<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT can_birth FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['can_birth']));
				echo '"';
			}
		?>
		required><br><br>

		<fieldset>
 	 	<legend>Gender<br></legend>
 	 	<input type="radio" name="can_gender" value="male" id="can_gender"
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT can_gender FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['can_gender'] == 'male'){
					echo "checked";
				}
			}
		?>
 	 	required> <label for="can_gender">Male</label><br>
  		<input type="radio" name="can_gender" value="female" id="can_female"
  		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT can_gender FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['can_gender'] == 'female'){
					echo "checked";
				}
			}
		?>
		> <label for="can_female">Female<br></label>
  		<input type="radio" name="can_gender" value="other" id="can_other"
  		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT can_gender FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['can_gender'] == 'other'){
					echo "checked";
				}
			}
		?>
		> <label for="can_other">Other</label>
		</fieldset><br>

  		<label for="can_image">Upload a recent image of the candidate<br></label>
  		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT can_image FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo "✅" . htmlspecialchars($row['can_image']) . " uploaded<br><br>";
			}
		?>
  		<input type="file" name="can_image" id="can_image" accept="image/*"><br><br>
  	

  		<label for="birth_cert">Upload a copy of the candidate's birth certificate or passport<br></label>
  		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT can_birthcert FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo "✅" . htmlspecialchars($row['can_birthcert']) . " uploaded<br><br>";
			}
		?>
  		<input type="file" name="birth_cert" id="birth_cert" accept="image/*"><br><br>
  		
  		<!-- Left off fixing accessibility code HERE -->

  		<label for="reports">Upload copies of the candidate's final (June) report cards for the past two years plus the most recent report card<br></label>
  		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT report_name FROM reports WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo "✅" . htmlspecialchars($row['report_name']) . " uploaded<br>";
			}
			echo "<br>";
		?>
		<input type="file" id="reports" name="addReports[]" multiple><br><br>


  		Please add the candidate's current courses (including academic code if they are in grade's 9 through 12)
  		<div id='TextBoxesGroup4'>
			<div id="CorTextBoxDiv">
				<?php 
		  		require_once "config.php";
		  		$user_id = $_SESSION["id"];
		  		$query = "SELECT course_num FROM courses WHERE id=$user_id";
					$result = mysqli_query($conn, $query);
					while($row = mysqli_fetch_array($result)){
						$counterc = 1;
						echo '<input type="text" name="course_num' . $counterc .
          				'" id="courseNum' . $counterc . '" value="' . htmlspecialchars($row['course_num']) . '"<br>';
					}
					 $counterc++;
				?>
			</div>
		</div>
			<input type='button' value="Add Course" id="CoraddButton">
			<input type='button' value="Remove Course" id="CorremoveButton"><br><br>
			

		<?php
		// Needs to happen after the javascript is called
			if(isset($_POST['submit'])) {

			$course_num = array('course_num1', 'course_num2', 'course_num3', 'course_num4', 'course_num5', 'course_num6', 'course_num7', 'course_num8', 'course_num9', 'course_num10');	
		  	// Course and course number query
		  	foreach($course_num as $course){
		    $course = mysqli_real_escape_string($conn, $_POST[$course]);

		    if($course == TRUE){
			    $query = "INSERT IGNORE INTO courses (id, course_num) VALUES (?, ?)";

			   		if($query = mysqli_prepare($conn, $query)){
			            // Bind variables to the prepared statement as parameters
			            mysqli_stmt_bind_param($query, "ss", $user_id, $course);

			        // Attempt to execute the prepared statement
			    	mysqli_stmt_execute($query);

			    	// Close statement
			        mysqli_stmt_close($query);
				    }
				}
			}
		} ?>

		<br><br>

  		Applying for entry in<br>
  		<input type="month" id="entry" name="can_entry" min="2020-06" 
  		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT can_entry FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['can_entry']));
				echo '"';
			}
		?>
		required>

<!-- Horizontal rule line across page -->
<hr>
<!-- Insert code here to enable fields if checkbox is set to 'yes' -->

		Is the candidate a non-Canadian applicant? <br><br>
 	 	<input type="radio" name="can_canres" value="yes" required="required" 
 	 	<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT can_canres FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['can_canres'] == 'yes'){
					echo "checked";
				}
			}
		?>
 	 	onclick="enable(true)"> Yes<br>
  		<input type="radio" name="can_canres" value="no" 
  		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT can_canres FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['can_canres'] == 'no'){
					echo "checked";
				}
			}
		?>
  		onclick="enable(false)"> No<br><br>

		Citizenship<br>
		<input type="text" name="can_cit" id="citizenship" 
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT can_cit FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['can_cit']));
				echo '"';
			}
		?>
		disabled="disabled"><br><br>

		Date of entry into Canada<br>
		<input type="text" name="can_entrydate" id="doe" disabled="disabled" 
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT can_entrydate FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['can_entrydate']));
				echo '"';
			}
		?>
		placeholder="yyyy-mm-dd"><br><br>

		Language spoken at home<br>
		<input type="text" name="can_lang" id="language" 
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT can_lang FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['can_lang']));
				echo '"';
			}
		?>
		disabled="disabled">

<hr>

		Candidate resides with (check all that apply)<br>
		<input type="checkbox" name="motherRes" 
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT resides_with FROM resides WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['resides_with'] == 'Mother'){
					echo "checked";
				}
			}
		?>
		value="Mother"> Mother<br>
		<input type="checkbox" name="fatherRes" 
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT resides_with FROM resides WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['resides_with'] == 'Father'){
					echo "checked";
				}
			}
		?>
		value="Father"> Father<br>
		<input type="checkbox" name="stepfatherRes" 
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT resides_with FROM resides WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['resides_with'] == 'Stepfather'){
					echo "checked";
				}
			}
		?>
		value="Stepfather"> Stepfather<br>
		<input type="checkbox" name="stepmotherRes" 
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT resides_with FROM resides WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['resides_with'] == 'Stepmother'){
					echo "checked";
				}
			}
		?>
		value="Stepmother"> Stepmother<br>
		<input type="checkbox" name="gaurdianRes" 
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT resides_with FROM resides WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['resides_with'] == 'Guardian'){
					echo "checked";
				}
			}
		?>
		value="Guardian"> Guardian<br>
		Other (Please specify): 
			<input type="text" name="otherRes"
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT * FROM resides WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['resides_with'] != 'Other' && $row['resides_with'] != 'Guardian' && $row['resides_with'] != 'Stepmother' && $row['resides_with'] != 'Stepfather' && $row['resides_with'] != 'Father' && $row['resides_with'] !=  "Mother"){
					echo 'value="';
					echo stripcslashes(htmlspecialchars($row['resides_with']));
					echo '"';
				} else {
					echo 'value=""';
				}
			}
		?>
			><br><br>

		School currently attending<br>
		<input type="text" name="can_school" 
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		
  		$query = "SELECT can_school FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				echo 'value="';
				echo stripcslashes(htmlspecialchars($row['can_school']));
				echo '"';
			}
		?>
		required><br><br>


		Schools attended in the past 3 years<br><br>
		<div id='TextBoxesGroup1'>
			<div id="TextBoxDiv1">
				<?php 
		  		require_once "config.php";
		  		$user_id = $_SESSION["id"];
		  		$query = "SELECT school_name FROM schools WHERE id=$user_id";
					$result = mysqli_query($conn, $query);
					while($row = mysqli_fetch_array($result)){
						$counterc = 1;
						echo '<input type="text" name="school_num' . $counterc .
          				'" id="school' . $counterc . '" value="' . stripcslashes(htmlspecialchars($row['school_name'])) . '"><br>';
					}
					 $counterc++;
				?>
			</div>
		</div>
			<input type='button' value="Add School" id="addButton">
			<input type='button' value="Remove School" id="removeButton">

		<?php
		// Needs to happen after the javascript is called
			if(isset($_POST['submit'])) {

			$school_num = array('school_num2', 'school_num3', 'school_num4', 'school_num5', 'school_num6', 'school_num7', 'school_num8', 'school_num9', 'school_num10');	
		  	
		  	foreach($school_num as $school){
		    $school = mysqli_real_escape_string($conn, $_POST[$school]);

		    if($school == TRUE){
			    $query = "INSERT IGNORE INTO schools (id, school_name) VALUES (?, ?)";

			   		if($query = mysqli_prepare($conn, $query)){
			            // Bind variables to the prepared statement as parameters
			            mysqli_stmt_bind_param($query, "ss", $user_id, $school);

			        // Attempt to execute the prepared statement
			    	mysqli_stmt_execute($query);

			    	// Close statement
			        mysqli_stmt_close($query);
				    }
				}
			}
			            
		} ?>

		<br><br>
		<!--
			<input type='button' value='Get TextBox Value' id='getButtonValue'>
		-->

		<h3 id="pargar">Parent/Guardian Information</h3>
		<div id='TextBoxesGroup3'>
			<?php 
		  		require_once "config.php";
		  		$user_id = $_SESSION["id"];
		  		$query = "SELECT * FROM guardian WHERE id=$user_id";
					$result = mysqli_query($conn, $query);
					while($row = mysqli_fetch_array($result)){
						$counterg = 1;
						echo 
          				'<label>Relation to candidate </label><input type="text" name="garRelation' . $counterg . '" id="garRelation' . $counterg . '" value="' . stripcslashes(htmlspecialchars($row['gar_rel'])) . '"><br>
          				<label>Surname </label><input type="text" name="garSurname' . $counterg . '" id="garSurname' . $counterg . '" value="' . stripcslashes(htmlspecialchars($row['gar_surname'])) . '"><br>
          				<label>Given Name(s) </label><input type="text" name="garName' . $counterg . '" id="garName' . $counterg . '" value="' . stripcslashes(htmlspecialchars($row['gar_name'])) . '"><br>
          				<label>Address </label><input type="text" name="garAddress' . $counterg . '" id="garAddress' . $counterg . '" value="' . stripcslashes(htmlspecialchars($row['gar_address'])) . '"><br>
          				<label>City </label><input type="text" name="garCity' . $counterg . '" id="garCity' . $counterg . '" value="' . stripcslashes(htmlspecialchars($row['gar_city'])) . '"><br>
          				<label>Postal Code </label><input type="text" name="garPostal' . $counterg . '" id="garPostal' . $counterg . '" value="' . stripcslashes(htmlspecialchars($row['gar_postal'])) . '"><br>
          				<label>Home Telephone </label><input type="text" name="garHomePH' . $counterg . '" id="garHomePH' . $counterg . '" value="' . stripcslashes(htmlspecialchars($row['gar_homeph'])) . '"><br>
          				<label>Cell Phone </label><input type="text" name="garCellPH' . $counterg . '" id="garCellPH' . $counterg . '" value="' . stripcslashes(htmlspecialchars($row['gar_cellph'])) . '"><br>
          				<label>Work Telephone </label><input type="text" name="garWorkPH' . $counterg . '" id="garWorkPH' . $counterg . '" value="' . stripcslashes(htmlspecialchars($row['gar_workph'])) . '"><br>
          				<label>Occupation/Title </label><input type="text" name="garOcc' . $counterg . '" id="garOcc' . $counterg . '" value="' . stripcslashes(htmlspecialchars($row['gar_occ'])) . '"><br>
          				<label>Employer </label><input type="text" name="garEmploy' . $counterg . '" id="garEmploy' . $counterg . '" value="' . stripcslashes(htmlspecialchars($row['gar_employer'])) . '"><br><br><br>';
					}
					 $counterg++;
				?>
		</div>
			<input type='button' value="Add Guardian" id="GaraddButton">
			<input type='button' value="Remove Guardian" id="GarremoveButton">

		<br><br>

		<?php
		// Needs to happen after the javascript is called
			if(isset($_POST['submit'])) {

				// Loop through each guardian
				for( $i=0 ; $i < 5 ; $i++ ) {
				  	${"garRelation$i"} = mysqli_real_escape_string($conn, $_POST['garRelation' . $i]);
				  	${"garSurname$i"} = mysqli_real_escape_string($conn, $_POST['garSurname' . $i]);
				  	${"garName$i"} = mysqli_real_escape_string($conn, $_POST['garName' . $i]);
				  	${"garAddress$i"} = mysqli_real_escape_string($conn, $_POST['garAddress' . $i]);
				  	${"garCity$i"} = mysqli_real_escape_string($conn, $_POST['garCity' . $i]);
				  	${"garPostal$i"} = mysqli_real_escape_string($conn, $_POST['garPostal' . $i]);
				  	${"garHomePH$i"} = mysqli_real_escape_string($conn, $_POST['garHomePH' . $i]);
				  	${"garCellPH$i"} = mysqli_real_escape_string($conn, $_POST['garCellPH' . $i]);
				  	${"garWorkPH$i"} = mysqli_real_escape_string($conn, $_POST['garWorkPH' . $i]);
				  	${"garOcc$i"} = mysqli_real_escape_string($conn, $_POST['garOcc' . $i]);
				  	${"garEmploy$i"} = mysqli_real_escape_string($conn, $_POST['garEmploy' . $i]);

				  	if(${"garRelation$i"} == TRUE){
					    $query = "INSERT IGNORE INTO guardian (id, gar_rel, gar_surname, gar_name, gar_address, gar_city, gar_postal, gar_homeph, gar_cellph, gar_workph, gar_occ, gar_employer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

					   		if($query = mysqli_prepare($conn, $query)){
					            // Bind variables to the prepared statement as parameters
					            mysqli_stmt_bind_param($query, "ssssssssssss", $user_id, ${"garRelation$i"}, ${"garSurname$i"}, ${"garName$i"}, ${"garAddress$i"}, ${"garCity$i"}, ${"garPostal$i"}, ${"garHomePH$i"}, ${"garCellPH$i"}, ${"garWorkPH$i"}, ${"garOcc$i"}, ${"garEmploy$i"});

					        // Attempt to execute the prepared statement
					    	mysqli_stmt_execute($query);

					    	// Close statement
					        mysqli_stmt_close($query);
						    }
				  	}
				  }
		} ?>

		
		<h3 id="sibhead">Sibling Information</h3>
	
		<div id='TextBoxesGroup2'>
			<?php 
		  		require_once "config.php";
		  		$user_id = $_SESSION["id"];
		  		$query = "SELECT * FROM siblings WHERE id=$user_id";
					$result = mysqli_query($conn, $query);
					while($row = mysqli_fetch_array($result)){
						$counterc = 1;
						echo 
          				'<label>Name </label><input type="text" name="siblingName' . $counterc . '" id="siblingName' . $counterc . '" value="' . stripcslashes(htmlspecialchars($row['sibling_name'])) . '"><br>
          				<label>Age </label><input type="text" name="siblingAge' . $counterc . '" id="siblingAge' . $counterc . '" value="' . stripcslashes(htmlspecialchars($row['sibling_age'])) . '"><br>
          				<label>School </label><input type="text" name="siblingSchool' . $counterc . '" id="siblingSchool' . $counterc . '" value="' . stripcslashes(htmlspecialchars($row['sibling_school'])) . '"><br><br><br>';

					}
					 $counterc++;
				?>
		</div>
			<input type='button' value="Add Sibling" id="SibaddButton">
			<input type='button' value="Remove Sibling" id="SibremoveButton">

			<?php
		// Needs to happen after the javascript is called
			if(isset($_POST['submit'])) {

				// Loop through each sibling
				for( $i=0 ; $i < 10 ; $i++ ) {
						// Sibling 1
						${"siblingName$i"} = mysqli_real_escape_string($conn, $_POST['siblingName' . $i]); 
						${"siblingAge$i"} = mysqli_real_escape_string($conn, $_POST['siblingAge' . $i]); 
						${"siblingSchool$i"} = mysqli_real_escape_string($conn, $_POST['siblingSchool' . $i]); 

						if(${"siblingName$i"} == TRUE){
						    $query = "INSERT IGNORE INTO siblings (id, sibling_name, sibling_age, sibling_school) VALUES (?, ?, ?, ?)";

						   		if($query = mysqli_prepare($conn, $query)){
						            // Bind variables to the prepared statement as parameters
						            mysqli_stmt_bind_param($query, "ssss", $user_id, ${"siblingName$i"}, ${"siblingAge$i"}, ${"siblingSchool$i"});

						        // Attempt to execute the prepared statement
						    	mysqli_stmt_execute($query);

						    	// Close statement
						        mysqli_stmt_close($query);
							    }
					  	}
					  	
				}
	
			mysqli_close($conn);
		} ?>


		<br><br><br>

		Correspondence should be sent to (check all that applies)<br>
		<input type="checkbox" name="bothCor" 
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT sent_to FROM corr WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['sent_to'] == 'Both'){
					echo "checked";
				}
			}
		?>
		value="Both"> Both Parents<br>
		<input type="checkbox" name="motherCor" 
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT sent_to FROM corr WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['sent_to'] == 'Mother'){
					echo "checked";
				}
			}
		?>
		value="Mother"> Mother (only)<br>
		<input type="checkbox" name="fatherCor" 
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT sent_to FROM corr WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['sent_to'] == 'Father'){
					echo "checked";
				}
			}
		?>
		value="Father"> Father (only)<br>
		<input type="checkbox" name="gaurdianCor" 
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT sent_to FROM corr WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['sent_to'] == 'Guardian'){
					echo "checked";
				}
			}
		?>
		value="Guardian"> Guardian<br>
		Other (Please specify): <input type="text" name="otherCor"
		<?php 
  		require_once "config.php";
  		$user_id = $_SESSION["id"];
  		$query = "SELECT sent_to FROM corr WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if ($row['sent_to'] != 'Both' && $row['sent_to'] != 'Guardian' && $row['sent_to'] != 'Father' && $row['sent_to'] !=  "Mother"){
					echo 'value="';
					echo stripcslashes(htmlspecialchars($row['sent_to']));
					echo '"';
				} 
			}
		?>
		><br><br>

		<button type="submit" name="submit" class="instr" onclick='showHide()'>Save & Continue</button>
	</div>
	</form>
</div>

	</div>
        <footer class="footer"><p class="information">2020 &#169 MSGBoard.org</p></footer>
    </div>


</body>
</html>

