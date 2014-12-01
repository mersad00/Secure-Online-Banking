<?php
require_once('constants.php');
$database = parse_ini_file("db_config.ini");
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
if(!isset($connection)){
$connection = mysqli_connect($database['host'], $database['username'], $database['password'], $database['dbname']);
}
if(!$connection){
	die("Database Connection Failure ". mysqli_error($connection));
}

$mysqli = new mysqli($database['host'], $database['username'], $database['password'], $database['dbname']);
if ($mysqli->connect_errno) {
	die ("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}

?>