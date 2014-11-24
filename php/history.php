
<?php require_once("utils/dbconnection.php"); ?>
<?php
include('session.php');

$uid=$_REQUEST["uid"];
$uid = stripslashes($uid);
$uid = mysql_real_escape_string($uid);

$sql = "SELECT 
t_timestamp,t_amount,case  t_confirmed when 0 then 'Not confimed' when 1 then 'Confirmed' end  as t_confirmation, 
afrom.a_name as from_account,
ato.a_name as to_account,t_description , afrom.a_balance
FROM transactions
inner join accounts as afrom on afrom.a_id = t_account_from 
inner join accounts as ato on ato.a_id = t_account_to
where afrom.a_user ='$uid'
order by t_timestamp desc";

$result = mysqli_query($connection,$sql);
$sqlBalance = "select balance 
from accounts as a join 
(select t_account_from, sum(t_amount) as balance from transactions  
group by t_account_from,t_confirmed
 having t_confirmed = 1) as t
  on a.a_id = t.t_account_from 
  where a.a_user='$uid'";
$reBalance = mysqli_query($connection,$sqlBalance);
if($reBalance === FALSE) {
    die(mysqli_error($connection)); // TODO: better error handling
}
$row = mysqli_fetch_assoc($reBalance);
echo "<h4 id=\"green\">Transaction history</h4>";
while($row = mysqli_fetch_array($reBalance))
{
    echo "Account balance:" .  $row['balance'];
}
echo "<table class=\"table table-striped table-condensed\">
<tr>
<th>Id</th>
<th>Date</th>
<th>Amount</th>
<th>From (Acc. name.)</th>
<th>To (Acc. name.)</th>
<th>Description</th>
<th>Confirmation status</th>
</tr>";
$i =1;
while($row = mysqli_fetch_array($result)) {
  echo "<tr>";
  echo "<td>" . $i . "</td>";
  echo "<td>" . $row['t_timestamp'] . "</td>";
  echo "<td>" . $row['t_amount'] . "</td>";
  echo "<td>" . $row['from_account'] . "</td>";
  echo "<td>" . $row['to_account'] . "</td>";
  echo "<td>" . $row['t_description'] . "</td>";
  echo "<td>" . $row['t_confirmation'] . "</td>";
  echo "</tr>";
  $i=$i+1;
}

echo "</table>";
mysqli_close($connection);
?>



