<?php
	
	class Upload{
		private $allow_ext_list = ['.jpg','.jpeg','.png','.gif'];
		private $file_max_size = 1048576;
		private $upload_path = './';
		private $prefix = 'zjc_';
		//ext2mime印射数组
		private $ext2mime = array(
		'.jpeg' => 'image/jpeg',
		'.png'	=> 'image/png',
		'.gif'	=> 'image/gif',
		'.jpg'	=> 'image/jpeg',
		'.html'	=> 'text/html',
		// ....
		);

		public function setAllowExtList(array $arr)
		{
			$this -> allow_ext_list = $arr;
		}
		public function setFileMaxSize(int $size)
		{
			$this -> file_max_size = $size;
		}
		public function setUploadPath($path)
		{
			if(is_dir($path) && is_writable($path))
			{
				$this -> upload_path = $path;
			}
		}
		public function setPrefix($fix)
		{
			$this -> prefix = $fix;

		}

	
		//上传文件
		public function uploadFile($file)
		{
			if($file['error'])
			{
				trigger_error('文件上传出现错误');
				return false;
			}


			//获取文件ext
			$file_ext=strrchr($file['name'],'.');
			//判断是否为允许文件类型
			if(!in_array($file_ext, $this -> allow_ext_list))
			{
				trigger_error('文件类型不对11s，请重新选择');
				return false;
			}
			//获取mime类型列表
			$allow_mime_list= $this->getMimeList($this->allow_ext_list);
			//获取文件mime
			$finfo = new Finfo(FILEINFO_MIME_TYPE);
			$file_mime= $finfo->file($file['tmp_name']);
			//判断
			if(!in_array($file_mime,$allow_mime_list))
			{
				trigger_error('文件类型不正确');
				return false;
			}
			//判断文件大小
			if($file['size']>$this->file_max_size)
			{
				trigger_error('文件太大');
				return false;
			}

			//创建子目录，以时间区分
			$subdir=date('Y-m').'/';
			//判断目录是否存在
			if(!is_dir($this->upload_path .$subdir))
			{
				//如果不存在就创建目录
				mkdir($this->upload_path .$subdir);
			}
			//科学取名
			$basename = uniqid($this->prefix,true).$file_ext;


			//移动文件
			$remove_result = move_uploaded_file($file['tmp_name'],$this->upload_path.$subdir.$basename);

			if(!$remove_result)
			{
				trigger_error('文件上传失败，请联系管理员');
				return false;
			}

			//上传成功
			return $subdir . $basename;


		}

		//获取mime类型列表
		private function getMimeList($allow_ext_list)
		{
			$allow_mime_list=[];
			foreach($allow_ext_list as $value)
			{
				$allow_mime_list[]= $this->ext2mime[$value];
			}
			return $allow_mime_list;
		}
		
	}

?>