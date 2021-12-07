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

$sqlMe = "SELECT picture FROM administrator WHERE username='".$username."'";
$resultMe = $conn->query($sqlMe);
$rowMe = $resultMe->fetch_assoc();

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

$sqlEnrolmentWaiting = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
						ON enrolment.dependentID = dependent.dependentID
						JOIN parent ON
						dependent.parentID = parent.username
						WHERE status = 'waiting' AND dateOfApplication LIKE '".$doa."'";

$resultEnrolmentWaiting = $conn->query($sqlEnrolmentWaiting);

$sqlEnrolmentApproved = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
						ON enrolment.dependentID = dependent.dependentID
						JOIN parent ON
						dependent.parentID = parent.username
						WHERE status = 'approved' AND dateOfApplication LIKE '".$doa."'";

$resultEnrolmentApproved = $conn->query($sqlEnrolmentApproved);

$sqlEnrolmentRejected = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
						ON enrolment.dependentID = dependent.dependentID
						JOIN parent ON
						dependent.parentID = parent.username
						WHERE status = 'rejected' AND dateOfApplication LIKE '".$doa."'";
$resultEnrolmentRejected = $conn->query($sqlEnrolmentRejected);

$sqlEnrolmentHistory = "SELECT * FROM enrolment ORDER BY enrolmentID";
$resultEnrolmentHistory = $conn->query($sqlEnrolmentHistory);

$sqlDependent = "SELECT *, dependent.fullName as dependentName FROM dependent JOIN parent
					ON dependent.parentID = parent.username ORDER BY dependent.fullName ASC";
$resultDependent = $conn->query($sqlDependent);


$sqlParent = "SELECT * FROM parent";
$resultParent = $conn->query($sqlParent);
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

		$(document).ready(function() {

			$('#clickEnrolment').click(function(){
				$('#enrolment').toggle();
			});

			$('#hideEnrolment').click(function(){
				$('#enrolment').toggle();
			});

			$('#clickApproved').click(function(){
				$('#approvedEnrolment').toggle();
			});

			$('#hideApprovedEnrolment').click(function(){
				$('#approvedEnrolment').toggle();
			});

			$('#clickRejected').click(function(){
				$('#rejectedEnrolment').toggle();
			});

			$('#hideRejectedEnrolment').click(function(){
				$('#rejectedEnrolment').toggle();
			});

			$('#clickEnrolmentHistory').click(function(){
				$('#enrolmentHistory').toggle();
			});

			$('#hideEnrolmentHistory').click(function(){
				$('#enrolmentHistory').toggle();
			});


			$('#clickDependent').click(function(){
				$('#dependent').toggle();
			});

			$('#hideDependent').click(function(){
				$('#dependent').toggle();

			});

			$('#clickParent').click(function(){
				$('#parent').toggle();
			});

			$('#hideParent').click(function(){
				$('#parent').toggle();

			});

			
		});
	</script>
