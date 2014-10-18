<?php
include('session.php');
include('banking.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>untitled</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.21" />
</head>

<body>
	<div id="main">
	<h1>Transfer</h1>
	<div id="transfer">
	<h2>Transfer</h2>
	<form action="" method="post">
	<label>From :</label>
	<span><?php echo $_SESSION['login_a_name'] . ' ' . $_SESSION['login_a_number']; ?></span>
	<br>
	<label>To (Account number):</label>
<input id="to_account" name="to_account" type="text" size="20">
<br>
<label> Amount:</label>
<input id="amount" name="amount" type="number" size ="20">
<br>
<label> Transaction code:</label>
<input id="transaction_code" name="transaction_code" type="text" size ="20">
<br>
<label>Details</label>
<input id="details" name="details" type="text" size="30">
<br>
<input name="submit" type="submit" value=" Submit ">
<br>
<span><?php echo $error; ?></span>
</form>
</div>
</div>
</body>

</html>
