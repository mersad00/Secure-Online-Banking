<?php
require_once ('HTMLPurifier.standalone.php');
if(!isset($_SESSION))
{
	require_once 'session.php';
}
$error=''; // Variable To Store Error Message
if (isset($_POST['submit-login'])) {
	if (empty($_POST['username']) || empty($_POST['password'])) 
		{
			$error = EMPTY_INPUT;
		}
		else
		{
			$config = HTMLPurifier_Config::createDefault();
			$purifier = new HTMLPurifier($config);
			
			// Define $username and $password
			$username=$_POST['username'];
			$password=$_POST['password'];
			$username = $purifier->purify($username);
			$password = $purifier->purify($password);
			// To protect MySQL injection for Security purpose
			$username = stripslashes($username);
			$password = stripslashes($password);
			$username = mysqli_real_escape_string($connection,$username);
			$password = mysqli_real_escape_string($connection,$password);
						
			$password = md5($password);
			
			// SQL query to fetch information of registerd users and finds user match.

			$selectUserQuery = "select u_name, u_password, u_active,u_id,accounts.a_id,
					accounts.a_number,accounts.a_name,u_type
					from users left outer join accounts on users.u_id = accounts.a_user
					where u_name = ? LIMIT 1";
			$column = array("u_name", "u_password", "u_active", "u_id", "a_id", "a_number", "a_name", "u_type") ;
			
			/* Prepared statement, stage 1: prepare */
			if (!($stmt = $connection->prepare($selectUserQuery))) {
				header("HTTP/1.1 500 Internal Server Error");
				die( "Login failed");
			}
			
			if(!$stmt->bind_param('s', $username)){
				die( "Login failed 2");
			}
			
			if (!$stmt->execute()) {				
				die( "Login failed 3");
			}
			$stmt->store_result();
			$data = array() ; // Array that accepts the data.
			$params = array() ; // Parameter array passed to 'bind_result()'
			foreach($column as $col_name)
			{
				// Assign the fetched value to the variable '$data[$name]'
				$params[] =& $data[$col_name] ;
			}
			$res = call_user_func_array(array($stmt, "bind_result"), $params) ;
			
			if(! $res)
			{
				echo "bind_result() failed: " . $mysqli->error . "\n" ;
			}
			else
			{
				$res = $stmt->fetch() ;
				if(!$res)
				{
					$res = 0 ;
				}
			}
			$stmt->close() ;
			
			$row_count = $res;
			
						 if ($row_count == 1) 
						 {
							
							// get variables from result.
							$username = $data['u_name'];
							$db_password = $data['u_password'];
							$u_active = $data['u_active'];
							$user_id = $data['u_id'];
							$account_id = $data['a_id'];
							$account_number = $data['a_number'];
							$account_name = $data['a_name'];
							$user_type = $data['u_type'];
							
						 
							// check for brute force attacks
						   if (checkbrute($user_id, $connection) == false) 
						   { //no bruteforce detected
							 if ($db_password == $password) 
							 { //user exists 
								if($u_active == 0) 
								{ //user is not activated yet
									$error= NOT_ACTIVE;
								}
								else
								{
									//avoid session fixation
									session_regenerate_id (true);
		
									$_SESSION['login_user'] = $username; // Initializing Session
									$_SESSION['login_id'] = $user_id;
									$_SESSION['login_a_id'] = $account_id; 
									$_SESSION['login_a_number'] = $account_number; 
									$_SESSION['login_a_name'] = $account_name;
									$_SESSION['login_user_type'] = $user_type;
									
									if($_SESSION['login_user_type']=='0') //Check for user type
									{
										header("location: profile.php");  //simple user
									}
									elseif ($_SESSION['login_user_type']=='1')
									{
										header("location: admin.php"); //user is admin
									}
								}
							}
							else 
							{ //password is incorrect
								$now = time();
								mysqli_query($connection,"INSERT INTO login_attempts(user_id, time)
                                    VALUES ('$user_id', '$now')");
								$error = WRONG_CREDENTIALS;
							}
						} 
						else 
						{ //brute force detected, account locked
							$error = ACCOUNT_LOCKED;
						}
					}
					else 
					{
						$error = WRONG_CREDENTIALS;
					}
				
			}

		mysqli_close($connection); // Closing Connection
	
}
	
	
function mysqli_result($res,$row=0,$col=0){
	if ($row >= 0 && mysqli_num_rows($res) > $row){
		mysqli_data_seek($res,$row);
		$resrow = mysqli_fetch_row($res);
		if (isset($resrow[$col])){
			return $resrow[$col];
		}
	}
	return false;
}

function checkbrute($user_id) {
    // Get timestamp of current time 
	global $connection;
    $now = time();
 
    // All login attempts are counted from the past 2 hours. 
    $valid_attempts = $now - (2 * 60 * 60);
 
    $login_attempts_query = "SELECT time 
                             FROM login_attempts 
                             WHERE user_id = '$user_id' 
                            AND time > '$valid_attempts'"; 
        
	$result = mysqli_query($connection,$login_attempts_query);
	$row_count = mysqli_num_rows($result);
		
        // If there have been more than 5 failed logins 
        if ($row_count > 5) {
			$updateStatusQuery = "UPDATE users SET u_active = 0 WHERE u_id = '$user_id'";
			mysqli_query($connection,$updateStatusQuery);
            return true;
        } else {
            return false;
        }
    return false;
}

?>
