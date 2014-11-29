<?php
ini_set('display_errors', 'On');
require_once 'session.php';
require_once 'utils/dbconnection.php';
$key="";
if (isset($_POST['generate']))
{
	require_once 'crypto.php';
	
	$key = getToken(16);
	$uid = $_SESSION['login_id'];
	$sql = "update users set u_akey='$key' where u_id='$uid'";
	if(!mysqli_query($connection,$sql)){
		die('Error storing SCS client key: ' . mysqli_error($connection));
		exit;
	}
}
else {
	///show current key
	$uid = $_SESSION['login_id'];
	$sql = "select u_akey from users where u_id='$uid'";
	$result = mysqli_query($connection,$sql);
	if($row = mysqli_fetch_array($result)){
		$key =$row['u_akey'];
	}
}

?>
<html>
<body>
<form method="POST">
<fieldset>
<legend>Generate SCS A-Key:</legend>
<input name="A-Key" type="text" value="<?php echo htmlentities($key) ?>"/><br>
<input name="generate" type="submit" value="Generate"/>
</fieldset>
</form>
</body>
</html>