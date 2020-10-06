<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] == NULL){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>TPS Admin</title>
	<link rel="stylesheet" href="style.css">
	<link rel="icon" href="tps_favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Print only specified sections -->
	<style media="screen">
	  .noPrint{ display: block; }
	  .yesPrint{ display: block !important; }
	</style> 
	<style media="print">
	  .noPrint{ display: none; }
	  .yesPrint{ display: block !important; }
	  /* Add Pagebreak on print */
	  .pagebreak { page-break-before: always; } 
	</style>
	<style>
		/* Application table style */
		table, td, th, tr {
		  border-collapse: collapse;
		  border: 1px solid black;
		  padding: 5px;

		}

		table {
		  display: inline-block;
		  vertical-align: top;
		}

		th {
		  background-color: #add8e6;
		  color: black;
		}

		.information {
		  text-align: center;
		  line-height: 50px;
		}

		/* Print Button */
		.print {
		  background-color: white;
		  color: black; 
		  border: black;
		  border-radius: 6px;
		  border: 1px solid black;
		  cursor: pointer;
		  width: fit-content;
		  height: fit-content;
		}

		h1, h2, h3, p, a, form, input, table{
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		}
	</style>
</head>
<body>

<div class="navigation noPrint">
	<img src="TPS_logo.png" class="logo">
	<div class="greeting">
		<p>Hello, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></p>
	    <p>
	        <a href="reset.php">Reset Your Password</a><br>
	        <a href="logout.php">Sign Out of Your Account</a>
	    </p>
	</div>
</div>

<div class="top noPrint"><h1>Search Applications</h1></div>

<div class="body">
	<div class="container login-box noPrint">

	 <script src="scripts/jquery.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){
		    $('.search-box input[type="text"]').on("keyup input", function(){
		        /* Get input value on change */
		        var inputVal = $(this).val();
		        var resultDropdown = $(this).siblings(".result");
		        if(inputVal.length){
		            $.get("scripts/backend-search.php", {term: inputVal}).done(function(data){
		                // Display the returned data in browser
		                resultDropdown.html(data);
		            });
		        } else{
		            resultDropdown.empty();
		        }
		    });
		    
		    // Set search input value on click of result item
		    $(document).on("click", ".result p", function(){
		        $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
		        $(this).parent(".result").empty();
		    });
		});
		</script>

		<form class="login" method='post' action="<?php $_PHP_SELF ?>">
		    <div class="search-box noPrint">
		        <input type="text" autocomplete="off" name="export_data" placeholder="Search user..." />
		        <input type='submit' value='Show Data' class="login-button" name='Export'> 
		        <div class="result"></div>
		    </div>
		</form>
