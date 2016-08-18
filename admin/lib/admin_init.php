<?php
	header('content-type:text/html;charset=utf-8');

	//定义tnblog目录路径
	define('ROOT_PATH',dirname(dirname(__DIR__)).'/');
	//定义tnblog目录下lib文件路径
	define('LIB_PATH',ROOT_PATH.'lib/');
	//定义上传文件目录
	define('UPLOAD_PATH',ROOT_PATH.'upload/');
	
	//定义后台路径根目录
	define('ADMIN_ROOT_PATH',dirname(__DIR__).'/');
	//定义lib文件路径
	define('ADMIN_LIB_PATH', ADMIN_ROOT_PATH.'lib/');
	//定义template模板文件路径
	define('ADMIN_TEMP_PATH', ADMIN_ROOT_PATH.'template/');
//	//开启session
//	session_start();

	//引入DAOMySQLi.class.php文件
	require_once(LIB_PATH.'daomysqli.class.php');

	
	
	//设置默认时区
	date_default_timezone_set('PRC');
	

	$arr = array(
		'host' => 'localhost',
		'user' => 'root',
		'pd' => 'root',
		'dbname' => 'tnblog',
		'port' => '3306',
		'charset' => 'utf8'
	);
	$mysqli = DAOMySQLi::getSingleton($arr);

	//引入sessionMySQLIplus.class.php
	require_once(ADMIN_LIB_PATH.'sessionMySQLiplus.class.php');
	 $session = new sessionMySQLi();


	//判定用户是否登录
	$request_name = basename($_SERVER['SCRIPT_NAME']);

	//如果用户请求的不是user.php页面的login或check，则判断登录状态
	if(!($request_name =='user.php'&&($action=='login'||$action=='check'||'captcha'==$action)))
	{
		if(!isset($_SESSION['user']))
		{
//			echo '请登录！';
//			header('Refresh:2 ; url=user.php?a=login');
			if(isset($_COOKIE['id'])&&isset($_COOKIE['password']))
			{
				//如果$_COOKIE存在，则验证数据是否合法
				$id=$_COOKIE['id'];
				$password =$_COOKIE['password'];
				
				//编写$sql语句
				$sql= "select * from tn_user where md5(concat(id,'salt'))='$id'   and md5(concat(password,'salt'))='$password'";
				$user = $mysqli -> _fetchOne($sql);
				if($user)
				{
					//说明数据合法，重新设置session
					$_SESSION['user']=$user;
				}else
				{
					//说明数据存在但不合法，返回登陆页面
					header('Location:user.php?a=login');
					exit;
				}
			}else
			{
				//返回登陆页面
				header('Location:user.php?a=login');
				exit;
			}
		}
	}

	
?>