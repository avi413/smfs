<?php
	require_once 'conn.php';
	require_once __DIR__ . '\..\environment.php';
	
	if(ISSET($_POST['save'])){
		$stud_no = $_POST['stud_no'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$gender = $_POST['gender'];
		$yrsec = $_POST['year']."".$_POST['section'];
		$password = md5($_POST['password']);
		if ($_ENV["MYENV"] == "SFMS_DEV")
			$stud_no = $stud_no."_DEV";

		$sqlstring = "INSERT INTO student (stud_no ,firstname ,lastname ,gender ,yrsec ,password)VALUES('$stud_no', '$firstname', '$lastname', '$gender', '$yrsec', '$password')";
		echo "$sqlstring";
		mysqli_query($conn, $sqlstring) or die(mysqli_error());
		
		header('location: student.php');
	}
?>