<?php 
	namespace Home\Model;
	use Think\Model;
	class TeacherModel extends Model {
		public function getAll() {
			$res = M('Teacher');
			return $res->select();
		}
	}

 ?>