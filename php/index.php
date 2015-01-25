<?php include('includes/top.php')?>
<?php
require_once("utils/dbconnection.php");
include('functions/login.php');
require_once 'session.php'; // Includes Login Script

?>
<body>
<?php include('includes/header.php')?>
<section id="myform">
    <div class="container">
    	<div class="row">
    	    <div class="col-xs-12">
        	    <div class="form-wrap">
                <h1>Online Banking Platform</h1>
                    <form role="form" action="" method="post" id="login-form" name = "login-form" autocomplete="off">
                        <div class="form-group">
                            <label for="username" class="sr-only">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="********" required >
                        </div>
						
						
                        <div class="checkbox">
                            <span class="character-checkbox" onclick="showPassword()"></span>
                            <span class="label">Show password</span>
                        </div>
					
					
						<div>
						<a href="<?php echo PHP_URL ?>forgot_password.php"><span class="small">Forgot password?</span></a>
						<p></p>
						
						</div>
						
                        <input type="submit" name = "submit-login" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Log in">
						        <?php if($error!=null) {?>
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <?php echo $error; ?>
                                        </div>
                                        <?php }?>
                    </form>
					<h4>Not a user yet?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo PHP_URL ?>register.php">Register</a></h4>
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
