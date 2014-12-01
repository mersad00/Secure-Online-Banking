<?php 
require_once("utils/constants.php");
require_once 'session.php';
include('includes/top.php');
include('membership.php');
?>
<body>
<?php include('includes/header.php')?>
<section id="myform">
    <div class="container">
    	<div class="row">
    	    <div class="col-xs-12">
        	    <div class="form-wrap">
                <h1>Registration</h1>
                    <form role="form" class = "form-horizontal" action="" method="post" id="registration-form" name="registration-form" autocomplete="off">
                        <div class="form-group">
							<label for="username" class="sr-only">Username</label>
							<input type="text" class="form-control" name="username" id="username" placeholder = "username" required/>
						</div>
                        <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required />
                        </div>		
						<div class="form-group">
                            <label for="confirmPassword" class="sr-only">Confirm Password</label>
                            <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" placeholder="Confirm password" required/>
                        </div>							
                        <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="somebody@example.com" required/>
                        </div>
						<div class="form-group">
                            <label for="account" class="sr-only">Account number</label>
                            <input type="number" name="account" id="account" class="form-control" placeholder="account number" required/>
                        </div>
						<div class="checkbox">
							<input id="employee" type="checkbox" name="employee" style="opacity:0; position:absolute; left:9999px;">
                            <span class="character-checkbox" onclick="asEmployee()"></span>
                            <span>Register as employee</span>
                        </div>
                        <input type="submit" name = "submit-register" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Register">
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

$(document).ready(function () {

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

	$('#registration-form').bootstrapValidator({
	
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            username: {
                message: 'The username is not valid',
                validators: {
                    notEmpty: {
                        message: 'The username is required and cannot be empty'
                    },
                    stringLength: {
                        min: 3,
                        max: 20,
                        message: 'The username must be more than 3 and less than 20 characters long'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9]+$/,
                        message: 'The username can only consist of alphabetical and number'
                    },
                    different: {
                        field: 'password',
                        message: 'The username and password cannot be the same as each other'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required and cannot be empty'
                    },
                    emailAddress: {
                        message: 'The email address is not a valid'
                    }
                }
            },
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
			account: {
			 validators: {
                    notEmpty: {
                        message: 'The account number is required and cannot be empty'
                    },
					stringLength: {
                        min: 10,
                        max: 10,
                        message: 'The account number should be 10 digits long'
                    }
                }
			
			}
		}
    });
});


</script>
</body>
</html>
