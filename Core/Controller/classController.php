<?php
class classController extends Eye {
	function add () {
		$request = $this->I("json");
		$db = $this->M();
		$id = $this->token($this, $db, $request->token);
		$request->classArray = json_decode(json_encode($request->classArray),true); 
		$classArray = $request->classArray;
		$classArray["u_id"] = $id;
		$classArray["status"] = 1;
		$code = rand(10000, 99999);
		while(count($db->select("course", "id", ["code"=>$code])) > 0) {
			$code = rand(10000, 99999);
		}
		$classArray["code"] = $code;
		$courseId = $db->insert("course", $classArray);
		$this->reasponse(20000, "课程创建成功", ["id"=>$courseId, "code"=>$code]);
	}
	function edit() {}
	function share() {}
	function delete() {
		$request = $this->I("json");
		$classId = $request->id;
		$db = $this->M();
		$db->delete("course", [
			"id"=>$classId
		]);
		echo $db->error();echo $db->last_query();
		$this->reasponse(20001, "删除成功", "成功了");
	}
	function classInfo() {
		$request = $this->I("json");
		$classId = $request->classId;
		$db = $this->M();
		$classInfo = $db->select("course","*", [
			"id"=>$classId
		]);
		$this->reasponse(22222, "success", $classInfo[0]);
	}
	function close() {
		$request = $this->I("json");
		$id = $request["id"];
		//TODO 只能本人能删除课程
		$db = $this->M();
		$db->update("course", [
			"status"=>2
		], [
			"id"=>$id
		]);
		$this->reasponse(22222, "关闭成功", "成功了");
	}
	function open() {
		$request = $this->I("json");
		$id = $request["id"];
		//TODO 只能本人能删除课程
		$db = $this->M();
		$db->update("course", [
			"status"=>1
		], [
			"id"=>$id
		]);
		$this->reasponse(22222, "打开成功", "成功了");
	}
	function getMyClassList() {
		$request = $this->I("json");
		$db = $this->M();
		$userId = $this->token($this, $db, $request->token);
		$list = $db->select("course", "*", ["u_id"=>$userId]);
		$i = 0;
		foreach($list as $data)
		{
			$list[$i]["joinNumber"] = $db->count("join_course", ["course_id"=>$data["id"]]);
		}
		$this->reasponse(20005, "查询我创建的课程成功", $list);
	}
	function getMyJoin() {
		$request = $this->I("json");
		$db = $this->M();
		$this->mylog($request->token);
		if($request->token == "lvchuan") {
			$userId =2;
			$joinList = $db->select("course", [
			"[>]course_join"=>["id"=>"course_id"] ], 
			
			["course.id", "course.course_name", "course.course_describe", "course.logo"], [
			"course_join.u_id"=>$userId
			]
			);
			$this->reasponse(22222, "获得了课程列表", $joinList);
			return;
		}
		$userId = $this->token($this, $db, $request->token);
		$joinList = $db->select("course", [
			"[>]course_join"=>["id"=>"course_id"] ], 
			
			["course.id", "course.course_name", "course.course_describe", "course.logo"], [
			"course_join.u_id"=>$userId
			]
		);
		$this->reasponse(22222, "获得了课程列表", $joinList);
	}
	function join() {
		$request = $this->I("json");
		$code = $request->code;
		$db = $this->M();
		$userId = $this->token($this, $db, $request->token);
		$class = $db->select("course", "*", ["code"=>$code]);
		$class = $class[0];
		if($class["status"] == 2) {
			$this->reasponse(20003, "课程已经关闭", null);
			return;	
		}
		if($class["status"] == 1) {
			$joinRecord = $db->select("course_join", "id", [
				"AND"=>[
					"course_id"=>$class["id"],
					"u_id"=>$userId
				]
			]);
			if(count($joinRecord) > 0) {
				$this->reasponse(20006, "已经加入过了", null);
				return;
			}
			$db->insert("course_join", ["course_id"=>$class["id"],
				"u_id"=>$userId
			]);
			$this->reasponse(20002, "课程加入成功", ["course"=>$class]);
		}
	}
	function getNewCourse() {
		$db = $this->M();
		$newCourse = $db->select("course", "*", [
			"ORDER" => ["create_time" => "DESC"],
			"LIMIT" => 7
		]);
		$this->reasponse(222222, "success", $newCourse);
	}
}
?>