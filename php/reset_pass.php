<?php
include('includes/top.php');
include('email.php');
require_once('utils/dbconnection.php');
$message = '';
$error = '';
if(isset($_GET['action']))
{          
    if($_GET['action']=="reset")
    {
        $encrypt = mysqli_real_escape_string($connection,$_GET['code']);
        $query = "SELECT u_id, u_password FROM users where md5(90*13+u_id+u_password)='".$encrypt."'";
        $result = mysqli_query($connection,$query);
        $rows = mysqli_fetch_array($result);
        if(count($rows)>=1)
        {
			
        }
        else
        {
            $message = 'Invalid key please try again. <a href="http://localhost/ws14secure/php/forget_password.php"><span class="small">Forget Password?</span></a>';
        }
		mysqli_close($connection);
    }
}
elseif(isset($_POST['action']))
{
    
    $encrypt      = mysqli_real_escape_string($connection,$_POST['action']);
    $password     = mysqli_real_escape_string($connection,$_POST['password']);
	$confirmpassword     = mysqli_real_escape_string($connection,$_POST['confirmpassword']);
	
	if(validatePasswordPolicy($password) && validateConfirmPassword($password,$confirmpassword)){
    $query = "SELECT u_id, u_password FROM users where md5(90*13+u_id+u_password)='".$encrypt."'";
//    echo $query;

    $result = mysqli_query($connection,$query);
    $rows = mysqli_fetch_array($result);
	
    if(count($rows)>=1)
    {
        $query = "update users set u_password='".md5($password)."' where u_id='".$rows['u_id']."'";
        mysqli_query($connection,$query);
		mysqli_close($connection);
//        echo $query;
        $message = "Your password changed sucessfully!<br/><a href='index.php'><span class='small'>Login</span></a>.";
    }
    else
    {
	
       $message = "Invalid key please try again. <a href='http://localhost/ws14secure/php/forget_password.php'><span class='small'>Forget Password?</span></a>";
     }
	}
}
else
{
    header("location: /index.php");
}

function validatePasswordPolicy($password){
global $error;
if (strlen($password) < 8 OR strlen($password) > 20) {
			$error .= '<p class="error">Password should be within 8-20 characters long.</p>';
			return FALSE;
}
$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);

if(!$uppercase) {
  	$error .= '<p class="error">Password should contain at least one uppercase character.</p>';
			return FALSE;
}
if(!$lowercase) {
  	$error .= '<p class="error">Password should contain at least one lowercase character.</p>';
			return FALSE;
}
if(!$number) {
  	$error .= '<p class="error">Password should contain at least a digit</p>';
			return FALSE;
}
return TRUE;
}

function validateConfirmPassword($password, $confirm_password){
# Validate Confirm Password #
global $error;
		if ($confirm_password != $password) {
			$error .= '<p class="error">Confirm password mismatch.</p>';
			return FALSE;
		}
return TRUE;
}

?>
<body>
<?php include('includes/header.php')?>
<section id="myform">
    <div class="container">
    	<div class="row">
    	    <div class="col-xs-12">
        	    <div class="form-wrap">
                <h1>Reset password</h1>
                    <form role="form" action="reset_pass.php" method="post" id="reset" name = "reset" autocomplete="off">
					<span class = "error"><?php echo $message; ?></span>
                        <div class="form-group">
                            <label for="newpassword" >New password: </label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="New password" required>
                        </div>		
 <div class="form-group">
                            <label for="confirmpassword" >Confirm new password: </label>
                            <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" placeholder="Confirm new password" required>
                        </div>	
						<input name="action" type="hidden" value="<?php echo $encrypt;?>" /></p>						
                        <input type="submit" name = "reset-password" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Change password">
						<span class = "error"><?php echo $error; ?></span>
                    </form>
					<hr>
        	    </div>
    		</div> <!-- /.col-xs-12 -->
    	</div> <!-- /.row -->
    </div> <!-- /.container -->
</section>
<script>
$(document).ready(function() {
 $.fn.bootstrapValidator.validators.securePassword = {
        validate: function(validator, $field, options) {
            var value = $field.val();
            if (value === '') {
                return true;
            }

            // Check the password strength
            if (value.length < 8) {
                return {
                    valid: false,
                    message: 'The password must be more than 8 characters long'
                };
            }

            // The password doesn't contain any lowercase character
            if (value === value.toLowerCase()) {
                return {
                    valid: false,
                    message: 'The password must contain at least one upper case character'
                }
            }

            // The password doesn't contain any uppercase character
            if (value === value.toUpperCase()) {
                return {
                    valid: false,
                    message: 'The password must contain at least one lower case character'
                }
            }

            // The password doesn't contain any digit
            if (value.search(/[0-9]/) < 0) {
                return {
                    valid: false,
                    message: 'The password must contain at least one digit'
                }
            }

            return true;
        }
    };
    $('#reset').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
             password: {
                
                    validators: {
                    notEmpty: {
                        message: 'The password is required and cannot be empty'
                    },
                    securePassword: {
                        message: 'The password is not valid'
                    }
                }
            
        },
		     confirmPassword: {
                validators: {
                    identical: {
                        field: 'password',
                        message: 'The password and its confirm are not the same'
                    }
                }
            },
      
        }
    });
});
</script>
<?php include('includes/bottom.php')?>
</body>
</html>