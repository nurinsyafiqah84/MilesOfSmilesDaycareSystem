<?php
include('../inc/dbconnect.php');
session_start();


$date =  date("Y-m-d");
$reply = $_POST['inputreply'];
$applicationID = $_POST['appID'];
$applicationEmail = $_POST['appemail'];

$_SESSION['applicationID'] = $applicationID;
$_SESSION['applicationEmail'] = $applicationEmail;

$sqlEmail = "SELECT email FROM administrator";
$resultEmail = $conn->query($sqlEmail);
$listmails = array();

while($rowEmail = $resultEmail->fetch_assoc()){
	array_push($listmails, $rowEmail['email']);
}

?>
	<title>Sending reply..</title>
	<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<script type="text/javascript">
		function invalid(){

		$.confirm({
			boxWidth: '27%',
			title: 'Error',
			content: 'Sorry, there was a problem to send your reply. Please try again',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				tryAgain: {
					text: 'Try again',
					btnClass: 'btn-red',
					action: function(){						
						window.location.replace("viewApplication.php?reload");
					}
				}
			}
		});		
	}
	function fileTooBig(){
		$.confirm({
			boxWidth: '27%',
			title: 'File is too big',
			content: 'Please use another picture. Maximum size is 10MB',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				tryAgain: {
					text: 'Try again',
					btnClass: 'btn-red',
					action: function(){
						window.location.replace("viewApplication.php?reload");
					}
				}
			}
		});
	}
	function fileNotFound(){
		$.confirm({
			boxWidth: '27%',
			title: 'File not found',
			content: 'Please try again',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				tryAgain: {
					text: 'Try again',
					btnClass: 'btn-red',
					action: function(){
						window.location.replace("viewApplication.php?reload");
					}
				}
			}
		});
	}

	function succeed(){
		window.location.replace("viewApplication.php?reload");
	}
	</script>
<?php

if($_FILES["inputfile"]["error"] == 4) //USER DOES NOT WANT TO UPLOAD FILE!! IT IS OKAY. INSERT OTHER COLUMN
{
	//INSERT EVERY COLUMN EXCEPT FILE
	$sqlReply = "INSERT INTO reply (reply, replySender, applicationID) VALUES('".$reply."', '".$applicationID."', '".$applicationID."')";
	if($conn->query($sqlReply) === TRUE)
	{
		require_once '../vendor/autoload.php';
				//create transport
		$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
		->setUsername("notify.sbppp@gmail.com")
		->setPassword("SBPPP2020");

				// Create the Mailer using your created Transport
		$mailer = new Swift_Mailer($transport);

				// Create a message
		$message = new Swift_Message();	
		$message->setSubject('[MoSD] #'.$applicationID.' New Reply from Nanny Applicant');
		$message->setFrom(['notify.sbppp@gmail.com' => 'Miles of Smiles Daycare Notification']);
		$message->setTo($listmails);
		$message->setBody('<html>' .
			'<body>' .
			'<div style="font-size: 14px; font-family: sans-serif; text-align: justify;">'.
			'Attention dearest administrator, '. 
			'<br><br>'.
			'You just received a new reply for nanny application from applicant #'. $applicationID .
			'. The snippet of the message is as follows \'' . substr($reply, 0, 150) . ' ...\''.
			'. Please log into the system to read the remaining message and take further action.'.
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
		<body onload="succeed();"></body>
		<?php
	}
	else
	{
		?>
		<body onload="invalid();"></body>
		<?php
	}
}
else //USER WANTS TO UPLOAD A FILE
{
	$file = $_FILES["inputfile"];
	$fileName = $file['name'];
	$fileTmpName = $file['tmp_name'];
	$fileSize = $file['name'];
	$fileError = $file['error'];
	$fileExt = explode('.', $fileName);
	$fileActualExt = strtolower(end($fileExt));
	if($fileError == 0) //FILE HAS NO PROBLEM 
	{
		if($fileSize < 10000) //FILE DOES NOT EXCEED 10MB
		{
			$fileNameNew = $applicationID . "_" . uniqid('', true) . "_". $fileName ;
			$fileDestination = 'uploads/'.$fileNameNew;
			move_uploaded_file($fileTmpName, $fileDestination);

			//INSERT EVERY COLUMN
			$sqlReply = "INSERT INTO reply (reply, attachment, replySender, applicationID) VALUES('".$reply."', '".$fileNameNew."' ,'".$applicationID."', '".$applicationID."')";

			if($conn->query($sqlReply) === TRUE)
			{
				require_once '../vendor/autoload.php';
				//create transport
				$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
							->setUsername("notify.sbppp@gmail.com")
							->setPassword("SBPPP2020");

				// Create the Mailer using your created Transport
				$mailer = new Swift_Mailer($transport);

				// Create a message
				$message = new Swift_Message();	
				$message->setSubject('[MoSD] #'.$applicationID.' New Reply from Nanny Applicant');
				$message->setFrom(['notify.sbppp@gmail.com' => 'Miles of Smiles Daycare Notification']);
				$message->setTo($listmails);
				$message->setBody('<html>' .
									'<body>' .
										'<div style="font-size: 14px; font-family: sans-serif; text-align: justify;">'.
											'Attention dearest administrator, '. 
											'<br><br>'.
											'You just received a new reply for nanny application from applicant #'. $applicationID .
											'. The snippet of the message is as follows \'' . substr($reply, 0, 150) . ' ...\''.
											'. Please log into the system to read the remaining message and take further action.'.
											'<br><br>'.			
											'<table border="0" width="70%" align="center">'.					
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
				<body onload="succeed();"></body>
				<?php
			}
			else
			{
				?>
				<body onload="invalid();"></body>
				<?php
			}
		}
		else
		{
			?>
			<body onload="fileTooBig();"></body>
			<?php
		}
	}
	else
	{
		?>
		<body onload="fileNotFound();"></body>
		<?php
	}
	
}

?>