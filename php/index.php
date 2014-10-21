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
<?php include('includes/top.php')?>
<body>
<?php include('includes/header.php')?>
<section id="myform">
    <div class="container">
    	<div class="row">
    	    <div class="col-xs-12">
        	    <div class="form-wrap">
                <h1>Online Banking Platform</h1>
                    <form role="form" action="" method="post" id="login-form" autocomplete="off">
                        <div class="form-group">
                            <label for="username" class="sr-only">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="username">
                        </div>
                        <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="********">
                        </div>
                        <div class="checkbox">
                            <span class="character-checkbox" onclick="showPassword()"></span>
                            <span class="label">Show password</span>
                        </div>
                        <input type="submit" name = "submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Log in">
						<span class = "error"><?php echo $error; ?></span>
                    </form>
					<h4>Not a user yet?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="register.php">Register</a></h4>
					<hr>
        	    </div>
    		</div> <!-- /.col-xs-12 -->
    	</div> <!-- /.row -->
    </div> <!-- /.container -->
</section>
<?php include('includes/bottom.php')?>
<!-- Scripts-->
<script>
function showPassword() {
    
    var key_attr = $('#password').attr('type');
    
    if(key_attr != 'text') {
        
        $('.checkbox').addClass('show');
        $('#password').attr('type', 'text');
        
    } else {
        
        $('.checkbox').removeClass('show');
        $('#password').attr('type', 'password');
        
    }
    
}
</script>
</body>
</html>
