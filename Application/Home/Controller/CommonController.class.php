<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {

	public function __construct()  
    {  
        parent::__construct();
        if (!session("student_id")) {
            $this->display("Index/yindao");
            exit;
        }
    } 

    public function participateAction() {
        //查出每个学院的总人数
        $department_num = M('Student')->alias('s')->join('__DEPARTMENT__ d on d.department_code=s.student_department')->where('s.Graduate_year<2015')->group('s.student_department')->field('count(s.student_id) as num,d.department_name,d.department_code')->select();

        //查处评论中各院人数
        $evalue_num = M('Evalue')->alias('e')->join('__STUDENT__ s on e.student_code=s.student_code')->join('__DEPARTMENT__ d on d.department_code=s.student_department')->where('s.Graduate_year<2015')->group('s.student_department')->field('count(distinct(s.student_id)) as num,d.department_name,d.department_code')->select();

        //print_r($evalue_num);

        $finalresult = array();
        foreach ($department_num as $item) {
            $finalresult[$item['department_code']] = $item;
            $finalresult[$item['department_code']]['enum'] = 0; 
        }

        foreach ($evalue_num as $item) {
            $finalresult[$item['department_code']]['enum'] = $item['num'];
            $finalresult[$item['department_code']]['percent'] = round(($finalresult[$item['department_code']]['enum'] / $finalresult[$item['department_code']]['num']) * 100,2)."%";
        }

        $this->assign("process", $finalresult);

        $this->display("Common/participate");
    }

    public function lotteryAction() {
        $student_code = session('student_code');
        $this->assign("student_code",$student_code);
        $this->display();
    }


    public function adviseAction() {
        //echo session("student_department");
        $this->display();
    }

    public function hotcommentAction() {

        $comment = D('Comment');

        $data = $comment->getAllComment();
        $this->assign('comment',$data);

        $this->display();
    }


    public function hotteacherAction() {

        $Model = M('Evalue');
        //$student_code = session('student_code');
        $schoolteacher = $Model->fetchSql(false)->alias('e')->join("__TEACHER__ t on e.evalue_choose_id=t.teacher_uuid")->field('count(e.evalue_grade) as amount,avg(e.evalue_grade) as grade,e.evalue_id,e.evalue_choose_id,t.teacher_name')->where("e.evalue_type=2")->group('e.evalue_choose_id')->order('grade desc,amount desc')->limit(8)->select();
        //print_r($schoolteacher);
        

        $student_department = session('student_department');
        $departmentteacher = $Model->fetchSql(false)->alias('e')->join("__TEACHER__ t on e.evalue_choose_id=t.teacher_uuid")->field('count(e.evalue_grade) as amount,avg(e.evalue_grade) as grade,e.evalue_id,e.evalue_choose_id,t.teacher_name')->where("e.evalue_type=2 and t.teacher_deparment_code='$student_department'")->group('e.evalue_choose_id')->order('grade desc,amount desc')->limit(8)->select();

        //print_r($departmentteacher);

        $this->assign("schoolteacher",$schoolteacher);
        $this->assign("departmentteacher",$departmentteacher);
        $this->assign("autoi1",1);
        $this->assign("autoi2",1);
        $this->display();
    }

    public function hotclassAction() {

        $Model = M('Evalue');

        $schoolcourse = $Model->fetchSql(false)->alias('e')->join("__COURSE__ c on e.evalue_choose_id=c.course_unicode")->field('count(e.evalue_grade) as amount,avg(e.evalue_grade) as grade,e.evalue_id,e.evalue_choose_id,c.course_name')->where("e.evalue_type=1")->group('e.evalue_choose_id')->order('grade desc,amount desc')->limit(8)->select();

        $student_department = session('student_department');

        $departmentcourse = $Model->fetchSql(false)->alias('e')->join("__COURSE__ c on e.evalue_choose_id=c.course_unicode")->field('count(e.evalue_grade) as amount,avg(e.evalue_grade) as grade,e.evalue_id,e.evalue_choose_id,c.course_name')->where("e.evalue_type=1 and course_depart_id='$student_department'")->group('e.evalue_choose_id')->order('grade desc,amount desc')->limit(8)->select();

        //print_r($schoolcourse);
        $this->assign("autoi1",1);
        $this->assign("autoi2",1);
        $this->assign("schoolcourse",$schoolcourse);
        $this->assign("departmentcourse",$departmentcourse);

        $this->display();
    }

//ajax


    public function adviseinfoAction() {
        $info = I('info');
        
        $comment = D('comment');
        $data['comment_department_id'] = session('student_department');
        $data['comment_student_id'] = session('student_code');
        $data['comment_info'] = $info;
        $data['comment_time'] = time();

        $res = $comment->insertComment($data);
        if ($res) {
            $this->ajaxReturn(array("status" => 1));
        } else {
            $this->ajaxReturn(array("status" => 0));
        }
    }

    public function addzanAction() {
        $id = I('comment_id');
        $res = M('Comment')->query("update jwc_comment set comment_zan=comment_zan+1 where comment_id='$id'");
    }

    public function subzanAction() {
        $id = I('comment_id');
        $res = M('Comment')->query("update jwc_comment set comment_zan=comment_zan-1 where comment_id='$id'");
    }

    public function addlotteryAction() {

        $data['student_id'] = session("student_code");

        if (!$data['student_id']) {
            return;
        }

        $prise = D('Prise');
        $res = $prise->selectPriseById();
        if(count($res)) {
            $result['result'] = 2;
            $this->ajaxReturn($result);
            return;
        }

        $data['career'] = I('career');
        $data['telephone'] = I('phone');
        $data['email'] = I('email');
        $data['hangye'] = I('hangye');
        $data['time'] = time();

        
        $res = $prise->addPrise($data);

        if($res) {
            $result['result'] = 1;
        } else {
            $result['result'] = 0;
        }

        $this->ajaxReturn($result);
    }

}