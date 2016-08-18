<?php
	header('content-type:text/html;charset=utf-8');
	//引入初始化文件
	require_once('lib/admin_init.php');
	//
	$action = isset($_GET['a'])?$_GET['a'] :'list';
	if($action=='list')//展示文章列表
	{
		//拼接$sql语句，并获取文章列表
		$sql_list = "select a.id, a.title, a.status, a.create_time, c.title as cat_title from tn_article  a left join tn_category  c on a.category_id = c.id"; 

		$article_list = $mysqli -> _fetchAll($sql_list);
		
		//引入模板
		require_once ADMIN_TEMP_PATH .'article_list.html';


	}else if($action =='add')//添加文章
	{
		//拼接sql语句,获取分类列表
		$sql_add = "select id, title from tn_category";
		$category_list = $mysqli -> _fetchAll($sql_add);
		//引入模板
		require_once ADMIN_TEMP_PATH .'article_add.html';


	}else if($action == 'insert')//提交文章内容
	{
		//初始化数据
		$id = null;
		$title = empty($_POST['subject'])?'':$_POST['subject'];
		$summary = empty($_POST['summary'])?'':$_POST['summary'];
		$content = empty($_POST['content'])?'':$_POST['content'];
		$create_time = time();
		$author_id = $_SESSION['user']['id'];
		$author_name = $_SESSION['user']['username'];
		$category_id = empty($_POST['category_id'])?'':$_POST['category_id'];
		$cover = empty($_POST['cover'])?'':$_POST['cover'];
		$status = empty($_POST['submit'])?'':$_POST['submit'];
		$is_delete = 0;
		$tags = '';


		//判断数据是否合法
		if($title ==''||$content==''||$summary=='')
		{
			echo '输入有误';
			header('Refresh:3; url = article.php?a=add');
			exit;
		}
		//向数据库添加文章
		$sql_insert = "insert into tn_article values(null,'$title','$summary','$content',$create_time,$author_id,'$author_name', $category_id,'$cover', '$status', $is_delete, '$tags')";

		//判断添加是否成功，并做出响应
		if($mysqli -> _query($sql_insert))
		{
			header('Location:article.php?a=list');
			exit;
		}else
		{
			echo '添加失败！！';
			header('Refresh:3;url=article.php?a=add');
			exit;
		}


	}else if($action == 'edit')//编辑文章内容
	{	
		//获取要编辑文章的id
		$id=isset($_GET['id'])?$_GET['id']:'';
		if($id)
		{
			//编写sql语句，获取要编辑文章原始信息
			$sql_edit = "select * from (select a.id,a.title,a.status,a.content,a.summary,a.cover, a.category_id, a.create_time, a.tags,c.title as cat_title from `tn_article` a left join `tn_category` c on a.category_id=c.id) ac where ac.id = $id ";
			$article_edit = $mysqli -> _fetchOne($sql_edit);
			$category_id = $article_edit['category_id'];

		//拼接sql语句,获取分类列表
		$sql_add = "select id, title from tn_category where id<>$category_id";
		$category_list = $mysqli -> _fetchAll($sql_add);
			
		}

		//引入文章编辑模板
		require_once ADMIN_TEMP_PATH .'article_edit.html';


	}else if($action=='update')//更新数据库内容
	{

		//初始化参数
		$id=empty($_POST['id'])?'':$_POST['id'];
		$title = empty($_POST['subject'])?'':$_POST['subject'];
		$summary = empty($_POST['summary'])?'':$_POST['summary'];
		$content = empty($_POST['content'])?'':$_POST['content'];
		$category_id = empty($_POST['category_id'])?'':$_POST['category_id'];
		$status = empty($_POST['submit'])?'':$_POST['submit'];
		$tags = empty($_POST['tag[]'])?'':$_POST['tag[]'];
	
		//获取现在的cover
		$sql="select `cover` from tn_article where `id` =$id";
		$list = $mysqli -> _fetchOne($sql);
		$old_cover = $list['cover'];




		$cover=empty($_FILES['cover']['name'])?'':$_FILES['cover'];
		//处理封面
		if($cover==$_FILES['cover'])
		{
			require LIB_PATH.'upload.class.php';
			//创建对象实例
			$upload_cover = new Upload();
			//设置目录
			$upload_cover -> setUploadPath(UPLOAD_PATH.'cover/');
			$upload_cover -> setPrefix('Cover_');
			///
			$upload_result = $upload_cover -> uploadFile($cover);
			if($upload_result)
			{
				$cover=$upload_result;
			}else
			{
				header("refresh:30, url=article.php?a=edit&id=$id");
				die('封面上传失败，请重新操作');
			}
		}


		//判断数据是否合法
		if($title ==''||$content==''||$summary=='')
		{
			echo '输入有误';
			header('Refresh:3; url = article.php?a=edit');
			exit;
		}
		//向数据库添加文章
		$sql_update = "update  tn_article set  title='$title',summary='$summary',content='$content',category_id=$category_id,cover='$cover', status='$status',tags='$tags', cover='$cover' where id = $id";

		//判断添加是否成功，并做出响应
		if($mysqli -> _query($sql_update))
		{
			header('Location:article.php?a=list');
			exit;
		}else
		{
			echo '添加失败！！';
			header('Refresh:3;url=article.php?a=edit');
			exit;
		}
	}else if($action ='delete')
	{
		//获取文章id
		$id=isset($_GET['id'])?$_GET['id']:'';
		if($id)
		{
			//编写sql语句
			$sql_delete="delete  from tn_article where id=$id";
			if($mysqli->_query($sql_delete))
			{
				header('Location:article.php?a=list');
				exit;
			}else
			{
				echo '删除失败！';
				header('Refresh 3;url=article.php?a=list');
				exit;
			}
		}
	}
?>