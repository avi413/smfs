<?php
	
	session_start();
	require_once 'environment.php';
	if(!ISSET($_SESSION['student'])){
		header('location:index.php');
	}
?>