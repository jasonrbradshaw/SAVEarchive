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

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
}

if(isset($_POST['submit'])) {
  
  // Teacher referral variables
  $teachFirst = mysqli_real_escape_string($conn,$_POST['teachFirst']);
  $teachLast = mysqli_real_escape_string($conn,$_POST['teachLast']);
  $teachSchool = mysqli_real_escape_string($conn,$_POST['teachSchool']);
  $tq1 = mysqli_real_escape_string($conn,$_POST['tq1']);
  $tq2 = mysqli_real_escape_string($conn,$_POST['tq2']);
  $tq3 = mysqli_real_escape_string($conn,$_POST['tq3']);
  $tq4 = mysqli_real_escape_string($conn,$_POST['tq4']);
  $tq5 = mysqli_real_escape_string($conn,$_POST['tq5']);
  $acper = mysqli_real_escape_string($conn,$_POST['acper']);
  $grpar = mysqli_real_escape_string($conn,$_POST['grpar']);
  $woralo = mysqli_real_escape_string($conn,$_POST['woralo']);
  $clabeh = mysqli_real_escape_string($conn,$_POST['clabeh']);
  $verski = mysqli_real_escape_string($conn,$_POST['verski']);
  $wricom = mysqli_real_escape_string($conn,$_POST['wricom']);
  $foldir = mysqli_real_escape_string($conn,$_POST['foldir']);
  $woreff = mysqli_real_escape_string($conn,$_POST['woreff']);
  $attspa = mysqli_real_escape_string($conn,$_POST['attspa']);
  $init = mysqli_real_escape_string($conn,$_POST['init']);
  $integ = mysqli_real_escape_string($conn,$_POST['integ']);
  $crithi = mysqli_real_escape_string($conn,$_POST['crithi']);
  $selcon = mysqli_real_escape_string($conn,$_POST['selcon']);
  $crea = mysqli_real_escape_string($conn,$_POST['crea']);
  $useof = mysqli_real_escape_string($conn,$_POST['useof']);
  $sport = mysqli_real_escape_string($conn,$_POST['sport']);
  $peerel = mysqli_real_escape_string($conn,$_POST['peerel']);
  $intcur = mysqli_real_escape_string($conn,$_POST['intcur']);
  $parcoo = mysqli_real_escape_string($conn,$_POST['parcoo']);
  $parexp = mysqli_real_escape_string($conn,$_POST['parexp']);
  $homcom = mysqli_real_escape_string($conn,$_POST['homcom']);
  $acapot = mysqli_real_escape_string($conn,$_POST['acapot']);

  // User Id acts as foreign key for all tables
  $user_id = $_SESSION["id"];


  // Prepare an insert statement for parentstmt table
    $sql = "INSERT INTO teacherref (id, teachFirst, teachLast, teachSchool, tq1, tq2, tq3, tq4, tq5, acper, grpar, woralo, clabeh, verski, wricom, foldir, woreff, attspa, init, integ, crithi, selcon, crea, useof, sport, peerel, intcur, parcoo, parexp, homcom, acapot) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE id=VALUES(id), teachFirst=VALUES(teachFirst), teachLast=VALUES(teachLast), teachSchool=VALUES(teachSchool), tq1=VALUES(tq1), tq2=VALUES(tq2), tq3=VALUES(tq3), tq4=VALUES(tq4), tq5=VALUES(tq5), acper=VALUES(acper), grpar=VALUES(grpar), woralo=VALUES(woralo), clabeh=VALUES(clabeh), verski=VALUES(verski), wricom=VALUES(wricom), foldir=VALUES(foldir), woreff=VALUES(woreff), attspa=VALUES(attspa), init=VALUES(init), integ=VALUES(integ), crithi=VALUES(crithi), selcon=VALUES(selcon), crea=VALUES(crea), useof=VALUES(useof), sport=VALUES(sport), peerel=VALUES(peerel), intcur=VALUES(intcur), parcoo=VALUES(parcoo), parexp=VALUES(parexp), homcom=VALUES(homcom), acapot=VALUES(acapot)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssssssssssssssssssssssssss", $user_id, $teachFirst, $teachLast, $teachSchool, $tq1, $tq2, $tq3, $tq4, $tq5, $acper, $grpar, $woralo, $clabeh, $verski, $wricom, $foldir, $woreff, $attspa, $init, $integ, $crithi, $selcon, $crea, $useof, $sport, $peerel, $intcur, $parcoo, $parexp, $homcom, $acapot);
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
            $subject = "New Teacher Referral for $can_first $can_last";

            $message = "
            <html>
            <head>
            <title>New Teacher Referral</title>
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
                            <p>$teachFirst $teachLast has submitted a new teacher referral for $can_first $can_last.</p>
                            
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
	<title>Teacher Referral</title>
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
	<h1>Teacher Referral</h1>
