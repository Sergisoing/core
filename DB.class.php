<?php
/*
	数据库类，采用PDO链接数据，单例模式
*/
class DB{
	//接口
	private static $ins;
	//数据名称
	private static $database_name = 'mysql';

	//私有化构造方法
	private function __construct() {
				
	}
	
	/*
	@function: 获取PDO句柄，数据库操作链接资源
	@param: void
	@return: void
	*/
	private static function getIns() {
		$DSN = self::$database_name . ':dbname=' . DB_NAME . ';host=' . DB_HOST . ';port=' . DB_PORT;
		self::$ins = new PDO($DSN, DB_USER, DB_PASS);
		var_dump(self::$ins);
	}
	
	/*
	@function: 获取多条结果
	@param: $sql (string sql语句) ,$data( array, 需要的参数 )
	@return: 语法错误返回NULL， 没有结果返回false,  有结果返回数组
	*/
	public static function getAll() {
		self::getIns();
	}
}
	

?>
