<?php
include('../inc/dbconnect.php');
session_start();
$date =  date("Y-m-d");
$username = "";

if(!isset($_SESSION['username'])){
	?>
	<body onload="invalidSession()">
	<?php
}else
	$username = $_SESSION['username'];


$sqlMe = "SELECT * FROM parent WHERE username='".$username."'";
$resultMe = $conn->query($sqlMe);
$rowMe = $resultMe->fetch_assoc();

$sqlSetting = "SELECT * FROM settingfee";
$resultSetting = $conn->query($sqlSetting);

if(idate('m') < 10){
	$doa = idate('Y') . "-0" . idate('m') . "-__%";
}else
	$doa = idate('Y') . "-" . idate('m') . "-__%";


if(idate('m') == 12){
	$nextMonth = 1;
	$nextMonth .= "/ " . (idate('Y') + 1);
}
else{
	$nextMonth = intval(idate('m')) + 1;
	$nextMonth .= "/ " . idate('Y');
}

$sqlGetToPay = "SELECT * FROM enrolment JOIN dependent ON enrolment.dependentID = dependent.dependentID JOIN parent ON dependent.parentID = parent.username WHERE status = 'approved' AND parent.username = '".$username."' AND dateOfApplication LIKE '".$doa."' AND enrolment.enrolmentID NOT IN (SELECT payment.enrolmentID FROM payment)";

$resultToPay = $conn->query($sqlGetToPay);

$sqlGetDependent = "SELECT * FROM dependent WHERE parentID = '".$username."'";
$resultDependent = $conn->query($sqlGetDependent);

$sqlEnrolmentHistory = "SELECT * FROM enrolment JOIN dependent 
						ON enrolment.dependentID = dependent.dependentID
						JOIN parent ON dependent.parentID = parent.username
						WHERE parent.username = '".$username."' ORDER BY enrolmentID";
$resultEnrolmentHistory = $conn->query($sqlEnrolmentHistory);

$sqlAlreadyApplied = "SELECT * FROM dependent WHERE dependent.parentID = '".$username."' 
				AND dependentID IN (SELECT dependentID from enrolment WHERE dateOfApplication LIKE '".$doa."')";
$resultAlreadyApplied = $conn->query($sqlAlreadyApplied);

$sqlEnrolmentApproved = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
						ON enrolment.dependentID = dependent.dependentID
						JOIN parent ON
						dependent.parentID = parent.username
						WHERE status = 'approved' AND dateOfApplication LIKE '".$doa."' AND dependent.parentID = '".$username."'";

