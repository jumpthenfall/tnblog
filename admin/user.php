<?php

	//接受用户请求
	$action = isset($_GET['a'])?$_GET['a']:'login';
	//引入初始化文件
	require_once('lib/admin_init.php');
//	ob_end_clean();
	

	if($action == 'login')
	{
		//引入登录的模板
		require_once(ADMIN_TEMP_PATH .'login.html');
	}
	elseif('captcha'==$action)
	{
		//引入工具类文件
		require LIB_PATH.'Captcha.class.php';
		//创建对象
		$captcha = new Captcha();
		$captcha->setWidth(320);
		$captcha->setHeight(30);
		$captcha->setCodeLength(4);
		$captcha->mkImage();
	}

	else if($action == 'check')
	{
		//验证验证码是否正确
		if(empty($_POST['captcha']))
		{
			header('refresh:3, url=user.php?a=login');
			die('验证码不能为空');
			
		}
		require LIB_PATH.'captcha.class.php';
		$captcha= new Captcha();
		$res_code=$captcha->checkCode($_POST['captcha']);
		if(!$res_code)
		{
			header('refresh:3,url=user.php?a=login');
			die('验证码不正确，请重新填写');
			
		}
		
		//获取表单数据
		$username = empty($_POST['username'])?'':$_POST['username'];
		$password = empty($_POST['password'])?'':$_POST['password'];
		$remember_me = empty($_POST['remember_me'])?'':$_POST['remember_me'];

		//验证用户名和密码
		$sql_login = "select * from tn_user where username = '$username'";
		$user_login = $mysqli -> _fetchOne($sql_login);

		if($user_login){
			if($user_login['password']==md5($password))
			{
				//将$user放入session中
				$_SESSION['user'] = $user_login;

				if($remember_me=='1')
				{
					setcookie('id',md5($user_login['id'].'salt'),time()+2*7*24*3600);
					setcookie('password',md5($user_login['password'].'salt'),time()+2*7*24*3600);
				}
				//跳转到管理页面
//				require ADMIN_TEMP_PATH .'index.html';
				header('Location:index.php');
				exit;
				
					//var_dump($_SESSION['user'] = $user_login);
			}else
			{
				//用户存在，密码错误
				
				header('Refresh:2; url=userg  uio2q.php?a=login');
				echo '你的密码有误！';
				exit;
			
			}
		}else
		{ 
			header('Refresh:2; url=user.php?a=login');
			echo '用户不存在';
			
			die;
		}
	}


	else if($action == 'logout')
	{
		setcookie('id',md5($user['id'].'salt'),time()-1);
		setcookie('password',md5($user['password'].'salt'),time()-1);
		header('location:user.php?a=login');
		exit;
	}
?>