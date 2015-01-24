<?php
require_once("utils/constants.php");
require_once("utils/dbconnection.php");
require 'mailer/PHPMailerAutoload.php';

require_once('./fpdi/fpdf.php');
require_once('./fpdi/fpdi.php');
require_once('./fpdi/FPDI_Protection.php');
require_once 'session.php';

function generatePdf($recipientName, $tanArray){
	
	//header("Location: functions/pdfgenerator.php"); /* Redirect browser */
	//exit();
			
	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(40,10,"Dear " . $recipientName);
	$pdf->Ln(10);
	$pdf->Cell(40,10,"here is you TAN list");
	$pdf->Ln(10);
	$pdf->Ln(10);
	
	$index = 1 ;
	foreach($tanArray as $tan){
		$pdf->Cell(40,10, $index . "   ". $tan);
		$pdf->Ln(10);
		$index = $index + 1;
	}
	
	$pdf->Ln(10);
	$pdf->Cell(40,10,"Secure Coding - Team 16");
	$pdf->Ln(10);
	
	$filename="./pdf/". $recipientName . ".pdf";
	
	if (file_exists($filename)) {
		unlink($filename);
	}
	
	$pdf->Output($filename,'F');
	
	$encryptedFile = encryptPdf($filename,$recipientName);
	return $encryptedFile;
}    

function encryptPdf($filename, $recipientName){
			
	//Password for the PDF file (I suggest using the email adress of the purchaser).
	$password = $recipientName;
	//Name of the original file (unprotected).
	$origFile = $filename;
	//Name of the destination file (password protected and printing rights removed).
	$destFile ="./pdf/protected_". $recipientName . ".pdf";
			
	$pdf =& new FPDI_Protection();
	$pdf->FPDF('P', 'in');
	//Calculate the number of pages from the original document.
	$pagecount = $pdf->setSourceFile($origFile);
	//Copy all pages from the old unprotected pdf in the new one.
	for ($loop = 1; $loop <= $pagecount; $loop++) {
		$tplidx = $pdf->importPage($loop);
		$pdf->addPage();
		$pdf->useTemplate($tplidx);
	}

	//Protect the new pdf file, and allow no printing, copy, etc. and
	//leave only reading allowed.
	$pdf->SetProtection(array(), $password);
	$pdf->Output($destFile, 'F');
	return $destFile;
}

function sendTansMailToUser($user_id, $connection){
	$sql = "SELECT u_id,u_name,u_email,tc.tc_code from users 
	join accounts on users.u_id = accounts.a_user join 
	transaction_codes tc on accounts.a_id  where u_id = '$user_id' and tc_account = accounts.a_id";
	$result = mysqli_query($connection,$sql);
	$i =1; 
	$tans ='<table border=\'1\'>	<tr><th>#</th><th>TAN</th></tr>';
	$tanArray = array();
	while($row = mysqli_fetch_array($result)) {
		$recipientEmail = $row['u_email'];
		$recipientName = $row['u_name'];
		$tans = $tans . "<tr><td>$i</td><td> {$row['tc_code']} </td></tr>";
		array_push($tanArray, $row['tc_code']);
		$i=$i+1;
	}
	$tans = $tans . '</table>';
	
	$encryptedFile = generatePdf($recipientName, $tanArray);
	
	if(sendMail($recipientEmail,$recipientName,$tans, $encryptedFile)){ return TRUE;}
	return FALSE;
}

function sendMail($recipientEmail,$recipientName,$tans, $encryptedFile){ 
	
		$mail = new PHPMailer;
		 
		$mail->isSMTP();                                      // Set mailer to use SMTP
			
		$mail->Host = 'smtp.gmail.com';                       // Specify main and backup server
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'g16.banking@gmail.com';                   // SMTP username
		$mail->Password = 'SecurePass!';               // SMTP password
 		$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
		$mail->Port = 587;                                    //Set the SMTP port number - 587 for authenticated TLS
		$mail->setFrom('g16.banking@gmail.com', 'Secure coding banking group 16');     //Set who the message is to be sent from
		//$mail->setFrom('team16@in.tum.de', 'Secure coding banking group 16');     //Set who the message is to be sent from
		//$mail->addReplyTo('labnol@gmail.com', 'First Last');  //Set an alternative reply-to address
		$mail->addAddress($recipientEmail);  // Add a recipient
		//$mail->addAddress('ellen@example.com');               // Name is optional
		//$mail->addCC('cc@example.com');
		//$mail->addBCC('bcc@example.com');
		$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		//$mail->addAttachment('/usr/labnol/file.doc');         // Add attachments
		//$mail->addAttachment('/images/image.jpg', 'new.jpg'); // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML
		 
		$mail->Subject = 'Secret TANs for your online banking transactions';
		$mail->Body    = 'Dear '.$recipientName.' ,<br> Please find your secure TANs for your online transactions in the attachment. <br>'
		
		
		. 'Your online banking team,<br>
		<b>G16 Secure Coding!</b>';
		$mail->AltBody = 'Dear '.$recipientName.',<br> Please use the attached secure TANs for your online transactions. <br>'
		
		. 'Your online banking team,<br>	<b>G16 Secure Coding!</b>';
		 
		$filename = $encryptedFile;
		$mail->AddAttachment($filename); // attach uploaded file
		
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
		if(!$mail->send()) {
		    //dvar_dump( 'Mailer Error: '. $recipientEmail .' '. $mail->ErrorInfo);
			return FALSE;
		}else{
		 return TRUE;
		}
}

