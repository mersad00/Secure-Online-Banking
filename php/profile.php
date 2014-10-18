<?php
include('session.php');
?>
<!DOCTYPE html>
<html>
<head>
<title>Your Home Page</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script src="http://code.jquery.com/jquery-latest.min.js"
        type="text/javascript"></script>
<script>
	function showUserInfo(val) {
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				var record = JSON.parse(xmlhttp.responseText)[0];
				var info = "<p>User name:"+record.u_name+"</p>"+
				"<p>Email:"+record.u_email+"</p>"+
				"<p>Account Number:"+record.a_number+"</p>";
				document.getElementById("userInfo").innerHTML=info;
			}
		}
		xmlhttp.open("GET","getuser.php?searchby=u_id&key="+val+"&all=0",true);
		xmlhttp.send();
	}
	$(document).ready(function() {
		var a = <?php echo $_SESSION['login_id']; ?>;
    showUserInfo(a);
});
</script>
</head>
<body>
<div id="profile">
<b id="welcome">Welcome : <i><?php echo $login_session; ?></i></b>
<b id="logout"><a href="logout.php">Log Out</a></b>

</div>
<a href="transfer.php">Transfer</a><br>
<a href='file_transaction.php'>Upload transaction file</a>
<div>
<div id="userInfo"></div>
<?php  
$_REQUEST['uid'] = $_SESSION['login_id'];
include('history.php');
?>
</div>
</body>
</html>
