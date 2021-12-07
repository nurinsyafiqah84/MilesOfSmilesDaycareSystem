<?php
session_start();

?>
<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<title>Join us as nanny!</title>
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<link rel="stylesheet" type="text/css" href="../css/nannyapplication.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

	<script type="text/javascript">
		
		$( function() {

			var now = new Date().getFullYear();
			$('#dob').change(function() { 
				
				var birth = this.value.toString();
				var y = birth.substring(0,4);

				var age = parseInt(now) - parseInt(birth);
    			if(age > 17){
    				$('#submit').prop('disabled', false);
    				$('#submit').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
          			$('#reset').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });

    			}
    			else{
    				$.confirm({
					boxWidth: '27%',
				    title: 'Invalid age',
				    content: 'Sorry, applicant must be at least 18 years old to apply for nanny position.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){
				            	$('#submit').prop('disabled', true);
				            	$('#submit').css({ 'color': 'white', 'background-color': 'gray' });
          						$('#reset').css({ 'color': 'white', 'background-color': 'gray' });
				            }
				        }
				    }
					});


    			}

			});

		});
		

		function validateApplication(){

			var name = form.name.value;
			var dob = form.dob.value;
			var email = form.email.value;
			var resume = form.file_resume.value;

			if(name.length == 0 || dob.length == 0 || email.length == 0){

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
			}else if(document.getElementById("resume").files.length == 0 ){
			    $.confirm({
					boxWidth: '27%',
				    title: 'Missing resume!',
				    content: 'Please upload your resume to continue.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){
				            	$('#resume').focus();
				            }
				        }
				    }
				});
				return false;
			}else if(document.getElementById("passport").files.length == 0 ){
			    $.confirm({
					boxWidth: '27%',
				    title: 'Missing photo!',
				    content: 'Please upload your photo to continue.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){
				            	$('#passport').focus();
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
	<div class="outerbox" style="margin-top:4%">
		<div class="innerbox">
			<center><h2>NANNY APPLICATION</h2></center>
				<form action="submitApplication.php" enctype="multipart/form-data" method="POST" name="form" onsubmit="return validateApplication();">
					<div class="row">
						<div class="part">
							<table align="center" cellpadding="6" border="0" width="100%">
								<tr>
									<td>Full Name</td>
									<td><input type="text" placeholder="full name" id="name" name="name"><span class="asterisk_input"></span></td>
								</tr>

								<tr>
									<td width="100px">Date of Birth</td>
									<td><input type="date" class="dob" id="dob" name="dob"><span class="asterisk_input" style="margin: 0px 0px 0px -25px; "></span></td>
								</tr>
								<tr>
									<td>E-mail</td>
									<td><input type="email" id="email" name="email"><span class="asterisk_input"></span></td>
								</tr>
								<tr>
									<td>Photo</td>
									<td><input type="file" accept="image/*" id="passport" name="passport" /><span class="asterisk_input" style="margin: 0px 0px 0px 9px; "></span></td>
								</tr>
								
							</table>
						</div>
						<div class="part" >
							<table align="center" width="94%" cellpadding="5" border="0">
								<tr>
									<td>Resume</td>
									<td>
										<input type="file" accept="application/pdf" id="resume" name="file_resume" /><span class="asterisk_input" style="margin: 0px 0px 0px 9px; "></span><br>
										<p class="warning"><strong>Note:</strong> Only of type .pdf are allowed.</p>
									</td>
								</tr>
								<tr>
									<td width="120px">Edu. Level</td>
									<td>
										<select name="eduLevel" id="eduLevel">
											<option value="SPM/ O-Level">SPM/ O-Level</option>
											<option value="STPM/ Matriculation/ Foundation/ A-Level">STPM/ Matriculation/ Foundation/ A-Level</option>
											<option value="Diploma">Diploma</option>
											<option value="Bachelor Degree">Bachelor Degree</option>
										</select><span class="asterisk_input" style="margin: 0px 0px 0px -20px; ">  </span>
									</td>
								</tr>
								<tr>
									<td>Field of Study</td>
									<td>
										<input type="text" name="fieldOfStudy" id="fieldOfStudy" />
									</td>
								</tr>
								
							</table>
						</div>		
						
					</div>
					<div align="center" style="margin-top:0;">
						<table align="center" width="90%" cellpadding="10" border="0">
							<tr>
								<td width="18%" style="text-align: center;">Remark</td>
								<td>
									<textarea cols="50" rows="8" id="remark" name="remark"></textarea>
								</td>
							</tr>
							<tr>
								<td colspan="2" style="text-align:center;"><input type="reset" name="reset" value="Reset" id="reset" class="submit">
									<input type="submit" name="submit" value="Submit" id="submit" class="submit" style="margin-left: 10px">
								</td>
								
							</tr>
						</table>
					</div>	
				</form>
			</div>
		</div>
	</div>
		
	<?php

	include('../footer.php');
	?>
	
	
</body>
</html>