<?php

    require('./Curl.class.php');
	
	$curl = new Curl();
	try{
		var_dump($curl->uploadFile('../img/01.img', 'testupload', 'image/jpeg', 'http://192.168.0.103/index.php' ));
		echo $curl->getRequestInfo();
	} catch( Exception $e  ) {
		echo $e->getMessage();
	}

?>
