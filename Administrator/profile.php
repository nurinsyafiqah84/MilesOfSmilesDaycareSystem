<?php
session_start();
include('../inc/dbconnect.php');
$username = $_SESSION['username'];
$sqlMe = "SELECT * FROM administrator WHERE username = '$username'";
$result = $conn->query($sqlMe);
$rowMe = $result->fetch_assoc();
include('../ajaxs/updateProfile.php');


	if(isset($_POST['submitpicture'])){
		
		$file = $_FILES["file_picture"];
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
				$fileDestination = '../attachment/profile/'.$fileNameNew;
				move_uploaded_file($fileTmpName, $fileDestination);

						//INSERT EVERY COLUMN
				$sqlRegister = "UPDATE administrator SET picture = '".$fileNameNew."' WHERE username = '".$username."'";

				if($conn->query($sqlRegister) === TRUE)
				{
					unset($_POST['submitpicture']);
					echo("<script>location.href = 'profile.php?changed';</script>");
				}else{
					?>
					<script>
						invalid();
					</script>
					<?php 
				}

			}else{
				?>
				<script>
					pictTooBig();
				</script>
				<?php 
			}
		}else{
			?>
			<script>
				pictNotFound();
			</script>
			<?php 
		}

	}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<title>My Profile - <?php echo $username; ?></title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/profile.css">
	<script type="text/javascript">
			
		$(document).ready(function (){

			$('#email').css('border','1px solid transparent');
			$('#phone').css('border','1px solid transparent');
			$('#pass1').css('border','1px solid transparent');
			$('#pass2').css('border','1px solid transparent');
			$('#email').prop('readonly',true);
			$('#phone').prop('readonly',true);
			$('#pass1').prop('readonly',true);
			$('#pass2').prop('readonly',true);

			$('#changepic').click(function(){
				$('#file_picture').toggle();
				$('#submitpicture').toggle();
			});

			$('#hideUpload').click(function(){
				$('#file_picture').val('');
				$('#file_picture').toggle();
				$('#submitpicture').toggle();
			 
			});

			$('#edit').click(function(){
				$('#email').css('border','1px solid #ccc');
				$('#phone').css('border','1px solid #ccc');
				$('#pass1').css('border','1px solid #ccc');
				$('#pass2').css('border','1px solid #ccc');
				$('#email').prop('readonly',false);
				$('#phone').prop('readonly',false);
				$('#pass1').prop('readonly', false);
				$('#pass2').prop('readonly', false);
				$('#cancelEdit').show();
				$('#edit').hide();
				$('#update').css('visibility', 'visible');
			});

			$('#cancelEdit').click(function(){
				$('#cancelEdit').hide();
				$('#edit').show();
				$('#update').css('visibility', 'hidden');
				$('#email').val("<?php echo $rowMe['email']?>");
				$('#phone').val("<?php echo $rowMe['phoneNo']?>");
				$('#pass1').val("<?php echo $rowMe['password']?>");
				$('#pass2').val("<?php echo $rowMe['password']?>");
				$('#email').css('border','1px solid transparent');
				$('#phone').css('border','1px solid transparent');
				$('#pass1').css('border','1px solid transparent');
				$('#pass2').css('border','1px solid transparent');
				$('#email').prop('readonly',true);
				$('#phone').prop('readonly',true);
				$('#pass1').prop('readonly',true);
				$('#pass2').prop('readonly',true);
			});

			$('#pass1').on('blur', function(){
				var pass1 = $('#pass1').val();
				if(pass1.length < 8 || pass1.length > 100){
					$.confirm({
						boxWidth: '27%',
					    title: '',
					    content: 'Invalid length of password! Password must be between 8 to 100 characters only.',
					    type: 'red',
					    typeAnimated: true,
					    useBootstrap: false,
					    buttons: {
					        tryAgain: {
					            text: 'Try again',
					            btnClass: 'btn-red',
					            action: function(){
					            	$('#pass1').val("");									
					            }
					        }
					    }
					});
				}
			});


			$('#pass2').on('blur', function(){
				var pass1 = $('#pass1').val();
				var pass2 = $('#pass2').val();

				
				if(pass2!=pass1){
					$.confirm({
						boxWidth: '27%',
						title: '',
						content: 'Password and confirm password is mismatch. Please try again.',
						type: 'red',
						typeAnimated: true,
						useBootstrap: false,
						buttons: {
							tryAgain: {
								text: 'Try again',
								btnClass: 'btn-red',
								action: function(){
									$('#pass1').val("");
									$('#pass2').val("");
								}
							}
						}
					});
				}
			});

			$('#email').on('blur', function(){
				var email = $('#email').val();
				if (email == '') {
					$.confirm({
						boxWidth: '27%',
						title: 'Empty field',
						content: 'Please fill in all fields',
						type: 'red',
						typeAnimated: true,
						useBootstrap: false,
						buttons: {
							ok: {
								text: 'Okay',
								btnClass: 'btn-red',
								action: function(){
								}
							}
						}
					});
					$('#update').prop('disabled', true);					
					$('#update').css({ 'color': 'white', 'background-color': 'gray' });
				}else if (/\s/.test(email)) {
					
					$.confirm({
						boxWidth: '27%',
						title: 'Email contains whitespaces',
						content: 'Email shall not have any whitespaces. Please try again',
						type: 'red',
						typeAnimated: true,
						useBootstrap: false,
						buttons: {
							ok: {
								text: 'Okay',
								btnClass: 'btn-red',
								action: function(){
								}
							}
						}
					});
					$('#update').prop('disabled', true);					
					$('#update').css({ 'color': 'white', 'background-color': 'gray' });
				}else{
					$('#update').prop('disabled', false);
			        $('#update').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
				}
			});

			function succeed(){
				window.location.replace("profile.php");	
			}

		});



		function update(){
			var username = $('#hiddenusername').val();
			var email = $('#email').val(); 
			var phone = $('#phone').val();
			var password = $('#pass1').val();
			$.ajax({

				url: "profile.php",
				type: "POST",
				cache: false,
				data:{
					updateadmin_check : 1,
					username: username,
					email: email,
					phone: phone,
					password: password,
				},
				success: function(response){
					if (response == 'updated' ) {
						location.reload();
					}else if (response == 'notupdated') {

						
					}
				}
			});
		}

		function validatePic(){

			if(document.getElementById("file_picture").files.length == 0 ){
			    $.confirm({
					boxWidth: '27%',
				    title: "",
				    content: 'Please upload your picture to continue.',
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

		function showpass(){
			var showpass1 = document.getElementById('pass1');
			var showpass2 = document.getElementById('pass2');

			if(showpass1.type == "password")
			{
				showpass1.type = "text";
				showpass2.type = "text";
			}
			else
			{
				showpass1.type = "password";
				showpass2.type = "password";
			}
		}

		function validateUpdate(){
			var email = $('#email').val();
			var phone = $('#phone').val();
			var password = $('#pass1').val();


			if(email.length < 3 || email.length > 200){

				$.confirm({
					boxWidth: '27%',
				    title: 'Invalid length of email!',
				    content: 'Email must be between 3 to 200 characters only.',
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
			}else{
				$.confirm({
					boxWidth: '27%',
					typeAnimated: true,
				    useBootstrap: false,
				    autoClose: 'cancel|15000',
				    title: 'Are you sure to update?',
				    content: '' +
				    '<form action="" class="formName" id="formdialog">' +
				    '<div class="form-group">' +
				    '<label>Enter your current password to continue</label>' +
				    '<input style="padding-top: 10px; padding-bottom: 10px" type="password" placeholder="current password" class="name form-control" required />' +
				    '</div>' +
				    '</form>',
				    buttons: {
				        formSubmit: {
				            text: 'Submit',
				            btnClass: 'btn-blue',
				            action: function () {
				                var currentpass = this.$content.find('.name').val();
				                var passform = $('#hiddenpw').val();
				                if(currentpass != passform){
				                    $.confirm({
										boxWidth: '27%',
									    title: 'Invalid password!',
									    content: 'Password entered does not match with your current password.',
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
				                }else{
				                	update();
				                }
				                
				            }
				        },
				        cancel: function () {
				        },
				    },
				    onContentReady: function () {
				        // bind to events
				        var jc = this;
				        this.$content.find('#formdialog').on('submit', function (e) {
				            // if the user submits the form by pressing enter in the field.
				            e.preventDefault();
				            jc.$$formSubmit.trigger('click'); // reference the button and click it
				        });
				    }
				});
			}
		}

		
	</script>

	<script type="text/javascript">
		function invalid(){

			$.confirm({
				boxWidth: '27%',
				title: '',
				content: 'Sorry, there was a problem to upload your picture. Please try again',
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
		}

		function pictTooBig(){
			$.confirm({
				boxWidth: '27%',
				title: '',
				content: 'Picture is too big. Please use another picture. Maximum size is 10MB',
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
		}

		function pictNotFound(){
			$.confirm({
				boxWidth: '27%',
				title: '',
				content: 'Picture is not found. Please try again.',
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
		}


	</script>

	<style type="text/css">
		input{
			width: 80%;
		}
	</style>
</head>
<body>

	<div class="outerbox">
		<div class="innerbox" style="padding-bottom: 30px">
			<header>
				<a href="../Administrator" class="logo">Miles of Smiles Daycare System</a>
				<nav>
					<ul>
						<li>
							<a title="home" href="../Administrator" ><i id="naviconheader" class="fa fa-home" aria-hidden="true"></i></a>
						</li>
						<li>
							<a title="nanny application" href="nannyApplication.php" title="nanny application">
								<i id="naviconheader" class="fa fa-ticket" aria-hidden="true"></i>
							</a>
						</li>
						<li>
							<a title="staff management" href="staffManagement.php"><i id="naviconheader" class="fa fa-users" aria-hidden="true"></i></a>
						</li>
						<li>
							<a href="report.php" title="report">
								<i id="naviconheader" class="fa fa-pie-chart" aria-hidden="true"></i>
							</a>
						</li>
						<li>
							<?php 
							if(empty($rowMe['picture']))
							{
								?>
								<a class="activebar" title="profile" href="profile.php" style="text-decoration-style: none; text-decoration: none; padding-right: 20px ; padding-bottom: 0; padding-top: 0;  padding-left: 0px">
									<div class="profile-picnohover" style="background-image: url('../attachment/profile/admin.png');" >
									</div>
								</a>
								<?php
							}
							else
							{
								?>
								<a class="activebar" title="profail" href="profile.php" style="text-decoration-style: none; text-decoration: none; padding-right: 20px ; padding-bottom: 0; padding-top: 0;  padding-left: 0px">
									<div class="profile-picnohover" style="background-image: url('../attachment/profile/<?php echo $rowMe['picture']?>');" >
									</div>
								</a>
								<?php
							}
							?>
						</li>
						<li>
							<a title="sign out" href="../signOut.php"><i style="font-size: 16px;" class="fa fa-sign-out" aria-hidden="true"></i></a>
						</li>
					</ul>
				</nav>
				<div class="clearfix"></div>
			</header>

			<div class="profileholder">
				<a class="changePicture" id="changepic" aria-label="Change Profile Picture">
				  	<?php
				  	if(empty($rowMe['picture']))
				  	{
				  		?>
				  		<div class="profile-pic" style="background-image: url('../attachment/profile/admin.png');" >
				  		<?php 
				  	}
				  	else
				  	{
				  		?>
				  		<div class="profile-pic" style="background-image: url('../attachment/profile/<?php echo $rowMe['picture']?>');" >
				  		<?php 
				  	}
				  	?>  	
				  		<span id="changespan">Change</span>
				    </div>
				</a>
				<form name="formpicture" onsubmit="return validatePic()" action="" id="formpicture" method="POST" enctype="multipart/form-data">
					<div style="display: inline-flex;">
						<input accept="image/jpeg, image/png, image/jpg" type="file" id="file_picture" name="file_picture" style="margin-left: 15%; margin-top: 8px; display: none; ">
						<input type="submit" id="submitpicture" name="submitpicture" class="submit" value="Save" style="cursor: pointer; display: none; width: 18%; " >
					</div>
					
				</form>	
			</div><p id="edit" class="hide" style="width: 19%;">edit</p><p id="cancelEdit" class="hide" style="display: none; width: 19%;">cancel</p>
			<div class="informationHolder" >
				INFORMATION
			</div>

			<div class="information">
				<table align="center" width="90%" cellpadding="6">
					<tr>
						<td style="width: 37%">
							USERNAME
						</td>
						<td>
							<?php echo $username; ?>
						</td>
					</tr>
					<tr>
						<td style="width: 37%">
							FULL NAME
						</td>
						<td>
							<?php echo $rowMe['fullName']; ?>
						</td>
					</tr>
					<tr>
						<td style="width: 37%">
							E-MAIL
						</td>
						<td>
							<input type="email" name="email" id="email" value="<?php echo $rowMe['email']?>">
						</td>
					</tr>
					<tr>
						<td style="width: 37%">
							PHONE
						</td>
						<td>
							<input type="number" id="phone" name="phone" value="<?php echo $rowMe['phoneNo']; ?>">
						</td>
					</tr>
				</table>
			</div>
			<div class="informationHolder" align="center">
				CHANGE PASSWORD
			</div>
			<div class="information">
				<table align="center" width="90%" cellpadding="6">
					<tr>
						<td style="width: 37%">
							PASSWORD
						</td>
						<td>
							<input id="pass1" type="password" name="pass1" value="<?php echo $rowMe['password']; ?>" >
							<span><i onclick="showpass()" style="margin-left: 4px; cursor: pointer;" class="fa fa-eye" aria-hidden="true"></i></span>
						</td>
					</tr>
					<tr>
						<td style="width: 37%">
							CONFIRM PASSWORD
						</td>
						<td>
							<input id="pass2" type="password" name="pass2" value="<?php echo $rowMe['password']; ?>">
						</td>
					</tr>
				</table>
			</div>
			<center>
				<input onclick="return validateUpdate()" type="submit" name="update" id="update" value="Save" class="submit" style="visibility: hidden;">
				<input type="hidden" name="hiddenusername" id="hiddenusername" value="<?php echo $username ?>"><input type="hidden" name="hiddenpw" id="hiddenpw" value="<?php echo $rowMe['password'] ?>">
			</center>













		</div>
	</div>
	
	<?php
	include('../footer.php');
	?>
	<div id="stickyholder" >
		<a class="sticky" href="#top">
			<i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i>
		</a>
	</div>
</body>
</html>