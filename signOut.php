<?php
session_start();
if(isset($_SESSION['username']))
{
	$_SESSION = array();
	session_destroy();
	header('Location:signIn.php');
	
}
else
{
	$_SESSION = array();
	session_destroy();
	header('Location:signIn.php');
}
?>