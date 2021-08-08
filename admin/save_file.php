
<?php
	require_once __DIR__ . '\..\function.php';
	require_once 'conn.php';

	$total = count($_FILES['file']['name']);
	for( $i=0 ; $i < $total ; $i++ ) {

		if(ISSET($_POST['save'])){
			$stud_no = $_POST['stud_no'];
			$file_name = $_FILES['file']['name'][$i];
			echo $file_name ."</br>";
			$file_type = $_FILES['file']['type'][$i];
			echo $file_type."</br>";
			$file_temp = $_FILES['file']['tmp_name'][$i];
			echo $file_temp."</br>";
			$location = getparamvalue('pdfdirectory')."/".$stud_no."/".$file_name;
			echo $location."</br>";
			$date = date("Y-m-d", strtotime("+3 HOURS"));
			if(!file_exists(getparamvalue('pdfdirectory')."/".$stud_no)){
				mkdir(getparamvalue('pdfdirectory')."/".$stud_no);
			}
			$sqlstring = "INSERT INTO storage (filename,file_type,date_uploaded,stud_no) VALUES('$file_name', '$file_type', '$date', '$stud_no')";
			

			echo $location."</br>";
			if(!file_exists($location)){
				if(move_uploaded_file($file_temp, $location)){
					echo $location."</br>";
					mysqli_query($conn, $sqlstring) or die(mysqli_error());
					if(file_exists(getparamvalue('pdfdirectory')."/".$file_name))
						unlink(getparamvalue('pdfdirectory')."/".$file_name);
				}
			}
			
		}
	}

	//getparamvalue('pdfdirectory');
	header('location: home.php');
?>