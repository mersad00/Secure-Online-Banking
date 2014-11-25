<?php
if(!isset($_SESSION)) 
    {        
	session_start(); //start session only if it is not already started
    }
$page = basename($_SERVER['PHP_SELF']);

//check if user has been logged in
if(!isset($_SESSION['login_user'])){
	
	//if user is calling from index leave him alone
	//otherwise redirect loop will occur
	if($page!='index.php')
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
require_once '../PhpRbac/src/PhpRbac/Rbac.php';
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
		//ensure only client can reach here
		$rbac->enforce('client-permission', $_SESSION['login_id']);
		break;
	default:
		//if I have not mentioned the page then employee as default can access only
		$rbac->enforce('employee-permission', $_SESSION['login_id']);
		break;
}

?>
