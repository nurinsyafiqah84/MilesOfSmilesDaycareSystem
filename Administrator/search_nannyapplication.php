<?php
include('../inc/dbconnect.php');
$date =  date("Y-m-d");
$output = '';
$total = 0;
$previousmonth = idate('m') - 1;

if($previousmonth < 10){
	$lastmonth = idate('Y') . "-0" . $previousmonth . "-__%";
}else{
	$lastmonth = idate('Y') . "-" . $previousmonth . "-__%";
}

if(idate('m') < 10){
	$thismonth = idate('Y') . "-0" . idate('m') . "-__%";
}else{
	$thismonth = idate('Y') . "-" . idate('m') . "-__%";
}

function getAge($dob,$condate){ 
	$birthdate = new DateTime(date("Y-m-d",  strtotime(implode('-', array_reverse(explode('/', $dob))))));
	$today= new DateTime(date("Y-m-d",  strtotime(implode('-', array_reverse(explode('/', $condate))))));           
	$age = $birthdate->diff($today)->y;

	return $age;
}




if(isset($_POST['querySearch'])){

	$search = mysqli_real_escape_string($conn, $_POST["querySearch"]);
	$querySearch = "";


	if($_POST['opt'] == "applicationIDAZ"){
		$querySearch = "SELECT * FROM nanny_application WHERE name LIKE '%".$search."%' OR applicationID = '".$search."' OR email = '%".$search."%' OR highestEduLevel = '".$search."' OR applicationStatus = '".$search."' OR applicationDateTime LIKE '".$search."%' ORDER BY applicationID";		

	}else if($_POST['opt'] == "applicationIDZA"){
		$querySearch = "SELECT * FROM nanny_application WHERE name LIKE '%".$search."%' OR applicationID = '".$search."' OR email = '%".$search."%' OR highestEduLevel = '".$search."' OR applicationStatus = '".$search."' OR applicationDateTime LIKE '".$search."%' ORDER BY applicationID DESC";	

	}else if($_POST['opt'] == "fullNameAZ"){
		$querySearch = "SELECT * FROM nanny_application WHERE name LIKE '%".$search."%' OR applicationID = '".$search."' OR email = '%".$search."%' OR highestEduLevel = '".$search."' OR applicationStatus = '".$search."' OR  applicationDateTime LIKE '".$search."%' ORDER BY name";
		
	}else if($_POST['opt'] == "fullNameZA"){
		$querySearch = "SELECT * FROM nanny_application WHERE name LIKE '%".$search."%' OR applicationID = '".$search."' OR email = '%".$search."%' OR highestEduLevel = '".$search."' OR applicationStatus = '".$search."' OR  applicationDateTime LIKE '".$search."%' ORDER BY name DESC";
		
	}else if($_POST['opt'] == "eduLevel"){
		$querySearch = "SELECT * FROM nanny_application WHERE name LIKE '%".$search."%' OR applicationID = '".$search."' OR email = '%".$search."%' OR highestEduLevel = '".$search."' OR applicationStatus = '".$search."' OR  applicationDateTime LIKE '".$search."%' ORDER BY highestEduLevel";

	}else if($_POST['opt'] == "ageAZ"){
		$querySearch = "SELECT * FROM nanny_application WHERE name LIKE '%".$search."%' OR applicationID = '".$search."' OR email = '%".$search."%' OR highestEduLevel = '".$search."' OR applicationStatus = '".$search."' OR  applicationDateTime LIKE '".$search."%' ORDER BY dateOfBirth";

	}else if($_POST['opt'] == "ageZA"){
		$querySearch = "SELECT * FROM nanny_application WHERE name LIKE '%".$search."%' OR applicationID = '".$search."' OR email = '%".$search."%' OR highestEduLevel = '".$search."' OR applicationStatus = '".$search."' OR  applicationDateTime LIKE '".$search."%' ORDER BY dateOfBirth DESC";

	}else if($_POST['opt'] == "statusAZ"){
		$querySearch = "SELECT * FROM nanny_application WHERE name LIKE '%".$search."%' OR applicationID = '".$search."' OR email = '%".$search."%' OR highestEduLevel = '".$search."' OR applicationStatus = '".$search."' OR  applicationDateTime LIKE '".$search."%' ORDER BY applicationStatus";


	}else if($_POST['opt'] == "statusZA"){
		$querySearch = "SELECT * FROM nanny_application WHERE name LIKE '%".$search."%' OR applicationID = '".$search."' OR email = '%".$search."%' OR highestEduLevel = '".$search."' OR applicationStatus = '".$search."' OR  applicationDateTime LIKE '".$search."%' ORDER BY applicationStatus DESC";

	}else if($_POST['opt'] == "thisMonth"){

		$querySearch = "SELECT * FROM nanny_application WHERE (name LIKE '%".$search."%' OR applicationID = '".$search."' OR email = '%".$search."%' OR highestEduLevel = '".$search."' OR applicationStatus = '".$search."' OR  applicationDateTime LIKE '".$search."%') AND applicationDateTime LIKE '".$thismonth."%' ORDER BY applicationID DESC";


	}else if($_POST['opt'] == "lastMonth"){

		$querySearch = "SELECT * FROM nanny_application WHERE (name LIKE '%".$search."%' OR applicationID = '".$search."' OR email = '%".$search."%' OR highestEduLevel = '".$search."' OR applicationStatus = '".$search."' OR  applicationDateTime LIKE '".$search."%') AND applicationDateTime LIKE '".$lastmonth."%' ORDER BY applicationID DESC";

	}else{
		$querySearch = "SELECT * FROM nanny_application ORDER BY applicationID";
	}
	
}else{
	$querySearch = "SELECT * FROM nanny_application ORDER BY applicationID";
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
		<th>Edu. Level</th>
		<th width="7%"><i title="resume" class="fa fa-file-pdf-o" aria-hidden="true"></i></th>
		<th width="19%">Status</th>
		<th width="5%"><i class="fa fa-search" aria-hidden="true"></i></th>
		</tr>
	'; 

	while($row = $result->fetch_assoc())
	{
		$total++;
		$output .= '
			<tr>
			<td align="center">' . $row["applicationID"]. '</td>
			<td>' . $row["name"] . '</td>
			<td align="center">' . getAge($row["dateOfBirth"],$date) . '</td>' .
			'<td align="center">' . $row["highestEduLevel"] .'</td>
			<td align="center"><a download="' . $row["resume"] . '" href="../NannyApplication/resume/' . $row["resume"] . '">
											<i title="' . $row["resume"] . '" class="fa fa-file-pdf-o" aria-hidden="true"></i>
										</a>' . '</td>';
		if($row['applicationStatus'] == "waiting"){
			$output .= '<td align="center" style="color: orange">' . $row["applicationStatus"] . '</td>';
		}else if($row['applicationStatus'] == "approved"){
			$output .= '<td align="center" style="color: green">' . $row["applicationStatus"] . '</td>';
		}else if($row['applicationStatus'] == "rejected"){
			$output .= '<td align="center" style="color: red">' . $row["applicationStatus"] . '</td>';
		}								
		$output .= '<td align="center">
			<a href="viewApplication.php?applicationID=' . $row["applicationID"] . '"  title="view nanny application"><i class="fa fa-search" aria-hidden="true"></i></a>
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
