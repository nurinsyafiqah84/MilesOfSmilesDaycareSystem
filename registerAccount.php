<?php
include('inc/dbconnect.php');
$sqlGetCat = "SELECT * FROM settingfee";
$resultGetCat = $conn->query($sqlGetCat);
$result_set = array();

while($row = $resultGetCat->fetch_assoc()) {
    $result_set[] = $row;
}

?>
<title>Account Registeration</title>
<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/header.css">
<script type="text/javascript">
	
	function invalid(){

		$.confirm({
			boxWidth: '27%',
			title: 'Error',
			content: 'Sorry, there was a problem to register your account. Please try again',
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

	function pictTooBig(){
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

	function pictNotFound(){
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
  			title: 'Successfully Registered!',
		    content: 'You may sign in with the username and password registered now.',
		    type: 'green',
		    typeAnimated: true,
		    useBootstrap: false,
		    buttons: {
		        ok: {
		        	boxWidth: '27%',
		            text: 'Okay',
		            btnClass: 'btn-green',		            
		            action: function(){
		            	window.location.replace('signIn.php');
		            }
		        }
		    }
		});
	}


</script>

<?php

	if(isset($_POST['regSubmit']))
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		$fullName = strtoupper($_POST['fullName']);
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$address = strtoupper($_POST['address']);

		if($_FILES["picture"]["error"] == 4) //USER DOES NOT WANT TO UPLOAD A PICTURE!! IT IS OKAY. INSERT OTHER COLUMN
		{
			//INSERT EVERY COLUMN EXCEPT PICTURE
			$sqlRegister = "INSERT INTO PARENT (username, password, fullName, email, phoneNo, address) VALUES('".$username."', '".$password."', '".$fullName."', '".$email."' , '".$phone."', '".$address."')";

			if($conn->query($sqlRegister) === TRUE)
			{
				//send email function
				require_once 'vendor/autoload.php';
						//create transport
				$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
				->setUsername("notify.sbppp@gmail.com")
				->setPassword("SBPPP2020");

								// Create the Mailer using your created Transport
				$mailer = new Swift_Mailer($transport);

								// Create a message
				$message = new Swift_Message();	

				$message->setSubject('[MoSD] Account Registration at Miles of Smiles Daycare System');
				$message->setFrom(['notify.sbppp@gmail.com' => 'Miles of Smiles Daycare Notification']);
				$message->setTo($email);
				$message->setBody('<html>' .
					'<body>' .
						'<div style="font-size: 14px; font-family: sans-serif; text-align: justify;">'.
							'Hi, <b>'. $username.'</b>, '. 
							'<br><br><br>'.
							'Thank you for signing up to keep in touch with the no.1 daycare choice in community. We offer supervision and care of your children for you! Feel free to login into our system and enrol your children! Registration is open every day for the upcoming month ^^. <br><br><br>And psssst... you get to choose for how many days your children will be with us. Aint that great?!'.
							'<br><br>'.			
							'<table align="center" width="70%" border="1" style="margin-top:10px; text-align: center">'.
								'<tr><th colspan="4" align="center">MoSD FEE</th></tr>'.
								'<tr>'.
									'<th>CATEGORY</th>'.
									'<th>MIN. AGE</th>'.
									'<th>MAX. AGE</th>'.
									'<th>PRICE PER DAY (RM)</th>'.
								'</tr>'.
								'<tr>'.
									'<td>' . $result_set[0]['category'].
									'</td>'.
									'<td>' . $result_set[0]['minAge'].
									'</td>'.
									'<td>' . $result_set[0]['maxAge'].
									'</td>'.
									'<td>' . $result_set[0]['pricePerDay'].
								'</td>'.

								'</tr>'.
								'<tr>'.
									'<td>' . $result_set[1]['category'].
									'</td>'.
									'<td>' . $result_set[1]['minAge'].
									'</td>'.
									'<td>' . $result_set[1]['maxAge'].
									'</td>'.
									'<td>' . $result_set[1]['pricePerDay'].
									'</td>'.

								'</tr>'.
								'<tr>'.
									'<td>' . $result_set[2]['category'].
									'</td>'.
									'<td>' . $result_set[2]['minAge'].
									'</td>'.
									'<td>' . $result_set[2]['maxAge'].
									'</td>'.
									'<td>' . $result_set[2]['pricePerDay'].
									'</td>'.

								'</tr>'.
								'<tr>'.
									'<td>' . $result_set[3]['category'].
									'</td>'.
									'<td>' . $result_set[3]['minAge'].
									'</td>'.
									'<td>' . $result_set[3]['maxAge'].
									'</td>'.
									'<td>' . $result_set[3]['pricePerDay'].
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
		else //USER WANTS TO UPLOAD A PICTURE
		{
			$file = $_FILES["picture"];
			$fileName = $file['name'];
			$fileTmpName = $file['tmp_name'];
			$fileSize = $file['name'];
			$fileError = $file['error'];

			$fileExt = explode('.', $fileName);
			$fileActualExt = strtolower(end($fileExt));

			if($fileError == 0) //PICTURE HAS NO PROBLEM 
			{
				if($fileSize < 10000) //PICTURE DOES NOT EXCEED 10MB
				{
					$fileNameNew = $username . "_profile." . $fileActualExt ;
					$fileDestination = 'attachment/profile/'.$fileNameNew;
					move_uploaded_file($fileTmpName, $fileDestination);

					//INSERT EVERY COLUMN
					$sqlRegister = "INSERT INTO PARENT VALUES('".$username."', '".$password."', '".$fullName."', '".$email."' , '".$fileNameNew."', '".$phone."', '".$address."')";

					if($conn->query($sqlRegister) === TRUE)
					{
						//send email function
						require_once 'vendor/autoload.php';
										//create transport
						$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
						->setUsername("notify.sbppp@gmail.com")
						->setPassword("SBPPP2020");

										// Create the Mailer using your created Transport
						$mailer = new Swift_Mailer($transport);

										// Create a message
						$message = new Swift_Message();	

						$message->setSubject('[MoSD] Account Registration at Miles of Smiles Daycare System');
						$message->setFrom(['notify.sbppp@gmail.com' => 'Miles of Smiles Daycare Notification']);
						$message->setTo($email);
						$message->setBody('<html>' .
							'<body>' .
								'<div style="font-size: 14px; font-family: sans-serif; text-align: justify;">'.
									'Hi, <b>'. $username.'</b>, '. 
									'<br><br><br>'.
									'Thank you for signing up to keep in touch with the no.1 daycare choice in community. We offer supervision and care of your children for you! Feel free to login into our system and enrol your children! Registration is open every day for the upcoming month ^^. <br><br><br>And psssst... you get to choose for how many days your children will be with us. Aint that great?!'.
									'<br><br>'.			
									'<table align="center" width="70%" border="1" style="margin-top:10px; text-align: center">'.
										'<tr><th colspan="4" align="center">MoSD FEE</th></tr>'.
										'<tr>'.
											'<th>CATEGORY</th>'.
											'<th>MIN. AGE</th>'.
											'<th>MAX. AGE</th>'.
											'<th>PRICE PER DAY (RM)</th>'.
										'</tr>'.
										'<tr>'.
											'<td>' . $result_set[0]['category'].
											'</td>'.
											'<td>' . $result_set[0]['minAge'].
											'</td>'.
											'<td>' . $result_set[0]['maxAge'].
											'</td>'.
											'<td>' . $result_set[0]['pricePerDay'].
										'</td>'.

										'</tr>'.
										'<tr>'.
											'<td>' . $result_set[1]['category'].
											'</td>'.
											'<td>' . $result_set[1]['minAge'].
											'</td>'.
											'<td>' . $result_set[1]['maxAge'].
											'</td>'.
											'<td>' . $result_set[1]['pricePerDay'].
											'</td>'.

										'</tr>'.
										'<tr>'.
											'<td>' . $result_set[2]['category'].
											'</td>'.
											'<td>' . $result_set[2]['minAge'].
											'</td>'.
											'<td>' . $result_set[2]['maxAge'].
											'</td>'.
											'<td>' . $result_set[2]['pricePerDay'].
											'</td>'.

										'</tr>'.
										'<tr>'.
											'<td>' . $result_set[3]['category'].
											'</td>'.
											'<td>' . $result_set[3]['minAge'].
											'</td>'.
											'<td>' . $result_set[3]['maxAge'].
											'</td>'.
											'<td>' . $result_set[3]['pricePerDay'].
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
					<body onload="pictTooBig();"></body>
					<?php 
				}
			}
			else
			{
				?>
				<body onload="pictNotFound();"></body>
				<?php
			}
			
		}

	}else{
		?>
		<body onload="invalid()"></body>
		<?php
	}
?>