<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
if (empty($_POST['username']) || empty($_POST['password'])) {
$error = "Username or Password is invalid";
}
else
{
// Define $username and $password
$username=$_POST['username'];
$password=$_POST['password'];
// To protect MySQL injection for Security purpose
$username = stripslashes($username);
$password = stripslashes($password);
$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);
$password = md5($password);
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = mysql_connect("localhost", "root", "SecurePass!");
// Selecting Database
$db = mysql_select_db("banking", $connection);
// SQL query to fetch information of registerd users and finds user match.
$query = mysql_query("select u_name,u_active,u_id from users where u_password='$password' AND u_name='$username'", $connection);
$rows = mysql_num_rows($query);
if ($rows == 1) {
	if(mysql_result($query,0,1)==0) {
		$error= "User is not activated yet, must wait for admin" . mysql_result($query,0,1);
	}
	else{
		$_SESSION['login_user']=$username; // Initializing Session
		$_SESSION['login_id']=mysql_result($query,0,2);
		header("location: profile.php"); // Redirecting To Other Page
	}
} else {
$error = "Username or Password is invalid";
}
mysql_close($connection); // Closing Connection
}
}
?>
