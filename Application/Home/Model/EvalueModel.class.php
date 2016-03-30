<?php 
	namespace Home\Model;
	use Think\Model;
	class EvalueModel extends Model {
		public function addEvlue($data) {
			$evlue = M('Evalue');
			
			return $evlue->addAll($data);
		}

		public function getEvluedCourseByStudent() {
			$evlue = M('Evalue');
			$student_code = session('student_code');
			return $evlue->fetchSql(false)->field('count(evalue_choose_id) as num')->where("evalue_type=1 and student_code='$student_code'")->select();
		}

		public function getEvluedTeacherByStudent() {
			$evlue = M('Evalue');
			$student_code = session('student_code');
			return $evlue->fetchSql(false)->field('count(evalue_choose_id) as num')->where("evalue_type=2 and student_code='$student_code'")->select();
		}

		public function getEvlueCourseIdByStudent() {
			$evlue = M('Evalue');
			$student_code = session('student_code');
			return $evlue->fetchSql(false)->field('evalue_choose_id')->where("evalue_type=1 and student_code='$student_code'")->select();
		}

		public function getEvlueTeacherIdByStudent() {
			$evlue = M('Evalue');
			$student_code = session('student_code');
			return $evlue->fetchSql(false)->field('evalue_choose_id')->where("evalue_type=2 and student_code='$student_code'")->select();
		}

		public function getEvlueMountByCode($code) {
			$evlue = M('Evalue');

			return $evlue->fetchSql(false)->field('count(evalue_id) as num')->where("evalue_type=1 and student_code='$code'")->select();
		}

	}
?>