<?php

session_start();// Starting Session
$uid = $_SESSION['login_id'];
$con=mysqli_connect("localhost","root","SecurePass!","banking");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$sql = "SELECT t_id,t_account_from,t_account_to,t_amount,t_timestamp
from transactions where t_confirmed=0";

$result = mysqli_query($con,$sql);
echo "<h1>Transactions which need confirmation</h1>";
echo "<Form action=\"\" method=\"post\"><table border='1'>
<tr>
<th>#</th>
<th>Transaction Id</th>
<th>Account Id From</th>
<th>Account Id To</th>
<th>Amount</th>
<th>Timestamp</th>
<th>Confirm?</th>
</tr>";
$i =1;
while($row = mysqli_fetch_array($result)) {
  echo "<tr>";
  echo "<td>" . $i . "</td>";
  echo "<td>" . $row['t_id'] . "</td>";
  echo "<td>" . $row['t_account_from'] . "</td>";
  echo "<td>" . $row['t_account_to'] . "</td>";
  echo "<td>" . $row['t_amount'] . "</td>";
  echo "<td>" . $row['t_timestamp'] . "</td>";
  echo "<td><input type=\"checkbox\" name=\"confirm[]\" value=\"".$row['t_id']."\"/></td>";
  echo "</tr>";
  $i=$i+1;
}

echo "</table>";
echo "<input name=\"submit\" type=\"submit\" value=\" Submit \">";
echo "</Form>";



if (isset($_POST['submit'])  && isset($_POST['confirm'])) {
		
		foreach($_POST['confirm'] as $u) {
			confirm_transaction($u);
		}
		header("location: admin.php");
}

function confirm_transaction($t_id){
	global $con;
	$sql = "update transactions set t_confirmed =1 where t_id='$t_id'";
	if(!mysqli_query($con,$sql)){
		die('Error confirming transaction: ' . mysqli_error($con));
	}
}


mysqli_close($con);



?>

