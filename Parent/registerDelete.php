<?php
include('../inc/dbconnect.php');
session_start();

$mNow = idate('m');
$yNow = idate('Y');


$date =  date("Y-m-d");

function getAge($dob,$condate){ 
    $birthdate = new DateTime(date("Y-m-d",  strtotime(implode('-', array_reverse(explode('/', $dob))))));
    $today= new DateTime(date("Y-m-d",  strtotime(implode('-', array_reverse(explode('/', $condate))))));           
    $age = $birthdate->diff($today)->y;

    return $age;
}

function getSubTotal($age, $conn) {
	$subTotal = 0;
	$sqlFee = "SELECT * FROM settingfee";
	$resultFee = $conn->query($sqlFee);
	while($rowFee = $resultFee->fetch_assoc()){

		if ($age >= $rowFee['minAge'] && $age <= $rowFee['maxAge']) {
			$subTotal = $rowFee['pricePerDay'];
			break;
		}
	}
	return $subTotal;
}


if(isset($_POST['apply_enrolment'])){
	$dependentID = $_POST['dependentID'];
	$totalDays = $_POST['totalDays'];
	$admissionMonth = 0;
	$admissionYear = 0;
	$feePerDay = 0;
	

	if($mNow == 12){
		$admissionMonth = 1;
		$admissionYear = $yNow+1;

	}else{
		$admissionMonth = $mNow+1;
		$admissionYear = $yNow;
	}

	$sql = "SELECT dateOfBirth FROM dependent WHERE dependentID = '".$dependentID."'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$age = getAge($row['dateOfBirth'],$date);
	$feePerDay = getSubTotal($age, $conn);

	$sqlEnrol = "INSERT INTO enrolment (totalDays, admissionMonth, admissionYear, feePerDay, dependentID) VALUES ('".$totalDays."', '".$admissionMonth."', '".$admissionYear."', '".$feePerDay."', '".$dependentID."')";
	if($conn->query($sqlEnrol) === TRUE){
		echo "applied";
	}else{
		echo "notapplied";
	}

}






?>