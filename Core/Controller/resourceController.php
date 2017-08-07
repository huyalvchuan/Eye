<?php
/**
 *文件服务器ftp同步
 * 利用ftp同步到文件服务器中
 */
	class resourceController extends Eye {
		function logoUpload() {
			$request = $this->I("json");
			$type = $request->type;
			$up = new FileUpload();
				$up -> set("path", DIR."/Core/File/logo/");
			    $up -> set("maxsize", 2000000);
			    $up -> set("allowtype", array("gif", "png", "jpg","jpeg"));
			    $up -> set("israndname", TRUE);
			    if($up -> upload("logo")) {
			       $path = "http://kysysgl.nwsuaf.edu.cn/zhiliao"."/Core/File/logo/".$up->getFileName();
			  	   $this->reasponse(40001, "上传成功", $path);
			    } else {
			        $this->reasponse(40002, $up->getErrorMsg(), null);
			    }					
		}
		function coursewareUpload() {
			$request = $this->I("json");
			$type = $request->type;
			$up = new FileUpload();
				$up -> set("path", DIR."/Core/File/source/");
			    $up -> set("maxsize", 5000000);
			    $up -> set("allowtype", array("ppt", "pptx","doc", "jpg","jpeg", "excel", "mp4"));
			    $up -> set("israndname", TRUE);
			    if($up -> upload("file")) {
			       $path ="http://kysysgl.nwsuaf.edu.cn/zhiliao"."/Core/File/source/".$up->getFileName();
			  	   $this->reasponse(40001, "上传成功", $path);
			    } else {
			        $this->reasponse(40002, $up->getErrorMsg(), null);
			    }					
		}
	}
?>