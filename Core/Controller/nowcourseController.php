<?php
	class nowcourseController extends Eye {
		public function add() {
			$request = $this->I("json");
//			$token = $request->token;
			$db = $this->M();
//			$userId = $this->token($this, $db, $token);
			$courseId = $request->courseId;
			$operation = $db->insert("course_history", [
				"course_id"=>$courseId,
				"status"=>1,
				"grade"=>0,
				"unknown_num"=>0,
				"title"=>$request->title
			]);
			$this->reasponse(33333, "创建成功", ["id"=>$operation]);
		}
		public function delete() {	
		}
		public function open() {
			$request = $this->I("json");
			$historyId = $request->historyId;
			$db = $this->M();
			$o = $db->update("course_history", [
				"status"=>1
			], [
				"id"=>$historyId
			]);
			if($o == 1) {
				$this->reasponse(33333, "success", null);
			}
		}
		public function close() {
			$request = $this->I("json");
			$historyId = $request->historyId;
			$db = $this->M();
			$o = $db->update("course_history", [
				"status"=>2	//关闭
			], [
				"id"=>$historyId
			]);
			if($o == 1) {
				$this->reasponse(33333, "success", null);
			}
		}
		public function addhistory() {
			$request = $this->I("json");
			$historyId = $request->historyId;
			$db = $this->M();
			$uId = $this->token($this, $db, $request->token);
			$history = $db->select("course_history", "*", [
				"id"=>$historyId
			]);
			if(count($history) == 0 || $history[0]["status"] == 2) {
				$this->reasponse(30003, "无法加入实时课堂，因为加入通道老师已经关闭", null);
				return;
			}
			$nowTime = time();
			if(strtotime($history[0]["create_time"])+2*60*60*1000 < $nowTime) {
				$this->reasponse(30004, "超过两小时，不允许加入", null);
				return;
			}
			$record = $db->select("history_record", "*", [
				"AND"=>[
				"u_id"=>$uId,
				"h_id"=>$historyId
				]
			]);
			if(count($record) ==1) {
				$this->reasponse(30005, "已经加入过了", ["history"=>$history]);
				return;
			}
			$n = $db->insert("history_record", [
				"u_id"=>$uId,
				"h_id"=>$historyId
			]);
			if($n > 0) {
				$this->reasponse(33333, "加入成功", ["history"=>$history]);
			} else {
				var_dump($db->error());
			}
			
		}
		public function addQuestion() {
			$request = $this->I("json");
			$historyId = $request->historyId;
			$db = $this->M();
			$uId = $this->token($this, $db, $request->token);
			$history = $db->select("course_history", [
				"id"=>$historyId
			]);
			if(count($history) == 0) {
				$this->reasponse(30006, "已经删除", null);
			}
			$n = $db->insert("course_question", [
				"content"=>$request->content,
				"u_id"=>$uId,
				"ch_id"=>$historyId
			]);
			$this->reasponse(33333, "问题上传成功", null);
		}
		public function addUnknow() {
				$request = $this->I("json");
				$historyId = $request->historyId;
			 	$db = $this->M();
				$history = $db->select("course_history", [
					"id"=>$historyId
				]);
				if($history[0]["status"] == 2) {
					$this->reasponse(30003, "课程已经关闭", null);
				}
				$db->update("course_history", ["unknown_num[+]"=>1], [
					"id"=>$historyId
				]);
				var_dump($db->error());
		}
		public function historyInfo() {
			$request = $this->I("json");
			$db = $this->M();
			$historyId = $request->historyId;
			$history = $db->select("course_history", "*", [
					"id"=>$historyId
			]);
			$this->reasponse(33333, "查询成功", ["historyInfo"=>$history[0]]);
		}
		public function getQuestion() {
				$request = $this->I("json");
				$historyId = $request->historyId;
				$db = $this->M();
				$page = $request->page;
				if($page = -1) {		//查询最新的10条问题
					$list = $db->select("course_question", ["id", "content", "create_time"], [
						"ORDER" => ["id"=>"DESC"],
						"ch_id"=>$historyId,
						 "LIMIT" =>10
					]);
					$this->reasponse(33333, "查询成功", ["list"=>$list]);
				} else {	
					$list = $db->select("course_question", ["id", "content", "create_time"], [
						"ORDER" => ["id"=>"DESC"],
						"ch_id"=>$historyId,
						 "LIMIT" =>100
					]);	
					$this->reasponse(33333, "查询成功", ["list"=>$list]);
				}
		}
		public function getList() {
			$request = $this->I("json");
			$courseId = $request->courseId;
			$db = $this->M();
			$list = $db->select("course_history", "*", [
				"course_id"=>$courseId
			]);
			$this->reasponse(33333, "查询成功", ["list"=>$list]);
		}
		public function getSignNum() {
			$request = $this->I("json");
			$historyId = $request->historyId;
			$db = $this->M();
			$num = $db->count("history_record",["h_id"=>$historyId]);
			$this->reasponse(33333, "success", ["num"=>$num]);
		}
		public function auth() {
			$request = $this->I("json");
			$historyId = $request->historyId;
			$token = $request->token;
			$db = $this->M();
			$uId = $this->token($this, $db, $token);
			$num = $db->count("history_record",["AND"=>[
				"u_id"=>$uId,
				"h_id"=>$historyId
			]]);
			if($num == 0) {
				$this->reasponse(30007, "未加入", null);
			} else {
				$this->reasponse(30008, "验证通过", null);
			}
		}
		public function getAllList() {
			$request = $this->I("json");
			$page = $request->page;
			$db = $this->M();
			$uId = $this->token($this, $db, $request->token);
			$list = $db->select("course_history", ["[>]course"=>["course_id"=>"id"]], [
					"course_history.id(id)","course_history.title(title)", "course_history.create_time(time)", "course_history.status(status)", "course.course_name(name)"
				],
				[
				"course.u_id"=>$uId,
				"ORDER"=>["course_history.id"=>"DESC"],
				"LIMIT"=>[$page*20, ($page+1)*20]
				]
			);
			foreach ($list as $key => $value) {
					$num = $db->count("history_record",["h_id"=>$value["id"]]);
				$list[$key]["num"] = $num;
			}
		  $this->reasponse(33333, "success", ["list"=>$list]);
		}	
		public function getListByCourse () {
			$request = $this->I("json");
			$page = $request->page;
			$db = $this->M();
			$uId = $this->token($this, $db, $request->token);
			$courseId = $request->courseId;
				$list = $db->select("course_history", ["[>]course"=>["course_id"=>"id"]], [
					"course_history.id(id)","course_history.title(title)", "course_history.create_time(time)", "course_history.status(status)", "course.course_name(name)"
				],
				[
				"AND"=>[
					"course.u_id"=>$uId,
					"course.id"=>$courseId
				],
				"ORDER"=>["course_history.id"=>"DESC"],
				"LIMIT"=>[$page*20, ($page+1)*20]
				]
			);
			foreach ($list as $key => $value) {
				$num = $db->count("history_record",["h_id"=>$value["id"]]);
			$list[$key]["num"] = $num;
			}
		  $this->reasponse(33333, "success", ["list"=>$list]);
			
		}
	  public function getHistoryNum() {
	  	$request = $this->I("json");
	  	$type = $request->type;
	  	$num = 0;
	  	if($type == "course") {
	  		$courseId = $request->courseId;
	  		$db = $this->M();
	  		$num = $db->count("course_history", [
	  			"course_id"=>$courseId
	  		]);
	  	} elseif($type == "all"){
	  		$db = $this->M();
	  		$uId = $this->token($this, $db, $request->token);
			$num = $db->count("course_history", ["[>]course"=>["course_id"=>"id"]], "course_history.id", [
				"course.u_id"=>$uId
			]);
	  	}
		$this->reasponse(33333, "suceess", ["num"=>$num]);
	  }	
	}
?>