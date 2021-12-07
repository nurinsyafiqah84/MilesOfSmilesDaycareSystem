<?php
include('../inc/dbconnect.php');
session_start();

if(isset($_POST['registernanny_check'])){
	$registeredBy = $_SESSION['username'];
	$username = $_POST['username'];
	$password = "pass1234";
	$fullName = strtoupper($_POST['fullName']);
	$address = strtoupper($_POST['address']);
	$phoneNo = $_POST['phoneNo'];

	$sqlRegister = "INSERT INTO nanny (username, password, fullName, address, phoneNo, registeredBy) VALUES ('".$username."', '".$password."', '".$fullName."', '".$address."', '".$phoneNo."', '".$registeredBy."')";

	if($conn->query($sqlRegister) === TRUE){
		$_SESSION['reload'] = "nanny";
		echo "registered";
	}else{
		echo "notregistered";
	}

}else if(isset($_POST['deletenanny_check'])){
	$nannyusername = $_POST['nannyusername'];
	$sqlDelete = "DELETE FROM nanny WHERE username = '".$nannyusername."'";
	if($conn->query($sqlDelete) === TRUE){
		$_SESSION['reload'] = "deleteNanny";
		echo "deleted";
	}else{
		echo "notdeleted";
	}

}else if(isset($_POST['registeradmin_check'])){
	$username = $_POST['username'];
	$password = "pass1234";
	$fullName = strtoupper($_POST['fullName']);
	$email = $_POST['email'];
	$phoneNo = $_POST['phoneNo'];

	$sqlRegister = "INSERT INTO administrator (username, password, fullName, email, phoneNo) VALUES ('".$username."', '".$password."', '".$fullName."', '".$email."', '".$phoneNo."')";

	if($conn->query($sqlRegister) === TRUE){
		$_SESSION['reload'] = "administrator";
		echo "registered";
	}else{
		echo "notregistered";
	}
}else if(isset($_POST['update_price'])){
	$newFee = $_POST['newPrice'];
	$settingfeeID = $_POST['settingFeeID'];

	$sql = "UPDATE settingfee SET pricePerDay = '".$newFee."' WHERE settingfeeID = '".$settingfeeID."'";
	if($conn->query($sql) === TRUE){
		echo "updated";
	}else{
		echo "notupdated";
	}

}else if(isset($_POST['backout'])){
	$enrolmentID = $_POST['enrolmentID'];

	$sql = "UPDATE enrolment SET status = 'backed out' WHERE enrolmentID = '".$enrolmentID."'";
	if($conn->query($sql) === TRUE){
		echo "backedout";
	}else{
		echo "notbackedout";
	}
}else{

}


?>