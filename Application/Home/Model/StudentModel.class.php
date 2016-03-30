<?php 
	namespace Home\Model;
	use Think\Model;
	class StudentModel extends Model {
		public function checkLogin($number, $name) {
			$res = M('Student')->where("student_code='$number' and student_name='$name'")->field("student_id,student_department,Graduate_year")->select();
			return $res;
		}

		public function getStudentCourseInfo($student_id) {
			$res = M('Student')->where("student_id='$student_id'")->field("student_course_info")->select();
			return $res;
		}

		public function getStudentCodeByCdcard($cdcard, $name) {
			$res = M('Student')->fetchSql(false)->where("student_cdcard='$cdcard' and student_name='$name'")->field("student_code")->select();
			return $res;
		}

		public function getStudentNameByCode($code) {
			return M('Student')->fetchSql(false)->field('student_name')->where("student_code='$code'")->select();
		}
	}

 ?>