<?php
include('membership.php');
session_start();// Starting Session
if(isset($_SESSION['login_user'])){
header('Location: profile.php'); // Redirecting To Home Page
exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Registration</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.21" />
</head>
<div id="main">
<h1>Registration</h1>
<div id="register">
<h2>Register Form</h2>
<form action="" method="post">
<label>UserName :</label>
<input id="name" name="username" placeholder="username" type="text" size="10">
<br>
<label>Password :</label>
<input id="password" name="password" placeholder="**********" type="password" size="10">
<br>
<label> Email:</label>
<input id="email" name="email" type="text">
<br>
<label>Account Number</label>
<input id="account" name="account" type="text" size="10">
<br>
<input name="submit" type="submit" value=" Register ">
<br>
<span><?php echo $error; ?></span>
</form>
</div>
</div>
<body>
	
</body>

</html>
