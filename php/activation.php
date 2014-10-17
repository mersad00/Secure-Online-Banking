<?php

session_start();// Starting Session
$uid = $_SESSION['login_id'];
$con=mysqli_connect("localhost","root","SecurePass!","banking");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$sql = "SELECT u_id,u_name,u_email,case u_type when 0 then 'Customer' when 1 then 'Employee' end as u_type_text
from users where u_active=0";

$result = mysqli_query($con,$sql);
echo "<h1>Users who need activation</h1>";
echo "<Form action=\"\" method=\"post\"><table border='1'>
<tr>
<th>#</th>
<th>UserId</th>
<th>User name</th>
<th>Email</th>
<th>User Type</th>
<th>Activate?</th>
</tr>";
$i =1;
while($row = mysqli_fetch_array($result)) {
  echo "<tr>";
  echo "<td>" . $i . "</td>";
  echo "<td>" . "<input name=\"uid[]\" type=\"number\" size=\"5\" value=\"".$row['u_id']."\" readonly/>" . "</td>";
  echo "<td>" . $row['u_name'] . "</td>";
  echo "<td>" . $row['u_email'] . "</td>";
  echo "<td>" . $row['u_type_text'] . "</td>";
  echo "<td><input type=\"checkbox\" name=\"active[]\" value=\"".$row['u_id']."\"/></td>";
  echo "</tr>";
  $i=$i+1;
}

echo "</table>";
echo "<input name=\"submit\" type=\"submit\" value=\" Submit \">";
echo "</Form>";



if (isset($_POST['submit'])  && isset($_POST['active'])  && isset($_POST['uid']) && is_array($_POST['active'])) {
		
		foreach($_POST['active'] as $u) {
			activate_user($u);
		}
		header("location: admin.php");
}

function activate_user($user_id,$user_type){
	global $con;
	$sql = "update users set u_active =1 where u_id='$user_id'";
	if(!mysqli_query($con,$sql)){
		die('Error activate user: ' . mysqli_error($con));
		exit;
	}
	$sql = "select u_type from users where u_id='$user_id'";
	$result = mysqli_query($con,$sql);
	if($row = mysqli_fetch_array($result)){
		if($row['u_type'] == 0){
			require('email.php');
			sendTansMailToUser($user_id);
		}
	}
}


mysqli_close($con);



?>
