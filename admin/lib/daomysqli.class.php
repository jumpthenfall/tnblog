<?php
	class DAOMySQLi{
		private $_host;
		private $_user;
		private $_pd;
		private $_dbname;
		private $_port;
		private $_charset;
		private $_mySQLi;
		private static  $_instance;
		//构造函数私有化，禁止new对象实例
		private function __construct($option)
		{
			$this -> _host = isset($option['host'])?$option['host']:'';
			$this -> _user = isset($option['user'])?$option['user']:'';
			$this -> _pd = isset($option['pd'])?$option['pd']:'';
			$this -> _dbname = isset($option['dbname'])?$option['dbname']:'';
			$this -> _port = isset($option['port'])?$option['port']:'';
			$this -> _charset = isset($option['charset'])?$option['charset']:'';

			if($this -> _host ==''||$this -> _user ==''||$this -> _pd =='' ||$this -> _dbname ==''||$this -> _port ==''||$this -> _charset=='')
			{
				echo '连接数据库参数不正确！！';
				exit;
			}else{
				//echo 'shujuzhengque';
			}


			
		//创建数据库对象实例
		$this -> _mySQLi = new MySQLi($this -> _host,$this -> _user ,$this -> _pd ,$this -> _dbname,$this -> _port);
			if($this ->_mySQLi -> connect_errno )
			{
			echo '连接数据库失败'.$this -> _mySQLi -> error;
			}
			
		}

		//禁止克隆
		private function __clone(){}
		//单例
		public static function getSingleton(array $option)
		{
			if(!(self::$_instance instanceof self))
			{
				self::$_instance = new self($option);
			}
			return self::$_instance;
		}


		//mysqli 查询
		public function _query($sql =''){
			if(!($res = $this -> _mySQLi -> query($sql)))
			{
				die('操作失败，语句： ' . $sql .'错误信息' . $this -> _mySQLi -> error);
			}
			return $res;
			
		}
		//获取多条查询数据，放入二维数组并返回
		public  function _fetchAll($sql1= ''){
			 $res = $this -> _query($sql1);

			$rows  = array();
			while($row =$res -> fetch_assoc() )
			{
				$rows[] = $row;
				
			}
			return $rows;

		}

		//获取一条查询数据并返回
		public function _fetchOne($sql='')
		{
			$res = $this -> _query($sql);
			$row = $res -> fetch_assoc();

			return isset($row)?$row:false;
		}

		//返回查询结果的条数
		public function _rows_num($sql = '')
		{
			$res = $this -> _query($sql);
			//num_rows 为对象属性，而不是函数
			$rows =  $res -> num_rows;
			return $rows;
		}
		


	}
	
?>