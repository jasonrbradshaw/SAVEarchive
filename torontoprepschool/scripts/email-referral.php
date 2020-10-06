<?php

// Email submission
if(isset($_POST['teach-submit'])){
    $to = $_POST['teachEmail']; // Teacher's email
    $from = "jbradshaw@torontoprepschool.com"; // this is your Email address
    $first_name = $_POST['teachFirst'];
    $last_name = $_POST['teachLast'];
    $subject = $_POST['Please complete the following referral for Toronto Prep School'];
    $subject2 = "Copy of your form submission";
    $message = "Hello, " . $first_name . " " . $last_name . ", \n\n A former student of yours has requested that you complete a referral needed for their acceptance at Toronto Prep School. Please click the following link to complete a short questionaire. http://localhost/msgboard.org/tps/teacher-referral.php";
    $message2 = $first_name . " " . $last_name . " successfully received your referral request.";

    $headers = "From:" . $from;
    $headers2 = "From:" . $to;
    mail($to,$subject,$message,$headers);
    mail($from,$subject2,$message2,$headers2); // sends a copy of the message to the sender
    header('Location: confirmation.php'); 
    exit;
    }
?>