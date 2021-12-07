<?php
include("../inc/dbconnect.php");
session_start();

?>

<title>Submitting..</title>
<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/header.css">
<script type="text/javascript">

	function invalid(){

		$.confirm({
			boxWidth: '27%',
			title: 'Error',
			content: 'Sorry, there was a problem to submit your application. Please try again',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				tryAgain: {
					text: 'Try again',
					btnClass: 'btn-red',
					action: function(){
						window.location.replace('index.php');
					}
				}
			}
		});		
	}
	function resumeTooBig(){
		$.confirm({
			boxWidth: '27%',
			title: 'Picture is too big',
			content: 'Please use another picture. Maximum size is 10MB',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				tryAgain: {
					text: 'Try again',
					btnClass: 'btn-red',
					action: function(){
						window.location.replace('index.php');
					}
				}
			}
		});
	}
	function resumeNotFound(){
		$.confirm({
			boxWidth: '27%',
			title: 'Picture not found',
			content: 'Please try again',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				tryAgain: {
					text: 'Try again',
					btnClass: 'btn-red',
					action: function(){
						window.location.replace('index.php');
					}
				}
			}
		});
	}

	function succeed(){
		$.confirm({
			boxWidth: '27%',
  			title: 'Application is successfully sent!',
		    content: 'A copy of application is sent to your email. Any update on your application will be notified by email. <br><br>You may view your application in View Application section.',
		    type: 'green',
		    typeAnimated: true,
		    useBootstrap: false,
		    buttons: {
		        ok: {
		        	boxWidth: '27%',
		            text: 'Okay',
		            btnClass: 'btn-green',		            
		            action: function(){
		            	window.location.replace('viewApplication.php');
		            }
		        }
		    }
		});
	}
</script>
<?php
	if(isset($_POST['submit'])){
		$name = strtoupper($_POST['name']);
		$dateOfBirth = $_POST['dob'];
		$email = $_POST['email'];
		$highestEduLevel = strtoupper($_POST['eduLevel']);
		$fieldOfStudy = strtoupper($_POST['fieldOfStudy']);
		$remark = $_POST['remark'];

	
		$file = $_FILES["file_resume"];
		$fileName = $file['name'];
		$fileTmpName = $file['tmp_name'];
		$fileSize = $file['name'];
		$fileError = $file['error'];
		$fileExt = explode('.', $fileName);
		$fileActualExt = strtolower(end($fileExt));

		if($fileError == 0) //RESUME HAS NO PROBLEM 
		{
			if($fileSize < 10000) //RESUME DOES NOT EXCEED 10MB
			{
				$fileNameNew = uniqid('', true) . "_".$name."_" . $fileName;
				$fileDestination = 'resume/'.$fileNameNew;
				move_uploaded_file($fileTmpName, $fileDestination);
				//INSERT
				$sqlApply = "INSERT INTO nanny_application (name, dateOfBirth, email, highestEduLevel, resume, fieldOfStudy, remark) VALUES('".$name."', '".$dateOfBirth."', '".$email."', '".$highestEduLevel."', '".$fileNameNew."', '".$fieldOfStudy."', '".$remark."')";
				if($conn->query($sqlApply) === TRUE)
				{
					$sqlID = "SELECT applicationID FROM nanny_application WHERE email = '".$email."' AND resume = '".$fileNameNew."' AND remark = '".$remark."' AND highestEduLevel = '".$highestEduLevel."'";
					$resultID = $conn->query($sqlID);
					$rowID = $resultID->fetch_assoc();
					$applicationID = $rowID['applicationID'];
					$_SESSION['applicationID'] = $applicationID;
					$_SESSION['applicationEmail'] = $email;
					require_once '../vendor/autoload.php';
					//create transport
					$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
						->setUsername("notify.sbppp@gmail.com")
						->setPassword("SBPPP2020");
					// Create the Mailer using your created Transport
					$mailer = new Swift_Mailer($transport);
					// Create a message
					$message = new Swift_Message();	
					$message->setSubject('[MoSD] #'.$applicationID.' Nanny Application Successfully Submitted');
					$message->setFrom(['notify.sbppp@gmail.com' => 'Miles of Smiles Daycare Notification']);
					$message->setTo($email);
					$message->setBody('<html>' .
										'<body>' .
											'<div style="font-size: 14px; font-family: sans-serif; text-align: justify;">'.
												'Hi, <b>'. strtoupper($name).'</b>, '. 
												'<br><br><br>'.
												'Thank you for your interest to join us as nanny! We favour kids lover with equipped skills to be one of us. Our team will get back to you soon. You may view your recently sent application in <a href="http://localhost/milesofsmilesdaycaresystem/index.php?open=viewapp">View Application</a> section. Please enter the application ID and the email as shown below to validate your credentials!<br><br><br>'.			
													'<table align="center" width="70%"  style="margin-top:10px; text-align: center">'.
														'<tr>'.
															'<th align="left">Application ID</th>'.
															'<td align="left">'. $applicationID .'</td>'.
														'</tr>'.
														'<tr>'.
															'<th align="left">E-mail</th>'.
															'<td align="left">'. $email .'</td>'.
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
					<body onload="return succeed();"></body>
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
				<body onload="resumeTooBig();"></body>
				<?php 
			}
		}
		else
		{
			?>
			<body onload="resumeNotFound();"></body>
			<?php
		}
	}else{

		?>
		<body onload="invalid()"></body>
		<?php
	}
?>