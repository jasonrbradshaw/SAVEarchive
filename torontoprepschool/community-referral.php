<?php
// creatialize the session
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
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
}

if(isset($_POST['submit'])) {
  
  // Teacher referral variables
  $commFirst = mysqli_real_escape_string($conn,$_POST['commFirst']);
  $commLast = mysqli_real_escape_string($conn,$_POST['commLast']);
  $years = mysqli_real_escape_string($conn,$_POST['years']);
  $cq1 = mysqli_real_escape_string($conn,$_POST['cq1']);
  $cq2 = mysqli_real_escape_string($conn,$_POST['cq2']);
  $cq3 = mysqli_real_escape_string($conn,$_POST['cq3']);
  $atten = mysqli_real_escape_string($conn,$_POST['atten']);
  $depen = mysqli_real_escape_string($conn,$_POST['depen']);
  $compa = mysqli_real_escape_string($conn,$_POST['compa']);
  $hone = mysqli_real_escape_string($conn,$_POST['hone']);
  $resp = mysqli_real_escape_string($conn,$_POST['resp']);
  $respon = mysqli_real_escape_string($conn,$_POST['respon']);
  $foldire = mysqli_real_escape_string($conn,$_POST['foldire']);
  $coop = mysqli_real_escape_string($conn,$_POST['coop']);
  $aenadv = mysqli_real_escape_string($conn,$_POST['aenadv']);
  $woreth = mysqli_real_escape_string($conn,$_POST['woreth']);
  $creat = mysqli_real_escape_string($conn,$_POST['creat']);
  $leader = mysqli_real_escape_string($conn,$_POST['leader']);
  $selfcon = mysqli_real_escape_string($conn,$_POST['selfcon']);
  $humo = mysqli_real_escape_string($conn,$_POST['humo']);
  $socmat = mysqli_real_escape_string($conn,$_POST['socmat']);
  $sports = mysqli_real_escape_string($conn,$_POST['sports']);
  $peerrel = mysqli_real_escape_string($conn,$_POST['peerrel']);
  $tenac = mysqli_real_escape_string($conn,$_POST['tenac']);
  $atti = mysqli_real_escape_string($conn,$_POST['atti']);
  $ambi = mysqli_real_escape_string($conn,$_POST['ambi']);
  $coach = mysqli_real_escape_string($conn,$_POST['coach']);

  // User Id acts as foreign key for all tables
  $user_id = $_SESSION["id"];


  // Prepare an insert statement for parentstmt table
    $sql = "INSERT INTO communityref (id, commFirst, commLast, years, cq1, cq2, cq3, atten, depen, compa, hone, resp, respon, foldire, coop, aenadv, woreth, creat, leader, selfcon, humo, socmat, sports, peerrel, tenac, atti, ambi, coach) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE id=VALUES(id), commFirst=VALUES(commFirst), commLast=VALUES(commLast), years=VALUES(years), cq1=VALUES(cq1), cq2=VALUES(cq2), cq3=VALUES(cq3), atten=VALUES(atten), depen=VALUES(depen), compa=VALUES(compa), hone=VALUES(hone), resp=VALUES(resp), respon=VALUES(respon), foldire=VALUES(foldire), coop=VALUES(coop), aenadv=VALUES(aenadv), woreth=VALUES(woreth), creat=VALUES(creat), leader=VALUES(leader), selfcon=VALUES(selfcon), humo=VALUES(humo), socmat=VALUES(socmat), sports=VALUES(sports), peerrel=VALUES(peerrel), tenac=VALUES(tenac), atti=VALUES(atti), ambi=VALUES(ambi), coach=VALUES(coach)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssssssssssssssssssss", $user_id, $commFirst, $commLast, $years, $cq1, $cq2, $cq3, $atten, $depen, $compa, $hone, $resp, $respon, $foldire, $coop, $aenadv, $woreth, $creat, $leader, $selfcon, $humo, $socmat, $sports, $peerrel, $tenac, $atti, $ambi, $coach);
        // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: referral-confirmation.html");
            } else{
              // Populate user error message here when done
                die('Could not enter data: ' . mysqli_error($conn));
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
        }

