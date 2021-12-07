<?php
include('../inc/dbconnect.php');
$output = "";
$total = 0;

if(isset($_POST['querySearch'])){

	$search = mysqli_real_escape_string($conn, $_POST["querySearch"]);
	$querySearch = "";

	if($_POST['par'] == "usernameAZ"){
		$querySearch = "SELECT * FROM administrator WHERE fullName LIKE '%".$search."%' OR username = '".$search."' OR email LIKE '%".$search."%' OR phoneNo LIKE '%".$search."%' ORDER BY username ASC";
	}else if($_POST['par'] == "usernameZA"){
		$querySearch = "SELECT * FROM administrator WHERE fullName LIKE '%".$search."%' OR username = '".$search."' OR email LIKE '%".$search."%' OR phoneNo LIKE '%".$search."%' ORDER BY username DESC";
	}else if($_POST['par'] == "fullNameAZ"){
		$querySearch = "SELECT * FROM administrator WHERE fullName LIKE '%".$search."%' OR username = '".$search."' OR email LIKE '%".$search."%' OR phoneNo LIKE '%".$search."%' ORDER BY fullName ASC";
	}else if($_POST['par'] == "fullNameZA"){
		$querySearch = "SELECT * FROM administrator WHERE fullName LIKE '%".$search."%' OR username = '".$search."' OR email LIKE '%".$search."%' OR phoneNo LIKE '%".$search."%' ORDER BY fullName DESC";
	}else{
		$querySearch = "SELECT * FROM administrator WHERE fullName LIKE '%".$search."%' OR username = '".$search."' OR email LIKE '%".$search."%' OR phoneNo LIKE '%".$search."%' ORDER BY username ASC";
	}

}else{
	$querySearch = "SELECT * FROM administrator ORDER BY username ASC";
}


$result = $conn->query($querySearch);
if($result->num_rows > 0)
{
	$output .= '
	<table  cellpadding="4"  align="center">
		<tr>
		<th width="20%">Username</th>
		<th >Full Name</th>
		<th width="10%" >Phone</th>
		<th width="7%"><i class="fa fa-search" aria-hidden="true"></i></th>
		</tr>
	'; 

	while($row = $result->fetch_assoc())
	{
		$total++;
		$output .= '
			<tr>
			<td align="center">' . $row["username"]. '</td>
			<td style="padding-left: 17px">' . $row["fullName"] . '</td>
			<td align="center" width="19%">' . $row["phoneNo"] . '</td>
			<td align="center">
			<a href="viewAdmin.php?adminID=' . $row["username"] . '" ><i class="fa fa-search" aria-hidden="true"></i></a>
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