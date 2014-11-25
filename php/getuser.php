<?php
require_once("utils/dbconnection.php");
require_once ('HTMLPurifier.standalone.php');
include('session.php');

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$searchBy=$_REQUEST["searchby"];
$key = $_REQUEST["key"];
$all = $_REQUEST["all"];

//fix xss
$key = $purifier->purify($key);
$all = $purifier->purify($all);
$searchBy = $purifier->purify($searchBy);

///some security checks!
$searchBy = stripslashes($searchBy);
$key = stripslashes($key);
$all = stripslashes($all);
$searchBy = mysql_real_escape_string($searchBy);
$key = mysql_real_escape_string($key);
$all = mysql_real_escape_string($all);
if($searchBy == 'acn') $searchBy ='a_number';
elseif($searchBy == 'name') $searchBy='u_name';
elseif($searchBy ='id') $searchBy ='u_id';
else {
	echo 'Error!';
	exit; 
}

if($all=='1')
 $key = "like '$key%'";
 else
 $key = " = '$key'";
 
$sql = "select u_id, u_name, u_email, a_number, a_balance from users inner join accounts on users.u_id = accounts.a_user
where $searchBy $key order by $searchBy";
//die($sql);		
$result = mysqli_query($connection,$sql);
$i =1;
$rows = array();
while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}
print json_encode($rows);

exit;
mysqli_close($connection);

?>


