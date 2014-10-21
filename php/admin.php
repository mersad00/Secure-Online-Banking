<?php
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
		 <div class="col-md-12">
		<h4><a href="customerTransactionHistory.php">Customers transaction history</a></h4>
		</div>
		</div>
		<div class="row">
		 <div class="col-md-12">
		<?php
	include('activation.php');?>
		</div>
		</div>
		<div class="row">
		 <div class="col-md-12">
		<?php
	include('confirmation.php');?>
		</div>
		</div>
		</div>

</body>

</html>
