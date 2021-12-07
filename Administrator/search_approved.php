<?php
include('../inc/dbconnect.php');
$date =  date("Y-m-d");
$output = '';
$total = 0;

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



if(isset($_POST['querySearchApproved'])){

	$search = mysqli_real_escape_string($conn, $_POST["querySearchApproved"]);
	$querySearchApproved = "";


	if($_POST['approvedOpt'] == "enrolmentIDAZ"){

		$querySearchApproved = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
		ON enrolment.dependentID = dependent.dependentID
		JOIN parent ON
		dependent.parentID = parent.username
		WHERE status = 'approved' AND dateOfApplication LIKE '".$doa."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."') ORDER BY enrolment.enrolmentID";

	}else if($_POST['approvedOpt'] == "default"){

		
		$querySearchApproved = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
		ON enrolment.dependentID = dependent.dependentID
		JOIN parent ON
		dependent.parentID = parent.username
		WHERE status = 'approved' AND dateOfApplication LIKE '".$doa."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."') ORDER BY enrolment.enrolmentID DESC";

	}else if($_POST['approvedOpt'] == "fullNameAZ"){
		
		$querySearchApproved = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
		ON enrolment.dependentID = dependent.dependentID
		JOIN parent ON
		dependent.parentID = parent.username
		WHERE status = 'approved' AND dateOfApplication LIKE '".$doa."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."') ORDER BY dependent.fullName";
	}else if($_POST['approvedOpt'] == "fullNameZA"){
		
		$querySearchApproved = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
		ON enrolment.dependentID = dependent.dependentID
		JOIN parent ON
		dependent.parentID = parent.username
		WHERE status = 'approved' AND dateOfApplication LIKE '".$doa."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."') ORDER BY dependent.fullName DESC";
	}else{
		$querySearchApproved = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
		ON enrolment.dependentID = dependent.dependentID
		JOIN parent ON
		dependent.parentID = parent.username
		WHERE status = 'approved' AND dateOfApplication LIKE '".$doa."' AND (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR enrolment.enrolmentID = '".$search."') ORDER BY enrolment.enrolmentID";
	}
	
}else{
	$querySearchApproved = "SELECT *, dependent.fullName AS dependentName FROM enrolment JOIN dependent 
	ON enrolment.dependentID = dependent.dependentID
	JOIN parent ON
	dependent.parentID = parent.username
	WHERE status = 'approved' AND dateOfApplication LIKE '".$doa."' ORDER BY enrolment.enrolmentID";
}

$result = $conn->query($querySearchApproved);
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
		<th width="19%">Parent</th>
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
			<td align="center">' . $row["totalDays"] . '</td>
			<td align="center">' . $row["username"] . '</td>
			<td align="center">
			<a href="viewEnrolment.php?enrolmentID=' . $row["enrolmentID"] . '"  title="view enrolment"><i class="fa fa-search" aria-hidden="true"></i></a>
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
