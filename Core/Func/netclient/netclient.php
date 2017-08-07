<?php
	class netclient {
		public $url;
		public $type;
		public $returnType;
		public $ch = null;
		function init($url, $type, $returnType) {
			$this->url = $url;
			$this->type = $type;
			$this->returnType = $returnType;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$this->ch = $ch;
		}
		function setUrl($url) {
			$this->url = $url;
			curl_setopt($this->ch, CURLOPT_URL, $url);
		}
		function netGet() {
			return $this->result(curl_exec($this->ch));
		}
		function netPost($post_data) {
			curl_setopt($this->ch, CURLOPT_POST, 1);
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post_data);
			return $this->result(curl_exec($this->ch));
		}
		function result($data) {
			if($this->returnType == "json") {
				return json_decode($data);
			} else {
				return $data;
			}
		}
	}
?>