<?php
if (session_id() == '') {
	//session_set_cookie_params(100, NULL, NULL, TRUE,TRUE);
	ini_set("session.cookie_httponly", 1);
	session_start();
}
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 10*60)) {
	// last request was more than 10 minutes ago
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if (!isset($_SESSION['CREATED'])) {
	$_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 5*60) {
	// session started more than 5 mins ago
	ini_set("session.cookie_httponly", 1);
	session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
	$_SESSION['CREATED'] = time();  // update creation time
}

$page = basename($_SERVER['PHP_SELF']);

//check if user has been logged in
if(!isset($_SESSION['login_user'])){
	//if user is calling from index leave him alone
	//otherwise redirect loop will occur
	if($page!='index.php' && $page!="register.php" && $page!="forgot_password.php" && $page!="reset_pass.php")
	{
		header('Location: index.php'); // Redirecting To Home Page
		die();
	}
	return;
}
// Storing Session
$user_check=$_SESSION['login_user'];
$login_session =$_SESSION['login_user'];
$login_type = $_SESSION['login_user_type'];


//newly added rbac provider
require_once $_SERVER['DOCUMENT_ROOT'] . '/ws14secure/PhpRbac/src/PhpRbac/Rbac.php';
$rbac = new \PhpRbac\Rbac();
//end of newly added rbac provider


switch($page){
	case 'activation.php':
	case 'confirmation.php':
	case 'admin.php':
	case 'history.php':
	case 'customerTransactionHistory.php':
		//ensure only employee can reach here
		$rbac->enforce('employee-permission', $_SESSION['login_id']);
		break;
	case 'profile.php':
	case 'administration.php':
	case 'clientkeygenerator.php':
		//ensure only client can reach here
		$rbac->enforce('client-permission', $_SESSION['login_id']);
		break;
	/*default:
		//if I have not mentioned the page then employee as default can access only
		$rbac->enforce('employee-permission', $_SESSION['login_id']);
		break;*/
}

?>
