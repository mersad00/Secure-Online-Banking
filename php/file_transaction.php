<?php
session_start(); // Starting Session
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>upload transaction file</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.21" />
</head>

<body>
	<form action="upload.php" method="post" enctype="multipart/form-data">
  Please choose your transaction file: <input type="file" name="uploadFile"><br>
  Your input must have following format: AccountNumber,Amount, Transaction Code, Description<br>
  <span><?php echo $error; ?></span><br>
  <input type="submit" value="Upload File">
</form>
</body>

</html>
