<?php

/*
Libcurl 类，用于数据传输，get数据和post数据
*/
class Curl {
	
	//curl_handel
	private $handel = null;
	//URL
	private $url = null;
	//HTTP headers
	private $headers = array();
	//返回信息是否包含头信心
	private $includeHeader = false;
	//请求方法
	private $method = 'GET';
	//post数据
	private $postData = array();
	//is https
	private $is_https = false;
	//is_debug debug调试http请求头，返回数据中包括http头信息
	private $is_debug = false;
	//timeout
	private $time_out = 0;
	//referer
	private $referer = null;

	public function setDebug( $debug = true ) {
		if( $debug ) {
			$this->debug = true;	
		}
	}
	
	public function setUrl($url) {
		if( strpos($url, 'https://') ) {
			$this->is_https = true;
		}
		$this->url = $url;
	}
	
	public function setTimeOut($timeout) {
		$this->time_out = $timeout;
	}	

	public function setReferer($referer) {
		$this->referer = $referer;
	}
	public function setMethod( $method ) {
		$this->method = $method;
	}
	
	public function setIncludeHeader($s = false) {
		$this->includeHeader = $s;
	}
	public function setPostData( $data ) {
		$this->postData = $data;
	}
	public function setHeaders( $headers ) {
		$this->headers = $headers;
	}
	public function __construct( $url = '', $headers = array(),$time_out = 10 ) {
		try{
			$this->handel = curl_init();
		} catch (Exception $e) {
			throw(new CurlException ('curl init error'));
		}
		$this->setTimeOut($time_out);
		if($url) {
			$this->setUrl( $url );
		}
	} 
	
	//GET
	public function doGet($url = '', $includeHeaders = false) {
		if( $url ) {
			$this->setUrl($url);
		}
		$this->setMethod( 'GET' );
		return $this->doRequest();
	}
	//POST
	public function doPost($url = '', $data = array(), $origin = false ) {
		if( $url ) {
			$this->setUrl($url);
		}
		if ( !$origin ) {
			$data = http_build_query($data);
		}
		$this->setPostData($data);
		$this->setMethod( 'POST' );
		return $this->doRequest();
	}
	
	//curl upload file
	public function uploadFile( $path, $filename , $type ,$url = '' ) {
		if( !function_exists('curl_file_create') ) {
			function curl_file_create($path, $type, $filename) {
				return '@'. $path .( $filename ? ';filename=' . $filename : '' ) . ( $type ? ';type=' . $type :'' );
			}
		}
		if($url) {
			$this->setUrl($url);
		}
		$file = array( 'file' => curl_file_create( realpath($path), $type, $filename ) );
		$this->setPostData( $file );
		$this->setMethod( 'POST' );
		return $this->doRequest();
	}

	private function doRequest() {
		if( !$this->url ) {
			throw(new CurlException ('url not found'));
		}
		if( $this->is_debug ) {
			curl_setopt($this->handel, CURLINFO_HEADER_OUT, true);
		}
		curl_setopt($this->handel, CURLOPT_TIMEOUT, $this->time_out);
		curl_setopt($this->handel, CURLOPT_URL, $this->url);
		if( 'POST' ==  $this->method ) {
			curl_setopt( $this->handel, CURLOPT_POST, 1 );
			curl_setopt( $this->handel, CURLOPT_POSTFIELDS, $this->postData );
		}
		curl_setopt($this->handel, CURLOPT_RETURNTRANSFER, 1);
		if( $this->includeHeader ) {
			curl_setopt( $this->handel, CURLOPT_HEADER, true );
		}
		if($this->is_https) {
			curl_setopt( $this->handel, CURLOPT_SSL_VERIFYHOST, false );
			curl_setopt( $this->handel, CURLOPT_SSL_VERIFYPEER, false );
		}
		if($this->referer) {
			curl_setopt( $this->handel, CURLOPT_REFRRER, $this->referer );
		}
		$res = curl_exec($this->handel);
		if( $res === false ) {
			throw(new CurlException( curl_error( $this->handel ) ));
		} else {
			return $res;
		}
	}

	public function getRequestInfo() {
		var_dump( curl_getinfo($this->handel) );
	} 


}
class CurlException extends Exception{
	
}
