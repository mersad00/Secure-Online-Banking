
<?php
session_start();// Starting Session
$uid = $_SESSION['login_id'];
$con=mysqli_connect("localhost","root","SecurePass!","banking");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con,"SELECT 
t_timestamp,t_amount,case  t_type when 0 then '+' when 1 then '-' end  as typet, 
afrom.a_name as from_account,
ato.a_name as to_account,t_description 
FROM transactions
inner join accounts as afrom on afrom.a_id = t_account_from inner join accounts as ato on ato.a_id = t_account_to
where afrom.a_id ='$uid' or ato.a_id='$uid' 
order by t_timestamp desc");
echo "<h1>Transaction history</h1>";
echo "<table border='1'>
<tr>
<th>#</th>
<th>Date</th>
<th>Amount</th>
<th>Type</th>
<th>From</th>
<th>To</th>
<th>Description</th>
</tr>";
$i =1;
while($row = mysqli_fetch_array($result)) {
  echo "<tr>";
  echo "<td>" . $i . "</td>";
  echo "<td>" . $row['t_timestamp'] . "</td>";
  echo "<td>" . $row['t_amount'] . "</td>";
  echo "<td>" . $row['typet'] . "</td>";
  echo "<td>" . $row['from_account'] . "</td>";
  echo "<td>" . $row['to_account'] . "</td>";
  echo "<td>" . $row['t_description'] . "</td>";
  echo "</tr>";
  $i=$i+1;
}

echo "</table>";

mysqli_close($con);
?>