</head>
<body>
	<div class="outerbox">
		<div class="innerbox">
			<header>
				<a href="../Administrator" class="logo">Miles of Smiles Daycare System</a>
				<nav>
					<ul>
						<li>
							<a title="home" href="../Administrator" class="activebar"><i id="naviconheader" class="fa fa-home" aria-hidden="true"></i></a>
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
				<a id="clickEnrolment" href="#enrolment" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="mainBox">
						<div class="mainPictureHolder">
							<img id="mainBoxPicture" src="../attachment/image/enrolment.png" width="70px" height="70px">
						</div>
						<div class="mainBoxText">
							<p class="mainBoxTitle">
								Enrolment<br><i style="color: orange;">waiting</i>
							</p>
							
								<?php 
								if($resultEnrolmentWaiting->num_rows == 0 )
								{
									echo "<p class='mainBoxDetail' style='color:gray;'>0</i>";
								}
								else{

									echo "<p class='mainBoxDetail'>" .$resultEnrolmentWaiting->num_rows;
								}
								 ?>
							</p>
						</div>
					</div>
				</a>
				<a id="clickApproved" href="#approvedEnrolment" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="mainBox">
						<div class="mainPictureHolder">
							<img id="mainBoxPicture" src="../attachment/image/enrolment.png" width="70px" height="70px">
						</div>
						<div class="mainBoxText">
							<p class="mainBoxTitle">
								Enrolment<br><i style="color: green;">approved</i>
							</p>
							
								<?php 
								if($resultEnrolmentApproved->num_rows == 0 )
								{
									echo "<p class='mainBoxDetail' style='color:gray;'>0</i>";
								}
								else{

									echo "<p class='mainBoxDetail'>" .$resultEnrolmentApproved->num_rows;
								}
								 ?>
							</p>
						</div>
					</div>
				</a>
				<a id="clickRejected" href="#rejectedEnrolment" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="mainBox">
						<div class="mainPictureHolder">
							<img id="mainBoxPicture" src="../attachment/image/enrolment.png" width="70px" height="70px">
						</div>
						<div class="mainBoxText">
							<p class="mainBoxTitle">
								Enrolment<br><i style="color: red;">rejected</i>
							</p>
							
								<?php 
								if($resultEnrolmentRejected->num_rows == 0 )
								{
									echo "<p class='mainBoxDetail' style='color:gray;'>0</i>";
								}
								else{

									echo "<p class='mainBoxDetail'>" .$resultEnrolmentRejected->num_rows;
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
				<a id="clickDependent" href="#dependent" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="mainBox">
						<div class="mainPictureHolder">
							<img id="mainBoxPicture" src="../attachment/image/children.png" width="70px" height="70px">
						</div>
						<div class="mainBoxText">
							<p class="mainBoxTitle">
								Dependent
							</p>
							
								<?php 
								if($resultDependent->num_rows == 0 )
								{
									echo "<p class='mainBoxDetail' style='color:gray;'>0</i>";
								}
								else{

									echo "<p class='mainBoxDetail' style='margin-top: 35px;'>" . $resultDependent->num_rows;
								}
								 ?>
							</p>
						</div>
					</div>
				</a>
				<a id="clickParent" href="#parent" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="mainBox">
						<div class="mainPictureHolder">
							<img id="mainBoxPicture" src="../attachment/image/parent.png" width="70px" height="70px">
						</div>
						<div class="mainBoxText">
							<p class="mainBoxTitle">
								Parent
							</p>
							
								<?php 
								if($resultParent->num_rows == 0 )
								{
									echo "<p class='mainBoxDetail' style='color:gray;'>0</i>";
								}
								else{

									echo "<p class='mainBoxDetail' style='margin-top: 35px;'>" . $resultParent->num_rows;
								}
								 ?>
							</p>
						</div>
					</div>
				</a>
			</div>

			<div id="enrolment" class="mainBoxSection">
				<div class="titleTextHolder">
					Enrolment <i style="color: orange;">waiting</i> <i style="font-weight: normal; float: right;"><?php echo $nextMonth ?></i>
				</div>
				<hr>
				<p id="hideEnrolment" class="hide">hide</p>
				<div class="mainBoxContent" >
					<?php
					if($resultEnrolmentWaiting->num_rows == 0){
						?>
						<center style="font-style: italic; color: gray; margin-top: 10px">NO ENROLMENT APPLICATION TO BE APPROVED</center>
						<?php
					}else{
						?>
						<div style="width:50%; margin-left:auto; margin-right: auto; margin-bottom: 10px;">
							<select style="width: 40%;" id="selectOptionWait">
								<option selected value="enrolmentIDAZ">Enrolment ID A-Z</option>
								<option value="default">Enrolment ID Z-A</option>
								<option value="fullNameAZ">Full Name A-Z</option>
								<option value="fullNameZA">Full Name Z-A</option>
							</select>
							<input style="width: 40%;" placeholder="Search" type="text" name="searchWaiting" id="searchWaiting">
						</div>
						
						<div id="table_waiting">
							
						</div>
						
						<?php
					}
					?>
				</div>
			</div>
			<div id="approvedEnrolment" class="mainBoxSection">
				<div class="titleTextHolder">
					Enrolment <i style="color: green;">approved</i><i style="font-weight: normal; float: right;"><?php echo $nextMonth ?></i>
				</div>
				<hr>
				<p class="hide" id="hideApprovedEnrolment">hide</p>
				<div class="mainBoxContent">
					<?php
					if($resultEnrolmentApproved->num_rows == 0)
					{
						?>
						<center style="font-style: italic; color: gray;">NO APPROVED ENROLMENT FOUND</center>
						<?php 
					}
					else
					{
						?>
						<div style="width:50%; margin-left:auto; margin-right: auto; margin-bottom: 10px;">
							<select style="width: 40%;" id="selectOptionApp">
								<option selected value="enrolmentIDAZ">Enrolment ID A-Z</option>
								<option value="default">Enrolment ID Z-A</option>
								<option value="fullNameAZ">Full Name A-Z</option>
								<option value="fullNameZA">Full Name Z-A</option>
							</select>
							<input style="width: 40%;" placeholder="Search" type="text" name="searchApproved" id="searchApproved">
						</div>
						
						<div id="table_approved">
							
						</div>
						
						<?php
					}
					?>
				</div>
			</div>
			<div id="rejectedEnrolment" class="mainBoxSection">
				<div class="titleTextHolder">
					Enrolment <i style="color: red;">rejected</i><i style="font-weight: normal; float: right;"><?php echo $nextMonth ?></i>
				</div>
				<hr>
				<p class="hide" id="hideRejectedEnrolment">hide</p>
				<div class="mainBoxContent">
					<?php
					if($resultEnrolmentRejected->num_rows == 0)
					{
						?>
						<center style="font-style: italic; color: gray;">NO REJECTED ENROLMENT FOUND</center>
						<?php 
					}
					else
					{

						?>
						<div style="width:50%; margin-left:auto; margin-right: auto; margin-bottom: 10px;">
							<select style="width: 40%;" id="selectOptionRej">
								<option selected value="enrolmentIDAZ">Enrolment ID A-Z</option>
								<option value="default">Enrolment ID Z-A</option>
								<option value="fullNameAZ">Full Name A-Z</option>
								<option value="fullNameZA">Full Name Z-A</option>
							</select>
							<input style="width: 40%;" placeholder="Search" type="text" name="searchRejected" id="searchRejected">
						</div>
						
						<div id="table_rejected">
							
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
			<div id="dependent" class="mainBoxSection">
				<div class="titleTextHolder">
					Dependent
				</div>
				<hr>
				<p class="hide" id="hideDependent">hide</p>
				<div class="mainBoxContent">
					<?php
					if($resultDependent->num_rows == 0)
					{
						?>
						<center style="font-style: italic; color: gray;">NO RECORD OF DEPENDENT FOUND</center>
						<?php 
					}
					else
					{

						?>
						<div style="width:50%; margin-left:auto; margin-right: auto; margin-bottom: 10px;">
							<select style="width: 40%;" id="sortDependent" title="sort">
								<option selected value="fullNameAZ">Full Name A-Z</option>
								<option value="fullNameZA">Full Name Z-A</option>
								<option value="ageAZ">Age A-Z</option>
								<option value="ageZA">Age Z-A</option>
								<option value="parentAZ">Parent A-Z</option>
								<option value="parentZA">Parent A-Z</option>
								<option value="neverEnrolled">Never Enrolled</option>
								<option value="enrolled">Enrolled</option>
							</select>
							<input style="width: 40%;" placeholder="Search" type="text" name="searchDependent" id="searchDependent">
						</div>
						
						<div id="table_dependent">
							
						</div>
						
						<?php
					}
					?>						
				</div>		
			</div>
			<div id="parent" class="mainBoxSection">
				<div class="titleTextHolder">
					Parent
				</div>
				<hr>
				<p class="hide" id="hideParent">hide</p>
				<div class="mainBoxContent">
					<?php
					if($resultParent->num_rows == 0)
					{
						?>
						<center style="font-style: italic; color: gray;">NO RECORD OF PARENT FOUND</center>
						<?php 
					}
					else
					{

						?>
						<div style="width:50%; margin-left:auto; margin-right: auto; margin-bottom: 10px;">
							<select style="width: 40%;" id="sortParent" title="sort">
								<option selected value="usernameAZ">Username A-Z</option>
								<option value="usernameZA">Username Z-A</option>
								<option value="fullNameAZ">Full Name A-Z</option>
								<option value="fullNameZA">Full Name Z-A</option>
								<option value="email">E-mail</option>
							</select>
							<input style="width: 40%;" placeholder="Search" type="text" name="searchParent" id="searchParent">
						</div>
						
						<div id="table_parent">
							
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

			load_data();
			load_enrolment();
			load_rejected();
			load_waiting();
			load_dependent();
			load_parent();

			//=======================================================================================

			function load_parent(querySearch, par){
				$.ajax({
					url:"search_parent.php",
					method:"POST",
					data:{querySearch:querySearch,
						par:par
					},
					success:function(data)
					{
						$('#table_parent').html(data);
					}
				});
			}

			$('#searchParent').keyup(function(){
				var search = $(this).val();
				var opt = $('#sortParent :selected').val();
				if(search != '')
				{
					load_parent(search, opt);
				}
				else
				{
					load_parent();
				}
			});

			$('#sortParent').change(function (){
				var search = $('#searchParent').val();
				var opt = $('#sortParent :selected').val();
				if(opt != '')
				{
					load_parent(search, opt);
				}
				else
				{
					load_parent();
				}

			});


			//=======================================================================================

			function load_dependent(querySearch, dep){
				$.ajax({
					url:"search_dependent.php",
					method:"POST",
					data:{querySearch:querySearch,
						dep:dep
					},
					success:function(data)
					{
						$('#table_dependent').html(data);
					}
				});
			}

			$('#searchDependent').keyup(function(){
				var search = $(this).val();
				var opt = $('#sortDependent :selected').val();
				if(search != '')
				{
					load_dependent(search, opt);
				}
				else
				{
					load_dependent();
				}
			});

			$('#sortDependent').change(function (){
				var search = $('#searchDependent').val();
				var opt = $('#sortDependent :selected').val();
				if(opt != '')
				{
					load_dependent(search, opt);
				}
				else
				{
					load_dependent();
				}

			});


			//=======================================================================================

			function load_data(querySearchApproved, approvedOpt)
			{
				$.ajax({
					url:"search_approved.php",
					method:"POST",
					data:{querySearchApproved:querySearchApproved,
						approvedOpt:approvedOpt
					},
					success:function(data)
					{
						$('#table_approved').html(data);
					}
				});
			}
			$('#searchApproved').keyup(function(){
				var search = $(this).val();
				var opt = $('#selectOptionApp :selected').val();
				if(search != '')
				{
					load_data(search, opt);
				}
				else
				{
					load_data();
				}
			});

			$('#selectOptionApp').change(function (){
				var search = $('#searchApproved').val();
				var opt = $('#selectOptionApp :selected').val();
				if(opt != '')
				{
					load_data(search, opt);
				}
				else
				{
					load_data();
				}

			});

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


			//////////////////////////////////////////////////////////////////////////////////////////////////////////

			function load_rejected(querySearchRejected, opt)
			{
				$.ajax({
					url:"search_rejected.php",
					method:"POST",
					data:{querySearchRejected:querySearchRejected,
						opt: opt
					},
					success:function(data)
					{
						$('#table_rejected').html(data);
					}
				});
			}
			$('#searchRejected').keyup(function(){
				var search = $(this).val();
				var opt = $('#selectOptionRej :selected').val();
				if(search != '')
				{
					load_rejected(search, opt);
				}
				else
				{
					load_rejected();
				}
			});

			$('#selectOptionRej').change(function (){
				var search = $('#searchRejected').val();
				var opt = $('#selectOptionRej :selected').val();
				if(opt != '')
				{
					load_rejected(search, opt);
				}
				else
				{
					load_rejected();
				}

			});

			//////////////////////////////////////////////////////////////////////////////////////////////////////////

			function load_waiting(querySearchWaiting, opt)
			{
				$.ajax({
					url:"search_waiting.php",
					method:"POST",
					data:{querySearchWaiting:querySearchWaiting,
						opt:opt
					},
					success:function(data)
					{
						$('#table_waiting').html(data);
					}
				});
			}
			$('#searchWaiting').keyup(function(){
				var search = $(this).val();
				var opt = $('#selectOptionWait :selected').val();
				if(search != '')
				{
					load_waiting(search, opt);
				}
				else
				{
					load_waiting();
				}
			});

			$('#selectOptionWait').change(function (){
				var search = $('#searchWaiting').val();
				var opt = $('#selectOptionWait :selected').val();
				if(opt != '')
				{
					load_waiting(search, opt);
				}
				else
				{
					load_waiting();
				}

			});



		});
	</script>
	<?php
	if(isset($_GET['reload'])){

		if($_GET['reload'] == "parent"){
			?>
			<script type="text/javascript">
				$('#parent').show();		
			</script>
			<?php
			unset($_GET['reload']);
		}else if($_GET['reload'] == "dependent"){
			?>
			<script type="text/javascript">
				$('#dependent').show();		
			</script>
			<?php
			unset($_GET['reload']);
		}else if($_GET['reload'] == "history"){
			?>
			<script type="text/javascript">
				$('#enrolmentHistory').show();		
			</script>
			<?php
			unset($_GET['reload']);
		}else if($_GET['reload'] == 'enrolmentApproval'){
			?>
			<script type="text/javascript">
				$('#enrolment').show();		
			</script>
			<?php
			unset($_GET['reload']);
		}else if($_GET['reload'] == 'approvedEnrolment'){
			?>
			<script type="text/javascript">
				$('#approvedEnrolment').show();		
			</script>
			<?php
			unset($_GET['reload']);
		}
		else if($_GET['reload'] == 'rejectedEnrolment'){
			?>
			<script type="text/javascript">
				$('#rejectedEnrolment').show();		
			</script>
			<?php
			unset($_GET['reload']);
		}
	}

	if(isset($_SESSION['action'])){
		if($_SESSION['action']=="approved"){
			?>
			<script type="text/javascript">
				$('#approvedEnrolment').show();		
			</script>
			<?php			
			unset($_SESSION['action']);
		}else if($_SESSION['action']=="rejected"){
			?>
			<script type="text/javascript">
				$('#rejectedEnrolment').show();
			</script>
			<?php	
		}
	}
	include('../footer.php');
	?>
	<div id="stickyholder" style="visibility: hidden;">
		<a class="sticky" href="#top">
			<i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i>
		</a>
	</div>
</body>
</html>