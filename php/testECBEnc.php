<?php
ini_set('display_errors', 'On');
require "crypto.php";

$encryption = new MCrypt('PnpklZ03bY1z7js4');
//echo $encryption->encrypt('123456') . "<br/>";
$re = $encryption->decrypt('dUP0l/4FbXRuzAPqxq619g==');
if(isset($re) && $re!=""){
	die('here set');
	echo $re;
}
else {
	echo 'fail';
}
?>