</div>

<div class="body">
	<form method="POST" action="<?php $_PHP_SELF ?>" enctype="multipart/form-data">
		<div class="container">
		<p class="center">The following named student is applying to Toronto Prep School and you have been selected to complete this confidential report. We ask you to do this so that we may better determine whether Toronto Prep School is suitable environment for this student. We are interested in the student’s academic potential, extracurricular activities and character.</p><br>

		<p class="center">If you have any questions on the completion of this form, please contact Fouli Tsimikalis in the Admissions Office.</p>
		<p class="center">Email: <a href="mailto:ftsimikalis@torontoprepschool.com">ftsimikalis@torontoprepschool.com</a></p>
		<p class="center">Phone: (416) 545 1020</p><br>

		First Name<br>
 	 	<input type="text" name="teachFirst"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        echo 'value="';
        echo stripcslashes(htmlspecialchars($row['teachFirst']));
        echo '"';
      }
    ?>
    required><br><br>

 	 	Last Name<br>
 	 	<input type="text" name="teachLast"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        echo 'value="';
        echo stripcslashes(htmlspecialchars($row['teachLast']));
        echo '"';
      }
    ?>
    required><br><br>

 	 	School Currently Teaching At<br>
 	 	<input type="text" name="teachSchool"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        echo 'value="';
        echo stripcslashes(htmlspecialchars($row['teachSchool']));
        echo '"';
      }
    ?>
    required><br><br>

		How long have you known the candidate and in what capacity? <br>
 	 	<textarea id="tq1" name="tq1" rows="10" maxlength="2000" required><?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        echo stripcslashes(htmlspecialchars($row['tq1']));
      }
    ?></textarea><br>
 	 	<div id="tcounter">Characters remaining (2000)</div><br>

 	 	In your opinion, what are the candidate’s two greatest strengths? <br>
 	 	<textarea id="tq2" name="tq2" rows="10" maxlength="2000" required><?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        echo stripcslashes(htmlspecialchars($row['tq2'])) ;
      }
    ?></textarea><br>
 	 	<div id="tcounter2">Characters remaining (2000)</div><br>
		
		In what area(s) should the candidate strive for improvement? <br>
 	 	<textarea id="tq3" name="tq3" rows="10" maxlength="2000" required><?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        echo stripcslashes(htmlspecialchars($row['tq3'])) ;
      }
    ?></textarea><br>
 	 	<div id="tcounter3">Characters remaining (2000)</div><br>

 	 	Has the candidate received any academic awards while at your school? If yes, please provide details. <br>
 	 	<textarea id="tq4" name="tq4" rows="10" maxlength="2000" required><?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        echo stripcslashes(htmlspecialchars($row['tq4'])) ;
      }
    ?></textarea><br>
 	 	<div id="tcounter4">Characters remaining (2000)</div><br>

 	 	We would appreciate any other comments that you feel will help portray an accurate assessment of this candidate. <br>
 	 	<textarea id="tq5" name="tq5" rows="10" maxlength="2000" required><?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        echo stripcslashes(htmlspecialchars($row['tq5'])) ;
      }
    ?></textarea><br>
 	 	<div id="tcounter5">Characters remaining (2000)</div><br>

 	 	<h2>Teacher Referral Checklist</h2>
 	 	<p class="center">Please select the box that reflects your knowledge of the candidate. If you have insufficient knowledge on a category, please select "Unsure".</p>

 	 	Academic Performance<br>
 	 	<input type="radio" name="acper" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['acper'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="acper" value="good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['acper'] == 'good'){
          echo "checked";
        }
      }
    ?>
      > Good<br>
  		<input type="radio" name="acper" value="satisfactory"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['acper'] == 'satisfactory'){
          echo "checked";
        }
      }
    ?>
      > Satisfactory<br>
  		<input type="radio" name="acper" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['acper'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="acper" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['acper'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Group Participation<br>
 	 	<input type="radio" name="grpar" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['grpar'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="grpar" value="good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['grpar'] == 'good'){
          echo "checked";
        }
      }
    ?>
      > Good<br>
  		<input type="radio" name="grpar" value="satisfactory"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['grpar'] == 'satisfactory'){
          echo "checked";
        }
      }
    ?>
      > Satisfactory<br>
  		<input type="radio" name="grpar" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['grpar'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="grpar" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['grpar'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Ability to Work Alone<br>
 	 	<input type="radio" name="woralo" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['woralo'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="woralo" value="good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['woralo'] == 'good'){
          echo "checked";
        }
      }
    ?>
      > Good<br>
  		<input type="radio" name="woralo" value="satisfactory"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['woralo'] == 'satisfactory'){
          echo "checked";
        }
      }
    ?>
      > Satisfactory<br>
  		<input type="radio" name="woralo" value="below par"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['woralo'] == 'below par'){
          echo "checked";
        }
      }
    ?>
      > Below Par <br>
  		<input type="radio" name="woralo" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['woralo'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Classroom Behaviour<br>
 	 	<input type="radio" name="clabeh" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['clabeh'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="clabeh" value="good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['clabeh'] == 'good'){
          echo "checked";
        }
      }
    ?>
      > Good<br>
  		<input type="radio" name="clabeh" value="satisfactory"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['clabeh'] == 'satisfactory'){
          echo "checked";
        }
      }
    ?>
      > Satisfactory<br>
  		<input type="radio" name="clabeh" value="disruptive"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['clabeh'] == 'disruptive'){
          echo "checked";
        }
      }
    ?>
      > Disruptive <br>
  		<input type="radio" name="clabeh" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['clabeh'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Verbal Skills<br>
 	 	<input type="radio" name="verski" value="excellent"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['verski'] == 'excellent'){
          echo "checked";
        }
      }
    ?>
    required> Excellent<br>
 	 	<input type="radio" name="verski" value="good"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['verski'] == 'good'){
          echo "checked";
        }
      }
    ?>
    > Good<br>
  		<input type="radio" name="verski" value="some difficulty"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['verski'] == 'some difficulty'){
          echo "checked";
        }
      }
    ?>
      > Some Difficulty<br>
  		<input type="radio" name="verski" value="disruptive"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['verski'] == 'disruptive'){
          echo "checked";
        }
      }
    ?>
      > Disruptive <br>
  		<input type="radio" name="verski" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['verski'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Written Communication<br>
 	 	<input type="radio" name="wricom" value="excellent"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['wricom'] == 'excellent'){
          echo "checked";
        }
      }
    ?>
    required> Excellent<br>
  		<input type="radio" name="wricom" value="some difficulty"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['wricom'] == 'some difficulty'){
          echo "checked";
        }
      }
    ?>
      > Some Difficulty<br>
  		<input type="radio" name="wricom" value="limited"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['wricom'] == 'limited'){
          echo "checked";
        }
      }
    ?>
      > Limited<br>
  		<input type="radio" name="wricom" value="poor"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['wricom'] == 'poor'){
          echo "checked";
        }
      }
    ?>
      > Poor <br>
  		<input type="radio" name="wricom" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['wricom'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Follows Directions<br>
 	 	<input type="radio" name="foldir" value="quickly and effectively"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['foldir'] == 'quickly and effectively'){
          echo "checked";
        }
      }
    ?>
    required> Quickly and Effectively<br>
  		<input type="radio" name="foldir" value="good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['foldir'] == 'good'){
          echo "checked";
        }
      }
    ?>
      > Good<br>
  		<input type="radio" name="foldir" value="needs help"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['foldir'] == 'needs help'){
          echo "checked";
        }
      }
    ?>
      > Needs Help<br>
  		<input type="radio" name="foldir" value="rarely"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['foldir'] == 'rarely'){
          echo "checked";
        }
      }
    ?>
      > Rarely <br>
  		<input type="radio" name="foldir" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['foldir'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Work Effort<br>
 	 	<input type="radio" name="woreff" value="maximum"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['woreff'] == 'maximum'){
          echo "checked";
        }
      }
    ?>
    required> Maximum<br>
  		<input type="radio" name="woreff" value="good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['woreff'] == 'good'){
          echo "checked";
        }
      }
    ?>
      > Good<br>
  		<input type="radio" name="woreff" value="sporadic"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['woreff'] == 'sporadic'){
          echo "checked";
        }
      }
    ?>
      > Sporadic<br>
  		<input type="radio" name="woreff" value="poor"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['woreff'] == 'poor'){
          echo "checked";
        }
      }
    ?>
      > Poor <br>
  		<input type="radio" name="woreff" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['woreff'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Attention Span<br>
 	 	<input type="radio" name="attspa" value="exceptionally good"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['attspa'] == 'exceptionally good'){
          echo "checked";
        }
      }
    ?>
    required> Exceptionally Good<br>
  		<input type="radio" name="attspa" value="good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['attspa'] == 'good'){
          echo "checked";
        }
      }
    ?>
      > Good<br>
  		<input type="radio" name="attspa" value="satisfactory"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['attspa'] == 'satisfactory'){
          echo "checked";
        }
      }
    ?>
      > Satisfactory<br>
  		<input type="radio" name="attspa" value="easily distracted"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['attspa'] == 'easily distracted'){
          echo "checked";
        }
      }
    ?>
      > Easily Distracted <br>
  		<input type="radio" name="attspa" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['attspa'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Initiative<br>
 	 	<input type="radio" name="init" value="often"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['init'] == 'often'){
          echo "checked";
        }
      }
    ?>
    required> Often<br>
  		<input type="radio" name="init" value="good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['init'] == 'good'){
          echo "checked";
        }
      }
    ?>
      > Good<br>
  		<input type="radio" name="init" value="rare"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['init'] == 'rare'){
          echo "checked";
        }
      }
    ?>
      > Rare<br>
  		<input type="radio" name="init" value="none"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['init'] == 'none'){
          echo "checked";
        }
      }
    ?>
      > None <br>
  		<input type="radio" name="init" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['init'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Integrity<br>
 	 	<input type="radio" name="integ" value="trustworthy"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['integ'] == 'trustworthy'){
          echo "checked";
        }
      }
    ?>
    required> Trustworthy<br>
  		<input type="radio" name="integ" value="reliable"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['integ'] == 'reliable'){
          echo "checked";
        }
      }
    ?>
      > Reliable<br>
  		<input type="radio" name="integ" value="unreliable"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['integ'] == 'unreliable'){
          echo "checked";
        }
      }
    ?>
      > Unreliable<br>
  		<input type="radio" name="integ" value="questionable"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['integ'] == 'questionable'){
          echo "checked";
        }
      }
    ?>
      > Questionable <br>
  		<input type="radio" name="integ" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['integ'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Critical Thinking<br>
 	 	<input type="radio" name="crithi" value="very perceptive"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['crithi'] == 'very perceptive'){
          echo "checked";
        }
      }
    ?>
    required> Very Perceptive<br>
  		<input type="radio" name="crithi" value="good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['crithi'] == 'good'){
          echo "checked";
        }
      }
    ?>
      > Good<br>
  		<input type="radio" name="crithi" value="fair"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['crithi'] == 'fair'){
          echo "checked";
        }
      }
    ?>
      > Fair<br>
  		<input type="radio" name="crithi" value="limited"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['crithi'] == 'limited'){
          echo "checked";
        }
      }
    ?>
      > Limited <br>
  		<input type="radio" name="crithi" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['crithi'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Self Confidence<br>
 	 	<input type="radio" name="selcon" value="positive"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['selcon'] == 'positive'){
          echo "checked";
        }
      }
    ?>
    required> Positive Self Image<br>
  		<input type="radio" name="selcon" value="good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['selcon'] == 'good'){
          echo "checked";
        }
      }
    ?>
      > Good<br>
  		<input type="radio" name="selcon" value="over confident"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['selcon'] == 'over confident'){
          echo "checked";
        }
      }
    ?>
      > Over Confident<br>
  		<input type="radio" name="selcon" value="limited"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['selcon'] == 'limited'){
          echo "checked";
        }
      }
    ?>
      > Limited <br>
  		<input type="radio" name="selcon" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['selcon'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Creativity<br>
 	 	<input type="radio" name="crea" value="very creative"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['crea'] == 'very creative'){
          echo "checked";
        }
      }
    ?>
    required> Very Creative<br>
  		<input type="radio" name="crea" value="good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['crea'] == 'good'){
          echo "checked";
        }
      }
    ?>
      > Good<br>
  		<input type="radio" name="crea" value="fair"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['crea'] == 'fair'){
          echo "checked";
        }
      }
    ?>
      > Fair<br>
  		<input type="radio" name="crea" value="limited"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['crea'] == 'limited'){
          echo "checked";
        }
      }
    ?>
      > Limited <br>
  		<input type="radio" name="crea" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['crea'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Use of Time<br>
 	 	<input type="radio" name="useof" value="effective"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['useof'] == 'effective'){
          echo "checked";
        }
      }
    ?>
    required> Effective<br>
  		<input type="radio" name="useof" value="good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['useof'] == 'good'){
          echo "checked";
        }
      }
    ?>
      > Good<br>
  		<input type="radio" name="useof" value="fair"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['useof'] == 'fair'){
          echo "checked";
        }
      }
    ?>
      > Fair<br>
  		<input type="radio" name="useof" value="poor"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['useof'] == 'poor'){
          echo "checked";
        }
      }
    ?>
      > Poor <br>
  		<input type="radio" name="useof" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['useof'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Sportsmanship<br>
 	 	<input type="radio" name="sport" value="outstanding"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['sport'] == 'outstanding'){
          echo "checked";
        }
      }
    ?>
    required> Outstanding<br>
  		<input type="radio" name="sport" value="good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['sport'] == 'good'){
          echo "checked";
        }
      }
    ?>
      > Good<br>
  		<input type="radio" name="sport" value="fair"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['sport'] == 'fair'){
          echo "checked";
        }
      }
    ?>
      > Fair<br>
  		<input type="radio" name="sport" value="poor"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['sport'] == 'poor'){
          echo "checked";
        }
      }
    ?>
      > Poor <br>
  		<input type="radio" name="sport" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['sport'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Peer Relationship<br>
 	 	<input type="radio" name="peerel" value="healthy"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['peerel'] == 'healthy'){
          echo "checked";
        }
      }
    ?>
    required> Healthy<br>
  		<input type="radio" name="peerel" value="relates well"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['peerel'] == 'relates well'){
          echo "checked";
        }
      }
    ?>
      > Relates Well<br>
  		<input type="radio" name="peerel" value="sporadic"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['peerel'] == 'sporadic'){
          echo "checked";
        }
      }
    ?>
      > Sporadic<br>
  		<input type="radio" name="peerel" value="poor"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['peerel'] == 'poor'){
          echo "checked";
        }
      }
    ?>
      > Poor <br>
  		<input type="radio" name="peerel" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['peerel'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Intellectual Curiosity<br>
 	 	<input type="radio" name="intcur" value="highly curious"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['intcur'] == 'highly curious'){
          echo "checked";
        }
      }
    ?>
    required> Highly Curious<br>
  		<input type="radio" name="intcur" value="active"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['intcur'] == 'active'){
          echo "checked";
        }
      }
    ?>
      > Active<br>
  		<input type="radio" name="intcur" value="fair"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['intcur'] == 'fair'){
          echo "checked";
        }
      }
    ?>
      > Fair<br>
  		<input type="radio" name="intcur" value="limited"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['intcur'] == 'limited'){
          echo "checked";
        }
      }
    ?>
      > Limited <br>
  		<input type="radio" name="intcur" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['intcur'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Parent Cooperation<br>
 	 	<input type="radio" name="parcoo" value="excellent"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['parcoo'] == 'excellent'){
          echo "checked";
        }
      }
    ?>
    required> Excellent<br>
  		<input type="radio" name="parcoo" value="good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['parcoo'] == 'good'){
          echo "checked";
        }
      }
    ?>
      > Good<br>
  		<input type="radio" name="parcoo" value="uncooperative"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['parcoo'] == 'uncooperative'){
          echo "checked";
        }
      }
    ?>
      > Uncooperative<br>
  		<input type="radio" name="parcoo" value="unknown"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['parcoo'] == 'unknown'){
          echo "checked";
        }
      }
    ?>
      > Unknown <br>
  		<input type="radio" name="parcoo" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['parcoo'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Parent Expectations<br>
 	 	<input type="radio" name="parexp" value="realistic"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['parexp'] == 'realistic'){
          echo "checked";
        }
      }
    ?>
    required> Realistic<br>
  		<input type="radio" name="parexp" value="low"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['parexp'] == 'low'){
          echo "checked";
        }
      }
    ?>
      > Low<br>
  		<input type="radio" name="parexp" value="unrealistic"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['parexp'] == 'unrealistic'){
          echo "checked";
        }
      }
    ?>
      > Unrealistic<br>
  		<input type="radio" name="parexp" value="unknown"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['parexp'] == 'unknown'){
          echo "checked";
        }
      }
    ?>
      > Unknown <br>
  		<input type="radio" name="parexp" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['parexp'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Homework Completion<br>
 	 	<input type="radio" name="homcom" value="always done"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['homcom'] == 'always done'){
          echo "checked";
        }
      }
    ?>
    required> Always Done<br>
  		<input type="radio" name="homcom" value="mostly done"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['homcom'] == 'mostly done'){
          echo "checked";
        }
      }
    ?>
      > Mostly Done<br>
  		<input type="radio" name="homcom" value="occasionally done"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['homcom'] == 'occasionally done'){
          echo "checked";
        }
      }
    ?>
      > Occasionally Done <br>
  		<input type="radio" name="homcom" value="rarely done"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['homcom'] == 'rarely done'){
          echo "checked";
        }
      }
    ?>
      > Rarely Done <br>
  		<input type="radio" name="homcom" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['homcom'] == 'unsure'){
          echo "checked";
        }
      }
    ?>
      > Unsure <br><br>

  		Academic Potential<br>
 	 	<input type="radio" name="acapot" value="excellent"
    <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['acapot'] == 'excellent'){
          echo "checked";
        }
      }
    ?>
    required> Excellent<br>
  		<input type="radio" name="acapot" value="very good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['acapot'] == 'very good'){
          echo "checked";
        }
      }
    ?>
      > Very Good<br>
  		<input type="radio" name="acapot" value="good"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['acapot'] == 'good'){
          echo "checked";
        }
      }
    ?>
      > Good<br>
  		<input type="radio" name="acapot" value="low"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['acapot'] == 'low'){
          echo "checked";
        }
      }
    ?>
      > Low <br>
  		<input type="radio" name="acapot" value="unsure"
      <?php 
      require_once "config.php";
      $user_id = $_SESSION["id"];
      $query = "SELECT * FROM teacherref WHERE id=$user_id";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_array($result)){
        if ($row['acapot'] == 'unsure'){
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