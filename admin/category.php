<?php

	//接受$action
	$action = isset($_GET['a'])?$_GET['a']:'';
	//引入初始化文件
	require_once ('lib/admin_init.php');
	if($action == 'list')
	{
		//编写sql语句
		$sql_list='select * from tn_category';
		$category_list = $mysqli -> _fetchAll($sql_list);
		require_once ADMIN_TEMP_PATH .'category_list.html';
	}else if($action == 'add')
	{
		//引入模板
	require_once ADMIN_TEMP_PATH .'category_add.html';
	}else if($action == 'insert')
	{
		//接收并验证数据
		$title = empty($_POST['title'])?'':$_POST['title'];
		$order_number = empty($_POST['order_number'])?'':$_POST['order_number'];
		if($title==''||$order_number==''||(!is_numeric($order_number)))
		{
			echo '填写不正确，请重新填写';
			header('Refresh:3 ; url=category.php?a=add');
			exit;
		}else
		{
			//编写sql语句
			$sql_add = "insert into tn_category values(null,'$title',$order_number)";
			if(!($mysqli -> _query($sql_add)))
			{
				echo '添加失败！！';
				header('Refresh:3 ;url=category.php?a=add');
				exit;
			}else
			{
				header('location:category.php?a=list');
				exit;
			}
		}
	}else if($action == 'delete')
	{	
		//获取id
		$id = isset($_GET['id'])?$_GET['id']:'';
		//编写sql语句
		$sql_delete = "delete from tn_category where id = '$id'";
		if($mysqli -> _query($sql_delete))
		{
			header('location:category.php?a=list');
			exit;
		}else
		{
			echo '删除失败！';
			header('Refresh:3 ;url=category.php?a=list');
			exit;
		}
	}else if($action == 'edit')
	{
		//获取id
		$id = isset($_GET['id'])?$_GET['id']:'';
		//编写sql语句
		$sql_edit = "select * from tn_category where id = '$id'";
		if(!($category_edit=$mysqli -> _fetchOne($sql_edit)))
		{
			echo '编辑失败！';
			header('Refresh:3 ;url=category.php?a=list');
			exit;
		}
		require_once ADMIN_TEMP_PATH .'category_edit.html';

	}else if($action =='update')
	{
		//获取数据
		$id = empty($_POST['id'])?'':$_POST['id'];
		$title = empty($_POST['title'])?'':$_POST['title'];

		$order_number = empty($_POST['order_number'])?'':$_POST['order_number'];
		//编写sql语句
		$sql_update = "update tn_category set title='$title',order_number = $order_number where id =$id";
		if($mysqli -> _query($sql_update))
		{
			header('location:category.php?a=list');
			exit;
		}else
		{
			echo '修改失败！！';
			header('Refresh:3; url=category.php?a=edit');
			exit;
		}



	}


	require_once 'lib/admin_init.php';
?>