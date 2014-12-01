	<div class="col-md-8">
			<table class="table">
                    <tbody><tr><th><h4 id="green"><b>Transfer Money</b></h4></th></tr></tbody></table>
			<div class="col-md-6" style=" border-right: 1px solid #333;">
			<h4 id="green">Use transaction Form</h4>
			<section id="myform">
				<form role="form" action="" method="post" id="transaction-form" name="transaction-form" class="form-horizontal" autocomplete="off">
				<div class="form-group">
                            <label for="to_account" class="col-sm-5 control-label">To: </label>
							 <div class="col-sm-7">
                            <input type="text" name="to_account" id="to_account" class="form-control" placeholder="Receiver account number" required>
							</div>
                 </div>
				 <div class="form-group">
                            <label for="amount" class="col-sm-5 control-label">Amount</label>
							    <div class="col-sm-7">
                            <input type="number" name="amount" id="amount" class="form-control" placeholder="Transfer amount" required>
							</div>
                 </div>
				 <div class="form-group">
                            <label for="transaction_code" class="col-sm-5 control-label">Transaction code</label>
							<div class="col-sm-7">
                            <input type="text" name="transaction_code" id="transaction_code" class="form-control" placeholder="TAN" required>
							</div>
                 </div>
				 <div class="form-group">
                            <label for="details" class="col-sm-5 control-label">Details</label>
							<div class="col-sm-7">
                            <input type="textbox" name="details" id="details" class="form-control" placeholder="Description...(optional)">
							</div>
				</div>
				<input type="hidden" name="user_token" id = "user_token" value="<?php echo  $_SESSION['user_token']; ?>" />
				<div class="col-sm-offset-5 col-sm-7">
					<input type="submit" name = "submit-transfer" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Transfer">
				</div>
				<div class="col-sm-offset-5 col-sm-7 control label">
					<span class = "error"><?php echo $error; ?></span>
				</div>
				</form>
			</section>
				
			</div>
			<div class="col-md-6">
			<h4 id="green">Use transaction file</h4>
				<section id="myform">
				<form action="functions/upload.php" method="post" enctype="multipart/form-data" id="js-upload-form">
            <div class="form-inline">
              <div class="form-group">
			  <div class="col-sm-12">
                <input type="file" class="btn btn-default btn-file" id="uploadFile" name="uploadFile" required>
				</div>
              </div>
			  <div class="col-sm-offset-2 col-sm-7">
              <input type="submit" class="btn btn-lg btn-block btn-upload" id="js-upload-submit" value="Upload File">
			  </div>
			  <div class="col-sm-3">
			  </div>
            </div>
			<div class="col-sm-12">
			  <span>Your input must have following format:<br> TAN,source_acc,destination_acc,amount,description </span>
			</div>
			<div class="col-sm-12">
			  <a target="_blank" href="/ws14secure/php/public/transactions.txt">download template</a>
			</div>
          </form>
		  </section>
			<div id="chooseFileFirst"> </div>
			</div>
			</div>
			
<script>
$(document).ready(function() {
    $('#transaction-form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            to_account: {
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
            },
			amount: {
                validators: {
                    notEmpty: {
                        message: 'Amount is required'
                    }
					 integer: {
                        message: 'The value is not an integer'
                    }
                }
            },
			transaction_code: {
                validators: {
                    notEmpty: {
                        message: 'The transaction code is required'
                    }
					stringLength: {
                        min: 14,
                        max: 14,
                        message: 'The trancaction code is not valid'
                    }
                }
            },
			details: {
                validators: {
                    notEmpty: {
                        message: 'Password is required'
                    }
					regexp: {
                        regexp: /^[a-zA-z0-9\s]+$/i,
                        message: 'Details can consist of alphanumeric characters and spaces only'
                    }
                }
            }
      
        }
    });
});
</script>