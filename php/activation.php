<?php
include('email.php');
require_once('utils/dbconnection.php');
require_once 'session.php';


$activateErr='';
$uid = $_SESSION['login_id'];
$con=mysqli_connect("localhost","root","SecurePass!","banking");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$sql = "SELECT u_id,u_name,u_email,case u_type when 0 then 'Customer' when 1 then 'Employee' end as u_type_text
from users where u_active=0";

$result = mysqli_query($con,$sql);
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
while($row = mysqli_fetch_array($result)) {
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
echo '<br>';
echo "<div id=\"refreshNotActive\"><a  href=\"admin.php\">Refresh</a></div>" ;
echo " <div class=\"col-sm-offset-10 col-sm-2\"><input class=\"btn btn-custom btn-lg btn-block\" name=\"submit\" type=\"submit\" value=\"Process\">";
echo "</div></Form>";


$sql = "SELECT u_id,u_name,u_email,case u_type when 0 then 'Customer' when 1 then 'Employee' end as u_type_text
from users where u_active=1";

$result = mysqli_query($con,$sql);
echo "<h3 id=\"green\">Active Users</h3>";
echo "<form action=\"". "\" method=\"post\"><table class=\"table table-striped table-condensed\">
<tr>
<th>UserId</th>
<th>User name</th>
<th>Email</th>
<th>User Type</th>
<th>Delete?</th>
</tr>";
$i =1;
while($row = mysqli_fetch_array($result)) {
	echo "<tr id=\"" . $row['u_id'] ."\"\">";
	echo "<td>" . $row['u_id'] . "</td>";
	echo "<td>" . $row['u_name'] . "</td>";
	echo "<td>" . $row['u_email'] . "</td>";
	echo "<td>" . $row['u_type_text'] . "</td>";
	//echo "<td><input type=\"checkbox\" name=\"active[]\" value=\"".$row['u_id']."\"/></td>";
	echo "<td><input type=\"checkbox\" name=\"delete[]\" value=\"".$row['u_id']."\"/></td>";
	echo "</tr>";
	$i=$i+1;
}

echo "</table>";
echo $activateErr;
echo '<br>';
echo "<div id=\"refreshActive\"><a href=\"admin.php\">Refresh</a></div>" ;
echo " <div class=\"col-sm-offset-10 col-sm-2\"><input class=\"btn btn-custom btn-lg btn-block\" name=\"submit\" type=\"submit\" value=\"Process\">";
echo "</div></Form>";


mysqli_close($con);

if (isset($_POST['submit'])  && isset($_POST['active'])  && is_array($_POST['active'])) {	
		foreach($_POST['active'] as $u) {
			activate_user($u);
		}
		//header("location: admin.php");
		//exit;
}

if (isset($_POST['submit'])  && isset($_POST['delete'])  && is_array($_POST['delete'])) {
	foreach($_POST['delete'] as $u) {
		delete_user($u);
	}
	//header("location: admin.php");
	//exit;
}

function activate_user($user_id){
	global $activateErr;
	$conm=mysqli_connect("localhost","root","SecurePass!","banking");
	// Check connection
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	mysqli_autocommit($conm, false); //start transaction
	$sql = "update users set u_active =1 where u_id='$user_id'";
	if(!mysqli_query($conm,$sql)){
		die('Error activate user: ' . mysqli_error($con));
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


function delete_user($user_id){
	global $activateErr;
	$conm=mysqli_connect("localhost","root","SecurePass!","banking");
	// Check connection
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	//$conm->autocommit(FALSE); //start transaction
	$sql = "delete from users where u_id='$user_id'";
	try{
		if(!mysqli_query($conm,$sql)){
			die('Error activate user: ' . mysqli_error($con));
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