</div>
</div>

	<div>
	<?php

		if(isset($_POST['export_data'])){
			include_once "config.php";

			// Explode input for SQL statement
			$name = explode(" ", $_POST['export_data']);
			// echo $name[0]; // first name
			// echo $name[1]; // last name

			// Handles multiple given names and stores in variable
			if ($name >= 3){
				$sliced = array_slice($name, 0, -1); // array minus last element
				$first_name = implode(" ", $sliced);  // combined array
				$last_name = end($name);
			} else {
				$first_name = reset($name);
				$last_name = end($name);
			}


			// Store id for select statements
			$user_id = [];

			$sql = "SELECT id FROM application WHERE (can_name = '" . $first_name ."' AND can_surname = '" . $last_name . "')";
			$result = mysqli_query($conn, $sql);
			while($row = mysqli_fetch_array($result)){
				$user_id = $row['id'];
			}
			// echo $user_id;

			// Store username to retrieve files
			$username = [];

			$sql = "SELECT username FROM users WHERE id=$user_id";
			$result = mysqli_query($conn, $sql);
			while($row = mysqli_fetch_array($result)){
				$username = $row['username'];
			}

			// Display account creation date
			$created = [];
			$query = "SELECT * FROM users WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){
					$created = $row['created_at'];
				}

			$query = "SELECT * FROM application WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){

				echo "<br>
				<h1><u>" . stripcslashes(htmlspecialchars($row['can_name'])) . " " . stripcslashes(htmlspecialchars($row['can_surname'])) . "</u></h1>
				<p class='information'>Account created on: " . $created . "</p>
				<button class='print noPrint' onclick='window.print()'>Print</button><br>
				<h2>Candidate Information</h2>
				<table>
				<tr>
				<th>Surname</th><th>First Name</th><th>Preferred Name</th><th>Address</th><th>Home Phone</th><th>Cell Phone</th><th>Birth Date</th><th>Gender</th><th>Candidate Photo</th><th>Candidate Identification</th><th>Applying For</th><th>Non-Canadian Resident</th>";

				// Only display these rows if applicant is not canadian
				if ($row['can_canres'] == 'yes') {
						echo "<th>Citizenship</th><th>Entry to Canada</th><th>Native Language</th>";
					}
				
				echo "<th>Current School</th>
				</tr>
				";
				
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['can_surname'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['can_name'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['can_prename'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['can_address'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['can_homeph'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['can_cellph'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['can_birth'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['can_gender'])) . "</td>";

					// Specify path to file
					$file = "files/" . $username . '/images/' . basename($row['can_image']);
					echo '<td><a href="' . $file . '" download>download</a></td>';

					// Specify path to file
					$file = "files/" . $username . '/images/' . basename($row['can_birthcert']);
					echo '<td><a href="' . $file . '" download>download</a><br></td>';

					echo "<td>" . stripcslashes(htmlspecialchars($row['can_entry'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['can_canres'])) . "</td>";

					// Only display these rows if applicant is not canadian
					if ($row['can_canres'] == 'yes') {
						echo "<td>" . stripcslashes(htmlspecialchars($row['can_cit'])) . "</td>";
						echo "<td>" . stripcslashes(htmlspecialchars($row['can_entrydate'])) . "</td>";
						echo "<td>" . stripcslashes(htmlspecialchars($row['can_lang'])) . "</td>";
					}
					
					echo "<td>" . stripcslashes(htmlspecialchars($row['can_school'])) . "</td></tr>";
				   
				}

				echo "</table>";

				echo "<br>
				<table class='appTable'>
				<tr>
				<th>Report Card Files</th>
				</tr>
				";

			// Reports
			$query = "SELECT * FROM reports WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){

					// Specify path to file
					$file = "files/" . $username . '/documents/' . basename($row['report_name']);
					echo '<tr><td><a href="' . $file . '" download>' . stripcslashes(htmlspecialchars($row['report_name'])) . '</a></td></tr>';
				   
				}

				echo "</table>";


				echo "
				<table class='appTable'>
				<tr>
				<th>Additional Documents</th>
				</tr>
				";

			// Additional documents
			$query = "SELECT * FROM additional WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){

					// Specify path to file
					$file = "files/" . $username . '/additional_documents/' . basename($row['additional_name']);
					echo '<tr><td><a href="' . $file . '" download>' . stripcslashes(htmlspecialchars($row['additional_name'])) . '</a></td></tr>';
				   
				}

				echo "</table>";


				echo "
				<table class='appTable'>
				<tr>
				<th>Resides With</th>
				</tr>
				";

			// Resides with
			$query = "SELECT * FROM resides WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){

					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['resides_with'])) . "</td></tr>";
				   
				}

				echo "</table>";

				echo "
				<table class='appTable'>
				<tr>
				<th>Schools Attended</th>
				</tr>
				";
			// Schools Attended
			$query = "SELECT * FROM schools WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){

					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['school_name'])) . "</td></tr>";
				   
				}

				echo "</table>";

				echo "
				<table class='appTable'>
				<tr>
				<th>Contact Email</th>
				</tr>
				";
			// Schools Attended
			$query = "SELECT email FROM users WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){

					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['email'])) . "</td></tr>";
				   
				}

			echo "</table><br><br>";


			echo "<h2>Parent/Guardian Information</h2>
				<table class='appTable'>
				<tr>
				<th>Relation</th><th>Surname</th><th>First Name</th><th>Address</th><th>City</th><th>Postal Code</th><th>Home Phone</th><th>Cell Phone</th><th>Work Phone</th><th>Occupation/Title</th><th>Employer</th>
				</tr>";
			// Parent/Guardian Information
			$query = "SELECT * FROM guardian WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){

					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['gar_rel'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['gar_surname'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['gar_name'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['gar_address'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['gar_city'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['gar_postal'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['gar_homeph'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['gar_cellph'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['gar_workph'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['gar_occ'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['gar_employer'])) . "</td></tr>";
				   
				}

			echo "</table>";

			echo "
				<table class='appTable'>
				<tr>
				<th>Send Correspondence To</th>
				</tr>";
			// Correspondence
			$query = "SELECT * FROM corr WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){

					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['sent_to'])) . "</td></tr>";
				   
				}

				
			echo "</table><br><br>";

			echo "<h2>Sibling Information</h2>
				<table class='appTable'>
				<tr>
				<th>Name</th><th>Age</th><th>Current School</th>
				</tr>";
			// Sibling Information
			$query = "SELECT * FROM siblings WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){

					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['sibling_name'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['sibling_age'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['sibling_school'])) . "</td></tr>";
				   
				}

				echo "</table><br><br>";

			// Parent Statement
			$query = "SELECT * FROM parentstmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){

				echo "<div class='pagebreak'><h2>Parent/Guardian Statement</h2></div>
				<table class='appTable'>
				<tr>
				<th>Person Completing Statement</th><th>Educational/Psychological Assesment</th>";
				if ($row['asses_ans'] == 'yes'){
					echo "<th>Assesment File</th>";
				}
				"</tr>";
				
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['par_first'])) . " " . stripcslashes(htmlspecialchars($row['par_surname'])) . "</td>";
					echo "<td>" . stripcslashes(htmlspecialchars($row['asses_ans'])) . "</td>";
					if ($row['asses_ans'] == 'yes'){
						// Specify path to file
						$file = "files/" . $username . '/documents/' . basename($row['asses_doc']);
						echo '<td><a href="' . $file . '" download>' . stripcslashes(htmlspecialchars($row['asses_doc'])) . '</a></td></tr>';
					}

					echo "</tr>";
					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>Briefly describe your child’s character and personality</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['par_la1'])) . "</td></tr>";
			
					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>What qualities do you admire in your child?</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['par_la2'])) . "</td></tr>";
			
					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>What do you consider to be your child’s greatest area of need, socially and/or academically?</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['par_la3'])) . "</td></tr>";
				
					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>Is there anything we should know about the history of your child’s education? Has your child ever skipped or repeated a grade?</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['par_la4'])) . "</td></tr>";
				
					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>Has your child ever been asked to withdraw from school, been suspended, put on probation, or missed school for an extended period of time? Please explain</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['par_la5'])) . "</td></tr>";
				
					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>Is your child currently seeing a counsellor, psychologist and/or psychiatrist? If yes, explain</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['par_la6'])) . "</td></tr>";
					
					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>Presently, does your child have any physical or mental restrictions affecting his or her ability to participate in school sports or other activities? If yes, please describe the nature of the restriction and any reasonable accommodation you feel may be necessary for your child’s participation</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['par_la7'])) . "</td></tr>";
					
					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>Are there any family circumstances that might affect your child’s performance at school? If yes, explain</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['par_la8'])) . "</td></tr>";
					
					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>How did you find out about Toronto Prep School?</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['par_la9'])) . "</td></tr>";
					echo "</table>";
				   
				}

				echo "<br><br>";

			// Candidate statement
			$query = "SELECT * FROM candidatestmt WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){

					echo "<div class='pagebreak'><h2>Candidate Statement</h2></div>
					<table class='appTable'>
					<tr>
					<th>The traits I like most about me are . . .</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['stu_la1'])) . "</td></tr>";
					echo "</table>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>The things I like most about school are . . .</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['stu_la2'])) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>In order of preference, list the three subjects you are the most excited about studying at school</th>
					</tr>";
					echo "<tr><td>#1: " . stripcslashes(htmlspecialchars($row['subj1'])) . "</td></tr>
						 <tr><td>#2: " . stripcslashes(htmlspecialchars($row['subj2'])) . "</td></tr>
						 <tr><td>#3: " . stripcslashes(htmlspecialchars($row['subj3'])) . "</td></tr>";


					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>List the extracurricular programs and any clubs or organizations you have been involved with most recently (sports, music, dance, community service etc.)</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['stu_la3'])) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>Describe your favourite memory and why it is special for you.</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['stu_la4'])) . "</td></tr>";
					
					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>The things I do best are . . .</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['stu_la5'])) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>I have difficulty with . . .</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['stu_la6'])) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>Who do you respect the most in your life and why?</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['stu_la7'])) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>If you could visit any country in the world, where would you go and why?</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['stu_la8'])) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>Describe the personal traits needed to be a good friend</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['stu_la9'])) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>What do you expect from Toronto Prep School?</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['stu_la10'])) . "</td></tr></table>";
				}

				echo "<br><br>";

			// Tearcher Referral
			$query = "SELECT * FROM teacherref WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();

			//Validate if referral exists
			$verifyTeach = "";

				while($row = mysqli_fetch_array($result)){
					$verifyTeach = stripcslashes($row['teachFirst']);

					echo "<div class='pagebreak'><h2>Teacher Referral</h2></div>
					<table class='appTable'>
					<tr>
					<th>First Name</th><th>Last Name</th><th>School Currently Teaching at</th><th>Email</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['teachFirst'])) . "</td>
						 <td>" . stripcslashes(htmlspecialchars($row['teachLast'])) . "</td>
						 <td>" . stripcslashes(htmlspecialchars($row['teachSchool'])) . "</td>";
					}

			$query = "SELECT * FROM referral WHERE id=$user_id AND ref_type='teacher'";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){
					if($verifyTeach == NULL){
						echo "Pending Teacher Referral.<br>Email: " . stripcslashes(htmlspecialchars($row['email']));
					} else {
					echo "<td>" . stripcslashes(htmlspecialchars($row['email'])) . "</td></tr>";
				}
			}

			$query = "SELECT * FROM teacherref WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){
		
					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>How long have you known the candidate and in what capacity?</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['tq1'])) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>In your opinion, what are the candidate’s two greatest strengths?</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['tq2'])) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>In what area(s) should the candidate strive for improvement?</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['tq3'])) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>Has the candidate received any academic awards while at your school? If yes, please provide details</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['tq4'])) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>We would appreciate any other comments that you feel will help portray an accurate assessment of this candidate</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['tq5'])) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>Academic Performance</th><th>Group Participation</th><th>Ability to Work Alone</th><th>Classroom Behaviour</th><th>Verbal Skills</th><th>Written Communication</th><th>Follows Directions</th><th>Work Effort</th><th>Attention Span</th><th>Initiative</th><th>Integrity</th>
					</tr>";
					echo "<tr><td>" . stripcslashes($row['acper']) . "</td>
						 <td>" . stripcslashes($row['grpar']) . "</td>
						 <td>" . stripcslashes($row['woralo']) . "</td>
						 <td>" . stripcslashes($row['clabeh']) . "</td>
						 <td>" . stripcslashes($row['verski']) . "</td>
						 <td>" . stripcslashes($row['wricom']) . "</td>
						 <td>" . stripcslashes($row['foldir']) . "</td>
						 <td>" . stripcslashes($row['woreff']) . "</td>
						 <td>" . stripcslashes($row['attspa']) . "</td>
						 <td>" . stripcslashes($row['init']) . "</td>
						 <td>" . stripcslashes($row['integ']) . "</td></tr></table>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>Critical Thinking</th><th>Self Confidence</th><th>Creativity</th><th>Use of Time</th><th>Sportsmanship</th><th>Peer Relationship</th><th>Intellectual Curiosity</th><th>Parent Cooperation</th><th>Parent Expectations</th><th>Homework Completion</th><th>Academic Potential</th>
					</tr>";
					echo "<tr><td>" . stripcslashes($row['crithi']) . "</td>
						 <td>" . stripcslashes($row['selcon']) . "</td>
						 <td>" . stripcslashes($row['crea']) . "</td>
						 <td>" . stripcslashes($row['useof']) . "</td>
						 <td>" . stripcslashes($row['sport']) . "</td>
						 <td>" . stripcslashes($row['peerel']) . "</td>
						 <td>" . stripcslashes($row['intcur']) . "</td>
						 <td>" . stripcslashes($row['parcoo']) . "</td>
						 <td>" . stripcslashes($row['parexp']) . "</td>
						 <td>" . stripcslashes($row['homcom']) . "</td>
						 <td>" . stripcslashes($row['acapot']) . "</td></tr></table>";
				}

				echo "<br><br>";

			// Community Referral
			$query = "SELECT * FROM communityref WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();

			// Verify if referral exists
			$verifyComm = "";

				while($row = mysqli_fetch_array($result)){

					$verifyComm = stripcslashes($row['commFirst']);

					echo "<div class='pagebreak'><h2>Community Referral</h2></div>
					<table class='appTable'>
					<tr>
					<th>First Name</th><th>Last Name</th><th>Years Candidate Known</th><th>Email</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['commFirst'])) . "</td>
						 <td>" . stripcslashes(htmlspecialchars($row['commLast'])) . "</td>
						 <td>" . stripcslashes(htmlspecialchars($row['years'])) . "</td>";
						}

			$query = "SELECT * FROM referral WHERE id=$user_id AND ref_type='community'";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){
					if($verifyComm == NULL){
						echo "Pending Community Referral.<br>Email: " . stripcslashes(htmlspecialchars($row['email']));
					}else{
					echo "<td>" . stripcslashes(htmlspecialchars($row['email'])) . "</td></tr>";
				}
			}

			$query = "SELECT * FROM communityref WHERE id=$user_id";
			$result = mysqli_query($conn, $query);
			$user_arr = array();
				while($row = mysqli_fetch_array($result)){
		
					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>In what capacity have you known the candidate?</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['cq1'])) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>In your opinion, what are the candidate’s strengths?</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['cq2'])) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>We would appreciate any comments that you feel will help portray an accurate assessment of this candidate.</th>
					</tr>";
					echo "<tr><td>" . stripcslashes(htmlspecialchars($row['cq3'])) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>Attentiveness</th><th>Dependability</th><th>Compassion</th><th>Honesty</th><th>Respect</th><th>Responsibility</th><th>Follows Directions</th><th>Cooperation</th><th>Sense of Adventure</th><th>Work Ethic</th><th>Creativity</th>
					</tr>";
					echo "<tr><td>" . stripcslashes($row['atten']) . "</td>
						 <td>" . stripcslashes($row['depen']) . "</td>
						 <td>" . stripcslashes($row['compa']) . "</td>
						 <td>" . stripcslashes($row['hone']) . "</td>
						 <td>" . stripcslashes($row['resp']) . "</td>
						 <td>" . stripcslashes($row['respon']) . "</td>
						 <td>" . stripcslashes($row['foldire']) . "</td>
						 <td>" . stripcslashes($row['coop']) . "</td>
						 <td>" . stripcslashes($row['aenadv']) . "</td>
						 <td>" . stripcslashes($row['woreth']) . "</td>
						 <td>" . stripcslashes($row['creat']) . "</td></tr>";

					echo "</table>";
					echo "<table class='appTable'>
					<tr>
					<th>Leaderership</th><th>Self Confidence</th><th>Humour</th><th>Social Maturity</th><th>Sportsmanship</th><th>Peer Relationship</th><th>Tenacity</th><th>Attitude</th><th>Ambition</th><th>Coachable</th>
					</tr>";
					echo "<tr><td>" . stripcslashes($row['leader']) . "</td>
						 <td>" . stripcslashes($row['selfcon']) . "</td>
						 <td>" . stripcslashes($row['humo']) . "</td>
						 <td>" . stripcslashes($row['socmat']) . "</td>
						 <td>" . stripcslashes($row['sports']) . "</td>
						 <td>" . stripcslashes($row['peerrel']) . "</td>
						 <td>" . stripcslashes($row['tenac']) . "</td>
						 <td>" . stripcslashes($row['atti']) . "</td>
						 <td>" . stripcslashes($row['ambi']) . "</td>
						 <td>" . stripcslashes($row['coach']) . "</td></tr></table>";
				}

  	
		}
		?>
	</div>

	</div>
        <footer class="footer"><p class="information">2020 &#169 MSGBoard.org</p></footer>
    </div>

</body>
</html>

