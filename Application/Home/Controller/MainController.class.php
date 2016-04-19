<?php
namespace Home\Controller;
use Think\Controller;
class MainController extends WeixinController {

	public function __construct()  
    {  
        parent::__construct();
        if (!session("student_id")) {
            
            $this->display("Index/yindao");
            exit;
        } else {
            $status = session('student_status');
            if ($status != 1)
                exit;
        }
    }  
    /*
    **
    ** 对应main.html
    **
    */
    public function yindaoAction() {
        $this->mainAction();
    }
	public function mainAction() {

        $main = D('Student');
        $res = $main->getStudentCourseInfo(session("student_id"));

        $evalue = D('Evalue');
        $evluedcoursenum = $evalue->getEvluedCourseByStudent();
        //print_r($evluedcoursenum);
        
        $student_course_info = json_decode($res[0]['student_course_info']);
        $courseamount = count($student_course_info->b) + count($student_course_info->c) + count($student_course_info->d) - $evluedcoursenum[0]['num'];
        

        $teacher = $this->getTeacherArray($student_course_info);
        $evluedteachernum = $evalue->getEvluedTeacherByStudent();
        
        $teacheramount = count($teacher) - $evluedteachernum[0]['num'];

        $modules = $this->getModules();

        //print_r($modules);

        $this->assign("student_code",session("student_code"));
        $this->assign("courseamount",$courseamount);
        $this->assign("teacheramount",$teacheramount);
        $this->assign("modules",$modules);

        $this->display('main');
    }
    /*
    **
    ** 对应class.html
    **
    */
    public function classAction() {

    	$main = D('Student');
        $res = $main->getStudentCourseInfo(session("student_id"));
        $student_course_info = json_decode($res[0]['student_course_info']);

        $evalue = D('Evalue');
        $course_id_array = $evalue->getEvlueCourseIdByStudent();
        //print_r($course_id_array);

        $course = array();
        foreach ($student_course_info->b as $item) {
        	$course[intval($item->c_id,10)] = $item;
        }
        foreach ($student_course_info->c as $item) {
        	$course[intval($item->c_id,10)] = $item;
        }
        foreach ($student_course_info->d as $item) {
        	$course[intval($item->c_id,10)] = $item;
        }
        //print_r($course);

        foreach ($course_id_array as $item) {
        	 unset($course[$item['evalue_choose_id']]);
        }
        

        shuffle($course);
        //print_r($course);

        $courseamount = count($course);

        $num = (5 < $courseamount) ? 5 : $courseamount;
        if ($num == 5) {
        	array_splice($course, $num);
        }

        $this->generateSession();

        $this->assign("course", $course);
        $this->assign("courseamount", $courseamount);
        $this->assign("autoi",1);

        $this->display('class');
    }


	/*
    **
    ** 对应teacher.html
    **
    */
	public function teacherAction() {

        $temp = $this->getModules();

        if (!$temp['teacher']){
            echo "该模块已关闭";
            exit;
        }


		$main = D('Student');
        $res = $main->getStudentCourseInfo(session("student_id"));
        $student_course_info = json_decode($res[0]['student_course_info']);

        $teacher = $this->getTeacherArray($student_course_info);

        $evalue = D('Evalue');
        $teacher_id_array = $evalue->getEvlueTeacherIdByStudent();
        
        foreach ($teacher_id_array as $item) {
        	 unset($teacher[$item['evalue_choose_id']]);
        }
        shuffle($teacher);
        //print_r($course);

        $teacheramount = count($teacher);

        $num = (6 < $teacheramount) ? 6 : $teacher;
        if ($num == 6) {
        	array_splice($teacher, $num);
        }

        $this->assign("teacher", $teacher);
        $this->assign("teacheramount", $teacheramount);
        $this->assign("autoi",1);

		$this->display();
	}



    /*
    ** 
    ** AJAX方法
    **
    */

    /*
	**
    ** 评论课程 添加入库
	**
	*/
    public function evluecourseAction() {

    	if (!session('student_code'))
    		return;

        $token = I('token');
        if ($token == session("token")) {
            $this->destroySession();
        } else {
            $this->ajaxReturn(array("status" => "overlap"));
        }

    	$evlue = json_decode($_POST['evlue']);
    	//$data['evlue'] = $evlue;
    	//print_r($evlue);
    	foreach ($evlue as $item) {
    		$data1['evalue_choose_id'] = $item->id;
    		$data1['evalue_grade'] = $item->score;
    		$data1['evalue_type'] = $item->type;
    		$data1['comment_typechoose'] = $item->comment_type;
    		$data1['student_code'] = session('student_code');
    		$data1['evalue_date'] = time();
    		$data[] = $data1;
    	}
    	

    	$evalue = D('Evalue');
    	$res = $evalue->addEvlue($data);

        $temp = $this->getModules();
        if ($temp['teacher'])
            $teacher = true;
        else 
            $teacher = false;

    	if ($res) {
    		$this->ajaxReturn(array("status" => "success","teacher" => $teacher));    
    	} else {
    		$this->ajaxReturn(array("status" => "fail"));
    	}
    }

    public function evlueteacherAction() {

    	if (!session('student_code'))
    		return;

    	$evlue = json_decode($_POST['evlue']);
    	//$data['evlue'] = $evlue;
    	//print_r($evlue);
    	foreach ($evlue as $item) {
    		$data1['evalue_choose_id'] = $item->id;
    		$data1['evalue_grade'] = $item->score;
    		$data1['evalue_type'] = $item->type;
    		$data1['comment_typechoose'] = $item->comment_type;
    		$data1['student_code'] = session('student_code');
    		$data1['evalue_date'] = time();
    		$data[] = $data1;
    	}
    	

    	$evalue = D('Evalue');
    	$res = $evalue->addEvlue($data);

        $temp = $this->getModules();

    	if ($res) {
    		$this->ajaxReturn(array("status" => "success"));    
    	} else {
    		$this->ajaxReturn(array("status" => "fail"));
    	}
    }


    
    /*
    ** 
    ** 私有方法
    **
    */

    /*
    **
    **	获取教师的数组
    **
    */
    function getTeacherArray($student_course_info) {
    	$teacher = array();
    	foreach ($student_course_info->b as $item) {
        	if (in_array($item->t_id,$teacher) || ($item->t_id < 0))
        		continue;
        	$teacher[$item->t_id] = $item;
        }
        foreach ($student_course_info->c as $item) {
        	if (in_array($item->t_id,$teacher) || ($item->t_id < 0))
        		continue;
        	$teacher[$item->t_id] = $item;
        }
        foreach ($student_course_info->d as $item) {
        	if (in_array($item->t_id,$teacher) || ($item->t_id < 0))
        		continue;
        	$teacher[$item->t_id] = $item;
        }

        return $teacher;
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

    /*
    **
    ** 生成session防止重复提交表单
    **
    */

    function generateSession() {
        session("token",mt_rand(10000,99999));
        $this->assign("token",session("token"));
    }

    function destroySession() {
        session("token",null);
    }
}

?>