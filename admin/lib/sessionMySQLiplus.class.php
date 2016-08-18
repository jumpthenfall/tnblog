<?php
	header('content-type:text/html;charset=utf-8');
	class sessionMySQLi{

		private static $mysqli;

		public function __construct()
		{
			//自定义存储
			ini_set('session.save_handler','user');

			//设置自定义session处理器
			session_set_save_handler(
				array($this,'sessionBegin'),
				array($this,'sessionEnd'),
				array($this,'sessionRead'),
				array($this,'sessionWrite'),
				array($this,'sessionDelete'),
				array($this,'sessionGC')
					
			);
			session_start();
		}

//			该方法，会在被调用时自动获得当前的sessionid
//			该方法，需要返回字符串，即使没有获取成功，也需要空字符串！
//			该方法，仅仅需要返回字符串数据即可，不需要对其反序列化！

		public function sessionRead($session_id){
			//echo '<br>','READ','<br>';
			//var_dump($session_id);
			
			//查询session
			$sql = "select `session_data` from `tn_session` where `session_id` = '$session_id'";
			$result=self::$mysqli -> query($sql);
			
			$row = $result -> fetch_assoc();
			//var_dump($row);
			return $row?$row['session_data']:'';
		}


		 /**
		 * 作用：将当前脚本周期处理好的session数据，持久化存储到数据区（数据表）
		 * 执行时机：脚本周期结束时执行！
		 * @param string $session_id 当前session的会话ID
		 * @param string $session_data 序列化好的当前会话所有的session数据
		 * sessionRead('12345679809abcdef')
		 * @return bool 执行的结果
		 */

		public function sessionWrite($session_id,$session_data){
		
//			echo '<br>','WRITE','<br>';
			
			//执行写入操作
			$sql = "replace into `tn_session` values ('$session_id','$session_data',unix_timestamp())";
			if(!isset(self::$mysqli ))
			{
				//连接数据库
				self::$mysqli  = new MySQLi('localhost','root','root','tnblog','3306');
				self::$mysqli  = set_charset('utf8');
			}
			$result = self::$mysqli  -> query($sql);
			return $result;

		}
			
		 /**
		 * 作用：将当前session的记录删除
		 * 执行时机：用户脚本调用了session_destroy()，被调用！
		 * @param string $session_id 当前session的会话ID，sessionRead('12345679809abcdef')
		 */
		public function sessionDelete($session_id){
//			echo '<br>','DELETE','<BR>';

			//执行删除操作
			$sql = "delete from `tn_session` where `session_id` = $session_id";
			return self::$mysqli  -> query($sql);
			
		}


		/**
		 * 作用：将过期的session记录删除
		 * 执行时机：session开启过程中，被概率调用！
		 * @param int $maxlifetime
		 */
		public function sessionGC($maxlifetime){
//			echo '<br>','GC','<BR>';

			//执行回收操作
			$sql = "delete from `tn_session` where `last_write` < unix_timestamp() - $maxlifetime";
			return self::$mysqli  -> query($sql);
			
		}

		public function sessionBegin(){
			//echo '<br>','BEGIN','<br>';
			//连接数据库
			self::$mysqli = new MySQLi('localhost','root','root','tnblog','3306');
			self::$mysqli  -> set_charset('utf8');
			return true;
			
		}

		public function sessionEnd(){
//			echo '<br>','END','<br>';
			return true;
		}
	}
?>