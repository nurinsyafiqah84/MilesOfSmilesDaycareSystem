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



if(isset($_POST['querySearch'])){

	$search = mysqli_real_escape_string($conn, $_POST["querySearch"]);
	$querySearch = "";
	



	if($_POST['par'] == "enrolmentIDAZ"){

		$querySearch = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent ON enrolment.dependentID = dependent.dependentID JOIN parent ON dependent.parentID = parent.username WHERE status = 'approved' AND dependent.parentID = '".$parentID."' AND dateOfApplication LIKE '".$doa."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."') AND enrolment.enrolmentID NOT IN (SELECT payment.enrolmentID FROM payment) ORDER BY enrolment.enrolmentID ";

	}else if($_POST['par'] == "enrolmentIDZA"){

		
		$querySearch = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent ON enrolment.dependentID = dependent.dependentID JOIN parent ON dependent.parentID = parent.username WHERE status = 'approved' AND dependent.parentID = '".$parentID."' AND dateOfApplication LIKE '".$doa."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."') AND enrolment.enrolmentID NOT IN (SELECT payment.enrolmentID FROM payment) ORDER BY enrolment.enrolmentID DESC";

	}else if($_POST['par'] == "fullNameAZ"){
		
		$querySearch = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent ON enrolment.dependentID = dependent.dependentID JOIN parent ON dependent.parentID = parent.username WHERE status = 'approved' AND dependent.parentID = '".$parentID."' AND dateOfApplication LIKE '".$doa."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."') AND enrolment.enrolmentID NOT IN (SELECT payment.enrolmentID FROM payment) ORDER BY dependent.fullName";
	}else if($_POST['par'] == "fullNameZA"){
		
		$querySearch = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent ON enrolment.dependentID = dependent.dependentID JOIN parent ON dependent.parentID = parent.username WHERE status = 'approved' AND dependent.parentID = '".$parentID."' AND dateOfApplication LIKE '".$doa."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."') AND enrolment.enrolmentID NOT IN (SELECT payment.enrolmentID FROM payment) ORDER BY dependent.fullName DESC";
	}else{
		$querySearch = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent ON enrolment.dependentID = dependent.dependentID JOIN parent ON dependent.parentID = parent.username WHERE status = 'approved' AND dependent.parentID = '".$parentID."' AND dateOfApplication LIKE '".$doa."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."') AND enrolment.enrolmentID NOT IN (SELECT payment.enrolmentID FROM payment) ORDER BY enrolmentID ASC";
	}
	
}else{
	$querySearch = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent ON enrolment.dependentID = dependent.dependentID JOIN parent ON dependent.parentID = parent.username WHERE status = 'approved' AND dependent.parentID = '".$parentID."' AND dateOfApplication LIKE '".$doa."' AND enrolment.enrolmentID NOT IN (SELECT payment.enrolmentID FROM payment) ORDER BY enrolmentID ASC";
}

$result = $conn->query($querySearch);
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
		<th width="19%">Total (RM)</th>
		<th width="5%"><i class="fa fa-search" aria-hidden="true"></i></th>
		</tr>
	'; 

	while($row = $result->fetch_assoc())
	{

		$totalFee = (float)$row['feePerDay'] * (float)$row['totalDays'];
		$total++;
		$output .= '
			<tr>
			<td align="center">' . $row["enrolmentID"]. '</td>
			<td>' . $row["dependentName"] . '</td>
			<td align="center">' . getAge($row["dateOfBirth"],$date) . '</td>
			<td align="center">' . $row["admissionMonth"] . "/" . $row["admissionYear"] . '</td>
			<td align="center">' . $row["totalDays"] . '</td>
			<td align="center">' . number_format((float)$totalFee, 2, '.', '') . '</td>
			<td align="center">
			<a href="viewEnrolment.php?enrolmentID=' . $row["enrolmentID"] . '&fromToPay"  title="view enrolment"><i class="fa fa-search" aria-hidden="true"></i></a>
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
