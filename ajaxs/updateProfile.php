<?php

if(isset($_POST['updateadmin_check'])){
	$username = $_POST['username'];
	$email = $_POST['email'];
	$phoneNo = $_POST['phone'];
	$password = $_POST['password'];

	$sql = "UPDATE administrator SET email = '".$email."', phoneNo = '".$phoneNo."', password = '".$password."' WHERE username = '".$username."'";

	if($conn->query($sql) === TRUE){
		echo "updated";	

	}else{
		echo "notupdated";	
	}

	exit();
}



?>