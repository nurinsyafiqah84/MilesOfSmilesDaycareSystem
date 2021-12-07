<?php
include('../inc/dbconnect.php');

if(isset($_POST['reject_check'])){
	$_SESSION['action']="rejected";
	$approver = $_SESSION['username'];

	$enrolmentID = $_POST['enrolmentID'];

	$sqlReject = "UPDATE enrolment SET status = 'rejected', approver = '".$approver."' WHERE enrolmentID = '".$enrolmentID."'";

	if($conn->query($sqlReject) === TRUE){
		echo "rejected";	

	}else{
		echo "notrejected";	
	}

	exit();
}


?>