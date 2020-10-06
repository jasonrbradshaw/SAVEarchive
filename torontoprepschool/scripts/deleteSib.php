<?php

include_once "../config.php";

$id = $_GET['id']; // get id through query string

$del = mysqli_query($conn,"DELETE FROM siblings where sibling_id = '$id'"); // delete query

if($del)
	{
	    mysqli_close($conn); // Close connection
	    header("location:../confirmation.php"); // redirects to all records page
	    exit;	
	}
	else
	{
	    echo "Error deleting record"; // display error message if not delete
	}
?>