
<?php
session_start();// Starting Session

$con=mysqli_connect("localhost","root","SecurePass!","G16Bank");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con,"SELECT username,email FROM members");
echo "<h1>Transaction history</h1>";
echo "<table border='1'>
<tr>
<th>#</th>
<th>Firstname</th>
<th>Lastname</th>
</tr>";
$i =1;
while($row = mysqli_fetch_array($result)) {
  echo "<tr>";
  echo "<td>" . $i . "</td>";
  echo "<td>" . $row['username'] . "</td>";
  echo "<td>" . $row['email'] . "</td>";
  echo "</tr>";
  $i=$i+1;
}

echo "</table>";

mysqli_close($con);
?>



