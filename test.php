<?php

    require('./Curl.class.php');
	
	$curl = new Curl();
	try{
		echo $curl->uploadFile('../upload.php', 'testupload', 'text/html', 'http://192.168.0.102/index.php' );
		echo $curl->getRequestInfo();
	} catch( Exception $e  ) {
		echo $e->getMessage();
	}

?>
