<?php
	require_once 'conn.php';
	
	if(ISSET($_POST['edit'])){
		$store_id = $_POST['store_id'];
		$note = $_POST['note'];
		$sqlstring = "UPDATE `storage` SET `note` = '$note' WHERE `store_id` = '$store_id'";
		mysqli_query($conn, $sqlstring) or die(mysqli_error());
		
		header('location: student_profile.php');
	}
?>