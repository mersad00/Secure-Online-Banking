<?php
if(!isset($_SESSION)) 
    {        
	session_start(); //start session only if it is not already started
    }
$page = basename($_SERVER['PHP_SELF']);

// Storing Session
$user_check=$_SESSION['login_user'];
$login_session =$_SESSION['login_user'];
$login_type = $_SESSION['login_user_type'];
if(!isset($login_session)){
	header('Location: index.php'); // Redirecting To Home Page
}

switch($page){
	case 'admin.php':
	//case 'getuser.php':
	case 'history.php':
	case 'customerTransactionHistory.php':
		$isEmployeePage = TRUE;
		break;
	default: 
		$isEmployeePage = FALSE;
		break;
}
if($page != 'getuser.php'){
	if($login_type != 1 && $isEmployeePage)
	{
		///has no access to admin group pages
		die('Access denied!'); // Redirecting To Home Page
		exit;
	}
	if($login_type == 1 && !$isEmployeePage){
		header('location: admin.php');
	}
}

?>
