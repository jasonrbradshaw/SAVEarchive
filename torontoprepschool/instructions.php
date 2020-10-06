<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Confirmation checkbox popup
if(empty($_POST['agree']) || $_POST['agree'] != 'agree') {
    echo 'Please indicate that you have read and understand the Toronto Prep School admission process';
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Application Instructions</title>
	<link rel="stylesheet" href="style.css">
	<link rel="icon" href="tps_favicon.png">
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

		h1, h2, h3, p, a{
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
	  <a class="active" href="instructions.php">Instructions</a>
	</div>
	<div class="navbox">
	  <a href="">Application</a>
	</div>
	<div class="navbox">
	  <a href="">Parent Statement</a>
	</div>
	<div class="navbox">
	  <a href="">Candidate Statement</a>
	</div>
	<div class="navbox">
	  <a href="">Referrals</a>
	</div>
	<div class="navbox">
	  <a href="">Confirmation</a>
	</div>
</div>


<div class="top"></div>

<div class="body">
<form class="login" action="application.php" onsubmit="if(document.getElementById('agree').checked) { return true; } else { alert('Please indicate that you have read and understand the Toronto Prep School admission process'); return false; }" method="post">
	<div class="container">
	<h1>Welcome to Toronto Prep School Admissions</h1>
		<p>Thank you for your interest in Toronto Prep School. Our Admissions Office realizes the significance of a family’s decision to consider a private school and it is our goal that our admissions process will be informative, constructive and a favourable experience.</p>

		<p>Toronto Prep School seeks to enrol a group of diverse students. We follow an open admission policy, whereby qualified candidates are admitted without an entrance test, but rather on assessment for evidence of good character, maturity and academic motivation. The Admissions Committee evaluates each applicant on the basis of a personal interview, academic record, and teacher and community recommendations.</p>

		<p>If further assistance is required upon reviewing the information provided, please feel free to contact our Admissions Office at 416.545.1020.</p>

		<p>We are pleased to learn of your interest in our school, and look forward to meeting you and your family.</p>

		<p>Sincerely Yours,</p>

		<p>Fouli Tsimikalis<br>
		Director of Admissions</p>

		<br>

	<h1>Toronto Prep School Admissions Process</h1>
		<p><i>“The search for the right school begins with the initiative to explore”</i></p>
		<p>Toronto Prep School works to sustain an environment where students of diverse backgrounds learn together in an atmosphere of acceptance, respect and appreciation for each other. We are committed to developing students who are confident and responsible and exhibit the values necessary to become effective communicators, informed and productive thinkers, self-directed learners, collaborative workers, skilled information processors and problem solvers.</p>
	<h2>The Application</h2>
		<p>Our admissions process is a chance to explore. It is designed to help parents and the prospective candidate make the most informed educational decision. We strive to enable the candidate, his or her family and Toronto Prep School to learn as much as possible about each other in order to determine the suitability of our school environment for each applicant. The journey always begins and ends with the individual candidate. The process enables your family to determine if our school is right for you, do we meet your requirements, are we conducive to your learning style, and do we offer you the opportunity to achieve your goals and ambitions, while affording our school the opportunity to discern the compatibility of each candidate with our education philosophy and expectations. We want to discover who the candidate really is, and as such, rely on a detailed application package (including teacher and community referrals, school reports, a parent statement, and a candidate’s statement) and an interview rather than writing an SSAT or entrance test.</p>

		<p>Once a competed application is received, the Director of Admissions will contact the candidate’s parents via email or telephone to arrange an interview.</p>

	<h2>The Interview</h2>
		<p>The interview is at the heart of the evaluation process for all parties and will include both the candidate and the parents. The dialogue is a cooperative discussion where the candidate is able to communicate their aspirations, needs and wants, passions, talents, attitudes and values. The parents are able to ascertain the role our school will play in their child’s development including curriculum and support programs, and our school will evaluate the candidate’s academic motivation, personality, character, interests, and how he/she learns. Through the application and interview process we will endeavour to evaluate if the candidate and our school are a compatible fit.</p>

		<p>The goal of TPS is to find those students who best fit with our program and values. Students are not chosen merely by their academic qualifications, though these, of course, play an important part in any decision. We are looking for students who want to be a part of our dynamic community, who are concerned about the wider society and are willing to share their talents and improve their skills. We stress the importance of extra-curricular activities and place effort and character above all.</p>

	<h2>The Decision</h2>
		<p>Admissions decisions are made by the Admissions Committee. The committee is comprised of the Director of Admissions, the Principal, and three senior Faculty members. The Admissions Committee reviews all applications immediately following the interview process and determines if there is a need for further information or documentation. The Committee will make a final decision within three days of the completion of the interview process. Parents will be contacted via email and a letter will be mailed to inform the candidate whether they have been accepted, wait listed or declined.</p>

		<p>If a candidate is accepted, an acceptance offer and a registration package will be issued.</p>

		<p>If a candidate is wait listed, he or she will be notified only if space becomes available.</p>

		<p>If a candidate is declined, no further review will take place. The candidate is welcome to reapply for the following school year.</p>

	<h2>Registration</h2>
		<p>Acceptance of the offer is acknowledged by submitting the completed registration forms and the registration fee which guarantees your space at TPS. Upon completion of the registration process, a meeting will be scheduled for the student and parents with the Principal, Vice Principal, or Guidance Counsellor for course selection. A brief overview of the courses offered can be found on the course offering pages of this booklet.</p>

	<h1>Application Instructions</h1>
	<ul>
  		<li>The completed admission form must be accompanied by a recent photograph of the candidate, a copy of the candidate’s birth certificate or passport, final (June) report cards for the past two years plus the most recent report card.</li>
  		<li>Complete the candidate statement (must be completed by the candidate).</li>
  		<li>Complete the parent statement.</li>
  		<li>Include a copy of any educational/psychological assessment(s) completed for the candidate.</li>
  		<li>All applications must be accompanied by a $150 application fee.</li>
  		<li>Teacher referral form must be sealed and signed across the seal and mailed directly to Toronto Prep School in the enclosed envelope.</li>
  		<li>Community referrals must be sealed and signed across the seal and mailed directly to Toronto Prep School in the enclosed envelope.</li>
  		<li>Any other referrals or documentation you feel will assist the Admissions Committee in making a more informed decision can be forwarded directly to Toronto Prep School.</li>
	</ul>

	<br>

	<h1>Interview</h1>

	<ul>
		<li>Upon receipt of your application, the Director of Admissions will contact you within three (3) days via email and/or by telephone to set up an interview time.</li>
		<li>Interviews are intended to allow all parties to learn more about each other and to ask questions.</li>
		<li>The candidate will be interviewed with their parents and/or individually.</li>
		<li>Candidates and their parents can receive a tour of the school and associated facilities before or after the interview.</li>
	</ul>

	<br>

	<h1>Our Pledge to Privacy Protection</h1>
	<h2>Privacy of Personal Information</h2>
		<p>Toronto Prep School is committed to protecting the privacy of all members of its community.</p>

		<p>Toronto Prep School (TPS) uses the information collected during the admissions process to communicate with you and to identify and evaluate the candidate. If the candidate is granted admission to TPS, the contact information will be used to deliver services, to keep you updated on the activities of the our school, including programs, services, special events, opportunities to volunteer, and to keep you informed via newsletters and other publications.</p> 

		<p>If the candidate is wait listed or not admitted to TPS,the information will be retained only until the admission process is completed.</p>

		<p>All of the information will be stored in a confidential database and/or secure files stored at the School. Access to the information is restricted to authorized\ staff only. By providing the information you are consenting to the use of the information as described above.</p>

		<p>The Toronto Prep School is committed to maintaining the confidentiality, privacy, and security of your personal information. If you have any questions regarding this statement or other privacy concerns, please contact the Director of Admissions.</p>

		<br>

	<h1>Fee Schedule</h1>
	<h2>Tuition</h2>
		<p>The tuition fee for the upcoming academic year is included in this package for both full-time and part-time students. It covers participation in the comprehensive school programme which includes:</p>
		<ul>
			<li>The most recent MacBook laptop with education specific software package and extended warranty</li>
			<li>GoodLife Fitness membership</li>
			<li>School Yearbook</li>
			<li>Deluxe school photo package</li>
			<li>English journal</li>
			<li>Athletic associations fees</li>
			<li>Extended after school and Saturday Club study programs</li>
		</ul>

		<p>Exceptional instructional materials, overnight or day trips, gym uniforms, and other optional charges such as TPS store purchases are not included in the tuition fee and will be billed accordingly. Parents are also responsible for purchasing school supplies such as stationary and textbooks.</p>

		<p>Payment schedules for full-time, part-time, and single semester students as well as sibling discounts are included in this package and in the registration package. There are two payment plans for full-time students including a single payment discount plan, and a standard payment plan. Payment options include direct deposit, credit card or post-dated cheques.</p>

	<h2>Registration Fee</h2>
		<p>All new students are required to pay a non-refundable registration fee of $3,500 upon acceptance of the offer of admission. This fee will be applied to the year’s tuition.</p>

		<p>The remainder of the tuition is to be paid based on the agreed upon payment schedule selected by each family.</p>

	<h2>MacBook Laptops</h2>
		<p>All students will receive their MacBook laptop the last week of August. Toronto Prep School will provide basic technical support. With proper maintenance and care the laptop is expected to meet the student’s computer requirements until graduation.</p>

	<h2>Fundraising and Parent Support</h2>
		<p>There is no compulsory donation or capital fund payments. Rather all parents are encouraged to partner with us as volunteers and donors in support of our annual charity drive. Each year TPS and students will select a charity to support and participate in ongoing events throughout the school year. We appreciate any support parents wish to extend to altruistic endeavours.</p>

		<br>

	<p class="center"><input type="checkbox" name="checkbox" value="check" id="agree" /> I have read and understand the Toronto Prep School admission process </p><br>
	<button type="submit" class="instr">Continue</button>
</form>

</div>
</div>

	</div>
        <footer class="footer"><p class="information">2020 &#169 MSGBoard.org</p></footer>
    </div>
</body>
</html>