$resultEnrolmentApproved = $conn->query($sqlEnrolmentApproved);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<link rel="stylesheet" type="text/css" href="../css/home.css">
	<title>Home - <?php echo ucfirst($username); ?></title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

	<script type="text/javascript">
		
		$( window ).scroll(function() {

			$("#stickyholder").css("visibility", "visible");

		});

		function invalidSession(){

			$.confirm({
				boxWidth: '27%',
				title: 'Error',
				content: 'Sorry, there was a problem to initiate your session. Please sign in again.',
				type: 'red',
				typeAnimated: true,
				useBootstrap: false,
				buttons: {
					tryAgain: {
						text: 'Try again',
						btnClass: 'btn-red',
						action: function(){
							window.location.replace('../signIn.php');
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
		$(document).ready(function() {


			$('#clickApply').click(function(){
				$('#apply').toggle();
			});

			$('#hideApply').click(function(){
				$('#apply').toggle();
			});

			$('#clickPay').click(function(){
				$('#pay').toggle();
			});

			$('#hidePay').click(function(){
				$('#pay').toggle();
			});

			$('#clickEnrolmentHistory').click(function(){
				$('#enrolmentHistory').toggle();
			});

			$('#hideEnrolmentHistory').click(function(){
				$('#enrolmentHistory').toggle();
			});

			function hideApply(){
				$('#apply').hide();
			}

			$('#submit_enrolment').click(function(){
				var dependentID = $('#applyDependent').val();
				var dependentName = $( "#applyDependent option:selected" ).text();
				var totalD = $('#totalDays').val();

				if(totalD.length == 0){
					$.confirm({
						boxWidth: '27%',
					    title: 'Blank field',
					    content: 'Please fill in the total days',
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
				}else if(totalD < 10 || totalD > 20){
					$.confirm({
						boxWidth: '27%',
					    title: 'Invalid total days',
					    content: 'Total days must be in between 10 and 20 days only. Please try again',
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
				else if(totalD.includes('.')){
					$.confirm({
						boxWidth: '27%',
					    title: 'Invalid total days',
					    content: 'Total days must contains only integer. Please try again',
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
					$.ajax({
						url: "registerDelete.php",
						type: "POST",
						data: {
							apply_enrolment: 1,
							dependentID: dependentID,
							totalDays: totalD			
						},
						cache: false,
						success: function(dataResult){
							if(dataResult == "applied"){

								$.alert({
									boxWidth: '27%',
									typeAnimated: true,
									useBootstrap: false,
									title: 'Enrolment for ' + dependentName.toLowerCase() + ' is sucessfully submitted. ',
									type: 'green',
									content: 'Enrolment will be reviewed shortly. The response for enrolment will be notified via email.',
								});
								$("#applyDependent option[value='" + dependentID + "']").remove();

								if( $('#applyDependent').has('option').length == 0 ) {
									$('#submit_enrolment').prop("disabled", "disabled");
									$('#applyDependent').prop("disabled", "disabled");
									$('#totalDays').prop("readonly", "readonly");
									$('#submit_enrolment').css("color", "gray");
									$('#submit_enrolment').css("cursor", "default");
								}
							}
							else if(dataResult == "notapplied"){
								$.alert({
									boxWidth: '27%',
									typeAnimated: true,
									useBootstrap: false,
									title: 'Unsucessful enrolment',
									type: 'red',
									content: 'Sorry, there was a problem to send the enrolment application',
								});
								
							}else{
								$.alert({
									boxWidth: '27%',
									typeAnimated: true,
									useBootstrap: false,
									title: 'Unsucessful enrolment',
									type: 'red',
									content: 'Sorry, there was a problem to send the enrolment application',
								});
							}							
						}
					});		
				}
				$('#totalDays').val("");
			});

			
		});
	</script>
	<style type="text/css">
		select{
			width: 80%;
		}
		input{
			width: 78%;
		}
	</style>
</head>
<body>
	<div class="outerbox">
		<div class="innerbox">
			<header>
				<a href="../Parent" class="logo">Miles of Smiles Daycare System</a>
				<nav>
					<ul>
						<li>
							<a title="home" href="../Parent" class="activebar"><i id="naviconheader" class="fa fa-home" aria-hidden="true"></i></a>
						</li>
						<li>
							<a title="dependent" href="dependent.php"><i id="naviconheader" class="fa fa-users" aria-hidden="true"></i></a>
						</li>
						<li>
						<li>
							<?php 
							if(empty($rowMe['picture']))
							{
								?>
								<a title="profile" href="profile.php" style="text-decoration-style: none; text-decoration: none; padding-right: 20px ; padding-bottom: 0; padding-top: 0;  padding-left: 0px">
									<div class="profile-picnohover" style="background-image: url('../attachment/image/parent.png');" >
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
				<a id="clickApply" href="#apply" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="mainBox">
						<div class="mainPictureHolder">
							<img id="mainBoxPicture" src="../attachment/image/applynow.jpg" width="70px" height="70px">
						</div>
						<div class="mainBoxText">
							<p class="mainBoxTitle">
								Enrolment<br><i style="color: gray;">Apply</i>
							</p>
							
							<p>
							</p>
						</div>
					</div>
				</a>
				<a id="clickPay" href="#pay" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="mainBox">
						<div class="mainPictureHolder">
							<img id="mainBoxPicture" src="../attachment/image/pay.jpg" width="70px" height="70px">
						</div>
						<div class="mainBoxText">
							<p class="mainBoxTitle">
								To Pay<br><i style="color: green;">Approved</i>
							</p>
							
								<?php 
								if($resultToPay->num_rows == 0 )
								{
									echo "<p class='mainBoxDetail' style='color:gray;'>0</i>";
								}
								else{

									echo "<p class='mainBoxDetail'>" .$resultToPay->num_rows;
								}
								 ?>
							</p>
						</div>
					</div>
				</a>
				<a id="clickEnrolmentHistory" href="#enrolmentHistory" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="mainBox">
						<div class="mainPictureHolder">
							<img id="mainBoxPicture" src="../attachment/image/enrolment.png" width="70px" height="70px">
						</div>
						<div class="mainBoxText">
							<p class="mainBoxTitle">
								Enrolment<br><i style="color: gray;">history</i>
							</p>
							
								<?php 
								if($resultEnrolmentHistory->num_rows == 0 )
								{
									echo "<p class='mainBoxDetail' style='color:gray;'>0</i>";
								}
								else{

									echo "<p class='mainBoxDetail'>" .$resultEnrolmentHistory->num_rows;
								}
								 ?>
							</p>
						</div>
					</div>
				</a>
			</div>

			<div id="apply" class="mainBoxSection">
				<div class="titleTextHolder">
					Enrolment <i style="color: gray;">Apply</i><i style="font-weight: normal; float: right;"><?php echo $nextMonth ?></i>
				</div>
				<hr>
				<p id="hideApply" class="hide">hide</p>
				<div class="mainBoxContent" >
					
					<?php
					if($resultAlreadyApplied->num_rows == $resultDependent->num_rows){
						?>
						<br><center style="font-style: italic; color: gray;">ALL OF YOUR DEPENDENT HAS APPLIED FOR <?php echo $nextMonth ?> ENROLMENT</center>
						<?php
					}else{
						?>
						<div class="informationHolder" style="margin-left:auto; margin-right: auto;">
							ENROLMENT FORM
						</div>
						<table cellpadding="4" style="width: 80%;margin-left: auto; margin-right:auto" align="center">
							
							<tr>
								<th>
									Dependent
								</th>
								<td>
									<select id="applyDependent">
										<?php
										$sqlNotApplied = "SELECT * FROM dependent WHERE dependent.parentID = '".$username."' AND dependentID NOT IN (SELECT dependentID from enrolment WHERE dateOfApplication LIKE '".$doa."')";
										$resultNotApplied = $conn->query($sqlNotApplied);
										while($rowNotApplied = $resultNotApplied->fetch_assoc()){
											?>
											<option id="applydependent<?php echo $rowNotApplied['fullName'] ?>" value="<?php echo $rowNotApplied['dependentID'] ?>"><?php echo $rowNotApplied['fullName'] ?></option>
											<?php
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<th>
									Total Day
								</th>
								<td>
									<input type="number" style="width: 40%;" name="totalDays" id="totalDays" placeholder="total days"><p class="warning">Note: min: 10 days. max: 20 days.</p>
								</td>
							</tr>
							<tr>
								<th>
									Admission
								</th>
								<td>
									<input type="text" style="width: 40%;cursor: default;" name="admissiona" id="admissiona" readonly value="<?php echo $nextMonth ?>">
								</td>
							</tr>
							<tr>
								<td colspan="2" align="center">
									<input type="submit" id="submit_enrolment" class="submit">
								</td>
							</tr>

						</table>

						<?php
					if($resultSetting->num_rows == 0){
						?>
						<br><center style="font-style: italic; color: gray; margin-top: 10px">NO RECORD OF SETTING FOUND</center>
						<?php
					}else{
						?>
						<div class="informationHolder" style="margin-left:auto; margin-right: auto;">
							PRICE PER DAY LIST
						</div>
						<table cellpadding="4"  align="center" style="width: 80%;">
							<tr>
								<th>Category</th>
								<th width="15%">Min. Age</th>
								<th width="15%">Max. Age</th>
								<th width="25%">Price (RM/Day)</th>
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
									</td>

								</tr>
								<?php
							}
							?>
						</table>
						
						<?php
						}
					}
					?>
				</div>
			</div>

			<div id="pay" class="mainBoxSection">
				<div class="titleTextHolder">
					To Pay <p class="warning" style="display: inline;">Note: Please pay before 30 to avoid cancellation of approval</p><i style="font-weight: normal; float: right;"><?php echo $nextMonth ?></i>
				</div>
				<hr>
				<p class="hide" id="hidePay">hide</p>
				<div class="mainBoxContent">
					<?php
					if($resultToPay->num_rows == 0)
					{
						?>
						<br><center style="font-style: italic; color: gray;">NO TO-PAY ENROLMENT FOUND</center>
						<?php 
					}
					else
					{
						?>
						<div style="width:50%; margin-left:auto; margin-right: auto; margin-bottom: 10px;">
							<select style="width: 40%;" id="selectToPay">
								<option selected value="enrolmentIDAZ">Enrolment ID A-Z</option>
								<option value="enrolmentIDZA">Enrolment ID Z-A</option>
								<option value="fullNameAZ">Full Name A-Z</option>
								<option value="fullNameZA">Full Name Z-A</option>
							</select>
							<input style="width: 40%;" placeholder="Search" type="text" name="searchToPay" id="searchToPay">
						</div>
						
						<div id="table_toPay">
							
						</div>
						
						<?php
					}
					?>
				</div>
			</div>
			<div id="enrolmentHistory" class="mainBoxSection">
				<div class="titleTextHolder">
					Enrolment <i style="color: gray;">history</i>
				</div>
				<hr>
				<p class="hide" id="hideEnrolmentHistory">hide</p>
				<div class="mainBoxContent">
					<?php
					if($resultEnrolmentHistory->num_rows == 0)
					{
						?>
						<center style="font-style: italic; color: gray;">NO RECORD OF ENROLMENT FOUND</center>
						<?php 
					}
					else
					{

						?>
						<div style="width:50%; margin-left:auto; margin-right: auto; margin-bottom: 10px;">
							<select style="width: 40%;" id="selectOption">
								<option value="enrolmentIDAZ">Enrolment ID A-Z</option>
								<option selected value="default">Enrolment ID Z-A</option>
								<option value="fullNameAZ">Full Name A-Z</option>
								<option value="fullNameZA">Full Name Z-A</option>
								<option value="statusAZ">Status A-Z</option>
								<option value="statusZA">Status Z-A</option>
							</select>
							<input style="width: 40%;" placeholder="Search" type="text" name="searchEnrolment" id="searchEnrolment">
						</div>
						
						<div id="table_enrolment">
							
						</div>
						
						<?php
					}
					?>						
				</div>		
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){

			load_toPay();
			load_enrolment();

			//========================================================================
			function load_toPay(querySearch, par){
				
				$.ajax({
					url:"search_topay.php",
					method:"POST",
					data:{querySearch:querySearch,
						par:par
						
					},
					success:function(data)
					{
						$('#table_toPay').html(data);

					}
				});
			}

			$('#searchToPay').keyup(function(){
				var search = $(this).val();
				var opt = $('#selectToPay :selected').val();
				if(search != '')
				{
					load_toPay(search, opt);
				}
				else
				{
					load_toPay();
				}
			});

			$('#selectToPay').change(function (){
				var search = $('#searchToPay').val();
				var opt = $('#selectToPay :selected').val();
				if(opt != '')
				{
					load_toPay(search, opt);
				}
				else
				{
					load_toPay();
				}

			});

			//=======================================================================

			//=======================================================================================

			function load_enrolment(querySearchEnrolment, options)
			{
				$.ajax({
					url:"search_enrolment.php",
					method:"POST",
					data:{querySearchEnrolment:querySearchEnrolment,
						options:options
					},
					success:function(data)
					{
						$('#table_enrolment').html(data);
					}
				});
			}
			$('#searchEnrolment').keyup(function(){
				var search = $(this).val();
				var opt = $('#selectOption :selected').val();
				if(search != '')
				{
					load_enrolment(search, opt);
				}
				else
				{
					load_enrolment();
				}
			});

			$('#selectOption').change(function (){
				var search = $('#searchEnrolment').val();
				var opt = $('#selectOption :selected').val();
				if(opt != '')
				{
					load_enrolment(search, opt);
				}
				else
				{
					load_enrolment();
				}

			});

		});
	</script>
	<?php
	include('../footer.php');
	?>
	<div id="stickyholder" style="visibility: hidden;">
		<a class="sticky" href="#top">
			<i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i>
		</a>
	</div>
</body>
</html>