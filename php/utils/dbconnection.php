<?php
require_once('constants.php');
$host = "localhost";
$username = "root";
$password = "SecurePass!";
$dbname = "banking";
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
if(!isset($connection)){
$connection = mysqli_connect($host, $username, $password, $dbname);
}
if(!$connection){
	die("Database Connection Failure ");
}

$mysqli = new mysqli($host, $username, $password, $dbname);
if ($mysqli->connect_errno) {
	die ("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}

?>
