<?php
	
		
	function getparamvalue($name) {
		require 'admin/conn.php';
		$query = mysqli_query($conn, "SELECT value FROM `parameters` WHERE `name` = '$name'") or die(mysqli_error());
		$fetch = mysqli_fetch_array($query);
		$value = $fetch['value'];

    return "$value";
	}


?>
