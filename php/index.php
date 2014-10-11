<?php
include('login.php'); // Includes Login Script
session_start();// Starting Session
if(isset($_SESSION['login_user'])){
header('Location: profile.php'); // Redirecting To Home Page
exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Online Banking System</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="main">
<h1>Group 16 Online Banking</h1>
<div id="login">
<h2>Login Form</h2>
<form action="" method="post">
<label>UserName :</label>
<input id="name" name="username" placeholder="username" type="text">
<label>Password :</label>
<input id="password" name="password" placeholder="**********" type="password">
<input name="submit" type="submit" value=" Login ">
<span><?php echo $error; ?></span>
</form>
<a href="/register.php">Register</a>
</div>
</div>
</body>
</html>
