<?php 

function loadService($serviceName){
	$file = APPPATH . DIRECTORY_SEPARATOR . 'services' . DIRECTORY_SEPARATOR . $serviceName;
	if(is_file($file)){
		include_once $file;
	}else{
		throw new Exception('Service ' . $file .' Not Exists', 1);
	}
}