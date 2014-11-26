<?php
require_once("utils/dbconnection.php");
require_once 'session.php';
ini_set('display_errors', 'On');

if(isset($_SESSION['tError'])){
	$error = $_SESSION['tError'];
}
else{
	$error=''; // Variable To Store Error Message
}
if (isset($_POST['submit'])) {
	if (empty($_POST['amount']) || empty($_POST['transaction_code']) || empty($_POST['to_account'])) {
		$error = "Input is invalid- empty";
	}
	else
	{
		$amount=$_POST['amount'];
		if($amount<=0) {
			$error = 'Amount must be positive';
			return;
		}
		
		$transaction_code=$_POST['transaction_code'];
		$account_to = $_POST['to_account'];
		$details = $_POST['details'];
		// To protect MySQL injection for Security purpose
		$transaction_code = stripslashes($transaction_code);
		$account_to = stripslashes($account_to);
		$details = stripslashes($details);


		$transaction_code = mysql_real_escape_string($transaction_code);
		$account_to = mysql_real_escape_string($account_to);
		$details = mysql_real_escape_string($details);

		//check transaction code is valid
		$sql ="select tc_active from transaction_codes where tc_code='$transaction_code' and tc_active = '1'";
		
		
		$query= mysqli_query($connection,$sql);
		$rows = mysqli_num_rows($query);
		if($rows == 0){
			$error = "transaction code is not valid!";
		}
		else
		{
			///Check that account exists in db
			$sql="select a_id from accounts where a_number='$account_to'";
			$query= mysqli_query($connection,$sql);
			$rows = mysqli_num_rows($query);
			if($rows!=1){
				$error = "Account number does not exist!";
			}
			else{
				$row = mysqli_fetch_assoc($query);
				$to_a_id = $row['a_id'];
				$from_a_id = $_SESSION['login_a_id'];
				$minus_ammount = -$amount;
				$confirmed = '1';
				if($amount>10000){
					$confirmed ='0';
				}
				if($to_a_id == $from_a_id){
					die('invalid parameters');
				}
				
				//perform transaction	
				$sql="INSERT INTO transactions (t_account_from,t_account_to,t_amount,t_code,t_description,t_confirmed)
				 VALUES ('$from_a_id', '$to_a_id', '$minus_ammount','$transaction_code','$details' ,'$confirmed')";
				 
				$connection->autocommit(FALSE); //start transaction
				if (!mysqli_query($connection,$sql)) {
					$connection->rollback();
					die('insert 1 Error: ' . $sql . mysqli_error($con));
				}
				$sql ="INSERT INTO transactions (t_account_from,t_account_to,t_amount,t_code,t_description,t_confirmed)
				 VALUES ('$to_a_id', '$from_a_id', '$amount','$transaction_code','$details' ,'$confirmed')";
				 if (!mysqli_query($connection,$sql)) {
					$connection->rollback();
					die('insert 2 Error: ' . mysqli_error($con));
				}
				///update balance
				$sql = "update accounts as a join 
				(select t_account_from,sum(t_amount) as balance 
				from transactions 
				group by t_account_from,t_confirmed 
				having t_confirmed=1) as t 
				on a.a_id = t.t_account_from 
				set a.a_balance = t.balance
				Where a.a_id ='$from_a_id' OR a.a_id='$to_a_id' ";
				
				//assume that there is unique combination of value, code, description
				$sql2 = "update accounts 
						set a_balance = a_balance + (select t_amount from transactions 
						where t_account_from='$from_a_id' and t_account_to='$to_a_id' 
								and t_amount='$minus_ammount' and t_code='$transaction_code'
								and t_description='$details'
								and t_confirmed=1)
						where a_id='$from_a_id'";		
												
				
				if (!mysqli_query($connection,$sql)) {
					$connection->rollback();
					die('Error updating balance: '. $sql . mysqli_error($con));
				}
				
				///deactive tan
				$sql = "update transaction_codes set tc_active = '0' where tc_code ='$transaction_code'";
				if (!mysqli_query($connection,$sql)) {
					$connection->rollback();
					die('Error: ' . mysqli_error($con));
				}
				$error = "Transaction has been executed successfully!";
				$_SESSION['tError'] = $error;
				$connection->commit();
				$connection->autocommit(TRUE); 
			
			
				header('Location: profile.php'); // Redirecting To Home Page
		}
		mysqli_close($connection); // Closing Connection

		}
}
}
?>

