<?php
require_once 'admin/conn.php';
	
	if(ISSET($_POST['save'])){
		$cllaim_num = $_POST['cllaim_num'];
		$stud_no = $_POST['stud_no'];
		$file_type = 'pdf';
		$file_temp = getparamvalue('pdfdirectory')."/".$stud_no;
		$file_name = $_POST['filename'];
		$location = $file_temp."/".$file_name;
		$fileedata = file_get_contents($location);
             // alternatively specify an URL, if PHP settings allow
		$base64 = base64_encode($fileedata);

	}
		ini_set('soap.wsdl_cache_enabled', 0);
		ini_set('soap.wsdl_cache_ttl', 900);
		ini_set('default_socket_timeout', 15);

		$params = array(
		  "MethodName" => "AttchFile",
		  "file_name" => $file_name,
		  "file_type" => $file_type,
		  "file_buffrt" => $base64
		
		);

		if($_ENV["MYENV"] =="SFMS")
			$wsdl = 'C:\inetpub\wwwroot\SFMS/FEMIRunWSDLInboundInt.WSDL';
		if($_ENV["MYENV"] =="SFMS_DEV")
			$wsdl = 'http://sbl_dev/WSDL/FEMIRunWSDLInboundInt.WSDL';


		$options = array(
				'uri'=>'http://schemas.xmlsoap.org/soap/envelope/',
				'style'=>SOAP_RPC,
				'use'=>SOAP_ENCODED,
				'soap_version'=>SOAP_1_1,
				'cache_wsdl'=>WSDL_CACHE_NONE,
				'connection_timeout'=>15,
				'trace'=>true,
				'encoding'=>'UTF-8',
				'exceptions'=>true,
			);
		try {
			$soap = new SoapClient($wsdl, $options);
			$data = $soap->RunWSDLInboundInt($params);
		}
		catch(Exception $e) {
			die($e->getMessage());
		}
		  
		var_dump($data);
		die;
		