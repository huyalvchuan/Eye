<?php 
//	phpinfo();
			$url = "www.baidu.com";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//			curl_setopt($ch, CURLOPT_HEADER, 0);
			echo curl_exec($ch);
			
?>