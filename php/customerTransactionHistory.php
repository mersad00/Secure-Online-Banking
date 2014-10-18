<?php
include('session.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Customer transaction history</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.21" />
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
				var rows="<table border='1'><tr><th>#</th><th>Name</th><th>Email</th><th>Account number</th><th>Action</th></tr>";
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
</head>
<body>
	<H2>Search customer transaction history</H2>
	<form> 
		Customer name: <input type="text" onkeyup="showRecords('name',this.value)"><br>
		Account number: <input type ="text" onchange = "showRecords('acn',this.value)"><br>
	</form>
	<div id="persons"><b>List of search records:</b></div>
	<div id="trans"><b>Transaction list</b></div>
</body>

</html>
