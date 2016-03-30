<?php
namespace Admin\Controller;
use Think\Controller;
class AjaxController extends Controller {
    public function checkloginAction(){

    	$result = D('Admin')->checkLogin(I('username'), I('password'));

    	if (!count($result)) {
    		$result = "nodata";
    	} else {
    		session("admin",$result);
    		$result = "success";
    	}

    	$this->ajaxReturn(array("result" => $result));
    }


    public function checkoutcommentAction() {

        $result = D('Home/Comment')->passComment(I('cid'));

        $this->ajaxReturn(array("result" => $result));

    }

    public function modifymoduleAction() {
        if ((I('open_id') == '' || I('status') == ''))
            return;
        $id = I('open_id');

        $result = M('onopen')->where("open_id='{$id}'")->setField("isopen",I('status'));

        $this->ajaxReturn(array("result" => $result));
    }
}