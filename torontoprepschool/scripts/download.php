<?php
include_once "../config.php";

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


// User application csv
$filename = $name[0] . $name[1] . "_application.csv";


$query = "SELECT * FROM application right JOIN reports ON application.`id` = reports.`id` WHERE application.id=$user_id";
$result = mysqli_query($conn, $query);
$user_arr = array();
	while($row = mysqli_fetch_array($result)){

		$can_surname = $row['can_surname'];
		$can_name = $row['can_name'];
		$can_prename = $row['can_prename'];
		$can_address = $row['can_address'];
		$can_homeph = $row['can_homeph'];
		$can_cellph = $row['can_cellph'];
		$can_birth = $row['can_birth'];
		$can_gender = $row['can_gender'];
		$can_image = $row['can_image'];
		$can_birthcert = $row['can_birthcert'];
		$can_entry = $row['can_entry'];
		$can_canres = $row['can_canres'];
		$can_cit = $row['can_cit'];
		$can_entrydate = $row['can_entrydate'];
		$can_lang = $row['can_lang'];
		$can_school = $row['can_school'];
		$report_name = $row['report_name'];

	    $user_arr[] = array($can_surname,$can_name,$can_prename,$can_address,$can_homeph,$can_cellph,$can_birth,$can_gender,$can_image,$can_birthcert,$can_entry,$can_canres,$can_cit,$can_entrydate,$can_lang,$can_school,$report_name);
	}

// file creation
$file = fopen($filename,"w");

// CSV headers
$csv_fields=array();

$csv_fields[] = 'Candidate Surname';
$csv_fields[] = 'Candidate Given Name(s)';
$csv_fields[] = 'Candidate Preferred Name';
$csv_fields[] = 'Candidate Address';
$csv_fields[] = 'Candidate Home Phone';
$csv_fields[] = 'Candidate Cell Phone';
$csv_fields[] = 'Candidate Birth Date';
$csv_fields[] = 'Candidate Gender';
$csv_fields[] = 'Candidate Image File';
$csv_fields[] = 'Candidate Identification File';
$csv_fields[] = 'Candidate School Entry Date';
$csv_fields[] = 'Candidate Canadian Resident';
$csv_fields[] = 'Candidate Citizenship';
$csv_fields[] = 'Candidate Entry Date into Canada';
$csv_fields[] = 'Candidate Native Language';
$csv_fields[] = 'Candidate Current School';
$csv_fields[] = 'Report Card File Name';


fputcsv($file, $csv_fields);

// Append data
foreach ($user_arr as $line){
	fputcsv($file, $line);
} 

fclose($file);

// download
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=".$filename);
header("Content-Type: application/csv; "); 

readfile($filename);

// deleting file
unlink($filename);
exit();


?>