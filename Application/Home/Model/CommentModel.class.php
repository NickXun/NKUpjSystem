<?php 
	namespace Home\Model;
	use Think\Model;
	class CommentModel extends Model {
		public function insertComment($data) {
			return M('Comment')->add($data);
		}

		public function getAllComment() {
			return M('Comment')->where('comment_ispass=1')->order('comment_zan desc')->select();
		}

		public function passComment($cid) {
			return M('Comment')->where("comment_id='{$cid}'")->setField('comment_ispass',1);
		}

	}

?>