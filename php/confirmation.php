<?php
ini_set('display_errors', 'On');
require_once 'session.php';
require "utils/dbconnection.php";
$uid = $_SESSION['login_id'];
if (isset($_POST['submit'])  && isset($_POST['confirm'])) {
		
		foreach($_POST['confirm'] as $u) {
			confirm_transaction($u);
		}
		//header("Refresh:1");
}




$sql = "SELECT t_id,t_account_from,t_account_to,t_amount,t_timestamp
from transactions where t_confirmed=0";

$result = mysqli_query($connection,$sql);

echo "<h1>Transactions which need confirmation</h1>";
echo "<Form action=\"\" method=\"post\"><table class=\"table table-striped table-condensed\">
<tr>
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
echo " <div class=\"col-sm-offset-10 col-sm-2\"><input class=\"btn btn-custom btn-lg btn-block\" name=\"submit\" type=\"submit\" value=\" Confirm \"/>";
echo "</div></Form>";





function confirm_transaction($t_id){
	global $connection;
	$sql = "update transactions set t_confirmed =1 where t_id='$t_id'";
	if(!mysqli_query($connection,$sql)){
		die('Error confirming transaction: ' . mysqli_error($connection));
	}
	$sql = "update accounts inner join (select sum(t_amount) as val, t_account_from as id from transactions where t_confirmed =1 group by t_account_from ) as b on b.id = accounts.a_id  set a_balance =b.val";
	if(!mysqli_query($connection,$sql)){
		die('Error confirming transaction: ' . mysqli_error($connection));
	}
}


mysqli_close($connection);



?>

