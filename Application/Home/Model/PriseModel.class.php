<?php 
	namespace Home\Model;
	use Think\Model;
	class PriseModel extends Model {
		public function addPrise($data) {
			return M('Prise')->add($data);
		}

		public function selectPriseById() {
			return M('Prise')->fetchSql(false)->field('prise_id')->where("student_id='".session('student_id')."'")->select();
		}
	}

 ?>