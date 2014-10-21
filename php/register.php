<?php
include('functions/login.php'); // Includes Login Script
 if(!isset($_SESSION)) 
    {        
	session_start(); //start session only if it is not already started
    }
if(isset($_SESSION['login_user'])){
header('Location: profile.php'); // Redirecting To Home Page
exit;
}
?>
<?php include('includes/top.php');?>
<?php include('membership.php');?>
<body>
<?php include('includes/header.php')?>
<section id="myform">
    <div class="container">
    	<div class="row">
    	    <div class="col-xs-12">
        	    <div class="form-wrap">
                <h1>Registration</h1>
                    <form role="form" action="" method="post" id="registration-form" autocomplete="off">
                        <div class="form-group">
                            <label for="username" class="sr-only">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="username">
                        </div>
                        <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="********">
                        </div>
                        <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="somebody@example.com">
                        </div>
						<div class="form-group">
                            <label for="account" class="sr-only">Account number</label>
                            <input type="text" name="account" id="account" class="form-control" placeholder="12345678">
                        </div>
						<div class="checkbox">
							<input id="employee" type="checkbox" name="employee" style="opacity:0; position:absolute; left:9999px;">
                            <span class="character-checkbox" onclick="asEmployee()"></span>
                            <span>Register as employee</span>
                        </div>
                        <input type="submit" name = "submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Register">
						<span class = "error"><?php echo $error; ?></span>
                    </form>
					<h4>Already a user?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php">Login</a></h4>
					<hr>
        	    </div>
    		</div> <!-- /.col-xs-12 -->
    	</div> <!-- /.row -->
    </div> <!-- /.container -->
	<?php include('includes/bottom.php')?>
</section>
<script>
function asEmployee() {
$(document).ready(function () {
  if($('#employee').is(':checked')){
		// Uncheck
		$('.checkbox').removeClass('show');
		$("#employee").prop("checked", false);    
    } else { 
		//Check
	    $('.checkbox').addClass('show');
        $("#employee").prop("checked", true); 
    }
}); 
}
</script>
</body>
</html>
