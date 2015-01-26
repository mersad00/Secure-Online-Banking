<?php
include('email.php');
require_once('utils/dbconnection.php');
require_once 'session.php';


$activateErr='';
$uid = $_SESSION['login_id'];

// if activate/delete user form

if ((isset($_POST['submit-activate']) || isset($_POST['submit-delete'])) && (isset($_POST['active'])  || isset($_POST['delete']) || isset($_POST['deactive'])))
{
	if($_POST['user_token']==$_SESSION['user_token'])
	{
		// user activation form is posted
		if(isset($_POST['active'])  && is_array($_POST['active']) && isset($_POST['submit-activate']))
		{
			foreach($_POST['active'] as $u) {
				activate_user($u);
			}
		}
		// user deletion form is posted
		if (isset($_POST['delete'])  && is_array($_POST['delete']) && (isset($_POST['submit-activate']) || isset($_POST['submit-delete']))) 
		{
			foreach($_POST['delete'] as $u) 
			{
				delete_user($u);
			}
		}
		
		// user deactivate posted
		if (isset($_POST['deactive'])  && is_array($_POST['deactive']) && isset($_POST['submit-delete'])) 
		{
			foreach($_POST['deactive'] as $u) 
			{
				deactivate_user($u);
			}
		}
	}
	else 
	{
		$activateErr = INVALID_TOKEN;
	}
	// refresh the page afer post submission
	header("location: admin.php");
}
// form not submitted yet 
else {
// create unique token to avoid csrf
if(!isset($_SESSION['user_token'])){
$form_token = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1).substr(md5(time()),1);
// commit token to session
$_SESSION['user_token'] = $form_token;	
}
$con=mysqli_connect("localhost","root","SecurePass!","banking");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$select_notactive_users = "SELECT u_id,u_name,u_email,case u_type when 0 then 'Customer' when 1 then 'Employee' end as u_type_text
from users where u_active=0";

$result_notactive = mysqli_query($con,$select_notactive_users ); 
echo "<h3 id=\"green\">Not Activated Users</h3>"; 
echo "<form action=\"\" method=\"post\"><table class=\"table table-striped table-condensed\">
<tr>
<th>UserId</th>
<th>User name</th>
<th>Email</th>
<th>User Type</th>
<th>Activate?</th>
<th>Delete?</th>
</tr>";
$i =1;
while($row = mysqli_fetch_array($result_notactive)) {
  echo "<tr>";
  echo "<td>" . $row['u_id'] . "</td>";
  echo "<td>" . $row['u_name'] . "</td>";
  echo "<td>" . $row['u_email'] . "</td>";
  echo "<td>" . $row['u_type_text'] . "</td>";
  echo "<td><input type=\"checkbox\" name=\"active[]\" value=\"".$row['u_id']."\"/></td>";
  echo "<td><input type=\"checkbox\" name=\"delete[]\" value=\"".$row['u_id']."\"/></td>";
  echo "</tr>";
  $i=$i+1;
}

echo "</table>";
echo $activateErr;
echo "<br/>";
echo "<div class=\"col-sm-offset-10 col-sm-2\">";
echo "<input type=\"hidden\" name=\"user_token\" id =\"user_token\" value=\"".$_SESSION['user_token']."\" />";
echo "<input class=\"btn btn-custom btn-lg btn-block\" name=\"submit-activate\" type=\"submit\" value=\"Process\">";
echo "</div></Form>";


// select already active users
$select_active_users = "SELECT u_id,u_name,u_email,case u_type when 0 then 'Customer' when 1 then 'Employee' end as u_type_text
from users where u_active=1";

