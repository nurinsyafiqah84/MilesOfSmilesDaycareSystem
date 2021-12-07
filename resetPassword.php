<?php
include('inc/dbconnect.php');
$date =  date("Y-m-d");

?>
<title>Reset Password</title>
<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/header.css">

<script type="text/javascript">
	function invalid(){

		$.confirm({
			boxWidth: '27%',
			title: 'Error',
			content: 'Sorry, there was a problem to reset your account. <br>Please try again.',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				tryAgain: {
					text: 'Try again',
					btnClass: 'btn-red',
					action: function(){
						window.location.replace('signIn.php');
					}
				}
			}
		});		
	}
	function notExist(){

		$.confirm({
			boxWidth: '27%',
			title: 'No match found',
			content: 'Username and email entered are not a match and cannot be found.',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				tryAgain: {
					text: 'Try again',
					btnClass: 'btn-red',
					action: function(){
						window.location.replace('signIn.php');
					}
				}
			}
		});		
	}

	function succeed(){
		$.confirm({
			boxWidth: '27%',
  			title: 'Reset password!',
		    content: 'Temporary password has successfully sent to your email. <br>Please log in immediately with the temporary password and proceed to update your new password.',
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

$tempPass = "";

if(isset($_POST['forgotusername']) && isset($_POST['forgotemail'])){
	function random_str(
	    int $length = 64,
	    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
	): string {
	    if ($length < 1) {
	        throw new \RangeException("Length must be a positive integer");
	    }
	    $pieces = [];
	    $max = mb_strlen($keyspace, '8bit') - 1;
	    for ($i = 0; $i < $length; ++$i) {
	        $pieces []= $keyspace[random_int(0, $max)];
	    }
	    return implode('', $pieces);
	}

	$username = $_POST['forgotusername'];
	$email = $_POST['forgotemail'];

	$sqlCheckAcc = "SELECT username FROM administrator WHERE username = '".$username."' AND email = '".$email."'";

	$resultCheck = $conn->query($sqlCheckAcc);

	if($resultCheck->num_rows > 0){
		$tempPass = random_str(32);
		$sqlReset = "UPDATE administrator SET password = '".$tempPass."' WHERE username = '".$username."' AND email = '".$email."'";

		if($conn->query($sqlReset) === TRUE){
			
		}else{
			?>
			<body onload="invalid()"></body>
			<?php
		}

	}else{

		$sqlCheckAcc = "SELECT username FROM parent WHERE username = '".$username."' AND email = '".$email."'";

		$resultCheck = $conn->query($sqlCheckAcc);
		if($resultCheck->num_rows > 0){
			$tempPass = random_str(32);
			$sqlReset = "UPDATE parent SET password = '".$tempPass."' WHERE username = '".$username."' AND email = '".$email."'";

			if($conn->query($sqlReset) === TRUE){
				
			}else{
				?>
				<body onload="invalid()"></body>
				<?php
			}
		}else{
			?>
			<body onload="notExist()"></body>
			<?php

		}
	}

	require_once 'vendor/autoload.php';
	//create transport
	$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
		->setUsername("notify.sbppp@gmail.com")
		->setPassword("SBPPP2020");

	// Create the Mailer using your created Transport
	$mailer = new Swift_Mailer($transport);

	// Create a message
	$message = new Swift_Message();	

	$message->setSubject('[MoSD] Password Reset');
	$message->setFrom(['notify.sbppp@gmail.com' => 'Miles of Smiles Daycare Notification']);
	$message->setTo($email);
	$message->setBody('<html>' .
						'<body>' .
							'<div style="font-size: 14px; font-family: sans-serif; text-align: justify;">'.
								'Hi, <b>'. $username.'</b>, '. 
								'<br><br>'.
								'Your request to reset your account password is successfully done! Please <a href="http://localhost/milesofsmilesdaycaresystem/signIn.php" target="_blank">sign in</a> to Miles of Smiles Daycare System with the temporary password below and secure your account by changing to a new password.'.
								'<br><br>'.			
								'<div align="center">'.
									'Temporary Password:<br>'.
									'<div style="width=60%; border: 1px solid black; padding-top: 15px; padding-bottom: 15px; height: 250px; background-color: rgba(205,214,209,255); font-size: 17px; font-weight: bolder;"> ' .
										$tempPass .
									'</div>' .

									'<br><br><table border="0" width="70%">'.					
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
													'	'.date("H:i A", time()+28800).' '.
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
	<body onload="succeed()"></body>
	<?php
}else{
	?>
	<body onload="invalid()"></body>
	<?php
}





?>