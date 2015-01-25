<?php
require_once ("utils/dbconnection.php");
require_once ('HTMLPurifier.standalone.php');

if (! isset ( $_SESSION )) {
	session_start (); // start session only if it is not already startedonnect
}

$config = HTMLPurifier_Config::createDefault ();
$purifier = new HTMLPurifier ( $config );

require_once ("utils/constants.php");
require_once 'session.php';

if (isset ( $_SESSION ['tError'] )) {
	$error = $_SESSION ['tError'];
} else {
	$error = ''; // Variable To Store Error Message
}
// Check if the token of the page and session match and only if yes, proceed

if (isset ( $_POST ['submit-transfer'] )) {
	if ($_POST ['user_token'] == $_SESSION ['user_token']) {
		if (empty ( $_POST ['amount'] ) || empty ( $_POST ['transaction_code'] ) || empty ( $_POST ['to_account'] )) {
			$error = "Input is invalid- empty";
		} else {
			
			$amount = $_POST ['amount'];
			if ($amount <= 0) {
				$error = 'Amount must be positive';
				return;
			}
			
			$transaction_code = $_POST ['transaction_code'];
			$account_to = $_POST ['to_account'];
			$details = $_POST ['details'];
			
			// fix xss
			$amount = $purifier->purify ( $amount );
			$transaction_code = $purifier->purify ( $transaction_code );
			$account_to = $purifier->purify ( $account_to );
			$details = $purifier->purify ( $details );
			
			// To protect MySQL injection for Security purpose
			$transaction_code = stripslashes ( $transaction_code );
			$account_to = stripslashes ( $account_to );
			$details = stripslashes ( $details );
			
			$transaction_code = mysql_real_escape_string ( $transaction_code );
			$account_to = mysql_real_escape_string ( $account_to );
			$details = mysql_real_escape_string ( $details );
			
			// check transaction code is valid
			$sql = "select tc_active from transaction_codes where tc_code='$transaction_code'";
			$SCSTan = false;
			$tacode = "";
			$query = mysqli_query ( $connection, $sql );
			$rows = mysqli_num_rows ( $query );
			if ($rows == 0) {
				// / check whether tan is coming from SCS
				if (! checkSCSTan ())
					return;
				$SCSTan = true;
				$tacode = $transaction_code;
				$transaction_code = "";
			} else if ($rows > 0) {
				//if tan is used we cannot use it again
				$row = mysqli_fetch_assoc ( $query );
				$active = $row ['tc_active'];
				if (! $active){
					$_SESSION ['tError'] = $error = "transaction code is not valid!";
					return;
				}
			}
			// /Check that account exists in db
			$sql = "select a_id from accounts where a_number='$account_to'";
			$query = mysqli_query ( $connection, $sql );
			$rows = mysqli_num_rows ( $query );
			if ($rows != 1) {
				$error = "Account number does not exist!";
			} else {
				$row = mysqli_fetch_assoc ( $query );
				$to_a_id = $row ['a_id'];
				$from_a_id = $_SESSION ['login_a_id'];
				$minus_ammount = - $amount;
				$confirmed = '1';
				if ($amount > 10000) {
					$confirmed = '0';
				}
				if ($to_a_id == $from_a_id) {
					die ( 'invalid parameters' );
				}
				
				// perform transaction
				$sql = "INSERT INTO transactions (t_account_from,t_account_to,t_amount,t_code,t_description,t_confirmed,t_acode)
				VALUES ('$from_a_id', '$to_a_id', '$minus_ammount','$transaction_code','$details' ,'$confirmed','$tacode')";
				
				$connection->autocommit ( FALSE ); // start transaction
				if (! mysqli_query ( $connection, $sql )) {
					$connection->rollback ();
				}
				$sql = "INSERT INTO transactions (t_account_from,t_account_to,t_amount,t_code,t_description,t_confirmed,t_acode)
				VALUES ('$to_a_id', '$from_a_id', '$amount','$transaction_code','$details' ,'$confirmed','$tacode')";
				if (! mysqli_query ( $connection, $sql )) {
					$connection->rollback ();
				}
				// /update balance
				$sql = "update accounts as a join
				(select t_account_from,sum(t_amount) as balance
				from transactions
				group by t_account_from,t_confirmed
				having t_confirmed=1) as t
				on a.a_id = t.t_account_from
				set a.a_balance = t.balance
				Where a.a_id ='$from_a_id' OR a.a_id='$to_a_id' ";
				
				// assume that there is unique combination of value, code, description
				$sql2 = "update accounts
				set a_balance = a_balance + (select t_amount from transactions
				where t_account_from='$from_a_id' and t_account_to='$to_a_id'
				and t_amount='$minus_ammount' and t_code='$transaction_code'
				and t_description='$details'
				and t_confirmed=1)
				where a_id='$from_a_id'";
				
				if (! mysqli_query ( $connection, $sql )) {
					$connection->rollback ();
				}
				
				if ($SCSTan) {
					// save the tan so it cannot be used more than once
					$sql = "INSERT INTO transaction_codes (tc_code, tc_account, tc_active) VALUES ('$tacode', '$from_a_id', '0' )";
					if (! mysqli_query ( $connection, $sql )) {
						$connection->rollback ();
					}
				} else {
					// /deactive tan
					$sql = "update transaction_codes set tc_active = '0' where tc_code ='$transaction_code'";
					if (! mysqli_query ( $connection, $sql )) {
						$connection->rollback ();
					}
				}
				$error = "Transaction has been executed successfully!";
				$_SESSION ['tError'] = $error;
				$connection->commit ();
				$connection->autocommit ( TRUE );
				
				header ( 'Location: profile.php' ); // Redirecting To Home Page
			}
			// mysqli_close($connection); // Closing Connection
			
			// }
		}
	} else {
		$error = RESUBMIT;
	}
}
function checkSCSTan() {
	global $connection, $error, $account_to, $amount, $transaction_code;
	// /check for SCS tan generator
	$uid = $_SESSION ['login_id'];
	$sql = "select u_akey from users where u_id='$uid'";
	$query = mysqli_query ( $connection, $sql );
	$result = mysqli_query ( $connection, $sql );
	if ($row = mysqli_fetch_array ( $result )) {
		$key = $row ['u_akey'];
	}
	if ($key == null) {
		$_SESSION ['tError'] = $error = "transaction code is not valid!";
		return false;
	}
	require_once 'crypto.php';
	$encryption = new MCrypt ( $key );
	$re = $encryption->decrypt ( $transaction_code );
	if ($re == "") {
		$_SESSION ['tError'] = $error = "transaction code is not valid!";
		return false;
	}
	$taninfo = explode ( ';', $re );
	if (isset ( $taninfo ) && is_array ( $taninfo )) {
		$tanAccount = $taninfo [0];
		$tanAmount = $taninfo [1];
		date_default_timezone_set ( 'UTC' );
		$date = new DateTime ( 'now' );
		$datetan=DateTime::createFromFormat('Y/m/d H:i:s a O',$taninfo [2]);
		//$datetan = new DateTime ( $taninfo [2] );
		$diff = date_diff ( $date, $datetan );
		$minutes = $diff->days * 24 * 60;
		$minutes += $diff->h * 60;
		$minutes += $diff->i;
		
		// /check whether current transaction comply the tan
		// /tan is only valid for 10 minutes
		if ($account_to != $tanAccount || $amount != $tanAmount || $minutes >= 10) {
			$_SESSION ['tError'] = $error = "transaction code is not valid!"; // ' amount/accout/ does not match with the input data";
			return false;
		}
		return true;
	}
	return false;
}
?>

