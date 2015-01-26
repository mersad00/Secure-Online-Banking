<?php
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
		die('Error storing SCS client key');
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
<section id="myform">
<form method="POST">

<div class="form-group">
<div class="col-sm-7">
	
<input name="A-Key" type="text" id="key" class="form-control" onclick="this.focus();this.select()" readonly="readonly" value="<?php echo htmlentities($key) ?>"/>
</div>
</div>
<div class="form-group">
<div class="col-sm-5">
<button type="submit" name= "generate" class="btn btn-custom">
<span class="glyphicon glyphicon-lock"></span>&nbsp;Get SCS Key
</button>
</div>
</div>
</form>
</section>
