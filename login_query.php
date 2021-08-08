<?php
	session_start();
	require_once 'environment.php';
	require 'admin/conn.php';
	
	if(ISSET($_POST['login'])){
		$stud_no = $_POST['stud_no'];
		if ($_ENV["MYENV"] == "SFMS_DEV")
			$stud_no = $stud_no."_DEV";
		$password = md5($_POST['password']);
		
		$query = mysqli_query($conn, "SELECT * FROM `student` WHERE `stud_no` = '".$stud_no."' && `password` = '".$password."'") or die(mysqli_error());
		$fetch = mysqli_fetch_array($query);
		$row = $query->num_rows;
		
		if($row > 0){
			$_SESSION['student'] = $fetch['stud_id'];
			header("location:student_profile.php");
		}else{
			echo "<center><label class='text-danger'>שם משתמש או סיסמה שגויים</label></center>";
		}
	}
?>