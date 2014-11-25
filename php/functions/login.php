<?php require_once("utils/dbconnection.php");?>
<?php
 if(!isset($_SESSION)) 
    {        
	session_start(); //start session only if it is not already started
    }
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
$username = mysqli_real_escape_string($connection,$username);
$password = mysqli_real_escape_string($connection,$password);
$password = md5($password);
// SQL query to fetch information of registerd users and finds user match.
$query = mysqli_query($connection,"select u_name,u_active,u_id,accounts.a_id,
	accounts.a_number,accounts.a_name,u_type
	from users left outer join accounts on users.u_id = accounts.a_user
	where u_password='$password' AND u_name='$username'");
$rows = mysqli_num_rows($query);
if ($rows == 1) {
	if(mysqli_result($query,0,1)==0) {
		$error= "User is not activated yet, must wait for admin";
	}
	else{
		
		//avoid session fixation
		session_regenerate_id (true);
		
		$_SESSION['login_user']=$username; // Initializing Session
		$_SESSION['login_id']=mysqli_result($query,0,2);
		$_SESSION['login_a_id'] =mysqli_result($query,0,3); 
		$_SESSION['login_a_number'] =mysqli_result($query,0,4); 
		$_SESSION['login_a_name'] =mysqli_result($query,0,5);
		$_SESSION['login_user_type'] =mysqli_result($query,0,6);
		if($_SESSION['login_user_type']=='0'){
			header("location: profile.php"); // Redirecting To Other Page
		}elseif ($_SESSION['login_user_type']=='1'){
			header("location: admin.php");
		}
		
	}
} else {
$error = "Username or Password is invalid";
}
mysqli_close($connection); // Closing Connection
}
}
function mysqli_result($res,$row=0,$col=0){
	if ($row >= 0 && mysqli_num_rows($res) > $row){
		mysqli_data_seek($res,$row);
		$resrow = mysqli_fetch_row($res);
		if (isset($resrow[$col])){
			return $resrow[$col];
		}
	}
	return false;
}
?>
