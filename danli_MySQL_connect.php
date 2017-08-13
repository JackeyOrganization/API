<?php
/*@param object 返回$_connectSourcr的连接数据库的指针*/
class Db{
	static private $_instance;
	static private $_connectSource;
	private $_dbConfig = array(
			'host'=>'127.0.0.1',
			'user'=>'root',
			'pwd'=>'admin',
			'db'=>'app'
		);
	//单例需要构造函数为非public
	private function __construct(){

	}

	static function getInstance(){
		if (!(self::$_instance instanceof self)) {
		self::$_instance = new self();
		}
		return self::$_instance;
	}

	public  function connect(){
		if (!self::$_connectSource) {
			
		self::$_connectSource = mysqli_connect($this->_dbConfig['host'],$this->_dbConfig['user'],$this->_dbConfig['pwd'],$this->_dbConfig['db']);

		mysqli_set_charset(self::$_connectSource,'utf8');
		if (mysqli_connect_error()) {
			//这里使用throw 是更严谨，避免数据库连接失败可以抛出一个问题给客户端
			throw new Exception('mysql connect error'.mysqli_connect_error());
			}
		}
		return self::$_connectSource;
	}
}
/*例子*/
// try { $conn =  Db::getInstance()->connect(); 
// $sql = "SELECT * FROM cms_photo";
// $res = $conn->query($sql);
// echo mysqli_num_rows($res);
// var_dump($res);
//}catch(Exception $e){//todo}