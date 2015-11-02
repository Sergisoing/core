<?php 
/**
 *@author：YQC
 *@function: DB produce,singe instance mode
 */
require( './Config.class.php' );
require( './Log.class.php' );
class DB{
		//the db instatnce
		private static $ins = null;
		//the db config 
		private $config = null;

		/**
		 *@param: void
		 *@function: 创建一个PDO实例
		 */
		private function __construct() {
				$this->config = Config::getDBConfig();
				try{
						self::$ins = new PDO($this->config['DSN'], $this->config['DB_USER'], $this->config['DB_PW']);
						self::$ins->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						self::$ins->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
				} catch ( PDOException $e ) {
						Log::error($e->getMessage(), 'db');
						die('DB error');
				}
		}

		/**
		 *@param:$sql (sql查询语句)， [$data(array 绑定的参数)]
		 *@func:获取多行数据
		 *@return: 成功返回结果，结果为空时返回false，sql语句错误时返回null
		 */
		public static function getAll ($sql, $data = array()) {
				self::getIns();
				try{
						Log::info($sql . ' ------ values ' . json_encode($data), 'sql');
						$sth = self::$ins->prepare($sql);
						$sth->execute($data);
						$res = $sth->fetchAll(PDO::FETCH_ASSOC);
						return $res === NULL ? NULL : ( $res ? $res : false );
				} catch (Exception $e){
						Log::error($e->getMessage(), 'db');
				}
		}

		/**
		 *@param: void
		 *@func: 获取数据库接口
		 */
		public static function getIns() {
				if (self::$ins) {
						return self::$ins;
				} else {
						new self();
						return self::$ins;
				}
		}

		/**
		 *@param: $sql(string sql), $data(array values)
		 *@func: 获取单行记录
		 *@return: array( key => value, key => value )
		 */
		public static function getOne($sql, $data = array()) {
				self::getIns();
				try{
						Log::info($sql . ' ------ values ' . json_encode($data), 'sql');
						$sth = self::$ins->prepare($sql);
						$sth->execute($data);
						$res = $sth->fetch(PDO::FETCH_ASSOC);
						return $res === NULL ? NULL : ( $res ? $res : false );
				} catch (Exception $e) {
						Log::error($e->getMessage(), 'db');
				}
		}

		/**
		 *@param: $sql String
		 *@param: $data Array
		 *@func: 获取单个数据，一个单元格
		 *@return: 成功返回结果，结果为空时返回false，sql语句错误时返回null
		 */
		public static function getCell($sql, $data = array()) {
				self::getIns();
				try{
						Log::info($sql . ' ------ values ' . json_encode($data), 'sql');
						$sth = self::$ins->prepare($sql);
						$sth->execute($data);
						$res = $sth->fetchColumn();
						return $res === NULL ? NULL : ( $res ? $res : false );
				} catch (Exception $e) {
						Log::error($e->getMessage(), 'db');
				}

		}

		/**
		 * 执行其他sql
		 *@param: $sql String
		 *@param: $data Array
		 *@return: 成功返回sql影响的条数 失败返回NULL
		 */
		public static function exec($sql, $data = array()) {
				self::getIns();
				try{
						Log::info($sql . ' ------ values ' . json_encode($data), 'sql');
						$sth = self::$ins->prepare($sql);
						$sth->execute($data);
						return $sth->rowCount();
				} catch (Exception $e) {
						 Log::error($e->getMessage(), 'db');
				}

		}


		/**
		 *@param:void
		 *@func: 防止克隆
		 */
		private function __clone() {
				return null;
		}
}
$db = DB::getCell('insert into user values (?,?,?)', array('','qinlvying','1325249560@qq.com'));
var_dump($db);
?>
