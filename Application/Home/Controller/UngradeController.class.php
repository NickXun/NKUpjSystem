<?php
namespace Home\Controller;
use Think\Controller;
class UngradeController extends WeixinController {

	public function __construct()  
    {  
        parent::__construct();
        if (!session("student_id")) {
            $status = session('student_status');
            if ($status != 2)
                exit;
            $this->display("Index/index");
            exit;
        }
    }  

	public function yindaoAction() {
		$this->untarget_user();
	}

	public function untarget_user() {

		$modules = $this->getModules();
		$this->assign("modules",$modules);

		$this->display();
	}


	function getModules() {
        $result = M('Onopen')->field("*")->select();

        $temp = array();

        foreach ($result as $key) {
            # code...
            $temp[$key['module_name']] = $key['isopen'];
        }

        return $temp;
    }
}