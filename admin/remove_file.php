<?php
	require_once __DIR__ . '\..\function.php';
	require_once 'conn.php';
	
	if(ISSET($_POST['store_id'])){
		$store_id = $_POST['store_id'];
		$query = mysqli_query($conn, "SELECT * FROM `storage` WHERE `store_id` = '$store_id'") or die(mysqli_error());
		$fetch  = mysqli_fetch_array($query);
		$filename = $fetch['filename'];
		$stud_no = $fetch['stud_no'];

		//echo "<script>console.log(".getparamvalue('pdfdirectory')."/".$stud_no."/".$filename. "' );</script>";
		if(unlink(getparamvalue('pdfdirectory')."/".$stud_no."/".$filename)){
			mysqli_query($conn, "DELETE FROM `storage` WHERE `store_id` = '$store_id'") or die(mysqli_error());
		}
	}
?>