// Send confirmation email to webmaster
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
            $subject = "New Community Referral for $can_first $can_last";

            $message = "
            <html>
            <head>
            <title>New Community Referral</title>
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
                            <p>$commFirst $commLast has submitted a new community referral for $can_first $can_last.</p>
                            
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

            mail($to,$subject,$message,$headers);

mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Community Referral</title>
	<link rel="stylesheet" href="style.css">
	<link rel="icon" href="tps_favicon.png">
	<script src="scripts/jquery.min.js"></script>
	<script src="scripts/max_char.js"></script>
	<meta name="viewport" content="width=device-width, creatial-scale=1">
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
	<h1>Community Referral</h1>
</div>

<div class="body">
	<form method="POST" action="<?php $_PHP_SELF ?>" enctype="multipart/form-data">
		<div class="container">
		<p class="center">The below named student is applying to to Toronto Prep School and you have been selected to complete this confidential report. We ask you to do this so that we may better determine whether we are a suitable environment for the candidate. We are interested in the student’s character, work ethic and extracurricular activities. </p><br>

		<p class="center">If you have any questions on the completion of this form, please contact Fouli Tsimikalis in the Admissions Office.</p>
		<p class="center">Email: <a href="mailto:ftsimikalis@torontoprepschool.com">ftsimikalis@torontoprepschool.com</a></p>
		<p class="center">Phone: (416) 545 1020</p><br>

		First Name<br>
 	 	<input type="text" name="commFirst"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        echo 'value="';
        echo stripcslashes(htmlspecialchars($row['commFirst']));
        echo '"';
      }
    ?>
    required><br><br>

 	 	Last Name<br>
 	 	<input type="text" name="commLast"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        echo 'value="';
        echo stripcslashes(htmlspecialchars($row['commLast']));
        echo '"';
      }
    ?>
    required><br><br>

 	 	How many years have you known the candidate?<br>
 	 	<input type="text" name="years"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        echo 'value="';
        echo stripcslashes(htmlspecialchars($row['years']));
        echo '"';
      }
    ?>
    required><br><br>
		
		In what capacity have you known the candidate?<br>
 	 	<textarea id="cq1" name="cq1" rows="10" maxlength="2000" required><?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        echo stripcslashes(htmlspecialchars($row['cq1']));
      }
    ?></textarea><br>
 	 	<div id="ccounter">Characters remaining (2000)</div><br>

 	 	In your opinion, what are the candidate’s strengths?<br>
 	 	<textarea id="cq2" name="cq2" rows="10" maxlength="2000" required><?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        echo stripcslashes(htmlspecialchars($row['cq2']));
      }
    ?></textarea><br>
 	 	<div id="ccounter2">Characters remaining (2000)</div><br>

 	 	We would appreciate any comments that you feel will help portray an accurate assessment of this candidate.<br>
 	 	<textarea id="cq3" name="cq3" rows="10" maxlength="2000" required><?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        echo stripcslashes(htmlspecialchars($row['cq3']));
      }
    ?></textarea><br>
 	 	<div id="ccounter3">Characters remaining (2000)</div><br>

 	 	<h2>Community Referral Checklist</h2>
 	 	<p class="center">Please select the box that reflects your knowledge of the candidate. If you have insufficient knowledge on a category, please select "Unsure".</p>
		
		Attentiveness<br>
 	 	<input type="radio" name="atten" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['atten'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="atten" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['atten'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="atten" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['atten'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="atten" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['atten'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="atten" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['atten'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Dependability<br>
 	 	<input type="radio" name="depen" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['depen'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="depen" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['depen'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="depen" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['depen'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="depen" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['depen'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="depen" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['depen'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Compassion<br>
 	 	<input type="radio" name="compa" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['compa'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="compa" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['compa'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="compa" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['compa'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="compa" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['compa'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="compa" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['compa'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Honesty<br>
 	 	<input type="radio" name="hone" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['hone'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="hone" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['hone'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="hone" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['hone'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="hone" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['hone'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="hone" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['hone'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Respect<br>
 	 	<input type="radio" name="resp" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['resp'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="resp" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['resp'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="resp" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['resp'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="resp" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['resp'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="resp" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['resp'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Responsibility<br>
 	 	<input type="radio" name="respon" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['respon'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="respon" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['respon'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="respon" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['respon'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="respon" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['respon'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="respon" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['respon'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Follows Directions<br>
 	 	<input type="radio" name="foldire" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['foldire'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="foldire" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['foldire'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="foldire" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['foldire'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="foldire" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['foldire'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="foldire" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['foldire'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Cooperation<br>
 	 	<input type="radio" name="coop" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['coop'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="coop" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['coop'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="coop" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['coop'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="coop" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['coop'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="coop" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['coop'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Sense of Adventure<br>
 	 	<input type="radio" name="aenadv" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['aenadv'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="aenadv" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['aenadv'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="aenadv" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['aenadv'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="aenadv" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['aenadv'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="aenadv" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['aenadv'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Work Ethic<br>
 	 	<input type="radio" name="woreth" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['woreth'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="woreth" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['woreth'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="woreth" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['woreth'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="woreth" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['woreth'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="woreth" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['woreth'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Creativity<br>
 	 	<input type="radio" name="creat" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['creat'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="creat" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['creat'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="creat" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['creat'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="creat" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['creat'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="creat" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['creat'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Leaderership<br>
 	 	<input type="radio" name="leader" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['leader'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="leader" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['leader'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="leader" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['leader'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="leader" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['leader'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="leader" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['leader'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Self Confidence<br>
 	 	<input type="radio" name="selfcon" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['selfcon'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="selfcon" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['selfcon'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="selfcon" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['selfcon'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="selfcon" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['selfcon'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="selfcon" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['selfcon'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Humour<br>
 	 	<input type="radio" name="humo" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['humo'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="humo" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['humo'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="humo" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['humo'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="humo" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['humo'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="humo" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['humo'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Social Maturity<br>
 	 	<input type="radio" name="socmat" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['socmat'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="socmat" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['socmat'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="socmat" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['socmat'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="socmat" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['socmat'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="socmat" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['socmat'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Sportsmanship<br>
 	 	<input type="radio" name="sports" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['sports'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="sports" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['sports'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="sports" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['sports'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="sports" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['sports'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="sports" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['sports'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Peer Relationship<br>
 	 	<input type="radio" name="peerrel" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['peerrel'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="peerrel" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['peerrel'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="peerrel" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['peerrel'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="peerrel" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['peerrel'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="peerrel" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['peerrel'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Tenacity<br>
 	 	<input type="radio" name="tenac" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['tenac'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="tenac" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['tenac'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="tenac" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['tenac'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="tenac" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['tenac'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="tenac" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['tenac'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Attitude<br>
 	 	<input type="radio" name="atti" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['atti'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="atti" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['atti'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="atti" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['atti'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="atti" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['atti'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="atti" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['atti'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Ambition<br>
 	 	<input type="radio" name="ambi" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['ambi'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="ambi" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['ambi'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="ambi" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['ambi'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="ambi" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['ambi'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="ambi" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['ambi'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Coachable<br>
 	 	<input type="radio" name="coach" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['coach'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="coach" value="above average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['coach'] == 'above average'){
          echo "checked";
        }
      }
    ?>
      > Above Average<br>
  		<input type="radio" name="coach" value="average"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['coach'] == 'average'){
          echo "checked";
        }
      }
    ?>
      > Average<br>
  		<input type="radio" name="coach" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['coach'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="coach" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM communityref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['coach'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

		<button type="submit" class="submit" name="submit">Submit</button>
	</div>
	</form>
</div>

  </div>
        <footer class="footer"><p class="information">2020 &#169 MSGBoard.org</p></footer>
    </div>

</body>
</html>