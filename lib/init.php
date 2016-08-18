<?php
	header('content-type:text/html;charset=utf-8');
	//在ini.php文件中完成初始化
	//确定路径
	define('ROOT_PATH', dirname(__DIR__).'/');
	//模板路径
	define('TEMP_PATH', ROOT_PATH.'template/');
	//lib文件路径
	define('LIB_PATH', ROOT_PATH.'lib/');
	//image文件路径
	define('IMG_PATH',ROOT_PATH.'img/');
	//引用DAO文件
	require_once(LIB_PATH.'daomysqli.class.php');


	//设置时区
	date_default_timezone_set('PRC');
	
	


	//创建mysqli实例
	
	$arr = array(
		'host' => 'localhost',
		'user' => 'root',
		'pd' => 'root',
		'dbname' => 'tnblog',
		'port' => '3306',
		'charset' => 'utf8'
	);
	$mysqli = DAOMySQLi::getSingleton($arr);
	
?>