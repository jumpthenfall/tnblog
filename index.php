<?php
	header('content-type:text/html;charset=utf-8');
	//接收$page
	$page = isset($_GET["page"])?$_GET["page"]:1;
	$page = $page<=0?1:(int)$page;

	//引入init.php
	require_once('./lib/init.php');
	
	$pagesize = 1;
	$offset = ceil(($page-1)*$pagesize);

	//获取最大页码
	$sql_rows_num = "select id from tn_article where status='publish'";
	$res_rows_num = $mysqli -> _rows_num($sql_rows_num);
	$pagemax = $res_rows_num/$pagesize;

	
	
	

	//实现category
	$sql_category = 'select * from tn_category ';
	$category_list = $mysqli -> _fetchAll($sql_category);
	
	//获取最新文章
	$sql_new = 'select id, title , create_time, author_name from tn_article order by id desc limit 0 ,5';
	
	$article_new = $mysqli -> _fetchAll($sql_new);

	
	
	
	//处理用户分类请求
	$category_id = isset($_GET['category_id'])?$_GET['category_id']:'';
	//WHERE的一个较好的筛选条件
	$where = "status = 'publish' ";
	if(!($category_id==''))
	{
		$where .= " and category_id = $category_id";
		
		$sql_list = "select * from tn_article where $where  order by id desc ";

		//获取每页信息数据
		$res_tags = $mysqli -> _fetchAll($sql_list);
		//引入模板
		require_once(TEMP_PATH . 'tags.html');

	}else
	{
		
		$sql_list = "select * from tn_article where $where  order by id desc limit $offset,$pagesize";

		//获取每页信息数据
		$article_list = $mysqli -> _fetchAll($sql_list);
		
		//引入模板
		require_once(TEMP_PATH . 'index.html');
	}





?>