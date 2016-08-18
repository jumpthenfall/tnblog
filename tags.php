<?php
	//接收$tags 
	$tags = isset($_GET['tags'])?$_GET['tags']:'love';

	//引入初始化文件
	require_once('./lib/init.php');


	//编写sql语句
	$sql_tags = "select * from tn_article where tags = '$tags'";
	//获取结果
	$res_tags = $mysqli -> _fetchAll($sql_tags);

	//获取最新文章
	$sql_new = 'select id, title , create_time, author_name from tn_article order by id desc limit 0 ,5';
	
	$article_new = $mysqli -> _fetchAll($sql_new);


	//引入模板
	require_once(TEMP_PATH .'tags.html');

?>