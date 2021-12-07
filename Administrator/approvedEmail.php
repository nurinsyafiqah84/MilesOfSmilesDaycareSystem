<?php
	$emailsend = $row['email'];
	$date =  date("Y-m-d");
	require_once '../vendor/autoload.php';
				//create transport
		$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
		->setUsername("notify.sbppp@gmail.com")
		->setPassword("SBPPP2020");

				// Create the Mailer using your created Transport
		$mailer = new Swift_Mailer($transport);

				// Create a message
		$message = new Swift_Message();	
		$message->setSubject('[MoSD] #'.$applicationID.' Your Application has been approved!');
		$message->setFrom(['notify.sbppp@gmail.com' => 'Miles of Smiles Daycare Notification']);
		$message->setTo($emailsend);
		$message->setBody('<html>' .
			'<body>' .
			'<div style="font-size: 14px; font-family: sans-serif; text-align: justify;">'.
			'Congratulations, '. $row['name'] . 
			'<br><br>'.
			'We are happy to announce that your nanny application #'. $applicationID . ' has just been approved. We welcome you to our Miles of Smiles Daycare family. Let us work patiently and passionately for our kids future.' .
			'<br>Please be present at our daycare on Monday at 8am. Cannot wait to have you here^^! '.
			'<br><br>'.			
			'<table border="0" width="70%" text-align="center">'.					
			'<tr>'.
			'<th>'.
			'<div style="font-family: sans-serif; font-size: 14px; text-align: left">'.
			'	Date '.
			'</div>'.
			'</th>'.
			'<td>'.
			':'.
			'</td>'.
			'<td>'.
			'<div style="font-family: sans-serif; font-size: 14px; padding-left: 25px; text-align: left">'.
			'	'. $date .' '.
			'</div>'.
			'</td>'.
			'</tr>'.
			'<tr>'.
			'<th>'.
			'<div style="font-family: sans-serif; font-size: 14px; text-align: left; text-align: left">'.
			'	Time '.
			'</div>'.
			'</th>'.
			'<td>'.
			':'.
			'</td>'.
			'<td>'.
			'<div style="font-family: sans-serif; font-size: 14px; padding-left: 25px">'.
			'	'.date("H:i A", time()+25200).' '.
			'</div>'.
			'</td>'.
			'</tr>'.
			'</table>'.
			'</div>'.
			'<br><br>'.
			'<div style="font-size: 14px; font-family: sans-serif; text-align: justify;">'.
			'To visit the mainpage of Miles of Smiles Daycare, please go to <a href="http://localhost/milesofsmilesdaycaresystem" style="color: blue">http://localhost/milesofsmilesdaycaresystem</a>.<br><br>Thank you and have a good day ahead!.'.
			'</div>'.
			'</div>'.
			'<br>'.
			'<div style="text-align: center; color: gray; font-size: 14px; font-family: sans-serif;">'.
			'<hr>'.
			'	This is an auto-generated email. Please do not reply to this email. '.
			'</div>'.
			'</body>' .
			'</html>',
			'text/html'
		);

						//send the email

		$result = $mailer->send($message);



?>