<?php
require_once("utils/dbconnection.php");
$uid = $_SESSION['login_id'];

//var_dump($uid); this is null!!

if (isset($_POST['userId']) && isset($_POST['newBalance']) && isset($_POST['accountNumber'])  ) {

	$userId = $_POST['userId'];
	$newBalance = $_POST['newBalance'];
	$accountNumber = $_POST['accountNumber'];
	
	$newBalance = mysql_real_escape_string($newBalance);
	$accountNumber = mysql_real_escape_string($accountNumber);
	
	if(!is_numeric ( $newBalance )){
		die( "Invalid balance entered");
	}
	if($newBalance < 0){
		die( "Invalid balance entered");
	}
	
	///update balance
	$sql = "update accounts set a_balance = ? where a_user = ? and a_number= ?";
	
	//33 is admin account id
	$sql ="INSERT INTO transactions (t_account_to,t_account_from,t_amount,t_code,t_description,t_confirmed)
		   VALUES ( 33, (select a_id from accounts where a_number = ?), ? - (select a_balance from accounts where a_number = ?) , ?, ? , 1)";
	
	/* Prepared statement, stage 1: prepare */
	if (!($stmt = $mysqli->prepare($sql))) {
	    //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	    die( "Update1 balance failed");
	}
	//
	$description = "admin reset balance with " . $newBalance ;
	$adminTan = 'admin0000000000';
	
	if(!$stmt->bind_param('sisss', $accountNumber, $newBalance, $accountNumber, $adminTan, $description)){
		//echo "Binding 123 parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		die( "Update2 balance failed");
	}

	if (!$stmt->execute()) {
		//die( "Execute failed: (" . $stmt->errno . ") " . $stmt->error);
		die( "Update3 balance failed");
	}
	
	///update balance
	$sql = "update accounts as a join
	(select t_account_from,sum(t_amount) as balance, t_code
	from transactions
	group by t_account_from,t_confirmed
	having t_confirmed=1) as t
	on a.a_id = t.t_account_from
	set a.a_balance = t.balance
	Where a.a_number = ? OR a.a_id=33 ";
	
	/* Prepared statement, stage 1: prepare */
	if (!($stmt2 = $mysqli->prepare($sql))) {
	    //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	    die( "Update4 balance failed");
	}
	
	if(!$stmt2->bind_param('s', $accountNumber)){
		//echo "Binding 123 parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		die( "Update5 balance failed");
	}
	
	if (!$stmt2->execute()) {
		//die( "Execute failed: (" . $stmt->errno . ") " . $stmt->error);
		die( "Update6 balance failed");
	}
}

?>
