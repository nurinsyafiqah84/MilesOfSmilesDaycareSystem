<?php
session_start();
include('../inc/dbconnect.php');

$enrolmentID = "";
$date =  date("Y-m-d");
$totalFee = 0.00;

function getAge($dob,$condate){ 
    $birthdate = new DateTime(date("Y-m-d",  strtotime(implode('-', array_reverse(explode('/', $dob))))));
    $today= new DateTime(date("Y-m-d",  strtotime(implode('-', array_reverse(explode('/', $condate))))));           
    $age = $birthdate->diff($today)->y;

    return $age;
}
$username = $_SESSION['username'];
$sqlMe = "SELECT picture FROM parent WHERE username='".$username."'";
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

		$(document).ready(function() {

			$('#clickPayDiv').click(function(){
				$('#paydiv').toggle();

				if($('#clickPayDiv').text() == "Pay Now"){
					
				}
			});



		});

	</script>
	<style type="text/css">
		#accept, #oppose{
			font-size: 40px;
			cursor: pointer;
		}
		input{
			width: 93%;
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
									<td id="tdcon"><?php echo $row['dependentName']?></td>
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
									<td id="tdcon"><?php echo $row['username']; ?></td>
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

									$totalFee = (float)$row['feePerDay'] * (float)$row['totalDays'];
									?>
									
									<center style="font-style: italic; color: gray;">
										NOT PAID YET
									</center>

									<center><a href="paydiv" id="clickPayDiv">Pay Now</a></center>
									<div class="paydiv" style="display: none;">
										<table cellpadding="2" cellspacing="2" style="width: 70%; margin-left: auto; margin-right: auto;">
											<tr>
												<td width="37%">Fee/Day (RM)</td>
												<td id="colonpay">:</td>
												<td colspan="2"><?php echo $row['feePerDay'] ?></td>
											</tr>
											<tr>
												<td >Total (RM)</td>
												<td id="colonpay" >:</td>
												<td><?php echo $totalFee ?> </td>
											</tr>
											<tr>
												<td >CC No</td>
												<td id="colonpay" >:</td>
												<td><input type="text" name=""></td>
											</tr>
											<tr>
												<td >CVV</td>
												<td id="colonpay" >:</td>
												<td><input type="number" name=""> </td>
											</tr>
											<tr>
												<td >Expiry Date</td>
												<td id="colonpay" >:</td>
												<td><input type="text" name=""> </td>
											</tr>
											<tr>
												<td align="right" colspan="3">
													<button class="submit">Pay</button>
												</td>
											</tr>
										</table>
									</div>
									<?php
									
								}

								?>

							</div>
							<?php
						}?>
					</div>
				</div>
			</div>
		</div>
		
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