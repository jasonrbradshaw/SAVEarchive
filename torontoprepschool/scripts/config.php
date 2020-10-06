<?php
/* DB connection */
define('DB_SERVER', '127.0.0.1:3306');
define('DB_USERNAME', 'u368368182_TPSadmin');
define('DB_PASSWORD', 'TpS2020!1');
define('DB_NAME', 'u368368182_tps');
 
/* Attempt to connect to MySQL database */
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>