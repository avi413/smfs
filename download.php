<?php
	require_once 'admin/conn.php';
	require_once 'environment.php';
	
	if(ISSET($_REQUEST['store_id'])){
		$store_id = $_REQUEST['store_id'];
		
		$query = mysqli_query($conn, "SELECT * FROM `storage` WHERE `store_id` = '$store_id'") or die(mysqli_error());
		$fetch  = mysqli_fetch_array($query);
		$filename = $fetch['filename'];
		$stud_no = $fetch['stud_no'];
		$insetfilename = explode(".",$filename);
		
		$server_path =	dirname(getcwd(),1)."/".$_ENV['MYENV']."/files/".$stud_no;
		$origin_files_path = "\\\\davidoff1\Nt_Disk\Tviot Scans\\".$stud_no."\\".$filename;
		//$origin_files_path =  getparamvalue('pdfdirectory')."\\".$stud_no."\\".$file_name;
		if(!file_exists(dirname(getcwd(),1)."/".$_ENV['MYENV']."/files/".$stud_no)){
			mkdir(dirname(getcwd(),1)."/".$_ENV['MYENV']."/files/".$stud_no);
		}
		$result = $conn->query("SELECT file_name,file_type FROM `tempfile` WHERE `stud_no` = '$stud_no'");	
		if ($row = $result->fetch_assoc())
		{
			
			$old_file_name = $row['file_name'];
			$old_file_type = $row['file_type'];
	
			if(copy($origin_files_path, $server_path."/".$filename))
			{
				
				$sql ="UPDATE tempfile SET file_name = '$insetfilename[0]', file_type = '$insetfilename[1]' WHERE stud_no= '$stud_no'";

				if($old_file_name != $insetfilename[0])
				{
					unlink($server_path."/".$old_file_name.".".$old_file_type);
					mysqli_query($conn, $sql) or die(mysqli_error());
				}
	
			}
			
		}
		else
		{
		
			if(copy($origin_files_path, $server_path."/".$filename))
			{
				
				mysqli_query($conn, "insert into `tempfile` (file_name, stud_no, file_type) VALUES ('$insetfilename[0]','$stud_no','$insetfilename[1]');") or die(mysqli_error());
			}
			
		}
			
		header("Content-Disposition: attachment; filename=".$filename);
		header("Content-Type: application/octet-stream;");
		readfile("files/".$stud_no."/".$filename);
	
	}
?>