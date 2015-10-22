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
                        $filename = ROOT . LOG_PATH . $type . '/' . date('Ymd') . '-' . $name . '.' . $type;
                } else {
                        $filename = ROOT . LOG_PATH . $type . '/' . date('YmdH') . '-' . $name . '.' . $type;
                }
		if (($handel = fopen( $filename, 'a' )) != false) {
			fwrite($handel, $cons . '     ' . date('Y-m-d H:i:s') . "\n\r");
			fclose($handel);
		}
	}

	public static function error ($con, $name = 'system') {
		self::write($con, 'error', $name);
	}
}


?>
