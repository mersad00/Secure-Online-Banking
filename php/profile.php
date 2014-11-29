<?php
include('functions/banking.php');
include('session.php');
include('includes/top.php');
?>
<body>
<!-- Page Heading -->
	<div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">Welcome
                    <small><?php echo $login_session; ?></small>
					<div id="logout"><h4><a href="logout.php">Logout</a></h4></div>
                </h1>
			</div>
        </div>
	</div>
		
<!-- Page Content -->
	<div class="container">
		<div class="row">
			<div class="col-md-4">
			<div id="userInfo"></div>
			<div id="clientKey">
			<?php include('clientkeygenerator.php') ?></div>
				</div>
<?php include('transfer.php') ?>
		</div>
		<div class = "row">
		<div class = "col-md-12">
		<?php  
					$_REQUEST['uid'] = $_SESSION['login_id'];
					include('history.php');
				?>
		</div>

</div>
</div>
<?php include('includes/bottom.php')?>
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
				var info = "<table class=\"table\">" +
                    "<tbody><tr class=\"noborder\"><th colspan=\"2\"><h4 id=\"green\"><b>User information</b></h4></th></tr><tr><td>Username:</td><td>"+record.u_name+"</td></tr>"+
				"<tr><td>Email:</td><td>"+record.u_email+"</td></tr>"+
				"<tr><td>Account Number:</td><td>"+record.a_number+"</td></tr>" +
				"<tr><td>Balance:</td><td>"+record.a_balance+"</td></tr></tbody></table>";
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
</body>
</html>

