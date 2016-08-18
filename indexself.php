<?php
	header('content-type:text/html;charset=utf-8');
	//接收$page
	$page = isset($_GET["page"])?$_GET["page"]:1;

	//引入init.php
	require_once('./lib/init.php');
	
	$pagesize = 1;
	$offset = ceil(($page-1)*$pagesize);

	//获取最大页码
	$sql_rows_num = 'select id from tn_article';
	$res_rows_num = $mysqli -> _rows_num($sql_rows_num);

	
	$pagemax = $res_rows_num/$pagesize;
	
	

	$sql_list = "select * from tn_article  order by id desc limit $offset,$pagesize";

	//获取数据
	$article_list = $mysqli -> _fetchAll($sql_list);
	
	//获取最新文章
	$sql_new = 'select id, title , create_time, author_name from tn_article order by id desc limit 0 ,5';
	
	$article_new = $mysqli -> _fetchAll($sql_new);

	
	
	





	//引入模板
	require_once(TEMP_PATH . 'index.html');
?>