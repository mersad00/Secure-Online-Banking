<?php
require('constants.php');

// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
if(!$connection){
	die("Database Connection Failure ". mysqli_error($connection));
}

$mysqli = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);
if ($mysqli->connect_errno) {
	die ("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}

?>