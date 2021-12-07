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


if(isset($_POST['querySearch'])){ //keyup
	$search = mysqli_real_escape_string($conn, $_POST["querySearch"]);
	$querySearch = "";

	if($_POST['dep'] == "fullNameAZ"){
		$querySearch = "SELECT *, dependent.fullName as dependentName FROM dependent JOIN parent
					ON dependent.parentID = parent.username WHERE dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR diet LIKE '%".$search."%' OR additionalSupportNeeds LIKE '%".$search."%' OR parent.fullName LIKE '%".$search."%' OR dateOfBirth LIKE '%".$search."%' ORDER BY dependent.fullName ASC";

	}else if($_POST['dep'] == "fullNameZA"){
		$querySearch = "SELECT *, dependent.fullName as dependentName FROM dependent JOIN parent
					ON dependent.parentID = parent.username WHERE dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR diet LIKE '%".$search."%' OR additionalSupportNeeds LIKE '%".$search."%' OR parent.fullName LIKE '%".$search."%' OR dateOfBirth LIKE '%".$search."%' ORDER BY dependent.fullName DESC";


	}else if($_POST['dep'] == "ageAZ"){
		$querySearch = "SELECT *, dependent.fullName as dependentName FROM dependent JOIN parent
					ON dependent.parentID = parent.username WHERE dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR diet LIKE '%".$search."%' OR additionalSupportNeeds LIKE '%".$search."%' OR parent.fullName LIKE '%".$search."%' OR dateOfBirth LIKE '%".$search."%' ORDER BY dependent.dateOfBirth ASC";

	}else if($_POST['dep'] == "ageZA"){
		$querySearch = "SELECT *, dependent.fullName as dependentName FROM dependent JOIN parent
					ON dependent.parentID = parent.username WHERE dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR diet LIKE '%".$search."%' OR additionalSupportNeeds LIKE '%".$search."%' OR parent.fullName LIKE '%".$search."%' OR dateOfBirth LIKE '%".$search."%' ORDER BY dependent.dateOfBirth DESC";
	}else if($_POST['dep'] == "parentAZ"){
		$querySearch = "SELECT *, dependent.fullName as dependentName FROM dependent JOIN parent
					ON dependent.parentID = parent.username WHERE dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR diet LIKE '%".$search."%' OR additionalSupportNeeds LIKE '%".$search."%' OR parent.fullName LIKE '%".$search."%' OR dateOfBirth LIKE '%".$search."%' ORDER BY parent.username ASC";

	}else if($_POST['dep'] == "parentZA"){
		$querySearch = "SELECT *, dependent.fullName as dependentName FROM dependent JOIN parent
					ON dependent.parentID = parent.username WHERE dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR diet LIKE '%".$search."%' OR additionalSupportNeeds LIKE '%".$search."%' OR parent.fullName LIKE '%".$search."%' OR dateOfBirth LIKE '%".$search."%' ORDER BY parent.username DESC";

	}else if($_POST['dep'] == "neverEnrolled"){
		$querySearch = "SELECT DISTINCT *, dependent.fullName as dependentName FROM dependent JOIN parent
					ON dependent.parentID = parent.username
                    WHERE (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR diet LIKE '%".$search."%' OR additionalSupportNeeds LIKE '%".$search."%' OR parent.fullName LIKE '%".$search."%' OR dateOfBirth LIKE '%".$search."%') AND  dependent.dependentID NOT IN (SELECT enrolment.dependentID FROM enrolment JOIN payment
                                                       ON enrolment.enrolmentID = payment.enrolmentID)";

	}else if($_POST['dep'] == "enrolled"){
		$querySearch = "SELECT DISTINCT *, dependent.fullName as dependentName FROM dependent JOIN parent
					ON dependent.parentID = parent.username JOIN enrolment
                    ON dependent.dependentID = enrolment.dependentID
                    WHERE (dependent.fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR diet LIKE '%".$search."%' OR additionalSupportNeeds LIKE '%".$search."%' OR parent.fullName LIKE '%".$search."%' OR dateOfBirth LIKE '%".$search."%') AND  enrolment.enrolmentID IN (SELECT payment.enrolmentID 
                                                     FROM payment)					                    
                    ORDER BY dependent.fullName ASC";

	}else{
		$querySearch = "SELECT *, dependent.fullName as dependentName FROM dependent JOIN parent
					ON dependent.parentID = parent.username ORDER BY dependent.fullName ASC";
	}




}else{

	$querySearch = "SELECT *, dependent.fullName as dependentName FROM dependent JOIN parent
					ON dependent.parentID = parent.username ORDER BY dependent.fullName ASC";

}


$result = $conn->query($querySearch);
if($result->num_rows > 0)
{
	$output .= '
	<table  cellpadding="4"  align="center">
		<tr>
		<th width="40%">Full Name</th>
		<th width="7%">Age</th>
		<th width="30%">Parent</th>
		<th width="5%"><i class="fa fa-search" aria-hidden="true"></i></th>
		</tr>
	'; 

	while($row = $result->fetch_assoc())
	{
		$total++;
		$output .= '
			<tr>
			<td>' . $row["dependentName"] . '</td>
			<td align="center">' . getAge($row["dateOfBirth"],$date) . '</td>
			<td align="center">' . $row["username"] . '</td>';
				
		$output .= '<td align="center">
			<a href="viewDependent.php?dependentID=' . $row["dependentID"] . '&reload=dependent" title="view dependent"><i class="fa fa-search" aria-hidden="true"></i></a>
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