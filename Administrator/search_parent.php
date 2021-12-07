<?php
include('../inc/dbconnect.php');
$date =  date("Y-m-d");
$output = '';
$total = 0;

if(isset($_POST['querySearch'])){

	$search = mysqli_real_escape_string($conn, $_POST["querySearch"]);
	$querySearch = "";
	if($_POST['par'] == "usernameAZ"){
		$querySearch = "SELECT * FROM parent WHERE fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR email LIKE '%".$search."%' OR phoneNo LIKE '%".$search."%' OR address LIKE '%".$search."%' ORDER BY username ASC";
	}else if($_POST['par'] == "usernameZA"){
		$querySearch = "SELECT * FROM parent WHERE fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR email LIKE '%".$search."%' OR phoneNo LIKE '%".$search."%' OR address LIKE '%".$search."%' ORDER BY username DESC";
	}else if($_POST['par'] == "fullNameAZ"){
		$querySearch = "SELECT * FROM parent WHERE fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR email LIKE '%".$search."%' OR phoneNo LIKE '%".$search."%' OR address LIKE '%".$search."%' ORDER BY fullName ASC";

	}else if($_POST['par'] == "fullNameZA"){
		$querySearch = "SELECT * FROM parent WHERE fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR email LIKE '%".$search."%' OR phoneNo LIKE '%".$search."%' OR address LIKE '%".$search."%' ORDER BY fullName DESC";
	}else if($_POST['par'] == "email"){
		$querySearch = "SELECT * FROM parent WHERE fullName LIKE '%".$search."%' OR username LIKE '%".$search."%' OR email LIKE '%".$search."%' OR phoneNo LIKE '%".$search."%' OR address LIKE '%".$search."%' ORDER BY email ASC";
	}
}else{

	$querySearch = "SELECT * FROM parent ORDER BY username ASC";

}


$result = $conn->query($querySearch);
if($result->num_rows > 0)
{
	$output .= '
	<table  cellpadding="4"  align="center">
		<tr>
		<th width="20%">Username</th>
		<th width="30%">Full Name</th>
		<th width="20%" >E-mail</th>
		<th width="15%">Phone</th>
		<th width="5%"><i class="fa fa-search" aria-hidden="true"></i></th>
		</tr>
	'; 

	while($row = $result->fetch_assoc())
	{
		$total++;
		$output .= '
			<tr>
			<td align="center">' . $row["username"]. '</td>
			<td>' . $row["fullName"] . '</td>
			<td align="left">' . $row["email"] . '</td>
			<td align="center">' . $row["phoneNo"] . '</td>
			<td align="center">
			<a href="viewParent.php?parentID=' . $row["username"] . '"&reload=parent><i class="fa fa-search" aria-hidden="true"></i></a>
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