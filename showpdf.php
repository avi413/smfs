<?php
		require 'admin/conn.php';	
		$file_name = $_POST['file_name'];
		$stud_no = $_POST['stud_no'];
		$site = "files";
		if($_ENV["MYENV"] =="SFMS_DEV")
		{
			$site = "dev";			
		}
		$insetfilename = explode(".",$file_name);
		if($insetfilename[1] == "pdf")
		{
			$server_path =	dirname(getcwd(),1)."/".$_ENV['MYENV']."/files/".$stud_no;
			$origin_files_path = "\\\\davidoff1\Nt_Disk\Tviot Scans\\".$stud_no."\\".$file_name;
			//$origin_files_path =  getparamvalue('pdfdirectory')."\\".$stud_no."\\".$file_name;
			if(!file_exists(dirname(getcwd(),1)."/".$_ENV['MYENV']."/files/".$stud_no)){
				mkdir(dirname(getcwd(),1)."/".$_ENV['MYENV']."/files/".$stud_no);
			}
			$result = $conn->query("SELECT file_name FROM `tempfile` WHERE `stud_no` = '$stud_no'");	
			if ($row = $result->fetch_assoc())
			{
				
				$old_file_name = $row['file_name'];
		
				if(copy($origin_files_path, $server_path."/".$file_name))
				{
					
					$sql ="UPDATE tempfile SET file_name = '$insetfilename[0]' WHERE stud_no= '$stud_no'";

					if($old_file_name != $insetfilename[0])
					{
						unlink($server_path."/".$old_file_name.".pdf");
						mysqli_query($conn, $sql) or die(mysqli_error());
					}
		
				}
				
			}
			else
			{
			
				if(copy($origin_files_path, $server_path."/".$file_name))
				{
					
					mysqli_query($conn, "insert into `tempfile` (file_name, stud_no) VALUES ('$insetfilename[0]','$stud_no');") or die(mysqli_error());
				}
				
			}
			sleep(3);
			echo "http://sblphp.davidoff.co.il/".$site."/files/".$stud_no."/".$file_name;
		
		}
		else
		{
		echo "false";
		}


?>
