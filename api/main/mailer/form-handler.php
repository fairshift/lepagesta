<?php
include('SMTPClass.php');

//email confirmation quick reminder
$GLOBALS['email_templates']['confirmation']['emailfrom'] =		"register@fairshift.org";
$GLOBALS['email_templates']['confirmation']['subject'] = 		"Lepa gesta reflections (Please confirm your email)";
$GLOBALS['email_templates']['confirmation']['message'] = 		'Dear %username%, visit <a href="http://fairshift.org/?code=%email_confirmation_code%">this link to confirm your email</a>. '.
																"Not you? Please ignore this email. ".
																"Have a nice day!";

//email invitation for 
$GLOBALS['email_templates']['cowriting_invitation']['emailfrom'] =	"invite@fairshift.org";
$GLOBALS['email_templates']['cowriting_invitation']['subject'] = 	"Invitation to Lepa gesta reflections";
$GLOBALS['email_templates']['cowriting_invitation']['message'] =  	"Dear, %username% invited you to cocreating %story% story on %sphereUrl%.".
																	"<a href=''>Downloading this Firefox extension</a> necessary to view and add your piece to the story.<br/>".
																	"Register with this %email_confirmation_code% or by visiting <a href='http://fairshift.org/?code=%email_confirmation_code%'>this link</a>.<br/>".
																	"%username% also left an appreciation for you. Once you write one of %number% secret keyword%plural% of value%plural% they feel you stand for, their appreciation will be shown to you.".
																	"Not interested? Please ignore this email.<br/>".
																	"Have a nice day!";

function mailer($emailto, $data, $function){

	//replace %variable% with $data['variable'];

	$emailfrom = $GLOBALS['email_templates'][$function]['emailfrom'];
	$emailto = $emailto;

	if(filter_var($emailto, FILTER_VALIDATE_EMAIL)){
		// retrieve parameters
		$subject = $GLOBALS['email_templates'][$function]['subject'];
		$message = $GLOBALS['email_templates'][$function]['message'];
		foreach($data as $key => $value){
			$subject = str_replace('%'.$key.'%', $value, $subject);
			$message = str_replace('%'.$key.'%', $value, $message);
		}
		$message = '<html><body>'.$message.'</body></html>';
		$response = sendEmail($subject, $message, $emailto, $emailfrom, 0);

		if($response == true){
			$response = $GLOBALS['email_templates'][$function]['success'];
		} else {
			$response = $GLOBALS['email_templates'][$function]['fail'];
		}
	} else {
		$response = $GLOBALS['email_templates'][$function]['fail'];
	}
}

// Run server-side validation
function sendEmail($subject, $content, $emailto, $emailfrom, $use_smtp = 0) {
	
	$from = $emailfrom;
	$subject =  filter($subject);
	$message = $content;

	$response = false;
	
	// Validate return email & inform admin
	$emailto = filter($emailto);

	// Setup final message
	//$body = wordwrap($message);
	$body = $message;

	if($use_smtp == '1'){
	
		$SmtpServer = 'SMTP SERVER';
		$SmtpPort = 'SMTP PORT';
		$SmtpUser = 'SMTP USER';
		$SmtpPass = 'SMTP PASSWORD';
		
		$to = $emailto;
		$SMTPMail = new SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $subject, $body);
		$SMTPChat = $SMTPMail->SendMail();
		$response = $SMTPChat ? $response_sent : $response_error;
		
	} else {
		
		// Create header
		$headers = "From: $from\r\n";
		$headers .= "Reply-To: $from\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/plain; charset=utf-8\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$headers = "From: $from\r\n";
		$headers .= "Reply-To: $from\r\n";
		//$headers .= "CC: susan@example.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		// Send email
		$response = @mail($emailto, $subject, $body, $headers);
		
	}
	return $response;
}

// Remove any un-safe values to prevent email injection
function filter($value) {
	$pattern = array("/\n/", "/\r/", "/content-type:/i", "/to:/i", "/from:/i", "/cc:/i");
	$value = preg_replace($pattern, "", $value);
	return $value;
}

?>