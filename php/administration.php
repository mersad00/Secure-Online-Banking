<?php
require_once("utils/dbconnection.php");
$uid = $_SESSION['login_id'];

//var_dump($uid); this is null!!

if (isset($_POST['userId']) && isset($_POST['newBalance']) && isset($_POST['accountNumber'])  ) {

	$userId = $_POST['userId'];
	$newBalance = $_POST['newBalance'];
	$accountNumber = $_POST['accountNumber'];
	
	$newBalance = mysql_real_escape_string($newBalance);
			
	if(!is_numeric ( $newBalance )){
		die( "Invalid balance entered");
	}
	if($newBalance < 0){
		die( "Invalid balance entered");
	}
	
	///update balance
	$sql = "update accounts set a_balance = ? where a_user = ? and a_number= ?";
	
	/* Prepared statement, stage 1: prepare */
	if (!($stmt = $mysqli->prepare($sql))) {
	    //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	    die( "Update balance failed");
	}
	echo $newBalance . " " . $userId . " ". $accountNumber;
	if(!$stmt->bind_param('dis', $newBalance,$userId,$accountNumber)){
		//echo "Binding 123 parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		die( "Update balance failed");
	}

	if (!$stmt->execute()) {
		//die( "Execute failed: (" . $stmt->errno . ") " . $stmt->error);
		die( "Update balance failed");
	}
	
	
}

?>
