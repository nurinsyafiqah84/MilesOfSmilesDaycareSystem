<?php
session_start();

include('inc/dbconnect.php');
include('processValidateUsername.php');

$sqlNannyProfile = "SELECT * FROM NANNY"; //to display nanny records on about us section 
$resultNannyProfile = $conn->query($sqlNannyProfile);

if(isset($_SESSION['applicationID']) && isset($_SESSION['applicationEmail'])){
	unset($_SESSION['applicationID']);
	unset($_SESSION['applicationEmail']);
}else if(isset($_SESSION['username'])){
	unset($_SESSION['username']);
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<title>Welcome to Miles of Smiles Daycare System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>


	<script type="text/javascript">		
		$( window ).scroll(function() {

			$("#stickyholder").css("visibility", "visible");

		});

		$(document).ready(function()
		{

			$("#clickregister").click(function(){
				$("#registerform").show();
				
			});

			$("#clickmore").click(function(){
				$("#hiringscope").show();
				$("#nannyprofile").show();
				$("#viewform").show();
			});

			$("#hideregisterform").click(function(){
				$("#registerform").hide();
			});

			$("#hidenannyprofile").click(function(){
				$("#nannyprofile").hide();
			});

			$("#hidehiringscope").click(function(){
				$("#hiringscope").hide();
			});

			$("#hideviewapp").click(function() {
				$("#viewform").hide();
			});


			<?php
					if(isset($_GET['open'])){
			?>
			
				$("#viewform").show();
			
			<?php
		}
			?>

		});

		$(function(){
			$('#repassword').change(function() { 
				var pw = $('#password').val();
				var repw = $('#repassword').val();

				if(pw != repw){
					$.confirm({
						boxWidth: '27%',
					    title: 'Mismatch password!',
					    content: 'The confirm password must match with the password.',
					    type: 'red',
					    typeAnimated: true,
					    useBootstrap: false,
					    buttons: {
					        tryAgain: {
					            text: 'Try again',
					            btnClass: 'btn-red',
					            action: function(){
					        		$('#submit').prop('disabled', true);
							        $('#reset').prop('disabled', true);
							        $('#submit').css({ 'color': 'white', 'background-color': 'gray' });
							        $('#reset').css({ 'color': 'white', 'background-color': 'gray' });
					            }
					        }
					    }
					});
				}else{

					$('#submit').prop('disabled', false);
			        $('#reset').prop('disabled', false);
			        $('#submit').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
			        $('#reset').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
				}
			});
		});


		function validateRegistrationForm(){

			var username = regForm.username.value;
			var password = regForm.password.value;
			var repassword = regForm.repassword.value;
			var fullName = regForm.fullName.value;
			var email = regForm.email.value;
			var phone = regForm.phone.value;
			var address = regForm.address.value;

			if(username.length == 0 || password.length == 0 || repassword.length == 0 || email.length == 0 || fullName.length == 0 || phone.length == 0 || address.length == 0){
				$.confirm({
					boxWidth: '27%',
				    title: 'Blank field!',
				    content: 'Please fill in all required fields.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){
				            }
				        }
				    }
				});

				return false;
			}
			else if(username.length < 5 || username.length > 50){

				$.confirm({
					boxWidth: '27%',
				    title: 'Invalid username!',
				    content: 'Username must be between 4 to 50 characters only.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){
				            }
				        }
				    }
				});
				return false;
			}else if(/\s/.test(username)){
				$.confirm({
					boxWidth: '27%',
				    title: 'Username contains whitespaces.',
				    content: 'Username shall not have any whitespaces. Please try again',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){
				            }
				        }
				    }
				});
				return false;
			}else if(password.length < 8 || password.length > 100){
				$.confirm({
					boxWidth: '27%',
				    title: 'Invalid password!',
				    content: 'Password must be between 8 to 100 characters only.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){
				            }
				        }
				    }
				});
				return false;
			}else if(repassword != password){

				$.confirm({
					boxWidth: '27%',
				    title: 'Mismatch password!',
				    content: 'The confirm password must match with the password.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){
				            }
				        }
				    }
				});
				return false;
			}else if(fullName.length < 1 || fullName.length > 100){

				$.confirm({
					boxWidth: '27%',
				    title: 'Invalid full name!',
				    content: 'Full name must be between 1 to 100 characters only.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){
				            }
				        }
				    }
				});
				return false;
			}else if(email.length < 3 || email.length > 200){

				$.confirm({
					boxWidth: '27%',
				    title: 'Invalid length of email!',
				    content: 'Full name must be between 3 to 200 characters only.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){
				            }
				        }
				    }
				});
				return false;
			}else if(phone.length < 10 || phone.length > 11){
				$.confirm({
					boxWidth: '27%',
				    title: 'Invalid phone!',
				    content: 'Phone must be between 10 to 11 digits only.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){
				            }
				        }
				    }
				});
				return false;
			}else if(address.length < 10 || address.length > 200){
				$.confirm({
					boxWidth: '27%',
				    title: 'Invalid address!',
				    content: 'Adress must be between 10 to 200 characters only.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){
				            }
				        }
				    }
				});
				return false;
			}
		}

		function validateViewApp(){
			var appID = viewappform.applicationID.value;
			var email = viewappform.viewemail.value;

			if(appID.length == 0 || email.length == 0){
				$.confirm({
					boxWidth: '27%',
				    title: 'Blank field!',
				    content: 'Please fill in all required fields.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){
				            }
				        }
				    }
				});		
				return false;		
			}else if(appID.length < 4){
				$.confirm({
					boxWidth: '27%',
				    title: 'Invalid Application ID',
				    content: 'Application ID must be atleast 4 digit',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){
				            }
				        }
				    }
				});		
				return false;		

			}else{
				return true;
			}
		}
	</script>
