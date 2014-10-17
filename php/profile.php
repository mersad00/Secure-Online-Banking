<?php
include('session.php');
if ( $_SESSION['login_user_type']=='1'){ 
	header("Location:admin.php");
    die(isset($_SESSION['login_user_type']) . $_SESSION['login_user_type']);
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Your Home Page</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="profile">
<b id="welcome">Welcome : <i><?php echo $login_session; ?></i></b>
<b id="logout"><a href="logout.php">Log Out</a></b>

</div>
<a href="transfer.php">Transfer</a><br>
<a href='file_transaction.php'>Upload transaction file</a>
<?php include 'history.php';?>
</body>
</html>
