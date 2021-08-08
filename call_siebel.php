<?php
require_once 'function.php';
require_once 'admin/conn.php';
require_once 'environment.php';

	if(ISSET($_POST['save'])){
		$claim_num = $_POST['claim_num'];
		$claim_num = str_replace(' ', '', $claim_num);
		$stud_no = $_POST['stud_no'];
		$store_id = $_POST['store_id'];
		$file_name = $_POST['filename'];
		$file_type = $_POST['filetype'];
		$doctype = $_POST['doctype'];
		$OriginalSendingDate = $_POST['OriginalSendingDate'];
		$location =  getparamvalue('pdfdirectory')."/".$stud_no."/".$file_name.".".$file_type;    //$file_temp."/".$file_name;

		$fileedata = file_get_contents($location);
             // alternatively specify an URL, if PHP settings allow
		$base64 = base64_encode($fileedata);
		$MyXML = "<?xml version=\"1.0\" encoding=\"UTF-16\"?>"
		.	"<AddSRAtt>"
		.	"<SRNumber>".$claim_num."</SRNumber>"
		.	"<ListOfAttachments>"
		.	"<Attachments>"
		.	"<OriginalSendingDate>".$OriginalSendingDate."</OriginalSendingDate>"
		.	"<FileType>".$doctype."</FileType>"
		.	"<FileComment></FileComment>"
		.	"<FileName>".$file_name."</FileName>"
		.	"<FileExt>".$file_type."</FileExt>"
		.	"<FileBuffer>".$base64."</FileBuffer>"

		.	"</Attachments>"
		.	"</ListOfAttachments>"
		.	"</AddSRAtt>";



	}
		
		ini_set('soap.wsdl_cache_enabled', 0);
		ini_set('soap.wsdl_cache_ttl', 900);
		ini_set('default_socket_timeout', 45);

		$params = array(
		  "MethodName" => "",
		  "ServiceName" => "AddSrAttIn",
		  "inXML" => $MyXML,
		
		);

		if($_ENV["MYENV"] =="SFMS")
			$wsdl = 'C:\inetpub\wwwroot\SFMS/FEMIRunWSDLInboundInt.WSDL';
		if($_ENV["MYENV"] =="SFMS_DEV")
			$wsdl = 'http://sbl_dev/WSDL/FEMIRunWSDLInboundInt.WSDL';
			
		$options = array(
				'uri'=>'http://schemas.xmlsoap.org/soap/envelope/',
				'style'=>SOAP_RPC,
				'use'=>SOAP_ENCODED,
				'soap_version'=>SOAP_1_1|SOAP_1_2,
				'cache_wsdl'=>WSDL_CACHE_NONE,
				'connection_timeout'=>15,
				'trace'=>true,
				'encoding'=>'UTF-8',
				'exceptions'=>true
			);
		try {
			$soap = new SoapClient($wsdl, $options);
			$data = $soap->RunWSDLInboundInt($params);
		
			if($data->Error_spcCode== "") 
			{ 
					$query= "update storage set Status = N'טופל', integrationId = '$claim_num' WHERE `store_id` = '$store_id'";
					$query = mysqli_query($conn, $query) or die(mysqli_error());
					unlink($location);
					var_dump($data);
					die;
					header('location: student_profile.php');
			    
			}
			else
			{ 
					$err = " שגיאה בהעלאת הקובץ לסמארט".$data->Error_spcCode;
					var_dump($data);
					die;
					header('location: student_profile.php?arg1='.$err);
			    
			}  

		}
		catch(Exception $e) {
			var_dump($data);
			die($e->getMessage());
			header('location: student_profile.php');
		}
		  
		
		header('location: student_profile.php');
		?>