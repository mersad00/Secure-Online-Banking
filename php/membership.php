<?php
ini_set('display_errors', 'On');
$error=''; // Variable To Store Error Message
$success= false;
if (isset($_POST['submit'])) {
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['account'])) {
$error = "Input is invalid- empty";
}
else
{
// Define $username and $password
$username=$_POST['username'];
$password=$_POST['password'];
$email = $_POST['email'];
$account = $_POST['account'];

// To protect MySQL injection for Security purpose
$username = stripslashes($username);
$password = stripslashes($password);
$email = stripslashes($email);
$account = stripslashes($account);

$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);
$password = md5($password);
$email = mysql_real_escape_string($email);
$account = mysql_real_escape_string($account);

// Establishing Connection with Server by passing server_name, user_id and password as a parameter
//$connection = mysql_connect("localhost", "root", "SecurePass!");
$con=mysqli_connect("localhost","root","SecurePass!","banking");

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


///Check that user doen't exist in db
$sql="select u_id from users where u_name='$username' or u_email='$email'";
$query= mysqli_query($con,$sql);
$rows = mysqli_num_rows($query);
if($rows>0){
$error = "user exist with username or email";
}
else{
	
$sql="INSERT INTO users (u_name, u_email, u_password) VALUES ('$username', '$email', '$password' )";

if (!mysqli_query($con,$sql)) {
  die('Error: ' . mysqli_error($con));
}
$memberid = mysqli_insert_id;
$balance = "0";
///Insert account
	$sql ="insert into accounts (a_user,a_number,a_balance) values ('$memberid','$account','$balance')";
	if (!mysqli_query($con,$sql)) {
  die('Error: ' . mysqli_error($con));
}
mysqli_close($con); // Closing Connection
header('Location: index.php'); // Redirecting To Home Page
//}
}
}
}
?>
