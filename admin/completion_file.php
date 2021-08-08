<?php
	require_once __DIR__ . '\..\function.php';
	require_once 'conn.php';

	if(ISSET($_POST['store_id'])){
		$store_id = $_POST['store_id'];
		//echo "$store_id";
		$new_stu_no = $_POST['stud_no'];
		$query = mysqli_query($conn, "SELECT * FROM `storage` WHERE `store_id` = '$store_id'") or die(mysqli_error());
		$fetch  = mysqli_fetch_array($query);
		$filename = $fetch['filename'];
		$filename = $fetch['filename'];
		$insetfilename = explode(".",$filename);
		$newfilename = $insetfilename[0]."_H.".$insetfilename[1];
		$stud_no = $fetch['stud_no'];
		$filefrom = "\\\\davidoff1\Nt_Disk\Tviot Scans\\".$stud_no."\\".$filename;
		$fileto = "\\\\davidoff1\Nt_Disk\Tviot Scans\\".$new_stu_no."\\".$newfilename;
		if($new_stu_no == $stud_no)
		{
			if(rename($filefrom, $fileto))
			{
				$sql ="UPDATE storage SET Status = 'השלמות', filename= '$newfilename' WHERE store_id = $store_id";
				mysqli_query($conn, $sql) or die(mysqli_error());
			}
		}
		else if(!file_exists(getparamvalue('pdfdirectory')."/".$new_stu_no)){
				mkdir(getparamvalue('pdfdirectory')."/".$new_stu_no);
			}
		if(file_exists($filefrom))
		{
				if(copy($filefrom, $fileto))
				{
					echo "move_uploaded_file";
					$sql ="UPDATE storage SET Status = 'השלמות', stud_no= '$new_stu_no', filename= '$newfilename' WHERE store_id = $store_id";
					unlink($filefrom);
					mysqli_query($conn, $sql) or die(mysqli_error());
					
				}
		}

	}
?>