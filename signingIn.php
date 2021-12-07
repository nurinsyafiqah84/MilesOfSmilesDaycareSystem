<?php
session_start();
include('inc/dbconnect.php');

?>
<title>Validating..</title>
<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/header.css">

<script type="text/javascript">

	function nanny(){
		$.confirm({
			boxWidth: '27%',
  			title: 'Successful authentication!',
		    content: 'You will be redirected to nanny homepage now.',
		    type: 'green',
		    typeAnimated: true,
		    useBootstrap: false,
		    buttons: {
		        ok: {
		        	boxWidth: '27%',
		            text: 'Okay',
		            btnClass: 'btn-green',		            
		            action: function(){
		            	window.location.replace('Nanny');
		            }
		        }
		    }
		});
	}

	function administrator(){
		$.confirm({
			boxWidth: '27%',
  			title: 'Successful authentication!',
		    content: 'You will be redirected to administrator homepage now.',
		    type: 'green',
		    typeAnimated: true,
		    useBootstrap: false,
		    buttons: {
		        ok: {
		        	boxWidth: '27%',
		            text: 'Okay',
		            btnClass: 'btn-green',		            
		            action: function(){
		            	window.location.replace('Administrator');
		            }
		        }
		    }
		});
	}

	function parent(){
		$.confirm({
			boxWidth: '27%',
  			title: 'Successful authentication!',
		    content: 'You will be redirected to parent homepage now.',
		    type: 'green',
		    typeAnimated: true,
		    useBootstrap: false,
		    buttons: {
		        ok: {
		        	boxWidth: '27%',
		            text: 'Okay',
		            btnClass: 'btn-green',		            
		            action: function(){
		            	window.location.replace('Parent');
		            }
		        }
		    }
		});
	}


</script>

<?php
if(isset($_POST['submit']))
{
	if(!isset($_SESSION['username']))
	{
		$_SESSION['username'] = $_POST['username'];		
	}

	$username = $_POST['username'];
	$password = $_POST['password'];


	$sqlNanny = "SELECT * FROM nanny WHERE username = '".$username."' AND password = '".$password."'";
	$sqlAdmin = "SELECT * FROM administrator WHERE username = '".$username."' AND password = '".$password."'";
	$sqlParent = "SELECT * FROM parent WHERE username = '".$username."' AND password = '".$password."'";

	$resultNanny = $conn->query($sqlNanny);
	$resultAdmin = $conn->query($sqlAdmin);
	$resultParent = $conn->query($sqlParent);

	if($resultNanny->num_rows == 1){
		?>
		<body onload="nanny()">
			
		</body>
		<?php
	}else if($resultAdmin->num_rows == 1){
		?>
		<body onload="administrator();">
			
		</body>
		<?php
	}else if($resultParent->num_rows == 1){
		?>
		<body onload="parent();">
			
		</body>
		<?php
	}else{
		$_SESSION['signInAttempt'] += 1;
		$attempt = 3 - $_SESSION['signInAttempt'];
		if($attempt == 0)
		{
			?>
			<body onload="attemptLimit();">		

			</body>
			<?php
		}
		else
		{
			?>
			<body onload="invalid();">		

			</body>
			<?php
		}
	}




}else{
	?>
		<body onload="invalid()"></body>
		<?php
}
?>

<script type="text/javascript">
	function invalid(){

		$.confirm({
			boxWidth: '27%',
			title: 'Account not found!',
			content: 'Incorrect username or password.<br><?php echo $attempt ?> attempt(s) left.',
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

	function attemptLimit(){
		$.confirm({
			boxWidth: '27%',
			title: 'Exceed attempt limit!',
			content: 'You have exceeded the sign in attempts.<br>Please try again after 10 seconds.',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				ok: {
					text: 'Okay',
					btnClass: 'btn-red',
					action: function(){
						window.location.replace('signIn.php');
					}
				}
			}
		});
	}
</script>