<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends WeixinController {
    public function indexAction(){
        if (isset($_GET['studentcode'])) {
            $this->assign('studentcode',$_GET['studentcode']);
            $this->assign('name',$_GET['name']);
        }
        $this->display('index');
    }

    public function findidAction() {
        $this->display();
    }

    public function logincheckAction() {
        $post_array = I('post.');
        $number = $post_array['number'];
        $name = $post_array['name'];
        if ($name && $number) {
            $student = D('Student');
            $res = $student->checkLogin($number, $name);
            if ($res) {
                session("student_id",$res[0]['student_id']);
                session("student_code",$number);
                session("student_department",$res[0]['student_department']);
                $year = date("Y");
                if ($res[0]['graduate_year'] <= $year)
                    $data['status'] = 1; //1-可评教人员
                else
                    $data['status'] = 2; //2-非毕业生
                session("student_status",$data['status']);
                $data['content'] = "登陆成功";
                $this->ajaxReturn($data);
            } else {
                $data['status'] = 0;
                $data['content'] = "学号或姓名错误";
                $this->ajaxReturn($data);
            }
        } else {
            $data['status'] = 0;
            $data['content'] = "学号或姓名错误";
            $this->ajaxReturn($data);
        }
    }

    public function studentcodeAction() {
        $cdcard = I('cdcard');
        $name = I('name');

        //$data['cdcard'] = $cdcard;
        //$data['name'] = $name;

        $student = D('Student');
        $student_code = $student->getStudentCodeByCdcard($cdcard, $name);

        $data['student_code'] = $student_code[0]['student_code'];
        //print_r($student_code[0]);
        if ($data['student_code']) {
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $data['status'] = 0;
            $this->ajaxReturn($data);
        }
    }

    public function shareAction() {
        $student_code = I('id');

        $evalue = D('Evalue');
        $data = $evalue->getEvlueMountByCode($student_code);

        $student = D('Student');
        $name = $student->getStudentNameByCode($student_code);
        $name = $name[0]['student_name'];
        //print_r($data);
        $num = $data[0]['num'];
        //echo $num;

        //选出第几个评论的
        $pos = M('Evalue')->where('evalue_date<(select min(evalue_date) from jwc_evalue where student_code="'.$student_code.'")')->field('count(distinct(student_code)) as pos')->select();

        $percent = $this->culpercent($num);

        $this->assign('pos', $pos[0]['pos']);
        $this->assign('student_code',$student_code);
        $this->assign('name',$name);
        $this->assign('num',$num);
        $this->assign('percent',$percent);

        $comment = D('Comment');

        $data = $comment->getAllComment();
        $this->assign('comment',$data);

        $this->display();
    }

    public function yindaoAction() {
        $this->display();
    }

    function culpercent($num) {
        $percent = 60;
        $num = intval($num);
        if ($num == 0)
            return 0;
        else if ($num > 0 && $num <= 10) {
            $percent += $num + 2 + rand(0,100) / 100;
        } else if ($num > 10 && $num <= 20) {
            $percent += $num + 5 + rand(0,100) / 100;
        } else if ($num > 20) {
            $percent += 25 + $num / 25;
        }
        return $percent;
    }
}
