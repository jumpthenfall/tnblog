<?php
ob_end_clean();
	class Captcha{
		private $width=300;
		private $height=30;
		private $code_length=4;

		public function setWidth($w)
		{
			if(isset($w)&&$w>10)
			{
				$this -> width = (int)$w;
			}else
			{
				trigger_error('请重新设置宽度');
				die;
			}
		}

		public function setHeight($h)
		{
			if(isset($h)&&$h>10)
			{
				$this -> height = (int)$h;
			}else
			{
				trigger_error('请重新设置高度');
				die;
			}
		}

		public function setCodeLength($len)
		{
			if(isset($len)&&$len<35&&$len>0)
			{
				$this -> code_length = (int)$len;
			}else
			{
				trigger_error('请重新设置验证长度');
				die;
			}
		}
		public function mkImage()
		{
			//生成画布
			$image=imagecreatetruecolor($this -> width,$this ->height);
			$color=imagecolorallocate($image,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
			imagefill($image,1,1,$color);
			//code字符串
			$captcha_str='ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
			$str_len=strlen($captcha_str);
			//设置验证码长度，并随机生成
			$str='';
			for($i=1;$i<=$this ->code_length;++$i)
			{
				$str .=$captcha_str[mt_rand(0,$str_len-1)];
			}
			//将验证码放入session
			@session_start();
			$_SESSION['captcha_code']=$str;
			//设置字体大小，并将验证码居中
			$font_size=5;
			$str_y=($this ->height-imagefontheight($font_size))/2;
			$str_x=($this ->width-imagefontwidth($font_size)*$this ->code_length)/2;
			$text_color=imagecolorallocate($image,mt_rand(100,150),mt_rand(100,150),mt_rand(100,150));
			imagestring($image,$font_size,$str_x,$str_y,$str,$text_color);

			//设置干扰点
			for($i=0;$i<500;++$i)
			{
				imagesetpixel($image,mt_rand(0,$this ->width-1),mt_rand(0,$this ->height-1),$text_color);
			}

			//设置header头
			header('content-type:image/png');
			//设置不要缓存
			header('cache-control:no-cache no-store must-revalidate');
			header('Expires: ' . gmdate('D, d M Y H:i:s', time()-1) . ' GMT');

			imagepng($image);
			imagedestroy($image);
		}

		public function checkCode($post_code)
		{
			@session_start();
			//校验验证码是否正确
			$result=isset($post_code)&&isset($_SESSION['captcha_code'])&&strtoupper($post_code)==$_SESSION['captcha_code'];
			//验证码校验完后就销毁
			if(isset($_SESSION['captcha_code']))
			{
				unset($_SESSION['captcha_code']);
			}

			return $result;
		}


	}

