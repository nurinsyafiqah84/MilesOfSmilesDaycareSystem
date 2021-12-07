<?php
include('../inc/dbconnect.php');
session_start();
$username = $_SESSION['username'];
$sqlMe = "SELECT * FROM administrator WHERE username='".$username."'";
$resultMe = $conn->query($sqlMe);
$rowMe = $resultMe->fetch_assoc();

$sqlNanny = "SELECT * FROM nanny";
$resultNanny = $conn->query($sqlNanny);

$sqlAdmin = "SELECT * FROM administrator";
$resultAdmin = $conn->query($sqlAdmin);

$sqlSetting = "SELECT * FROM settingfee";
$resultSetting = $conn->query($sqlSetting);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<link rel="stylesheet" type="text/css" href="../css/home.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Staff Management & Setting </title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
	<script type="text/javascript">

		$( window ).scroll(function() {

			$("#stickyholder").css("visibility", "visible");

		});


		$(document).ready(function() {

			$('#clickNanny').click(function(){
				$('#nannys').toggle();
			});

			$('#hideNanny').click(function(){
				$('#nannys').toggle();
			});

			$('#addNannyIcon').click(function(){
				$('#cancelNanny').show();
				$('#addNannyIcon').hide();
				$('#addNanny').show();				
			});

			$('#cancelNanny').click(function(){
				$('#addNannyIcon').show();
				$('#cancelNanny').hide();
				$('#addNanny').hide();				
			});

			$('#clickadministrator').click(function(){
				$('#admins').toggle();
			});

			$('#hideAdmin').click(function(){
				$('#admins').toggle();
			});

			$('#clicksetting').click(function(){
				$('#setting').toggle();
			});

			$('#hidesetting').click(function(){
				$('#setting').toggle();
			});

			$('#addAdminIcon').click(function(){
				$('#cancelAdmin').show();
				$('#addAdminIcon').hide();
				$('#addAdmin').show();		
			});

			$('#cancelAdmin').click(function(){
				$('#addAdminIcon').show();
				$('#cancelAdmin').hide();
				$('#addAdmin').hide();				
			});

			$('#nanny_username').on('blur', function(){
				var username = $('#nanny_username').val();
				if (username == '') {
				    username_state = false;
				    return;
				}
				$.ajax({
				    url: '../processValidateUsername.php',
				    type: 'post',
				    data: {
				      'username_check' : 1,
				      'username' : username,
				   },
					success: function(response){
				    if (response == 'taken' ) {
				    	username_state = false;
				        $.confirm({
				          boxWidth: '27%',
				            title: 'Username already exist!',
				            content: 'Sorry, the username entered is taken. Please use other username.',
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
				        $('#submit_nanny').prop('disabled', true);
				        $('#submit_nanny').css({ 'color': 'white', 'background-color': 'gray' });
				      }else if (response == 'not_taken') {
				        username_state = true;
				        $('#submit_nanny').prop('disabled', false);
				        $('#submit_nanny').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
				      }
				    }
				});
			});			
			$('#admin_username').on('blur', function(){
				var username = $('#admin_username').val();
				if (username == '') {
				    username_state = false;
				    return;
				}
				$.ajax({
				    url: '../processValidateUsername.php',
				    type: 'post',
				    data: {
				      'username_check' : 1,
				      'username' : username,
				   },
					success: function(response){
				    if (response == 'taken' ) {
				    	username_state = false;
				        $.confirm({
				          boxWidth: '27%',
				            title: 'Username already exist!',
				            content: 'Sorry, the username entered is taken. Please use other username.',
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
				        $('#submit_admin').prop('disabled', true);
				        $('#submit_admin').css({ 'color': 'white', 'background-color': 'gray' });
				      }else if (response == 'not_taken') {
				        username_state = true;
				        $('#submit_admin').prop('disabled', false);
				        $('#submit_admin').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
				      }
				    }
				});
			});			

			

		});

		function updateprice(obj){
			var settingFeeID = obj.id.substring(6);;
			var idsettingID = "#" + settingFeeID;
			var hideupdate = "#update" + settingFeeID;
			var cancelupdate = "#cancel" + settingFeeID;
			
			$(idsettingID).prop("readonly", false);
			$(idsettingID).css("border", "1px solid black");
			$('.updateelse').attr('onClick', '');
			$('.updateelse').css('color', 'gray');
			$('.updateelse').css('cursor', 'default');	
			$(hideupdate).hide();
			$(cancelupdate).show();


			$(idsettingID).keypress(function (e) {
			 var key = e.which;
			 if(key == 13)  // the enter key code
			  {
			  	var newPrice = $(this).val();
			  	const decimalCount = num => {
				   // Convert to String
				   const numStr = String(num);
				   // String Contains Decimal
				   if (numStr.includes('.')) {
				      return numStr.split('.')[1].length;
				   };
				   // String Does Not Contain Decimal
				   return 0;
				}

				if(decimalCount(newPrice) > 2){
					$.confirm({
						boxWidth: '27%',
					    title: 'Invalid price!',
					    content: 'Price shall not have more than 2 decimal places. Please try again',
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
				}else if(newPrice < 10){
					$.confirm({
						boxWidth: '27%',
					    title: 'Invalid price!',
					    content: 'Minimum price is RM10.00. Please try again',
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
					$.confirm({
						boxWidth: '27%',
						typeAnimated: true,
					    useBootstrap: false,
					    title: 'Are you sure to update the price?',
					    content: 'The new price will not affect the previous enrolments.' +
					    '<form action="" class="formName" id="formdialog">' +
					    '<div class="form-group">' +
					    '<label>Enter your password to continue</label>' +
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
										    content: 'Password entered does not match with your password.',
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
					                	update(newPrice, settingFeeID);
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
			});
						
		}

		function update(newPrice, settingFeeID){
			$.ajax({
				url: "registerDelete.php",
				type: "POST",
				data: {
					update_price: 1,
					newPrice: newPrice,
					settingFeeID: settingFeeID			
				},
				cache: false,
				success: function(dataResult){
					if(dataResult == "updated"){
						$.alert({
							boxWidth: '27%',
							typeAnimated: true,
							useBootstrap: false,
							title: '',
							type: 'green',
							content: 'Price is sucessfully updated',
						});
						
						$(idsettingID).val(newPrice);
						
					}
					else if(dataResult == "notupdated"){
						$.alert({
							boxWidth: '27%',
							typeAnimated: true,
							useBootstrap: false,
							title: '',
							type: 'red',
							content: 'Unsucessful price update.',
						});
						$(idsettingID).val(ogprice);
					}
					var idsettingID = "#" + settingFeeID;
					var hideupdate = "#update" + settingFeeID;
					var cancelupdate = "#cancel" + settingFeeID;
					var idogprice = "#ogprice" + settingFeeID;
					var ogprice = $(idogprice).val();

					$(hideupdate).show();
					$(idsettingID).css("border", "1px solid transparent");
					$(idsettingID).prop("readonly", true);

					$('.updateelse').attr('onClick', 'updateprice(this);');
					$('.updateelse').css('color', 'rgba(117, 20, 117, 0.8)');	
					$('.updateelse').css('cursor', 'pointer');		
					$(cancelupdate).hide();

				}
			});
		}

		function cancelupdate(obj){
			var settingFeeID = obj.id.substring(6);
			var idsettingID = "#" + settingFeeID;
			var hideupdate = "#update" + settingFeeID;
			var cancelupdate = "#cancel" + settingFeeID;
			var idogprice = "#ogprice" + settingFeeID;
			var ogprice = $(idogprice).val();
			
			$(hideupdate).show();
			$(idsettingID).prop("readonly", true);
			$(idsettingID).val(ogprice);
			$(idsettingID).css("border", "1px solid transparent");
			$('.updateelse').attr('onClick', 'updateprice(this);');
			$('.updateelse').css('color', 'rgba(117, 20, 117, 0.8)');	
			$('.updateelse').css('cursor', 'pointer');		
			$(cancelupdate).hide();
		}
	</script>

	<style type="text/css">
		input{
			
			width: 80%;
		}

		a#cancelNanny:hover{
			color: red;
		}
		a#addNannyIcon:hover{
			color: green;
		}
	</style>
</head>
<body>
	<div class="outerbox">
		<div class="innerbox">
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
							<a title="staff management" class="activebar" href="staffManagement.php"><i id="naviconheader" class="fa fa-users" aria-hidden="true"></i></a>
						</li>
						<li>
							<a href="report.php"   title="report">
								<i id="naviconheader" class="fa fa-pie-chart" aria-hidden="true"></i>
							</a>
						</li>
						<li>
							<?php 
							if(empty($rowMe['picture']))
							{
								?>
								<a title="profile" href="profile.php" style="text-decoration-style: none; text-decoration: none; padding-right: 20px ; padding-bottom: 0; padding-top: 0;  padding-left: 0px">
									<div class="profile-picnohover" style="background-image: url('../attachment/profile/admin.png');" >
									</div>
								</a>
								<?php
							}
							else
							{
								?>
								<a title="profile" href="profile.php" style="text-decoration-style: none; text-decoration: none; padding-right: 20px ; padding-bottom: 0; padding-top: 0;  padding-left: 0px">
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
			<div align="center">
				<a id="clickNanny" href="#nannys" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="mainBox">
						<div class="mainPictureHolder">
							<img id="mainBoxPicture" src="../attachment/image/nannys.png" width="70px" height="70px">
						</div>
						<div class="mainBoxText">
							<p class="mainBoxTitle">
								Nanny<br><br>
							</p>
							
								<?php 
								if($resultNanny->num_rows == 0 )
								{
									echo "<p class='mainBoxDetail' style='color:gray;'>0</i>";
								}
								else{

									echo "<p class='mainBoxDetail'>" .$resultNanny->num_rows;
								}
								 ?>
							</p>
						</div>
					</div>
				</a>
				<a id="clickadministrator" href="#admins" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="mainBox">
						<div class="mainPictureHolder">
							<img id="mainBoxPicture" src="../attachment/image/administrator.png" width="70px" height="70px">
						</div>
						<div class="mainBoxText">
							<p class="mainBoxTitle">
								Administrator<br><br>
							</p>
							
								<?php 
								if($resultAdmin->num_rows == 0 )
								{
									echo "<p class='mainBoxDetail' style='color:gray;'>0</i>";
								}
								else{

									echo "<p class='mainBoxDetail'>" .$resultAdmin->num_rows;
								}
								 ?>
							</p>
						</div>
					</div>
				</a>
				<a id="clicksetting" href="#setting" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="mainBox">
						<div class="mainPictureHolder">
							<img id="mainBoxPicture" src="../attachment/image/settingprice.png" width="70px" height="70px">
						</div>
						<div class="mainBoxText">
							<p class="mainBoxTitle">
								Price Setting<br><br>
							</p>
							<p class='mainBoxDetail'><i class="fa fa-wrench" aria-hidden="true"></i></p>
						</div>
					</div>
				</a>
			</div>




			<div id="nannys" class="mainBoxSection">
				<div class="titleTextHolder">
					Nanny 
				</div>
				<hr>
				<p id="hideNanny" class="hide">hide</p>
				<div class="mainBoxContent" >
					<a href="#addNanny"  style="margin-left: 5px;" id="addNannyIcon" ><i title="register nanny" class="fa fa-user-plus" aria-hidden="true"></i></a>
					<a id="cancelNanny" style="margin-left: 5px;display: none;"><i title="cancel" class="fa fa-user-times" aria-hidden="true" ></i> </a>
					
					<div id="addNanny" style="display: none ; margin-top: 10px; margin-bottom: 32px; width: 80%; margin-left: auto; margin-right:auto;">
						<div class="informationHolder" align="center" style="width: 100%">
							REGISTER NANNY
						</div>
						<table width="100%" align="center" cellpadding="5">
							<tr>
								<td align="center" width="30%">
									USERNAME
								</td>
								<td>
									<input width="40%" type="text" id="nanny_username"><span class="asterisk_input"></span><p class="warning">Note: username is permanent.</p>
								</td>
							</tr>
							<tr>
								<td align="center">
									PASSWORD
								</td>
								<td>
									<input type="text" readonly="" value="pass1234">
								</td>
							</tr>
							<tr>
								<td align="center">
									FULL NAME
								</td>
								<td>
									<input type="text" id="nanny_fullName" ><span class="asterisk_input"></span>
								</td>
							</tr>
							<tr>
								<td align="center">
									ADDRESS
								</td>
								<td>
									<textarea cols="39" rows="3" id="nanny_address"></textarea><span class="asterisk_input"></span>
								</td>
							</tr>
							<tr>
								<td align="center">
									PHONE
								</td>
								<td>
									<input type="number" id="nanny_phoneNo" ><span class="asterisk_input"></span>
								</td>
							</tr>
						</table>
						<center>
							<input type="submit" name="submit_nanny" id="submit_nanny" class="submit" value="Register">
						</center>
					</div>
					<?php
					if($resultNanny->num_rows == 0){
						?>
						<center style="font-style: italic; color: gray; margin-top: 10px">NO RECORD OF NANNY FOUND</center>
						<?php
					}else{
						?>
						<div style="width:50%; margin-left:auto; margin-right: auto; margin-bottom: 10px;">
							<select style="width: 40%;" id="sortNanny">
								<option selected value="usernameAZ">Username A-Z</option>
								<option value="usernameZA">Username Z-A</option>
								<option value="fullNameAZ">Full Name A-Z</option>
								<option value="fullNameZA">Full Name Z-A</option>
							</select>
							<input style="width: 40%;" placeholder="Search" type="text" name="searchNanny" id="searchNanny">
						</div>
						
						<div id="table_nanny">
							
						</div>
						
						<?php
					}
					?>
				</div>
			</div>

			<div id="admins" class="mainBoxSection">
				<div class="titleTextHolder">
					Administrator 
				</div>
				<hr>
				<p id="hideAdmin" class="hide">hide</p>
				<div class="mainBoxContent" >
					<a href="#addAdmin" class="accept" style="margin-left: 5px;" id="addAdminIcon" ><i title="register nanny" class="fa fa-user-plus" aria-hidden="true"></i></a>
					<a id="cancelAdmin" class="oppose" style="margin-left: 5px;display: none;"><i title="cancel" class="fa fa-user-times" aria-hidden="true" ></i> </a>
					
					<div id="addAdmin" style="display: none ; margin-top: 10px; margin-bottom: 32px; width: 80%; margin-left: auto; margin-right:auto;">
						<div class="informationHolder" align="center" style="width: 100%">
							REGISTER ADMINISTRATOR
						</div>
						<table width="100%" align="center" cellpadding="5">
							<tr>
								<td align="center" width="30%">
									USERNAME
								</td>
								<td>
									<input width="40%" type="text" id="admin_username"><span class="asterisk_input"></span><p class="warning">Note: username is permanent.</p>
								</td>
							</tr>
							<tr>
								<td align="center">
									PASSWORD
								</td>
								<td>
									<input type="text" readonly="" value="pass1234">
								</td>
							</tr>
							<tr>
								<td align="center">
									FULL NAME
								</td>
								<td>
									<input type="text" id="admin_fullName" ><span class="asterisk_input"></span>
								</td>
							</tr>
							<tr>
								<td align="center">
									E-MAIL
								</td>
								<td>
									<input type="email" name="admin_email" id="admin_email"><span class="asterisk_input"></span>
								</td>
							</tr>
							<tr>
								<td align="center">
									PHONE
								</td>
								<td>
									<input type="number" id="admin_phoneNo" ><span class="asterisk_input"></span>
								</td>
							</tr>
						</table>
						<center>
							<input type="submit" name="submit_admin" id="submit_admin" class="submit" value="Register">
						</center>
					</div>
					<?php
					if($resultAdmin->num_rows == 0){
						?>
						<center style="font-style: italic; color: gray; margin-top: 10px">NO RECORD OF ADMINISTRATOR FOUND</center>
						<?php
					}else{
						?>
						<div style="width:50%; margin-left:auto; margin-right: auto; margin-bottom: 10px;">
							<select style="width: 40%;" id="sortAdmin">
								<option selected value="usernameAZ">Username A-Z</option>
								<option value="usernameZA">Username Z-A</option>
								<option value="fullNameAZ">Full Name A-Z</option>
								<option value="fullNameZA">Full Name Z-A</option>
							</select>
							<input style="width: 40%;" placeholder="Search" type="text" name="searchAdmin" id="searchAdmin">
						</div>
						
						<div id="table_admin">
							
						</div>
						
						<?php
					}
					?>
				</div>
			</div>


			<div id="setting" class="mainBoxSection">
				<div class="titleTextHolder">
					Price Setting 
				</div>
				<hr>
				<p id="hidesetting" class="hide">hide</p>
				<div class="mainBoxContent" >
					<?php
					if($resultSetting->num_rows == 0){
						?>
						<center style="font-style: italic; color: gray; margin-top: 10px">NO RECORD OF SETTING FOUND</center>
						<?php
					}else{
						?>						
						<div id="table_setting" style="width: 90%; margin-left: auto; margin-right:auto; margin-top: 30px;">
							<table cellpadding="4"  align="center">
								<tr>
									<th>Category</th>
									<th width="15%">Min. Age</th>
									<th width="15%">Max. Age</th>
									<th width="25%">Price (RM/Day)</th>
									<th><i class="fa fa-pencil-square-o" title="update price" aria-hidden="true"></i></th>
								</tr>
								<?php
								while($rowSetting = $resultSetting->fetch_assoc()){
									?>
									<tr>
										<td align="center"><?php echo $rowSetting['category'] ?></td>
										<td align="center"><?php echo $rowSetting['minAge'] ?></td>
										<td align="center"><?php echo $rowSetting['maxAge'] ?></td>
										<td align="center">
											<input style="text-align: center;border: 1px solid transparent;" type="number" name="price" id="<?php echo $rowSetting['settingfeeID'] ?>" readonly value="<?php echo $rowSetting['pricePerDay'] ?>">
											<input type="hidden" value="<?php echo $rowSetting['pricePerDay'] ?>" id="ogprice<?php echo $rowSetting['settingfeeID'] ?>">
										</td>
										<td align="center">
											<a onclick="updateprice(this)" class="updateelse" id="update<?php echo $rowSetting['settingfeeID'] ?>" >
												<i title="update price" class="fa fa-pencil-square-o" aria-hidden="true"></i>
											</a>
											<a onclick="cancelupdate(this)" id="cancel<?php echo $rowSetting['settingfeeID'] ?>" style="display: none;">
												<i title="cancel update" class="fa fa-ban" aria-hidden="true"></i>
											</a>
										</td>
									</tr>
									<?php
								}


								?>
								<input type="hidden" name="hiddenpw" id="hiddenpw" value="<?php echo $rowMe['password']; ?>">
							</table><br>
							<i style="color: slategray;">Changes made will not affect the previous enrolments. Changes will be applied for the upcoming enrolment onwards.</i>							
						</div>
						
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
	include('../footer.php');
	?>
	<div id="stickyholder" style="visibility: hidden;">
		<a class="sticky" href="#top">
			<i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i>
		</a>
	</div>
	<script type="text/javascript">

		function deletenanny(obj){
			var nannyusername = obj.id;
			$.confirm({
				boxWidth: '27%',
				title: 'Delete nanny?',
				content: 'Are you sure to delete this '+ nannyusername +' account? This cannot be undone.',
				type: 'red',
				typeAnimated: true,
				useBootstrap: false,
				autoClose: 'cancel|5000',
				buttons: {
					confirm: {
						text: 'Delete',
						btnClass: 'btn-red',
						action: function(){
							$.ajax({
								url: "registerDelete.php",
								type: "POST",
								data: {
									deletenanny_check: 1,
									nannyusername: nannyusername			
								},
								cache: false,
								success: function(dataResult){
									if(dataResult == "deleted"){
										<?php
										echo("location.href = 'staffManagement.php';");
										?>
									}
									else if(dataResult == "notdeleted"){
									}
									
								}
							});
						}			        	
					},
					cancel: {
						text: 'Cancel',
						btnClass: 'btn-default',
						action: function(){
						}
					}
				}
			});
		}
		
		$(document).ready(function(){

			load_nanny();
			load_admin();
			//=====================================================
			function load_nanny(querySearch, par){
				$.ajax({
					url:"search_nanny.php",
					method:"POST",
					data:{querySearch:querySearch,
						par:par
					},
					success:function(data)
					{
						$('#table_nanny').html(data);
					}
				});
			}

			$('#searchNanny').keyup(function(){
				var search = $(this).val();
				var opt = $('#sortNanny :selected').val();
				if(search != '')
				{
					load_nanny(search, opt);
				}
				else
				{
					load_nanny();
				}
			});

			$('#sortNanny').change(function (){
				var search = $('#searchNanny').val();
				var opt = $('#sortNanny :selected').val();
				if(opt != '')
				{
					load_nanny(search, opt);
				}
				else
				{
					load_nanny();
				}

			});

			//===============================================================
			function load_admin(querySearch, par){
				$.ajax({
					url:"search_admin.php",
					method:"POST",
					data:{querySearch:querySearch,
						par:par
					},
					success:function(data)
					{
						$('#table_admin').html(data);
					}
				});
			}

			$('#searchAdmin').keyup(function(){
				var search = $(this).val();
				var opt = $('#sortAdmin :selected').val();
				if(search != '')
				{
					load_admin(search, opt);
				}
				else
				{
					load_admin();
				}
			});

			$('#sortAdmin').change(function (){
				var search = $('#searchAdmin').val();
				var opt = $('#sortAdmin :selected').val();
				if(opt != '')
				{
					load_admin(search, opt);
				}
				else
				{
					load_admin();
				}

			});

			//===============================================================
			function load_addnanny(username, fullName, address, phoneNo){
				
				$.ajax({
					url: "registerDelete.php",
					type: "POST",
					data: {
						registernanny_check: 1,
						username: username,
						fullName: fullName,
						address: address,
						phoneNo: phoneNo				
					},
					cache: false,
					success: function(dataResult){
						if(dataResult == "registered"){
							<?php
							echo("location.href = 'staffManagement.php';");
							?>
						}
						else if(dataResult == "notregistered"){
						  
						}
						
					}
				});
			}

			$('#submit_nanny').on('click', function(){
				var username = $('#nanny_username').val();
				var fullName = $('#nanny_fullName').val();
				var address = $('#nanny_address').val();
				var phoneNo = $('#nanny_phoneNo').val();

				if(username.length == 0  || fullName.length == 0 || phoneNo.length == 0 || address.length == 0){
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
				}else if(username.length < 5 || username.length > 50){

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
				}else if(phoneNo.length < 10 || phoneNo.length > 11){
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
					
				}else{
					$.confirm({
						boxWidth: '27%',
						title: 'Register nanny?',
						content: 'Are you sure to register this nanny?',
						type: 'green',
						typeAnimated: true,
						useBootstrap: false,
						autoClose: 'cancel|5000',
						buttons: {
							confirm: {
								text: 'Register',
								btnClass: 'btn-green',
								action: function(){
									load_addnanny(username, fullName, address, phoneNo);
								}			        	
							},
							cancel: {
								text: 'Cancel',
								btnClass: 'btn-default',
								action: function(){
								}
							}
						}
					});
				}
				
			});


			//=================================================================
			function load_addadmin(username, fullName, email, phoneNo){
				
				$.ajax({
					url: "registerDelete.php",
					type: "POST",
					data: {
						registeradmin_check: 1,
						username: username,
						fullName: fullName,
						email: email,
						phoneNo: phoneNo				
					},
					cache: false,
					success: function(dataResult){
						if(dataResult == "registered"){
							<?php
							echo("location.href = 'staffManagement.php';");
							?>
						}
						else if(dataResult == "notregistered"){
						  
						}
						
					}
				});
			}

			$('#submit_admin').on('click', function(){
				var username = $('#admin_username').val();
				var fullName = $('#admin_fullName').val();
				var email = $('#admin_email').val();
				var phoneNo = $('#admin_phoneNo').val();
				
				if(username.length == 0  || fullName.length == 0 || phoneNo.length == 0 || email.length == 0){
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
				}else if(username.length < 5 || username.length > 50){

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
				}else if(phoneNo.length < 10 || phoneNo.length > 11){
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
				}else if(email.length < 3 || email.length > 100){
					$.confirm({
						boxWidth: '27%',
					    title: 'Invalid email!',
					    content: 'Adress must be between 3 to 100 characters only.',
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
					$.confirm({
						boxWidth: '27%',
						title: 'Register administrator?',
						content: 'Are you sure to register this admin?',
						type: 'green',
						typeAnimated: true,
						useBootstrap: false,
						autoClose: 'cancel|5000',
						buttons: {
							confirm: {
								text: 'Register',
								btnClass: 'btn-green',
								action: function(){
									load_addadmin(username, fullName, email, phoneNo);
								}			        	
							},
							cancel: {
								text: 'Cancel',
								btnClass: 'btn-default',
								action: function(){
								}
							}
						}
					});
				}	
			});

		});

		<?php
		if(isset($_SESSION['reload']) && $_SESSION['reload'] == "nanny"){
			unset($_SESSION['reload']);
			?>
				
				$('#nannys').show();
				$('#nannys')[0].scrollIntoView();
				$.alert({
					boxWidth: '27%',
					typeAnimated: true,
					useBootstrap: false,
					title: '',
					type: 'green',
					content: 'Nanny account is sucessfully registered',
				});
			
			<?php
		}else if(isset($_SESSION['reload']) && $_SESSION['reload'] == "deleteNanny"){
			unset($_SESSION['reload']);
			?>
				
				$('#nannys').show();
				$('#nannys')[0].scrollIntoView();
				$.alert({
					boxWidth: '27%',
					typeAnimated: true,
					useBootstrap: false,
					title: '',
					type: 'green',
					content: 'Nanny account is sucessfully deleted',
				});
			
			<?php
		}elseif(isset($_SESSION['reload']) && $_SESSION['reload'] == "administrator"){
			unset($_SESSION['reload']);
			?>
				
				$('#admins').show();
				$('#admins')[0].scrollIntoView();
				$.alert({
					boxWidth: '27%',
					typeAnimated: true,
					useBootstrap: false,
					title: '',
					type: 'green',
					content: 'Administrator account is sucessfully registered',
				});
			
			<?php
		}
	?>
		
	</script>
</body>
</html>