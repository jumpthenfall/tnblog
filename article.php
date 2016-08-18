<?php
	//引入初始化文件
	require_once('./lib/init.php');
	//接收$id
	$id = isset($_GET['id'])?$_GET['id']:'';

	//编写sql语句
	$sql = "select * from tn_article where id = $id";
	//获取结果
	$article = $mysqli -> _fetchOne($sql);

	//实现category
	$sql_category = 'select * from tn_category ';
	$category_list = $mysqli -> _fetchAll($sql_category);

	//获取最新文章
	$sql_new = 'select id, title , create_time, author_name from tn_article order by id desc limit 0 ,5';
	
	$article_new = $mysqli -> _fetchAll($sql_new);

	//引入模板
	require_once(TEMP_PATH ."article.html");
?>