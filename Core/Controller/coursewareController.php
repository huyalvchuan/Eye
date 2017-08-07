<?php
class coursewareController extends Eye {
	function add() {
		$request = $this->I("json");
		$request->coursewareArray = json_decode(json_encode($request->coursewareArray),true);
		$this->mylog($request->coursewareArray["url"].$request->coursewareArray["courseware_name"].$request->coursewareArray["course_id"]);
		$courseware = $request->coursewareArray;
		$db = $this->M();
		$this->mylog($db->error());
		$url = $request->coursewareArray["url"];
		$type = explode('.',$url);
		$request->coursewareArray["courseware_name"] =  	$request->coursewareArray["courseware_name"].".".$type[count($type)-1];
		$coursewareId = $db->insert("courseware", $request->coursewareArray );
		$this->reasponse(40003, "success", $coursewareId);
	}
	function delete() {
		$request = $this->I("json");
		$coursewareId = $request->id;
		$db = $this->M();
		$db->delete("courseware", [
			"id"=>$coursewareId
		]);
		$this->reasponse(44444, "删除成功", $coursewareId);
	}
	function getList() {
		$request = $this->I("json");
		$courseId = $request->id;
		$db = $this->M();
		$list = $db->select("courseware", "*", [
			"course_id"=>$courseId
		]);
		$this->reasponse(44444, "查询成功", $list);
	}
	function getInfo () {
		$request = $this->I("json");
		$coursewareId = $request->id;
		$db = $this->M();
		$courseware = $db->select("courseware", "*", [
			"id"=>$coursewareId
		]);
		$this->reasponse(44444, "查询成功", $courseware[0]);
	}
}
?>