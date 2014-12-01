<?php 
include('includes/top.php');
include('email.php');
require_once('utils/dbconnection.php');
?>
<?php

$message = "";
if(isset($_POST['submit-password-request']))
    {
        $email      = mysqli_real_escape_string($connection,$_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) // Validate email address
        {
            $message =  "Invalid email address please type a valid email!!";
        }
        else
        {
            $query = "SELECT u_id, u_name, u_password FROM users where u_email='".$email."'";
            $result = mysqli_query($connection,$query);
            $rows = mysqli_fetch_array($result);
            
            if(count($rows)>=1)
            {
                $encrypt = md5(90*13+$rows['u_id'] + $rows['u_password']);
                $message = "Your password reset link is sent to your e-mail address.";
                $to=$email;
				$recipientName = $rows['u_name'];
                
               sendResetPasswordLink($to,$recipientName, $encrypt);
			   mysqli_close($connection);
               
            }
            else
            {
                $message = "Account not found please signup now!!";
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
                        <input type="submit" name = "submit-password-request" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Request password">
						
                    </form>
					<hr>
        	    </div>
    		</div> <!-- /.col-xs-12 -->
    	</div> <!-- /.row -->
    </div> <!-- /.container -->
</section>
<?php include('includes/bottom.php')?>
</body>
</html>
