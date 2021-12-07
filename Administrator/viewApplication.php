<?php
include('../inc/dbconnect.php');
session_start();
$onereplyfromapplicant = 0;
include('applicationApproval.php');
?>
<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" type="text/css" href="../css/header.css">
		<link rel="stylesheet" type="text/css" href="../css/nannyapplication.css">
		<link rel="stylesheet" type="text/css" href="../css/hiring.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<?php
$username = $_SESSION['username'];
$sqlMe = "SELECT picture FROM administrator WHERE username ='".$username."'";
$resultMe = $conn->query($sqlMe);
$rowMe = $resultMe->fetch_assoc();

if(isset($_GET['applicationID'])){
	$sqlMe = "SELECT picture FROM administrator WHERE username ='".$username."'";
	$resultMe = $conn->query($sqlMe);
	$rowMe = $resultMe->fetch_assoc();
	$applicationID = $_GET['applicationID'];
	$sql = "SELECT * FROM nanny_application WHERE applicationID = '".$applicationID."' ";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$sqlGetReplies = "SELECT *, reply.replySender AS sender FROM reply
						INNER JOIN nanny_application ON reply.applicationID = nanny_application.applicationID
						WHERE nanny_application.applicationID = '".$applicationID."'
						GROUP BY reply.replyID
						ORDER BY reply.replyID ASC";
	$resultReply = $conn->query($sqlGetReplies);

	if(isset($_SESSION['display']) && $_SESSION['display'] == "approvedEmail"){
		include('approvedEmail.php');
		unset($_SESSION['display']);
	}else if(isset($_SESSION['display']) && $_SESSION['display'] == "rejectedEmail"){
		include('rejectedEmail.php');
		unset($_SESSION['display']);
	}

	?>

		<title>Application #<?php echo $applicationID?></title>
		<script type="text/javascript">
			
			$( window ).scroll(function() {

				$("#stickyholder").css("visibility", "visible");

			});

			

			function clickshow()
			{
				$(".new").show();
				$("textarea").focus();
			}

			function hideDiv()
			{
				$("textarea").value = "";
				$(".new").hide();
			}

			function clickAlertClosed()
			{
				$.alert({
					boxWidth: '27%',
					typeAnimated: true,
						useBootstrap: false,
				    title: 'Application is reviewed!',
				    content: 'Sorry, this application has been reviewed. Reply cannot be done.',
				});
			}

			function emptyreply(){
				var reply = formreply.inputreply.value;
				if(reply.length < 3 || reply.length > 1000){
					$.confirm({
						boxWidth: '27%',
						title: 'Invalid reply!',
						content: 'Reply must be between 3 and 1000 characters only.',
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
		<style type="text/css">
			.innerbox{
				padding-bottom: 30px;
			}
			td{
				padding-left: 8px;
			}
			#remark{
				padding: 20px 50px;
			}
			.basicInformation{
				margin-bottom: 5px;
			}
			.informationHolder{
				margin-top: 0px;
			}
			ul{
				list-style-type: none;
			}
			#accept, #oppose{
				font-size: 40px;
				cursor: pointer;
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
								<a title="home" href="../Administrator"><i id="naviconheader" class="fa fa-home" aria-hidden="true"></i></a>
							</li>
							<li>
								<a title="nanny application" class="activebar" href="nannyApplication.php" title="nanny application">
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
				<div class="basicInformation">
					<div class="categorybox" style="margin-left:0">
						<div class="categorytext">
							<p class="categorytitle"><b>
								<i class="fa fa-ticket" aria-hidden="true"></i></b>
							</p>

							<p class="categorydetail">
								<?php
								echo '#' .$row['applicationID'];
								?>
							</p>
						</div>
					</div>
					<div class="categorybox">
						<div class="categorytext">
							<p class="categorytitle">
								Status
							</p>
							<?php
							if($row['applicationStatus'] == "waiting")
							{
								echo "<p class='categorydetail' style='color: orange'>" . ucfirst($row['applicationStatus']);
							}
							else if($row['applicationStatus'] == "rejected")
							{
								echo "<p class='categorydetail' style='color: red'>" . ucfirst($row['applicationStatus']);
							}
							else
							{
								echo "<p class='categorydetail' style='color: green'>" . ucfirst($row['applicationStatus']);
							}
							?>
							</p>
						</div>
					</div>
					<div class="categorybox">
						<div class="categorytext">
							<p class="categorytitle"><b>
								<i class="fa fa-calendar-o" aria-hidden="true"></i></b>
							</p>
							<p class="categorydetail">
								<?php
								$newdate = date("d-m-Y", strtotime($row['applicationDateTime'])); echo $newdate; 
								?>	
							</p>
						</div>
					</div>
					<div class="categorybox">
						<div class="categorytext">
							<p class="categorytitle"><b>
								<i class="fa fa-clock-o" aria-hidden="true"></i></b>
							</p>

							<p class="categorydetail">
								<?php
								echo date('h:i A', strtotime($row['applicationDateTime'])); 
								?>	
							</p>
						</div>
					</div>
				</div>

				<center>
					<div class="informationHolder">
						APPLICANT INFORMATION
					</div>
					<div class="information" >
						<table align="center" width="100%" cellpadding="5">
							<tr>
								<td style="width: 5%">
									<i class="fa fa-id-card-o" aria-hidden="true"></i>
								</td>
								<td style="width: 50%">
									<?php echo $row['name']?>
								</td>
								<td style="width: 5%">
									<i class="fa fa-graduation-cap" aria-hidden="true"></i>
								</td>
								<td>
									<?php echo $row['highestEduLevel']?>
								</td>

							</tr>
							<tr>
								<td>
									<i class="fa fa-birthday-cake" aria-hidden="true"></i>
								</td>
								<td>
									<?php echo date("d-m-Y", strtotime($row['dateOfBirth'])); ?>
								</td>
								<td style="width: 5%">
									<i class="fa fa-comment-o" aria-hidden="true"></i>
								</td>
								<td>
									<?php echo $row['fieldOfStudy']?>
								</td>
							</tr>
							<tr>
								<td>
									<i class="fa fa-envelope-o" aria-hidden="true"></i>
								</td>
								<td>
									<?php echo $row['email']?>
								</td>
								<td >
									<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
								</td>
								<td>
									<a download="<?php echo $row['resume'] ?>" href="resume/<?php echo $row['resume'] ?>"><?php echo $row['resume']; ?></a>
								</td>
							</tr>
							<tr>
								<td style="text-align:center; text-decoration-line: ;" colspan="4">
									Remark
								</td>
							</tr>
							<tr>
								<td style="text-align:center;" id="remark" colspan="4">
									<?php
									if($row['remark'] == "")
										echo "-";
									else
										echo $row['remark'];
									?>
								</td>
							</tr>
						</table>
					</div>
					<div class="informationHolder">
						APPLICATION RESULT
					</div>
					<div class="information" >
						<table align="center" width="100%" cellpadding="5">
							<tr>								
								<?php
								if($row['applicationStatus'] == "waiting"){
									?><td colspan="4" align="center" id="remark" style="color: gray; font-style: italic; text-align: center;"><?php
									echo "This application has not been reviewed yet. Review now.";
								}else if($row['applicationStatus'] == "approved"){
									?><td colspan="4" align="center" id="remark"  style="color: green;font-style: italic; text-align: center; "><?php
									echo "This application has been approved";
								}else{
									?><td colspan="4" align="center" id="remark"  style="color: red;font-style: italic; font-style: italic;text-align: center; "><?php
									echo "This application has been rejected.";
								}
									?>

								</td>
							</tr>
						<?php
						if($row['applicationStatus'] == "waiting"){

						}else{
							?>
							<tr>
								<td width="16%" colspan="4" style="border-right: 1px solid cornsilk;">
									Reviewed By
									<?php
									if($row['approver'] == "")
										echo "-";
									else{
										echo $row['approver'] . "  @ " ;
										$newdate = date("d-m-Y", strtotime($row['completedDateTime'])); echo $newdate;
										echo " " . date('h:i A', strtotime($row['completedDateTime']));
									}
									?>
								</td>
								
							</tr>
							<?php
						}
						?>
						</table>
						<?php 
						if($row['applicationStatus'] == "waiting"){
							?>
							<div class="enrolmentDetail" id="actionDetail">
								<div class="titleTextHolder" align="center">
									<p id="askStatement" style="font-style: italic; color: gray;">Approve this application?</p>
									<i id="accept" title="approve application" onclick="approveAlert()" class="fa fa-check-circle-o" aria-hidden="true"></i>
									<i id="oppose" title="reject application" class="fa fa-times-circle-o" onclick="rejectAlert()" aria-hidden="true"></i>
								</div>
							</div>
							<?php
						}
						?>
							
					</div>
				</center>
			</div>
		</div>
		
		<div class="outerbox" style="margin-top:30px; border-radius: 30px;">
			<div class="innerbox" style="padding-bottom: 25px;">
				<div style="padding-left: 20px; padding-right: 20px; padding-top: 10px;">
					<div class="informationHolderReply" >
						REPLIES
					</div>
				</div>
				<div class="comment_block">
					<?php
							if($resultReply->num_rows == 0 && ($row['applicationStatus'] == "rejected" || $row['applicationStatus'] == "approved")){ //application result has been announced! No replies shall be allowed now.
								?>
								<p align="center" style="font-style: italic; padding-top: 10px; padding-bottom: 20px; padding-right: 20px; padding-left: 20px; color: gray">Application for position nanny has been reviewed. Leaving replies under this application are no longer allowed.</p>
								<?php
							}else{
								// there is at least one reply exist
								if($resultReply->num_rows > 0){
									while($rowReply = $resultReply->fetch_assoc()){
										if($rowReply['replySender'] != $applicationID){ // administrator's reply
									?><div class="new_comment" >
										<ul class="user_comment" >
											<div style="display: inline-flex;">
												<div class="user_avatar">												<?php
												$sqlAdmin = "SELECT * FROM administrator WHERE username = '".$rowReply['replySender']."'";
												$resultAdmin = $conn->query($sqlAdmin);
												$rowAdmin = $resultAdmin->fetch_assoc();
												if($rowAdmin['picture'] == "")
												{
													?>
													<img src="../attachment/profile/admin.png">
													<?php
												}
												else
												{
													?>
													<img src="../attachment/profile/<?php echo $rowAdmin['picture'] ?>">
													<?php
												}
												?>

											</div>
											<div class="comment_body" style="display: inline-block;" >
												<p style="padding-right: 10px;"><?php echo $rowReply['reply']; ?></p>
												<?php
												if($rowReply['attachment'] != "")
												{
													?>
													<div style="padding-top:0px; padding-left: 10px;">
														<p style="font-size: 12px;"><a download="<?php echo $rowReply['attachment'] ?>" href="../NannyApplication/uploads/<?php echo $rowReply['attachment'] ?>"><?php echo $rowReply['attachment']; ?></a></p>
													</div>
													<?php
												}
												?>
											</div>
										</div>

										<div class="comment_toolbar">
											<div class="comment_details">
												<ul >
													<li ><i class="fa fa-clock-o"></i><?php echo " " . date('h:i A', strtotime($rowReply['replyDateTime'])); ?> </li>
													<li><i class="fa fa-calendar"></i><?php echo " " . date("d-m-Y", strtotime(($rowReply['replyDateTime'])));; ?></li>
													<li><i class="fa fa-pencil"></i> 
														<span class="user"><?php echo strtolower($rowAdmin['fullName']); ?></span>
													</li>
													
												</ul>
											</div>
										</div>
									</ul>
								</div>
								<?php
										}else{ //reply sender is the applicant
											$onereplyfromapplicant++;
											?>
											<div class="new_comment">
												<ul class="user_comment">
													<div style="display: inline-flex;">
														<div class="user_avatar">
															<?php
															if($row['passport'] == "")
															{
																?>
																<img src="../attachment/profile/applicant.png">
																<?php
															}
															else
															{
																?>
																<img src="profile/<?php echo $row['passport'] ?>">
																<?php 
															}
															?>

														</div>
														<div class="comment_body" style="display: inline-block;">
															<p style="padding-right: 10px;"><?php echo $rowReply['reply']; ?></p>
															<?php
															if($rowReply['attachment'] != "")
															{
																?>
																<div style="padding-top: 0px; padding-left: 10px;">
																	<p style="font-size: 12px;"><a download="<?php echo $rowReply['attachment'] ?>" href="../NannyApplication/uploads/<?php echo $rowReply['attachment'] ?>"><?php echo $rowReply['attachment']; ?></a></p>
																</div>
																<?php
															}
															?>
														</div>
													</div>
													<div class="comment_toolbar">
														<div class="comment_details">
															<ul>
																<li ><i class="fa fa-clock-o"></i><?php echo " " . date('h:i A', strtotime($rowReply['replyDateTime'])); ?> </li>
																<li><i class="fa fa-calendar"></i><?php echo " " . date("d-m-Y", strtotime(($rowReply['replyDateTime'])));; ?></li>
																<li><i class="fa fa-pencil"></i> 
																	<span class="user"><?php echo strtolower($row['name']); ?></span>
																</li>
																<li>
																	<?php
																	if($row['applicationStatus'] == "waiting" )
																	{
																		?>
																		<div onclick="clickshow()">
																			<i class="fa fa-reply" style="color: green; margin-left: 10px;font-size: 13px; cursor: pointer; " title="reply"></i>
																		</div>
																		<?php
																	}
																	else
																	{
																		?>
																		<div onclick="clickAlertClosed()">
																			<i class="fa fa-reply" style="color: gray; margin-left: 10px;font-size: 13px; cursor: pointer; " ></i>
																		</div>
																		<?php 
																	}
																	?>
																</li>			
															</ul>
														</div>
													</div>
												</ul>
											</div>							
												<?php

											}
										}
								}else{ //no replies found but not waiting, not rejected nor approved

								}
								
								if($row['applicationStatus'] == "waiting" && $onereplyfromapplicant == 0){ //Application has not been checked by administrator.
								?>
									<div class="new" id="new" style="margin-top: 10px">
								<?php
								}else {?>
									<div class="new" id="new" style="display: none;margin-top: 10px">
										<i class="fa fa-times" aria-hidden="true" id="closediv" onclick="hideDiv()" title="Cancel"></i><?php
								}?>									
									<div class="user_avatar" style="">
										
										<?php
										if(empty($rowMe['picture'])){
											?>
											<img src="../attachment/profile/admin.png">
											<?php
										}else{
											?>
											<img src="../attachment/profile/<?php echo $rowMe['picture']?>">
											<?php 
										}

										?>

									</div>
									<form enctype="multipart/form-data" action="sendReply.php" onsubmit="return emptyreply()" name="formreply" method="post">
										<div class="input_comment">
											<input type="hidden" name="appID" id="appID" value="<?php echo $applicationID ?>">
											<input type="hidden" name="appemail" id="appemail" value="<?php echo $row['email'] ?>">
											<input type="hidden" name="appname" id="appname" value="<?php echo $row['name'] ?>">
											<textarea style="margin-top: 5px;" cols="70" rows="7" placeholder="reply" id="inputreply" name="inputreply" ></textarea>
											<input type="file" id="inputfile" name="inputfile" accept="image/jpeg, image/png, image/jpg, application/pdf" style="display: inline; width: 70%;"><input type="submit" name="submit" class="submit" value="Reply" id="buttonReply" />
											<p style="font-size: 12px; margin-top: -5px"><strong>Note:</strong> Only .pdf, .png, .jpeg, and .jpg format are allowed. max. size 10MB.
											</p>
										</div>
									</form>   
									</div>  <?php

								}

								?>
							</div>

						</div>
					</div>
		<?php	
		if(isset($_GET['reload'])){?>
			<script type="text/javascript">
				window.scrollTo(0,document.body.scrollHeight);
			</script>
			<?php
		}
		include('../footer.php');
		?>
		<script type="text/javascript">
			$('document').ready(function(){


			});

			function approveAlert(){
				var applicationID = <?php echo $applicationID ?>;
				$.confirm({
					boxWidth: '27%',
					title: 'Approve application?',
					content: 'Are you sure to approve this application #'+ applicationID +'? This action cannot be undone.',
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

				var applicationID = <?php echo $applicationID ?>;
				$.ajax({
					url: "viewApplication.php?reload",
					type: "POST",
					cache: false,
					data:{
						approve_check : 1,
						applicationID: applicationID,
					},
					success: function(response){
						if (response == 'approved' ) {
							location.reload();
						}else if (response == 'notApproved') {


						}
					}
				});					
			}


			function rejectAlert(){
				var applicationID = <?php echo $applicationID ?>;
				$.confirm({
					boxWidth: '27%',
					title: 'Reject application?',
					content: 'Are you sure to reject this application #' + applicationID +'? This action cannot be undone.',
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
				var applicationID = <?php echo $applicationID ?>;
				$.ajax({
					url: "viewApplication.php?reload",
					type: "POST",
					cache: false,
					data:{
						reject_check : 1,
						applicationID: applicationID,
					},
					success: function(response){
						if (response == 'rejected' ) {
							location.reload();
							
						}else if (response == 'notrejected') {
							
						}
					}
				});
			}

		</script>
		<div id="stickyholder" style="visibility: hidden;">
			<a class="sticky" href="#top">
				<i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i>
			</a>
		</div>	
	</body>
	</html>





	<?php
}else{ //get is not initiated
	?>
	<script type="text/javascript">
		$.confirm({
			boxWidth: '27%',
			title: 'Error',
			content: 'Sorry, there was a problem to search the application. Please try again.',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				tryAgain: {
					text: 'Try again',
					btnClass: 'btn-red',
					action: function(){
						window.location.replace('nannyApplication.php');
					}
				}
			}
		});	
	</script>
	<?php
}
?>