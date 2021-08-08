<?php
	require_once 'environment.php';
	if($_ENV["MYENV"] == "SFMS_DEV")
		$conn = mysqli_connect("localhost", "root", "Aa123456", "sfms_dev");
	if($_ENV["MYENV"] == "SFMS")
		$conn = mysqli_connect("localhost", "root", "Aa123456", "sfms");
	$conn->set_charset('utf8');
	if(!$conn){
		die("Error: Failed to connect to database!");
	}
	
	$default_query = mysqli_query($conn, "SELECT * FROM `user`") or die(mysqli_error());
	$check_default = mysqli_num_rows($default_query);
	
	if($check_default === 0){
		$enrypted_password = md5('admin');
		mysqli_query($conn, "INSERT INTO `user` VALUES('', 'Administrator', '', 'admin', '$enrypted_password', 'administrator')") or die(mysqli_error());
		return false;
	}
?>