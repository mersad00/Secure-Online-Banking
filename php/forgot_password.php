<?php 
include('includes/top.php');
include('email.php');
require_once('utils/constants.php');
require_once('utils/dbconnection.php');
require_once ('reCAPTCHA/recaptchalib.php');

// Register API keys at https://www.google.com/recaptcha/admin
$siteKey 	= SITE_KEY;
$secret 	= SECRET_KEY;
$lang 		= LANGUAGE;
// The response from reCAPTCHA
$resp 		= null;
// The error code from reCAPTCHA, if any
$error 		= null;
$reCaptcha	= new ReCaptcha($secret);
// Check for the reCAPTCHA response?
$message 	= "";
if ($_POST["g-recaptcha-response"]) 
{
    $resp = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}
// check for csrf
if($_POST['user_token'] == $_SESSION['user_token'] && isset($_POST['submit-password-request']))
{   
	if($resp == null){
		$message = NOT_ROBOT;
	}
	else
	{
    
        $email			= mysqli_real_escape_string($connection,$_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) // Validate email address
        {
			$message	= INVALID_EMAIL;
        }
        else
        {
            $query 		= "SELECT u_id, u_name, u_password FROM users where u_email='".$email."'";
            $result 	= mysqli_query($connection,$query);
            $rows 		= mysqli_fetch_array($result);
            
            if(count($rows) >= 1)
            {
				
				$encrypt	= uniqid(mt_rand(), true);
				$token		= md5($encrypt);
				// get current time and add 1 hour to get the expiration time
				$time = time() + (3600);
				$sql="INSERT INTO reset_tokens (user, token, expire_time) VALUES ('$rows[u_id]', '$token', '$time')";
				if (!mysqli_query($connection,$sql)) {
					mysqli_rollback($connection);
					die('Error: ' . mysqli_error($connection));
				}
				
                $message	= RESETLINK_SENT;
                $to			= $email;
				$recipientName = $rows['u_name'];
                
               sendResetPasswordLink($to,$recipientName, $encrypt);
			   mysqli_close($connection);
               
            }
            else 
            {
            // this user does not exist in our database, but we do not want to reveal such information
              $message	= RESETLINK_SENT;
			}
        }
    }
}
?>
<body>
<?php include('includes/header.php')?>
<section id="myform">
    <div class="container">
    	<div class="row">
    	    <div class="col-xs-12">
        	    <div class="form-wrap">
                <h5>Please enter the email that you use with your account: </h5>
                    <form role="form" action="" method="post" id="resetpassword-form" name = "resetpassword-form" autocomplete="off">
					<span class = "error"><?php echo $message; ?></span>
                        <div class="form-group">
                            <label for="username" class="sr-only">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" required>
                        </div>	
                     <div class="form-group">
                        <div id="captcha_element"></div>
					</div>
					<div>
						By mistake here? <a href="<?php echo PHP_URL ?>index.php"><span class="small">Login</span></a>
						<p></p>
						
						</div>
						<input type="hidden" name="user_token" id = "user_token" value="<?php echo  $_SESSION['user_token']; ?>" />
						<input type="submit" name = "submit-password-request" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Request password">
                    </form>
					<hr>
        	    </div>
    		</div> <!-- /.col-xs-12 -->
    	</div> <!-- /.row -->
    </div> <!-- /.container -->
</section>

<script type="text/javascript">
	var onloadCallback = function() {
        grecaptcha.render('captcha_element', {
          'sitekey' : '<?php echo SITE_KEY;?>'
        });
    };
</script>
<?php include('includes/bottom.php')?>
</body>
</html>
