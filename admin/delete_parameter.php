<?php
	require_once 'conn.php';
	
	if(ISSET($_POST['id'])){
		mysqli_query($conn, "DELETE FROM `parameters` WHERE `id` = '$_POST[id]'") or die(mysqli_error());
	}
?>