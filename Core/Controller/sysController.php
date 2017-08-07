<?php
	class sysController extends Eye {
		function state () {
			echo "run";	
		}
		function recieve() {
			$this->reasponse(1, "test", ["hello"=>2]);
		}
		function test() {		
		}
		function testToken() {	
		}
		function sc() {
			$config["pc_edition"] = 1;
			echo json_encode($config);
		}	
	}
?>