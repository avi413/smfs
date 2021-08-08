<?php
	require_once 'conn.php';
	
	if(ISSET($_POST['edit'])){
		$id = $_POST['id'];
		$name = $_POST['name'];
		$value = $_POST['value'];
		$type = $_POST['type'];
		mysqli_query($conn, "UPDATE `parameters` SET `name` = '$name', `value` = '$value', `type` = '$type' WHERE `id` = '$id'") or die(mysqli_error());
		
		echo "<script>alert('עדכון הצליח')</script>";
		echo "<script>window.location = 'parameters.php'</script>";
	}
?>