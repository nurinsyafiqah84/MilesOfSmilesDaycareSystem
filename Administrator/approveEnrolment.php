<?php
include('../inc/dbconnect.php');



if(isset($_POST['approve_check'])){
	$_SESSION['action']="approved";
	$approver = $_SESSION['username'];

	$enrolmentID = $_POST['enrolmentID'];

	$sqlApprove = "UPDATE enrolment SET status = 'approved', approver = '".$approver."' WHERE enrolmentID = '".$enrolmentID."'";

	if($conn->query($sqlApprove) === TRUE){
		echo "approved";	

	}else{
		echo "notApproved";	
	}

	exit();
}



?>