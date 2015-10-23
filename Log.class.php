<?php
/*
   作者： yqc
   功能：日志类
 */

class Log{
	//每个日志文件大小
	private static $per_log_size = 1048576;//1024*1024 1M
	//记录文件的文件名格式（每小时换一个文件，还是每天换一个文件）
	private static $log_way = 'day';
	private static function write ($cons, $type, $name) {
		$path = ROOT . LOG_PATH . $type . '/';
                //判断路径是否合法
                if (!is_dir($path)) {
                        if(!mkdir( $path , '775' , true )){ 
                                return false;   
                        }   
                }   
                //按照日期存
                if(self::$log_way == 'day') {
                        $filename = $path . date('Ymd') . '-' . $name . '.' . $type;
                } else {
                        $filename = $path . date('YmdH') . '-' . $name . '.' . $type;
                }
		if (($handel = fopen( $filename, 'a' )) != false) {
			fwrite($handel, $cons . '     ' . date('Y-m-d H:i:s') . "\n\r");
			fclose($handel);
		}
	}

	/*
	@function: 记录错误信息
	@pararm: $con(string 记录的内容), $name(记录文件名)
	@return: void
	*/
	public static function error ($con, $name = 'system') {
		self::write($con, 'error', $name);
	}

	/*
	@function: 记录普通信息日志
	@param: $info(string 记录的信息), $name (文件名)
	@return: void
	*/
	public static function info ($info, $name = 'system') {
		self::write($info, 'info', $name);
	}
}
?>
