<?php 
	namespace Admin\Model;
	use Think\Model;
	class AdminModel extends Model {
		public function checkLogin($username, $password) {
			return M('Admin')->fetchSql(false)->field('*')->where("admin_name='{$username}' and admin_password='{$password}'")->select();
		}
	}

 ?>