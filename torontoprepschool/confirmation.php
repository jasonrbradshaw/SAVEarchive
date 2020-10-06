<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
}

// Confirmation checkbox popup
if(empty($_POST['agree']) || $_POST['agree'] != 'agree') {
    echo 'Please indicate that you have completed this application accurately and honestly.';
}

// Send confirmation email to webmaster
if(isset($_POST['submit'])) {
require_once "config.php";
// Sanitized referral information
	$user_id = $_SESSION["id"];
    $can_first = "";
    $can_last = "";

            $query = "SELECT can_name, can_surname FROM application WHERE id=".$user_id."";
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_array($result)){
                $can_first = htmlspecialchars($row['can_name']);
                $can_last = htmlspecialchars($row['can_surname']);
            }

 			
            // Teacher Referral
            $to = "webmaster@torontoprepschool.com";
            $subject = "New Online Submission from $can_first $can_last";

            $message = "
            <html>
            <head>
            <title>New Submission</title>
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
                            <p>A new online application has been submitted by $can_first $can_last.</p>
                            
                            <p> Click <a href='https://msgboard.org/TPS/login.php?token=$token'>here</a> to login to the admin panel and view data.</p><br>
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

            if(mail($to,$subject,$message,$headers)){
                // Redirect to login page
                header("location: Confirmation.html");
            }

}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Payment and Confirmation</title>
	<link rel="stylesheet" href="style.css">
	<link rel="icon" href="tps_favicon.png">
	<script src="scripts/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script src="scripts/max_char.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
        .information {
          text-align: center;
          line-height: 50px;
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
</head>
<body>

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
	  <a href="referral.php">Referrals</a>
	</div>
	<div class="navbox">
	  <a  class="active" href="confirmation.php">Confirmation</a>
	</div>
</div>

<div class="top">
	<h1>Payment and Confirmation</h1>
</div>

<div class="body">
	<form action="<?php $_PHP_SELF ?>" onsubmit="if(document.getElementById('agree').checked) { return true; } else { alert('Please indicate that you have completed this application accurately and honestly.'); return false; }" method="post">
		<div class="container">

		<h2>Confirmation</h2>

		<p>Please ensure that all of your information is correct.</p>
	
		<?php
		require_once "config.php";

		$user_id = $_SESSION["id"];

		$sql1 = "SELECT * FROM application WHERE id=".$user_id."";
		$result = mysqli_query($conn, $sql1); 
			echo "<br>";
			echo "<h3><u>Candidate Information</u></h3>"
			;

			// Verify candidate information exists
			$verifyCan = "";

			while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
				$verifyCan = stripcslashes(htmlspecialchars($row['can_name']));

				if($verifyCan == NULL){
					echo "<b>No candidate information submitted.</b>";
				}else{
					echo "<b>Candidate Surname</b><br><p>" . stripcslashes(htmlspecialchars($row['can_surname'])) . "</p>";
					echo "<b>Candidate Name</b><br><p>" . stripcslashes(htmlspecialchars($row['can_name'])) . "</p>";
					echo "<b>Candidate Preferred Name</b><br><p>" . stripcslashes(htmlspecialchars($row['can_prename'])) . "</p>";
				    echo "<b>Candidate Address</b><br><p>" . stripcslashes(htmlspecialchars($row['can_address'])) . "</p>";
				    echo "<b>Candidate Home Phone</b><br><p>" . stripcslashes(htmlspecialchars($row['can_homeph'])) . "</p>";
				    echo "<b>Candidate Cell Phone</b><br><p>" . stripcslashes(htmlspecialchars($row['can_cellph'])) . "</p>";
				    echo "<b>Candidate Date of Birth</b><br><p>" . stripcslashes(htmlspecialchars($row['can_birth'])) . "</p>";
				    echo "<b>Candidate Gender</b><br><p>" . stripcslashes(htmlspecialchars($row['can_gender'])) . "</p>";
				    echo "<b>Candidate Image File</b><br><p>" . stripcslashes(htmlspecialchars($row['can_image'])) . "</p>";
				    echo "<b>Candidate Identification Image File</b><br><p>" . stripcslashes(htmlspecialchars($row['can_birthcert'])) . "</p>";
				}

			}


		// Report card files
		$sql2 = "SELECT * FROM reports WHERE id=".$user_id."";
		$result = mysqli_query($conn, $sql2); 

		if($verifyCan == NULL){
		}else{
			echo "<b>Candidate's Report Card Files</b>"; 
				while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
					echo "<p>" . stripcslashes(htmlspecialchars($row['report_name'])) . "</p>";
				}
		}


		// Current courses
		$sql3 = "SELECT * FROM courses WHERE id=".$user_id."";
		$result = mysqli_query($conn, $sql3); 
		if($verifyCan == NULL){
		}else{
			echo "<b>Candidate's Current Courses</b>";
				while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
					
					echo "<p>" . stripcslashes(htmlspecialchars($row['course_num'])) . "</p>";
				}
		}

		// Non-Canadian residents
		$result = mysqli_query($conn, $sql1); 
			if($verifyCan == NULL){
			}else{
				while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
					
					echo "<b>Applying for Entry in</b><br><p>" . stripcslashes(htmlspecialchars($row['can_entry'])) . "</p>";

					if ($row['can_canres'] == 'yes'){
						echo "<b>Candidate's Citizenship</b><br><p>" . stripcslashes(htmlspecialchars($row['can_cit'])) . "</p>";
						echo "<b>Candidate's Entry Date into Canada</b><br><p>" . stripcslashes(htmlspecialchars($row['can_entrydate'])) . "</p>";
						echo "<b>Language Spoken at Home</b><br><p>" . stripcslashes(htmlspecialchars($row['can_lang'])) . "</p>";
					}
				}
			}

		// Candidate resides with
		$sql4 = "SELECT * FROM resides WHERE id=".$user_id."";
		$result = mysqli_query($conn, $sql4); 
		if($verifyCan == NULL){
		}else{
			echo "<b>Candidate Resides With</b>";
				while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
					
					echo "<p>" . stripcslashes(htmlspecialchars($row['resides_with'])) . "</p>";
				}
		}

		// Current school
		$result = mysqli_query($conn, $sql1); 
			if($verifyCan == NULL){
			}else{
				while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
					
					echo "<b>Candidate's Current School</b><br><p>" . stripcslashes(htmlspecialchars($row['can_school'])) . "</p>";
				}
			}

		// Past schools
		$sql5 = "SELECT * FROM schools WHERE id=".$user_id."";
		$result = mysqli_query($conn, $sql5); 
		if($verifyCan == NULL){
		}else{
			echo "<b>Candidate's Past Schools</b>";
				while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
					
					echo "<p>" . stripcslashes(htmlspecialchars($row['school_name'])) . "</p>";
				}
		}

		echo "<br><a href='application.php'>edit</a>";
		echo "<br><hr>";

		

		echo "<h3><u>Parent/Guardian Information</u></h3>";

		$sql6 = "SELECT * FROM guardian WHERE id=".$user_id."";
		$result = mysqli_query($conn, $sql6); 

		$verifyGar = "";
			
			// Auto Increment counter
			$i = 1;
			while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
				$verifyGar = stripcslashes(htmlspecialchars($row['gar_rel']));

			if($verifyGar == NULL){

			}else{
				echo "<b><i>Parent/Guardian #" . $i . "</i></b><br>";
				echo "<a href='scripts/deletePar.php?id=" . $row['gar_id'] . "'>Delete Parent/Guardian</a><br><br>";
				echo "<b>Relationship to Candidate</b><br><p>" . stripcslashes(htmlspecialchars($row['gar_rel'])) . "</p>";
				echo "<b>Parent/Guardian Surname</b><br><p>" . stripcslashes(htmlspecialchars($row['gar_surname'])) . "</p>";
				echo "<b>Parent/Guardian Name</b><br><p>" . stripcslashes(htmlspecialchars($row['gar_name'])) . "</p>";
				echo "<b>Parent/Guardian Address</b><br><p>" . stripcslashes(htmlspecialchars($row['gar_address'])) . "</p>";
				echo "<b>Parent/Guardian City</b><br><p>" . stripcslashes(htmlspecialchars($row['gar_city'])) . "</p>";
				echo "<b>Parent/Guardian Postal Code</b><br><p>" . stripcslashes(htmlspecialchars($row['gar_postal'])) . "</p>";
				echo "<b>Parent/Guardian Home Phone Number</b><br><p>" . stripcslashes(htmlspecialchars($row['gar_homeph'])) . "</p>";
				echo "<b>Parent/Guardian Cell Phone Number</b><br><p>" . stripcslashes(htmlspecialchars($row['gar_cellph'])) . "</p>";
				echo "<b>Parent/Guardian Work Phone Number</b><br><p>" . stripcslashes(htmlspecialchars($row['gar_workph'])) . "</p>";
				echo "<b>Parent/Guardian Occupation or Title</b><br><p>" . stripcslashes(htmlspecialchars($row['gar_occ'])) . "</p>";
				echo "<b>Parent/Guardian Employer</b><br><p>" . stripcslashes(htmlspecialchars($row['gar_employer'])) . "</p><br>";

				$i++;
			}
		}

		// Correspondence should be sent to
		$sql8 = "SELECT * FROM corr WHERE id=".$user_id."";
		$result = mysqli_query($conn, $sql8); 
		if($verifyGar == NULL){
		}else{
			echo "<b>Correspondence should be sent to</b>";
				while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
					echo "<p>" . stripcslashes(htmlspecialchars($row['sent_to'])) . "</p>";
				}
			}

		echo "<br><a href='application.php#pargar'>edit</a>";
		echo "<hr>";

		echo "<h3><u>Sibling Information</u></h3>";

		$sql7 = "SELECT * FROM siblings WHERE id=".$user_id."";
		$result = mysqli_query($conn, $sql7); 
			
			// Auto Increment counter
			$i = 1;
			while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
				echo "<b><i>Sibling #" . $i . "</i></b><br>";
				echo "<a href='scripts/deleteSib.php?id=" . $row['sibling_id'] . "'>Delete Sibling</a><br><br>";
				echo "<b>Sibling's Name</b><br><p>" . stripcslashes(htmlspecialchars($row['sibling_name'])) . "</p>";
				echo "<b>Sibling's Age</b><br><p>" . stripcslashes(htmlspecialchars($row['sibling_age'])) . "</p>";
				echo "<b>Name of Sibling's School</b><br><p>" . stripcslashes(htmlspecialchars($row['sibling_school'])) . "</p><br>";
				$i++;
			}
		

		echo "<a href='application.php#sibhead'>edit</a>";
		echo "<br><hr>";

		// Parent Statement
		echo "<h3><u>Parent Statement</u></h3>";

		$sql9 = "SELECT * FROM parentstmt WHERE id=".$user_id."";
		$result = mysqli_query($conn, $sql9); 
		
			while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
				echo "<b>Name of person completing Parent Statement</b><br><p>" . stripcslashes(htmlspecialchars($row['par_first'])) . " " . stripcslashes(htmlspecialchars($row['par_surname'])) . "</p>";
				echo "<b>Briefly describe your child’s character and personality</b><br><p>" . stripcslashes(htmlspecialchars($row['par_la1'])) . "</p>";
				echo "<b>What qualities do you admire in your child?</b><br><p>" . stripcslashes(htmlspecialchars($row['par_la2'])) . "</p>";
				echo "<b>What do you consider to be your child’s greatest area of need, socially and/or academically?</b><br><p>" . stripcslashes(htmlspecialchars($row['par_la3'])) . "</p>";
				echo "<b>Is there anything we should know about the history of your child’s education? Has your child ever skipped or repeated a grade?</b><br><p>" . stripcslashes(htmlspecialchars($row['par_la4'])) . "</p>";
				echo "<b>Has your child ever been asked to withdraw from school, been suspended, put on probation, or missed school for an extended period of time? Please explain.</b><br><p>" . stripcslashes(htmlspecialchars($row['par_la5'])) . "</p>";
				echo "<b>Does your child have a psychological or educational assessment?</b><br><p>" . stripcslashes(htmlspecialchars($row['asses_ans'])) . "</p>";
				echo "<b>Document File Name</b><br><p>" . stripcslashes(htmlspecialchars($row['asses_doc'])) . "</p>";
				echo "<b>Is your child currently seeing a counsellor, psychologist and/or psychiatrist? If yes, explain.</b><br><p>" . stripcslashes(htmlspecialchars($row['par_la6'])) . "</p>";
				echo "<b>Presently, does your child have any physical or mental restrictions affecting his or her ability to participate in school sports or other activities? If yes, please describe the nature of the restriction and any reasonable accommodation you feel may be necessary for your child’s participation.</b><br><p>" . stripcslashes(htmlspecialchars($row['par_la7'])) . "</p>";
				echo "<b>Are there any family circumstances that might affect your child’s performance at school? If yes, explain.</b><br><p>" . stripcslashes(htmlspecialchars($row['par_la8'])) . "</p>";
				echo "<b>How did you find out about Toronto Prep School?</b><br><p>" . stripcslashes(htmlspecialchars($row['par_la9'])) . "</p>";
			}

		echo "<br><a href='parent-statement.php'>edit</a>";
		echo "<br><hr>";

		// Candidate Statement
		echo "<h3><u>Candidate Statement</u></h3>";
		$sql10 = "SELECT * FROM candidatestmt WHERE id=".$user_id."";
		$result = mysqli_query($conn, $sql10); 
		
			while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
				echo "<b>The traits I like most about me are . . .</b><br><p>" . stripcslashes(htmlspecialchars($row['stu_la1'])) . "</p>";
				echo "<b>The things I like most about school are . . .</b><br><p>" . stripcslashes(htmlspecialchars($row['stu_la2'])) . "</p>";
				echo "<b>In order of preference, list the three subjects you are the most excited about studying at school.</b><br>
				<p>#1: " . stripcslashes(htmlspecialchars($row['subj1'])) . "</p>
				<p>#2: " . stripcslashes(htmlspecialchars($row['subj2'])) . "</p>
				<p>#3: " . stripcslashes(htmlspecialchars($row['subj3'])) . "</p>";
				echo "<b>List the extracurricular programs and any clubs or organizations you have been involved with most recently (sports, music, dance, community service etc.)</b><br><p>" . stripcslashes(htmlspecialchars($row['stu_la3'])) . "</p>";
				echo "<b>Describe your favourite memory and why it is special for you</b><br><p>" . stripcslashes(htmlspecialchars($row['stu_la4'])) . "</p>";
				echo "<b>The things I do best are . . .</b><br><p>" . stripcslashes(htmlspecialchars($row['stu_la5'])) . "</p>";
				echo "<b>I have difficulty with . . .</b><br><p>" . stripcslashes(htmlspecialchars($row['stu_la6'])) . "</p>";
				echo "<b>Who do you respect the most in your life and why?</b><br><p>" . stripcslashes(htmlspecialchars($row['stu_la7'])) . "</p>";
				echo "<b>If you could visit any country in the world, where would you go and why?</b><br><p>" . stripcslashes(htmlspecialchars($row['stu_la8'])) . "</p>";
				echo "<b>Describe the personal traits needed to be a good friend</b><br><p>" . stripcslashes(htmlspecialchars($row['stu_la9'])) . "</p>";
				echo "<b>What do you expect from Toronto Prep School?</b><br><p>" . stripcslashes(htmlspecialchars($row['stu_la10'])) . "</p>";
			}

		echo "<br><a href='candidate-statement.php'>edit</a>";
		echo "<br><hr>";

		// Referrals
		echo "<h3><u>Referral Information</u></h3>";
		$sql10 = "SELECT * FROM referral WHERE id='$user_id' AND ref_type='teacher'";
		$result = mysqli_query($conn, $sql10); 
		
			while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
				echo "<b><i>Teacher Referral</i></b><br><br>";
				echo "<b>First Name</b><p>" . stripcslashes(htmlspecialchars($row['first_name'])) . "</p>";
				echo "<b>Last Name</b><p>" . stripcslashes(htmlspecialchars($row['last_name'])) . "</p>";
				echo "<b>Email</b><p>" . stripcslashes(htmlspecialchars($row['email'])) . "</p>";
			}

		$sql10 = "SELECT * FROM referral WHERE id='$user_id' AND ref_type='community'";
		$result = mysqli_query($conn, $sql10); 
		
			while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
				echo "<b><i>Community Referral</i></b><br><br>";
				echo "<b>First Name</b><p>" . stripcslashes(htmlspecialchars($row['first_name'])) . "</p>";
				echo "<b>Last Name</b><p>" . stripcslashes(htmlspecialchars($row['last_name'])) . "</p>";
				echo "<b>Email</b><p>" . stripcslashes(htmlspecialchars($row['email'])) . "</p>";
			}

		
		// Report card files
		$sql12 = "SELECT additional_name FROM additional WHERE id=".$user_id."";
		$result = mysqli_query($conn, $sql12); 
		$verifyAddit = "";
			while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
				$verifyAddit = htmlspecialchars($row['additional_name']);
			}

			if($verifyAddit == NULL){
			}else{
				echo "<b>Additional Documents</b>"; 
				$sql12 = "SELECT additional_name FROM additional WHERE id=".$user_id."";
				$result = mysqli_query($conn, $sql12); 
					while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
						echo "<p>" . htmlspecialchars($row['additional_name']) . "</p>";
					}
			}

		echo "<br><a href='referral.php'>edit</a>";
		echo "<br><hr>";

	
		mysqli_close($conn);
		?>
	
		<br>

		<h2>Payment</h2>
		<p>Please submit a $150 deposit with your application.</p>
		<div id="paypal-button-container" style="z-index: -1;"></div>
		<script src="https://www.paypal.com/sdk/js?client-id=sb&currency=CAD" data-sdk-integration-source="button-factory"></script>
			<script>
			  paypal.Buttons({
			      style: {
			          shape: 'rect',
			          color: 'gold',
			          layout: 'vertical',
			          label: 'pay',
			          
			      },
			      createOrder: function(data, actions) {
			          return actions.order.create({
			              purchase_units: [{
			                  amount: {
			                      value: '1'
			                  }
			              }]
			          });
			      },
			      onApprove: function(data, actions) {
			          return actions.order.capture().then(function(details) {
			              alert('Transaction completed by ' + details.payer.name.given_name + '!');
			          });
			      }
			  }).render('#paypal-button-container');
			</script>

			<br><br>

		<p class="center"><input type="checkbox" name="checkbox" value="check" id="agree" /> I have completed my submission accurately and honestly.</p>
		<br>

		<button type="submit" name="submit" class="submit">Submit</button>
	</div>
	</form>
</div>

	</div>
        <footer class="footer"><p class="information">2020 &#169 MSGBoard.org</p></footer>
    </div>

</body>
</html>