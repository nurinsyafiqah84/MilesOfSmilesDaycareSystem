<?php
include('../inc/dbconnect.php');
session_start();
$username = $_SESSION['username'];

$sql = "SELECT * FROM nanny_application ORDER BY applicationID DESC";
$result = $conn->query($sql);

$sqlWaiting = "SELECT * FROM nanny_application WHERE applicationStatus = 'waiting'";
$resultWaiting = $conn->query($sqlWaiting);

$sqlMe = "SELECT picture FROM administrator WHERE username ='".$username."'";
$resultMe = $conn->query($sqlMe);
$rowMe = $resultMe->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Nanny Application [NEW <?php echo $resultWaiting->num_rows ?>]</title>
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<link rel="stylesheet" type="text/css" href="../css/home.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
	<script type="text/javascript">
		
		$( window ).scroll(function() {

			$("#stickyholder").css("visibility", "visible");

		});

		$(document).ready(function(){
			$('.mainBoxSection').show();
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
							<a title="home" href="../Administrator"><i id="naviconheader" class="fa fa-home" aria-hidden="true"></i></a>
						</li>
						<li>
							<a title="nanny application" href="nannyApplication.php" class="activebar" title="nanny application">
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
			<div class="mainBoxSection" style="width:90%; margin-left: auto; margin-right: auto; padding-top:7px; margin-top: 40px;">
				<div class="titleTextHolder">
					Nanny Application
				</div>
				<hr>
				<div class="mainBoxContent" style="margin-top:30px; width: 98%; margin-left: auto; margin-right: auto;">
					<?php
					if($result->num_rows == 0)
					{
						?>
						<center style="font-style: italic; color: gray;">NO RECORD OF NANNY APPLICATION FOUND</center>
						<?php 
					}
					else
					{

						?>
						<div style="width:50%; margin-left:auto; margin-right: auto; margin-bottom: 10px; margin-bottom: 25px">
							<select style="width: 30%;" id="sortapp">
								<option selected value="applicationIDAZ">ID A-Z</option>
								<option value="applicationIDZA">ID Z-A</option>
								<option value="fullNameAZ">Name A-Z</option>
								<option value="fullNameZA">Name Z-A</option>
								<option value="eduLevel">Edu. Level</option>
								<option value="ageAZ">Age A-Z</option>	
								<option value="ageZA">Age Z-A</option>								
								<option value="statusAZ">Status A-Z</option>
								<option value="statusZA">Status Z-A</option>
								<option value="thisMonth">This Month</option>
								<option value="lastMonth">Last Month</option>
							</select>
							<input style="width: 40%;" placeholder="Search" type="text" name="searchapp" id="searchapp">
						</div>
						
						<div id="table_nannyApplication">
							
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
			load_app();

			function load_app(querySearch, opt){
				$.ajax({
					url:"search_nannyapplication.php",
					method:"POST",
					data:{querySearch:querySearch,
						opt:opt
					},
					success:function(data)
					{
						$('#table_nannyApplication').html(data);
					}
				});
			}

			$('#searchapp').keyup(function(){
				var search = $(this).val();
				var opt = $('#sortapp :selected').val();
				if(search != '')
				{
					load_app(search, opt);
				}
				else
				{
					load_app();
				}
			});

			$('#sortapp').change(function (){
				var search = $('#searchapp').val();
				var opt = $('#sortapp :selected').val();
				if(opt != '')
				{
					load_app(search, opt);
				}
				else
				{
					load_app();
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