function sendActivationCode($recipientEmail,$recipientName, $activation_code) {
		$mail = new PHPMailer;
		 
		$mail->isSMTP();                                      // Set mailer to use SMTP
			
		$mail->Host = 'smtp.gmail.com';                       // Specify main and backup server
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'g16.banking@gmail.com';                   // SMTP username
		$mail->Password = 'SecurePass!';               // SMTP password
 		$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
		$mail->Port = 587;                                    //Set the SMTP port number - 587 for authenticated TLS
		$mail->setFrom('g16.banking@gmail.com', 'Secure coding banking group 16');     //Set who the message is to be sent from
		//$mail->setFrom('team16@in.tum.de', 'Secure coding banking group 16');     //Set who the message is to be sent from
		//$mail->addReplyTo('labnol@gmail.com', 'First Last');  //Set an alternative reply-to address
		$mail->addAddress($recipientEmail);  // Add a recipient
		//$mail->addAddress('ellen@example.com');               // Name is optional
		//$mail->addCC('cc@example.com');
		//$mail->addBCC('bcc@example.com');
		$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		//$mail->addAttachment('/usr/labnol/file.doc');         // Add attachments
		//$mail->addAttachment('/images/image.jpg', 'new.jpg'); // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML
		 
		$mail->Subject = 'Email Verification';
		$mail->Body    = 'Dear '.$recipientName.' ,<br> We need to make sure that you entered the right email. Please verify your email and get started using your Secure Banking account.<br>
		<a href="'.BASE_URL.'activation/'.$activation.'">'.BASE_URL.'activation/'.$activation.'</a><br/><br/>Your online banking team,<br>
		<b>G16 Secure Coding!</b>';
		
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
		if(!$mail->send()) {
		    //dvar_dump( 'Mailer Error: '. $recipientEmail .' '. $mail->ErrorInfo);
			return FALSE;
		}else{
		 return TRUE;
		}
}

function sendResetPasswordLink($recipientEmail,$recipientName, $activation) {
		$mail = new PHPMailer;
		 
		$mail->isSMTP();                                      // Set mailer to use SMTP
			
		$mail->Host = 'smtp.gmail.com';                       // Specify main and backup server
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'g16.banking@gmail.com';                   // SMTP username
		$mail->Password = 'SecurePass!';               // SMTP password
 		$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
		$mail->Port = 587;                                    //Set the SMTP port number - 587 for authenticated TLS
		$mail->setFrom('g16.banking@gmail.com', 'Secure coding banking group 16');     //Set who the message is to be sent from
		//$mail->setFrom('team16@in.tum.de', 'Secure coding banking group 16');     //Set who the message is to be sent from
		//$mail->addReplyTo('labnol@gmail.com', 'First Last');  //Set an alternative reply-to address
		$mail->addAddress($recipientEmail);  // Add a recipient
		//$mail->addAddress('ellen@example.com');               // Name is optional
		//$mail->addCC('cc@example.com');
		//$mail->addBCC('bcc@example.com');
		$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		//$mail->addAttachment('/usr/labnol/file.doc');         // Add attachments
		//$mail->addAttachment('/images/image.jpg', 'new.jpg'); // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML
		 
		$mail->Subject = 'Reset Password';
		$mail->Body    = 'Dear '.$recipientName.' ,<br> You requested to reset your password. Please click the link below and follow the instructions.<br>
		<a href="'."".'localhost/ws14secure/php/reset_pass.php?code='.$activation.'&action=reset">'."localhost/ws14secure/php/".'reset/'.$activation.'</a><br/><br/>Your online banking team,<br>
		<b>G16 Secure Coding!</b>';
		
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
		if(!$mail->send()) {
		    //dvar_dump( 'Mailer Error: '. $recipientEmail .' '. $mail->ErrorInfo);
			return FALSE;
		}else{
		 return TRUE;
		}
}
?>
