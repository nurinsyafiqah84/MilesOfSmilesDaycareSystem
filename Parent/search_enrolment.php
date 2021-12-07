<?php
include('../inc/dbconnect.php');
session_start();
$date =  date("Y-m-d");
$output = '';
$total = 0;
$totalFee = 0;
$parentID = $_SESSION['username'];

if(idate('m') < 10){
	$doa = idate('Y') . "-0" . idate('m') . "-__%";
}else
$doa = idate('Y') . "-" . idate('m') . "-__%";


function getAge($dob,$condate){ 
	$birthdate = new DateTime(date("Y-m-d",  strtotime(implode('-', array_reverse(explode('/', $dob))))));
	$today= new DateTime(date("Y-m-d",  strtotime(implode('-', array_reverse(explode('/', $condate))))));           
	$age = $birthdate->diff($today)->y;

	return $age;
}


if(isset($_POST['querySearchEnrolment'])){ //keyup
$search = mysqli_real_escape_string($conn, $_POST["querySearchEnrolment"]);
//$search = $_POST['querySearchEnrolment'];
$querySearchEnrolment = "";

	if($_POST['options'] == "enrolmentIDAZ"){
		
		$querySearchEnrolment = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
		ON enrolment.dependentID = dependent.dependentID
		JOIN parent ON
		dependent.parentID = parent.username
		WHERE dependent.parentID = '".$parentID."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."' OR admissionMonth = '".$search."' OR admissionYear = '".$search."' OR status = '".$search."' OR enrolment.dateOfApplication LIKE '".$search."%') ORDER BY enrolment.enrolmentID ASC";

	}else if($_POST['options'] == "default"){

		
		$querySearchEnrolment = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
		ON enrolment.dependentID = dependent.dependentID
		JOIN parent ON
		dependent.parentID = parent.username
		WHERE dependent.parentID = '".$parentID."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."' OR admissionMonth = '".$search."' OR admissionYear = '".$search."' OR status = '".$search."' OR enrolment.dateOfApplication LIKE '".$search."%') ORDER BY enrolment.enrolmentID DESC";

	}else if($_POST['options'] == "fullNameAZ"){
		
		$querySearchEnrolment = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
		ON enrolment.dependentID = dependent.dependentID
		JOIN parent ON
		dependent.parentID = parent.username
		WHERE dependent.parentID = '".$parentID."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."' OR admissionMonth = '".$search."' OR admissionYear = '".$search."' OR status = '".$search."' OR enrolment.dateOfApplication LIKE '".$search."%') ORDER BY dependent.fullName ASC";
	}else if($_POST['options'] == "fullNameZA"){
		
		$querySearchEnrolment = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
		ON enrolment.dependentID = dependent.dependentID
		JOIN parent ON
		dependent.parentID = parent.username
		WHERE dependent.parentID = '".$parentID."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."' OR admissionMonth = '".$search."' OR admissionYear = '".$search."' OR status = '".$search."' OR enrolment.dateOfApplication LIKE '".$search."%') ORDER BY dependent.fullName DESC";
	}else if($_POST['options'] == "statusAZ"){

		$querySearchEnrolment = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
		ON enrolment.dependentID = dependent.dependentID
		JOIN parent ON
		dependent.parentID = parent.username
		WHERE dependent.parentID = '".$parentID."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."' OR admissionMonth = '".$search."' OR admissionYear = '".$search."' OR status = '".$search."' OR enrolment.dateOfApplication LIKE '".$search."%') ORDER BY enrolment.status ASC";
	}else if($_POST['options'] == "statusZA"){

		$querySearchEnrolment = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
		ON enrolment.dependentID = dependent.dependentID
		JOIN parent ON
		dependent.parentID = parent.username
		WHERE dependent.parentID = '".$parentID."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."' OR admissionMonth = '".$search."' OR admissionYear = '".$search."' OR status = '".$search."' OR enrolment.dateOfApplication LIKE '".$search."%') ORDER BY enrolment.status DESC";
	}else{
		$querySearchEnrolment = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent ON enrolment.dependentID = dependent.dependentID JOIN parent ON dependent.parentID = parent.username WHERE dependent.parentID = '".$parentID."'  AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."') AND enrolment.enrolmentID NOT IN (SELECT payment.enrolmentID FROM payment) ORDER BY enrolmentID ASC";
	}
	
	
}else{ //no keyup
	$querySearchEnrolment = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
	ON enrolment.dependentID = dependent.dependentID
	JOIN parent ON
	dependent.parentID = parent.username
	WHERE dependent.parentID = '".$parentID."'
	ORDER BY enrolment.enrolmentID DESC";
}

$result = $conn->query($querySearchEnrolment);
if($result->num_rows > 0)
{
	$output .= '
	<table  cellpadding="4"  align="center">
		<tr>
		<th width="8%">#</th>
		<th width="40%">Full Name</th>
		<th width="7%">Age</th>
		<th width="14%">Admission</th>
		<th width="7%">Days</th>
		<th width="19%">Status</th>
		<th width="5%"><i class="fa fa-search" aria-hidden="true"></i></th>
		</tr>
	'; 

	while($row = $result->fetch_assoc())
	{
		$total++;
		$output .= '
			<tr>
			<td align="center">' . $row["enrolmentID"]. '</td>
			<td>' . $row["dependentName"] . '</td>
			<td align="center">' . getAge($row["dateOfBirth"],$date) . '</td>
			<td align="center">' . $row["admissionMonth"] . "/" . $row["admissionYear"] . '</td>
			<td align="center">' . $row["totalDays"] . '</td>';
			if($row['status'] == "waiting"){
				$output .= '<td align="center"  style="color: orange">' . $row["status"] . '</td>';  
			}else if($row['status'] == "approved"){
				$output .= '<td align="center" style="color: green">' . $row["status"] . '</td>';
			}else if($row['status'] == "rejected"){
				$output .= '<td align="center" style="color: red">' . $row["status"] . '</td>';
			}else{
				$output .= '<td align="center" style="color: gray">' . $row["status"] . '</td>';
			}
				
		$output .= '<td align="center">
			<a href="viewEnrolment.php?enrolmentID=' . $row["enrolmentID"] . '&reload=history"><i class="fa fa-search" aria-hidden="true"></i></a>
			</td>
			</tr>	
		';

	}
	$output .='</table>';
	$output .='<br><center style="font-style: italic">'. $total . ' record(s) found.</center>';
	echo $output;
}
else
{
	$output ='<center style="font-style: italic; color: gray;">NO RECORD FOUND FOR THE SELECTED OPTION OR SEARCH</center>';
	echo $output;
}

?>