</head>

<body>
	<div class="outerbox" id="top">

		<div class="innerbox">
			<header>
				<a href="" class="logo">Miles of Smiles Daycare System</a>
				<nav>
					<ul>
						<li>
							<a href="signIn.php"><i title="sign in" style="font-size: 16px;" class="fa fa-sign-in" aria-hidden="true"></i></a>
						</li>
					</ul>
				</nav>
				<div class="clearfix"></div>
			</header>

			<div class="description">				
				<h3 style="width: auto; letter-spacing: 2px; line-height: 30px; ">A trusted, restful and safe child care choice for our local families</h3>
				Miles of Smiles Daycare,
				A popular, flexible child care choice for children from age 0 to 12.
				We are a trusted part of the local community, with an exceptional team of friendly, approachable nanny staff who love helping little eyes and smiles light up every day. 
			</div>
			

			<!-- RIGHT FLOAT NAV BUTTON -->
			<div class="loginholder">
				<a id="clickregister" href="#registerform">Register</a>
				<a id="clickmore" href="#nannyprofile">About Us</a>
			</div>


			<!-- REGISTER FORM -->


			<div id="registerform" class="scope" style="display:none">
				<div class="titleTextHolder">
					Register Account for Parent/ Custodian
				</div>
				<hr>
				<p id="hideregisterform" class="hide">hide</p>

				<center>
					<form action="registerAccount.php" enctype="multipart/form-data" method="POST" name="regForm" onsubmit="return validateRegistrationForm()">
						<div class="informationHolder" align="center">
							PARENT ACCOUNT
						</div>
						<div class="information">
							<table align="center" cellpadding="5">
								<tr>
									<td>USERNAME</td>
									<td><input type="text" placeholder="username" id="username" name="username"><span></span><span class="asterisk_input">  </span></td>
								</tr>
								<tr>
									<td>PASSWORD</td>
									<td><input type="password" placeholder="password" id="password" name="password"><span class="asterisk_input"></span></td>
								</tr>
								<tr>
									<td>CONFIRM PASSWORD</td>
									<td><input type="password" placeholder="re-type password" id="repassword" name="repassword"><span class="asterisk_input"></span></td>
								</tr>
								<tr>
									<td>FULL NAME</td>
									<td><input type="text" placeholder="full name" name="fullName"><span class="asterisk_input"></span></td>
								</tr>
								<tr>
									<td>E-MAIL</td>
									<td><input type="email" id="email" placeholder="example@example" name="email"><span></span><span class="asterisk_input"></span></td>
								</tr>
								<tr>
									<td>PICTURE</td>
									<td>
										<input type="file" accept="image/png, image/gif, image/jpeg, image/jpg" name="picture" /><br>
										<p class="warning"><strong>Note:</strong>Only picture of type .png, .gif, .jpeg and .jpg are allowed.</p>
									</td>
								</tr>
								<tr>
									<td>PHONE</td>
									<td><input type="number" placeholder="number" name="phone" /><span class="asterisk_input"></span></td>
								</tr>
								<tr>
									<td>ADDRESS</td>
									<td><input type="text" name="address" placeholder="address"><span class="asterisk_input"></span></td>
								</tr>
								<tr>
									<td colspan="2" style="text-align: center;"><input id="reset" class="submit" type="reset" name="reset" value="Reset"><input type="submit" id="submit" class="submit" name="regSubmit" value="Register"></td>
								</tr>
							</table>
						</div>
					</form>
				</center>


			</div>





			<!-- ADDITIONAL INFORMATION 
				NANNY
			-->

			<div id="nannyprofile" class="scope" style="display: none;margin-top: 16px;">
				<div class="titleTextHolder">
					Our Nanny
				</div>
				<hr>
				<p id="hidenannyprofile" class="hide">hide</p>
				<center>
					<?php
						while($rowNanny = $resultNannyProfile->fetch_assoc()){
							?>
							<div class="nannyprofileholder">							
								<?php
								
								if(empty($rowNanny['gambar']))
								{ ?>
									<div align="center" class="profile-pic" style="background-image: url('attachment/profile/nannyicon.png');" >
									</div>
									
									<?php 
								
								}
								else
								{
									?>
									<div align="center" class="profile-pic" style="background-image: url('attachment/profile/<?php echo $rowNanny['gambar'] ?>'); " >
									</div>
									
									<?php
								}
								echo "<b>" . $rowNanny['fullName'] . "</b>";
							?>
							</div>
							<?php
						}
					?>

				</center>
			</div>

			<!--
				APPLICATION FOR NANNY POSITION
			-->

			<div id="hiringscope" class="scope" style="display:none;">
				<div class="titleTextHolder">
					Nanny Application
				</div>
				<hr>
				<p id="hidehiringscope" class="hide">hide</p>
				<center><h2 style="color: brown; font-weight: bolder;">We are always looking for great, loving nanny!</h2></center>
				<div id="hiringscopeinner">
					
					
					<center>
						<p id="jobdescription">Interested in spending most of your weekdays in daycare environment? <br>Have passion and love watching children grow and learn new things in front of your eyes? <br>Have experience in relevant courses or any skills about children? Click button below to join us!
							<div class="clickbutton">
								<a target="_blank" href="NannyApplication" >Apply for Nanny</a>
							</div>
						</p>
					</center>
				</div>

			</div>

			<!-- 
				VIEW JOB APPLICATION
			-->
			<div id="viewform" class="scope" style="display:none;">
				<div class="titleTextHolder">
					View Application
				</div>
				<hr>
				<p id="hideviewapp" class="hide">hide</p>
				<center>
					
					<form action="NannyApplication/viewApplication.php" name="viewappform" method="POST" onsubmit="return validateViewApp()">
						<div class="informationHolder" align="center">
							NANNY APPLICATION
						</div>
						<div class="information">
							<table align="center" cellpadding="5">
								<tr>
									<td>APPLICATION ID</td>
									<td>
										<input type="text" name="applicationID" id="applicationID"><span class="asterisk_input"></span>
									</td>
								</tr>
								<tr>
									<td>E-MAIL</td>
									<td><input type="email" name="viewemail" id="viewemail"><span class="asterisk_input"></span></td>
								</tr>
								<tr>
									<td colspan="2" style="text-align:center;">
										<input type="submit" name="submitView" class="submit">
									</td>
								</tr>
							</table>
						</div>
						
					</form>
				</center>

			</div>


		</div>
			    	
	</div>
	<?php

	include('footer.php');
	?>
	<div id="stickyholder" style="visibility: hidden;">
		<a class="sticky" href="#top">
			<i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i>
		</a>
	</div>
</body>

</html>


<script src="js/validateUsername.js"></script> <!-- AJAX load username/ email without reloading the page -->
