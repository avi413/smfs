<?php
	require_once 'conn.php';
	
	if(ISSET($_POST['save'])){

		$name = $_POST['name'];
		$value = $_POST['value'];
		$type = $_POST['type'];
		$sqlstring = "INSERT INTO parameters (name ,value,type)VALUES('$name', '$value', '$type')";
		mysqli_query($conn, $sqlstring) or die(mysqli_error());
		
		header('location: parameters.php');
	}
?>