<?php
session_start(); // Starting Session

ini_set('display_errors', 'On');
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
	if (empty($_POST['amount']) || empty($_POST['transaction_code']) || empty($_POST['to_account'])) {
		$error = "Input is invalid- empty";
	}
else
{
	$amount=$_POST['amount'];
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

		// Establishing Connection with Server by passing server_name, user_id and password as a parameter
		//$connection = mysql_connect("localhost", "root", "SecurePass!");
		$con=mysqli_connect("localhost","root","SecurePass!","banking");
		if (mysqli_connect_errno()) {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

		//check transaction code is valid
		$sql ="select tc_active from transaction_codes where tc_code='$transaction_code' and tc_active = '1'";
		
		
		$query= mysqli_query($con,$sql);
		$rows = mysqli_num_rows($query);
		if($rows == 0){
			$error = "transaction code is not valid!";
		}
		else
		{
			///Check that account exists in db
			$sql="select a_id from accounts where a_number='$account_to'";
			$query= mysqli_query($con,$sql);
			$rows = mysqli_num_rows($query);
			if($rows!=1){
				$error = "Account number does not exist!";
			}
			else{
				$row = mysqli_fetch_assoc($query);
				$to_a_id = $row['a_id'];
				$from_a_id = $_SESSION['login_a_id'];
				$minus_ammount = -$amount;
				//perform transaction	
				$sql="INSERT INTO transactions (t_account_from,t_account_to,t_amount,t_code,t_description)
				 VALUES ('$from_a_id', '$to_a_id', '$minus_ammount','$transaction_code','$details' )";
				 
				$con->autocommit(FALSE); //start transaction
				if (!mysqli_query($con,$sql)) {
					$con->rollback();
					die('insert 1 Error: ' . $sql . mysqli_error($con));
				}
				$sql ="INSERT INTO transactions (t_account_from,t_account_to,t_amount,t_code,t_description)
				 VALUES ('$to_a_id', '$from_a_id', '$amount','$transaction_code','$details' )";
				 if (!mysqli_query($con,$sql)) {
					$con->rollback();
					die('insert 2 Error: ' . mysqli_error($con));
				}
				///update balance
				$sql = "update accounts as a join 
				(select t_account_from,sum(t_amount) as balance from transactions group by transactions.t_account_from ) as t 
				on a.a_id = t.t_account_from 
				set a.a_balance = t.balance
				Where a.a_id ='$from_a_id' OR a.a_id='$to_a_id'";
				
				if (!mysqli_query($con,$sql)) {
					$con->rollback();
					die('Error: ' . mysqli_error($con));
				}
				
				///deactive tan
				$sql = "update transaction_codes set tc_active = '0' where tc_code ='$transaction_code'";
				if (!mysqli_query($con,$sql)) {
					$con->rollback();
					die('Error: ' . mysqli_error($con));
				}
				
				$con->commit();
				$con->autocommit(TRUE); 
			
			
				header('Location: profile.php'); // Redirecting To Home Page
		}
		mysqli_close($con); // Closing Connection

		}
}
}
?>

