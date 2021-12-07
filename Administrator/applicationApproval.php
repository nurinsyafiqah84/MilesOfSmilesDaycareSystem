<?php
include('../inc/dbconnect.php');
$datetimenow = date("Y-m-d") . " " . date("H:i:s", time()+25200);

if(isset($_POST['approve_check'])){

	$approver = $_SESSION['username'];

	$applicationID = $_POST['applicationID'];

	$sqlApprove = "UPDATE nanny_application SET applicationStatus = 'approved', completedDateTime = '".$datetimenow."', approver = '".$approver."' WHERE applicationID = '".$applicationID."'";

	if($conn->query($sqlApprove) === TRUE){
		$_SESSION['display'] = "approvedEmail"; 
		echo "approved";	

	}else{
		echo "notApproved";	
	}

	exit();
}else if(isset($_POST['reject_check'])){

	$approver = $_SESSION['username'];

	$applicationID = $_POST['applicationID'];

	$sqlReject = "UPDATE nanny_application SET applicationStatus = 'rejected', completedDateTime = '".$datetimenow."', approver = '".$approver."' WHERE applicationID = '".$applicationID."'";

	if($conn->query($sqlReject) === TRUE){
		$_SESSION['display'] = "rejectedEmail"; 
		echo "rejected";	

	}else{
		echo "notrejected";	
	}

	exit();
}


?>