$result_active_users = mysqli_query($con,$select_active_users);
echo "<h3 id=\"green\">Active Users</h3>";
echo "<form action=\"". "\" method=\"post\" name=\"activate_user\" id=\"activate_user\"><table class=\"table table-striped table-condensed\">
<tr>
<th>UserId</th>
<th>User name</th>
<th>Email</th>
<th>User Type</th>
<th>Delete?</th>
<th>Deactivate?</th>
</tr>";
$i =1;
while($row = mysqli_fetch_array($result_active_users)) {
	echo "<tr id=\"" . $row['u_id'] ."\"\">";
	echo "<td>" . $row['u_id'] . "</td>";
	echo "<td>" . $row['u_name'] . "</td>";
	echo "<td>" . $row['u_email'] . "</td>";
	echo "<td>" . $row['u_type_text'] . "</td>";
	echo "<td><input type=\"checkbox\" name=\"delete[]\" value=\"".$row['u_id']."\"/></td>";
	echo "<td><input type=\"checkbox\" name=\"deactive[]\" value=\"".$row['u_id']."\"/></td>";
	echo "</tr>";
	$i=$i+1;
}

echo "</table>";
echo $activateErr;
echo '<br>';
echo "<div class=\"col-sm-offset-10 col-sm-2\">";
echo "<input type=\"hidden\" name=\"user_token\" id =\"user_token\" value=\"".$_SESSION['user_token']."\" />";
echo "<input class=\"btn btn-custom btn-lg btn-block\" name=\"submit-delete\" type=\"submit\" value=\"Process\">";
echo "</div></Form>";


mysqli_close($con);
 
}


function activate_user($user_id){
	global $activateErr;
	$conm=mysqli_connect("localhost","root","SecurePass!","banking");
	// Check connection
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	// To protect MySQL injection for Security purpose
	$user_id = stripslashes ( $user_id );
	$user_id = mysql_real_escape_string ( $user_id );
	
	mysqli_autocommit($conm, false); //start transaction
	$sql = "update users set u_active =1 where u_id='$user_id'";
	if(!mysqli_query($conm,$sql)){
		die('Error activate user: ');
		exit;
	}
	try{
		$sql = "select u_type from users where u_id='$user_id'";
		$result = mysqli_query($conm,$sql);
		$commit = TRUE;
		if($row = mysqli_fetch_array($result)){
			if($row['u_type'] == 0){
				if(!sendTansMailToUser($user_id, $conm)){
						die( 'Failed to send mail to user. Activation failed');
						mysqli_rollback($conm);
						$commit = FALSE;
				}
			}
		}
		if($commit){
			mysqli_commit($conm);
			mysqli_autocommit($conm, TRUE);
		} 
		mysqli_close($conm);
	}
	catch(Exception $e){
			mysqli_rollback($conm);
			echo 'Failed to activate user';
	}
}

function deactivate_user($user_id){
	global $activateErr;
	$conm=mysqli_connect("localhost","root","SecurePass!","banking");
	// Check connection
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	// To protect MySQL injection for Security purpose
	$user_id = stripslashes ( $user_id );
	$user_id = mysql_real_escape_string ( $user_id );
	$sql = "update users set u_active = 0 where u_id = '$user_id'";
		try{
		if(!mysqli_query($conm,$sql)){
			die('Error deactivate user: ');
			exit;
		}
	
		mysqli_close($conm);
	}
	catch(Exception $e){
		mysqli_rollback($conm);
		echo 'Failed to deactivate user';
	}
}


function delete_user($user_id){
	global $activateErr;
	$conm=mysqli_connect("localhost","root","SecurePass!","banking");
	// Check connection
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	// To protect MySQL injection for Security purpose
	$user_id = stripslashes ( $user_id );
	$user_id = mysql_real_escape_string ( $user_id );
	//$conm->autocommit(FALSE); //start transaction
	$sql = "delete from users where u_id='$user_id'";
	try{
		if(!mysqli_query($conm,$sql)){
			die('Error delete user: ');
			exit;
		}
	
		mysqli_close($conm);
	}
	catch(Exception $e){
		mysqli_rollback($conm);
		echo 'Failed to delete user';
	}
}


?>
