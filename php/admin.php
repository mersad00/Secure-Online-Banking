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
		<h4 id="green">Search customers transaction history</h4>
		<section id="myForm">
		<form class="form-horizontal" autocomplete="off">
				<div class="form-group">
                            <label for="customerName" class="col-sm-3 control-label">Customer name: </label>
							 <div class="col-sm-offset-2 col-sm-3">
                            <input type="text" name="customerName" id="customerName" class="form-control" onkeyup="showRecords('name',this.value)" >
							</div>
                 </div>
				 <div class="form-group">
                            <label for="accountNumber" class="col-sm-3 control-label">Account: </label>
							    <div class="col-sm-offset-2 col-sm-3">
                            <input type="text" name="accountNumber" id="accountNumber" class="form-control" onchange = "showRecords('acn',this.value)" >
							</div>
                 </div>
		</section>
	<div id="persons"></div>
	<div id="trans"></div>
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
			<script>
	function showRecords(str,val) {
		if (str=="") {
			document.getElementById("persons").innerHTML="";
			return;
		} 
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				var arr = JSON.parse(xmlhttp.responseText);
				//document.getElementById("persons").innerHTML=arr.length;
				var rows="<h4 id=\"green\">List of search records:</h4><table class=\"table table-striped table-condensed\"><tr><th>#</th><th>Name</th><th>Email</th><th>Account number</th><th>Action</th></tr>";
				for(i = 0; i < arr.length; i++) {
				  var row=  "<tr><td>#</td><td>"+arr[i]['u_name']+
				  "</td><td>"+arr[i]['u_email']+"</td><td>"+arr[i]['a_number']+
				  "</td><td><input name=\"submit_"+arr[i]['u_id']+
				  "\" type=\"submit\" size =\"20\" value=\"View Transactions \" onclick=\"showTrans("+arr[i]['u_id']+")\">"
				  +"</tr>";
					rows += row;
				}
				rows+= "</table>";
				document.getElementById("persons").innerHTML=rows;
			}
		}
		xmlhttp.open("GET","getuser.php?searchby="+str+"&key="+val+'&all=1',true);
		xmlhttp.send();
	}
	function showTrans(str) {
		if (str=="") {
			document.getElementById("trans").innerHTML="";
			return;
		} 
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				document.getElementById("trans").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","history.php?uid="+str,true);
		xmlhttp.send();
	}
	</script>
<?php include('includes/bottom.php')?>
</body>

</html>
