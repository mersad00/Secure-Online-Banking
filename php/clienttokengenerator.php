<?php
require_once 'session.php';
require_once 'utils/dbconnection.php';
$key="";
if (isset($_POST['generatetoken']))
{
	///check client key exists
	$uid = $_SESSION['login_id'];
	$sql = "select u_akey from users where u_id='$uid'";
	$result = mysqli_query($connection,$sql);
	if($row = mysqli_fetch_array($result)){
		$key =$row['u_akey'];
	}
	else 
	{
		die("no Key has been generated! first generate A-Key (Activation key)!");
	}
	require_once 'crypto.php';
	$encryption = new MCrypt ( $key );
	$datevalid = new DateTime ( 'now' );
	$datevalid->add(date_interval_create_from_date_string('10 minutes'));
	$validuntil = $datevalid->format('Y/m/d H:i:s a');
	
	date_default_timezone_set ( 'UTC' );
	$date = new DateTime ( 'now' );
	$plaintext = $date->format('Y/m/d H:i:s a O');
	$token = $encryption->encrypt ( $plaintext );
	$_SESSION['client_token']=$token;
	$_SESSION['client_token_validation'] = $validuntil;
}
else {
	if(isset($_SESSION['client_token'])){
		$token = $_SESSION['client_token'];
		$validuntil = $_SESSION['client_token_validation'] ;
	}
	$token="";
	$validuntil ="";
}

?>
<section id="mytokenform">
<form method="POST">

<div class="form-group">
<div class="col-sm-7">
<textarea name="token" rows="8" cols="50" id="generatedtoken" class="form-control" onclick="this.focus();this.select()" readonly="readonly"><?php echo htmlentities($token) ?></textarea>
</div>
<br/>
</div>
<div class="form-group">
<div class="col-sm-5">
	<button type="submit" name= "generatetoken" class="btn btn-custom">
Get Token
</button>
 <span class="glyphicon glyphicon-info-sign text-warning"  data-toggle="tooltip" data-placement="right" title="Generate token needed for one transaction"></span>
</div>
</div>
</form>
 

<div class="col-sm-12">
<?php if($validuntil!=null) {?>
<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
 Valid until:<?php echo htmlentities($validuntil) ?>
</div>
<?php }?>
</div>
</section>
