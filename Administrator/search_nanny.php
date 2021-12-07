<?php
include('../inc/dbconnect.php');
$output = "";
$total = 0;

if(isset($_POST['querySearch'])){

	$search = mysqli_real_escape_string($conn, $_POST["querySearch"]);
	$querySearch = "";

	if($_POST['par'] == "usernameAZ"){
		$querySearch = "SELECT * FROM nanny WHERE fullName LIKE '%".$search."%' OR username = '".$search."' OR address LIKE '%".$search."%' OR phoneNo LIKE '%".$search."%' ORDER BY username ASC";
	}else if($_POST['par'] == "usernameZA"){
		$querySearch = "SELECT * FROM nanny WHERE fullName LIKE '%".$search."%' OR username = '".$search."' OR address LIKE '%".$search."%' OR phoneNo LIKE '%".$search."%' ORDER BY username DESC";
	}else if($_POST['par'] == "fullNameAZ"){
		$querySearch = "SELECT * FROM nanny WHERE fullName LIKE '%".$search."%' OR username = '".$search."' OR address LIKE '%".$search."%' OR phoneNo LIKE '%".$search."%' ORDER BY fullName ASC";
	}else if($_POST['par'] == "fullNameZA"){
		$querySearch = "SELECT * FROM nanny WHERE fullName LIKE '%".$search."%' OR username = '".$search."' OR address LIKE '%".$search."%' OR phoneNo LIKE '%".$search."%' ORDER BY fullName DESC";
	}else{
		$querySearch = "SELECT * FROM nanny WHERE fullName LIKE '%".$search."%' OR username = '".$search."' OR address LIKE '%".$search."%' OR phoneNo LIKE '%".$search."%' ORDER BY username ASC";
	}

}else{
	$querySearch = "SELECT * FROM nanny ORDER BY username ASC";
}


$result = $conn->query($querySearch);
if($result->num_rows > 0)
{
	$output .= '
	<table  cellpadding="4"  align="center">
		<tr>
		<th width="20%">Username</th>
		<th >Full Name</th>
		<th width="17%" >Phone</th>
		<th width="10%">Action</th>
		</tr>
	'; 

	while($row = $result->fetch_assoc())
	{
		$total++;
		$output .= '
			<tr>
			<td align="center">' . $row["username"]. '</td>
			<td style="padding-left: 17px">' . $row["fullName"] . '</td>
			<td align="center">' . $row["phoneNo"] . '</td>
			<td align="center">
			<a onclick="deletenanny(this)" id="'. $row["username"] .'" class="oppose"><i class="fa fa-trash" aria-hidden="true"></i></a>
			<a href="viewNanny.php?nannyID=' . $row["username"] . '" ><i class="fa fa-search" aria-hidden="true"></i></a>
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