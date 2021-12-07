<?php
session_start();
include('../inc/dbconnect.php');
include('approveEnrolment.php');
include('rejectEnrolment.php');

$enrolmentID = "";
$date =  date("Y-m-d");

function getAge($dob,$condate){ 
    $birthdate = new DateTime(date("Y-m-d",  strtotime(implode('-', array_reverse(explode('/', $dob))))));
    $today= new DateTime(date("Y-m-d",  strtotime(implode('-', array_reverse(explode('/', $condate))))));           
    $age = $birthdate->diff($today)->y;

    return $age;
}
$username = $_SESSION['username'];
$sqlMe = "SELECT picture FROM administrator WHERE username='".$username."'";
$resultMe = $conn->query($sqlMe);
$rowMe = $resultMe->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<link rel="stylesheet" type="text/css" href="../css/enrolment.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
	<script type="text/javascript">

		$( window ).scroll(function() {

			$("#stickyholder").css("visibility", "visible");

		});


		function invalid(){

			$.confirm({
				boxWidth: '27%',
				title: 'Invalid enrolment',
				content: 'Sorry, there was a problem to view the enrolment application. Please try again',
				type: 'red',
				typeAnimated: true,
				useBootstrap: false,
				buttons: {
					tryAgain: {
						text: 'Try again',
						btnClass: 'btn-red',
						action: function(){
							window.location.replace('../Administrator?reload=enrolmentApproval');
						}
					}
				}
			});		
		}
	</script>
	<style type="text/css">
		#accept, #oppose{
			font-size: 40px;
			cursor: pointer;
		}
	</style>

	<?php
	if(isset($_GET['enrolmentID'])){
		$enrolmentID = $_GET['enrolmentID'];

		$sql = "SELECT *, dependent.fullName as dependentName, parent.fullName as parentName FROM ENROLMENT
		JOIN dependent ON enrolment.dependentID = dependent.dependentID
		JOIN parent ON dependent.parentID = parent.username
		WHERE enrolment.enrolmentID = '".$enrolmentID."'";

		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		?>
		<title>Enrolment #<?php echo $enrolmentID ?></title>
		
</head>
<body>

		<div class="outerbox">
			<div class="innerbox" style="padding-bottom: 30px">
				<header>
					<?php
					if(isset($_GET['reload']) && $_GET['reload']=="history"){
						?><a href="../Administrator?reload=history" class="logo">Miles of Smiles Daycare System</a><?php
					}else if($row['status']=="waiting"){
						?><a href="../Administrator?reload=enrolmentApproval" class="logo">Miles of Smiles Daycare System</a><?php
					}else if($row['status']=="approved"){
						?><a href="../Administrator?reload=approvedEnrolment" class="logo">Miles of Smiles Daycare System</a><?php
					}else if($row['status']=="rejected"){
						?><a href="../Administrator?reload=rejectedEnrolment" class="logo">Miles of Smiles Daycare System</a><?php
					}else{
						?><a href="../Administrator?reload=approvedEnrolment" class="logo">Miles of Smiles Daycare System</a><?php
					}

					?>
					<nav>
						<ul>
							<li>
								<?php
								if(isset($_GET['reload']) && $_GET['reload']=="history"){
									?><a class="activebar" title="home" href="../Administrator?reload=history"><?php
								}else if($row['status']=="waiting"){
									?><a class="activebar" title="home" href="../Administrator?reload=enrolmentApproval"><?php
								}else if($row['status']=="approved"){
									?><a class="activebar" title="home" href="../Administrator?reload=approvedEnrolment"><?php
								}else if($row['status']=="rejected"){
									?><a class="activebar" title="home" href="../Administrator?reload=rejectedEnrolment"><?php
								}else{
									echo '<a class="activebar" title="home" href="../Administrator" >';
								}

								?>
								<i id="naviconheader" class="fa fa-home" aria-hidden="true"></i></a>
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
				<div class="mainBoxSection" style="width:90%; margin-left: auto; margin-right: auto; padding-top:7px">

					<div class="mainBoxContent" style="margin-top:14px; line-height: 1.6; width: 98%; margin-left: auto; margin-right: auto;">

						<?php
						if($row['status'] == "waiting"){
							?><center id="wait" style="color: orange; font-weight: bold; font-style: italic;"><i style="font-style: normal; color: black; font-weight: normal;">Status:</i> <?php echo $row['status']?> </center><?php
						}
						else if($row['status'] == "rejected"){
							?><center id="rej" style="color: red; font-weight: bold; font-style: italic;"><i style="font-style: normal; color: black;  font-weight: normal;">Status:</i> Rejected </center><?php
						}
						else if($row['status'] == "approved"){
							?><center id="ap" style="color: green; font-weight: bold; font-style: italic;"><i style="font-style: normal; color: black;  font-weight: normal;">Status:</i> Approved </center><?php
						}else if($row['status'] == "backed out"){
							?><center id="backedout" style="color: gray; font-weight: bold; font-style: italic;"><i style="font-style: normal; color: black;  font-weight: normal;">Status:</i> backed out </center><?php
						}
						?>
						<center id="rej" style="display: none; color: red; font-weight: bold; font-style: italic;"><i style="font-style: normal; color: black;  font-weight: normal;">Status:</i> Rejected </center>
						<center id="ap" style="display: none;color: green; font-weight: bold; font-style: italic;"><i style="font-style: normal; color: black;  font-weight: normal;">Status:</i> Approved </center>
						<center>
							Reviewed By: 
							<?php if($row['approver'] == "")
							echo "<span style='margin-left: 30px; color: gray'>-</span>";
							else
								echo $row['approver']; ?>

						</center>
						<center>
							Date of Application: <?php echo date("d-m-Y", strtotime($row['dateOfApplication'])) . " " . date('h:i A', strtotime($row['dateOfApplication'])) ?>
						</center>
						<div class="enrolmentDetail">
							<table style="width: 100%;" cellpadding="2">
								<tr>
									<td width="16%">Enrolment #</td>
									<td id="colon">:</td>
									<td id="tdcon"><b><?php echo $row['enrolmentID']?></b></td>
									<td></td>
									<td ></td>
									<td width="13%">Admission</td>
									<td id="colon">:</td>
									<td id="tdcon"><?php echo $row['admissionMonth'] . "/" . $row['admissionYear'] ?></td>


								</tr>
								<tr>
									<td>Total Days</td>
									<td id="colon">:</td>
									<td id="tdcon"><?php echo $row['totalDays'] ?> DAYS</td>
									<td colspan="5"></td>
								</tr>
							</table>

						</div>


						<div class="enrolmentDetail">
							<div class="titleTextHolder">
								Dependent Information
							</div>
							<hr>
							<table style="width: 100%;" cellpadding="2">
								<tr>
									<td width="16%">Full Name</td>
									<td id="colon">:</td>
									<td id="tdcon"><a target="_blank" href="viewDependent.php?dependentID=<?php echo $row['dependentID'];?>"><?php echo $row['dependentName']?></a></td>
									<td></td>
									<td ></td>
									<td width="13%">Age</td>
									<td id="colon">:</td>
									<td id="tdcon"><?php echo getAge($row['dateOfBirth'],$date); ?> Y/O</td>


								</tr>
								<tr>
									<td>Support Needs</td>
									<td id="colon">:</td>
									<td id="tdcon">
										<?php 
										if($row['additionalSupportNeeds'] == "")
											echo "<span style='margin-left: 30px; color: gray'>-</span>";
										else
											echo $row['additionalSupportNeeds']; ?>
									</td>
									<td></td>
									<td ></td>
									<td width="13%">Diet</td>
									<td id="colon">:</td>
									<td id="tdcon">
										<?php 
										if($row['diet'] == "")
											echo "<span style='margin-left: 30px; color: gray'>-</span>";
										else
											echo $row['diet']; ?>
									</td>
								</tr>
								<tr>
									<td>Remarks</td>
									<td id="colon">:</td>
									<td width="auto" colspan="4" style="text-align: justify;">
										<?php 
										if($row['remark'] == "")
											echo "<span style='margin-left: 30px; color: gray'>-</span>";
										else
											echo $row['remark']; ?>
									</td>

								</tr>
							</table>
						</div>
						<div class="enrolmentDetail">
							<div class="titleTextHolder">
								Parent Information
							</div>
							<hr>
							<table style="width: 100%; " cellpadding="2">
								<tr>
									<td width="16%">Username</td>
									<td id="colon">:</td>
									<td id="tdcon"><a target="_blank" href="viewParent.php?parentID=<?php echo $row['username']; ?>"><?php echo $row['username']; ?></a></td>
									<td></td>
									<td ></td>
									<td width="13%">Phone</td>
									<td id="colon">:</td>
									<td id="tdcon"><?php echo $row['phoneNo'] ?></td>


								</tr>
								<tr>
									<td>Full Name</td>
									<td id="colon">:</td>
									<td id="tdcon"><?php echo $row['parentName'] ?></td>

								</tr>
								<tr>
									<td>Address</td>
									<td id="colon">:</td>
									<td id="tdcon" colspan="2"><?php echo $row['address']?></td>

								</tr>
								<tr>
									<td>E-mail</td>
									<td id="colon">:</td>
									<td id="tdcon"><?php echo $row['email'] ?></td>
								</tr>
							</table>
						</div>

						<?php
						if($row['status'] != "waiting" && $row['status'] != "rejected"){
							?>
							<div class="enrolmentDetail" id="paymentDetail">
								<div class="titleTextHolder">
									Payment Information
								</div>
								<hr>
								<?php
								$sqlPayment = "SELECT * FROM payment WHERE enrolmentID = '".$enrolmentID."'";
								$resultPayment = $conn->query($sqlPayment);
								if($resultPayment->num_rows > 0){
									$rowPay = $resultPayment->fetch_assoc();
									?>
									<div align="center" style="width: 80%;">
										<table cellpadding="2">
											<tr>
												<td width="25%">Receipt #</td>
												<td id="colon">:</td>
												<td colspan="2" ><?php echo $rowPay['receiptID'] ?></td>
												<td></td><td></td><td align="center" style="border: 1px solid black; color:white; background-color: black;"><b>PAID</b></td>
											</tr>
											<tr>
												<td>Timestamp</td>
												<td id="colon">:</td>
												<td colspan="2"><?php echo $rowPay['datetime'] ?></td>
											</tr>
											<tr>
												<td>Fee Per Day (RM)</td>
												<td id="colon">:</td>
												<td colspan="2"><?php echo $row['feePerDay'] ?></td>
												<td width="17%">Total (RM)</td>
												<td id="colon">:</td>
												<td><?php echo $rowPay['totalFee'] ?> </td>
											</tr>
										</table>
									</div>

									<?php
								}else{
									?>
									<center style="font-style: italic; color: gray;">NOT PAID YET</center>
									<?php
									if($row['status'] == "approved"){
										?>
										<center><a id="backout" style="color: indianred;">CHANGE APPLICATION STATUS TO BACKED OUT?</a></center>
										<?php
									}
								}

								?>

							</div>
							<?php
						}

						if($row['status'] == "waiting"){
							?>
							<div class="enrolmentDetail" id="actionDetail">
								<div class="titleTextHolder" align="center">
									<p id="askStatement" style="font-style: italic; color: gray;">Approve this enrolment?</p>
									<i id="accept" title="approve enrolment" onclick="approveAlert()" class="fa fa-check-circle-o" aria-hidden="true"></i>
									<i id="oppose" title="reject enrolment" class="fa fa-times-circle-o" onclick="rejectAlert()" aria-hidden="true"></i>
								</div>

							</div>
							<?php
						}

						?>
					</div>
				</div>

			</div>

		</div>
		<script type="text/javascript">
			$('document').ready(function(){

				$('#backout').on('click', function(){
					$.confirm({
						boxWidth: '27%',
						title: 'Enrolment is a backed out?',
						content: 'Are you sure to change the status to backed out? This action cannot be undone.',
						type: 'red',
						typeAnimated: true,
						useBootstrap: false,
						autoClose: 'cancel|5000',
						buttons: {
							confirm: {
								text: 'Back out',
								btnClass: 'btn-red',
								action: function(){
									$.ajax({
										url: "registerDelete.php",
										type: "POST",
										data: {
											backout: 1,
											enrolmentID: <?php echo $enrolmentID ?>			
										},
										cache: false,
										success: function(dataResult){
											if(dataResult == "backedout"){
												<?php
												echo("location.href = 'viewEnrolment.php?enrolmentID=" . $enrolmentID ."';");
												?>
											}
											else if(dataResult == "notbackedout"){
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
				});
			});



			function approveAlert(){
				$.confirm({
					boxWidth: '27%',
					title: 'Approve enrolment?',
					content: 'Are you sure to approve this enrolment? This action cannot be undone.',
					type: 'green',
					typeAnimated: true,
					useBootstrap: false,
					autoClose: 'cancel|5000',
					buttons: {
						confirm: {
							text: 'Approve',
							btnClass: 'btn-green',
							action: function(){
								approveNow();
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

			function approveNow(){

				var enrolmentID = <?php echo $enrolmentID ?>;
				$.ajax({
					url: "viewEnrolment.php",
					type: "POST",
					cache: false,
					data:{
						approve_check : 1,
						enrolmentID: enrolmentID,
					},
					success: function(response){
						if (response == 'approved' ) {
							$('#wait').hide();
							$('#ap').show();
							$('#actionDetail').hide();
							location.reload();
						}else if (response == 'notApproved') {


						}
					}
				});					
			}

			function rejectAlert(){
				$.confirm({
					boxWidth: '27%',
					title: 'Reject enrolment?',
					content: 'Are you sure to reject this enrolment? This action cannot be undone.',
					type: 'red',
					typeAnimated: true,
					useBootstrap: false,
					autoClose: 'cancel|5000',
					buttons: {
						confirm: {
							text: 'Reject',
							btnClass: 'btn-red',
							action: function(){
								rejectNow();
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

			function rejectNow(){
				var enrolmentID = <?php echo $enrolmentID ?>;
				$.ajax({
					url: "viewEnrolment.php",
					type: "POST",
					cache: false,
					data:{
						reject_check : 1,
						enrolmentID: enrolmentID,
					},
					success: function(response){
						if (response == 'rejected' ) {
							$('#wait').hide();
							$('#rej').show();
							$('#actionDetail').hide();
							location.reload();
						}else if (response == 'notrejected') {


						}
					}
				});
			}

		</script>
		<?php

		include('../footer.php');

	}else{
		?>
	</head>
	<body onload="invalid()">
		<?php
	}

	?>
	<div id="stickyholder" style="visibility: hidden;">
		<a class="sticky" href="#top">
			<i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i>
		</a>
	</div>	
</body>
</html>