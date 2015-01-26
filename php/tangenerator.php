<?php
include("crypto.php");

function generateTans($user_id, $account_id,$nums,$con)
{
	// To protect MySQL injection for Security purpose
	$user_id = stripslashes ( $user_id );
	$account_id = stripslashes ( $account_id );
	$nums = stripslashes ( $nums );
		
	$user_id = mysql_real_escape_string ( $user_id );
	$account_id = mysql_real_escape_string ( $account_id );
	$nums = mysql_real_escape_string ( $nums );
	
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
    for ($x=0; $x<$nums; $x++) {
		$r = rand(1000,9999999);
		$val = $user_id . '-' . $account_id . $r;
		$tan = "F" . fnEncrypt($val);
		
		$sql="INSERT INTO transaction_codes (tc_code, tc_account, tc_active) VALUES ('$tan', '$account_id', '1' )";
		if (!mysqli_query($con,$sql)) {
			$con->rollback();
			die('Error');
		}
	}
}


?>
