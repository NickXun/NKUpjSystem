<?php
namespace Admin\Controller;
use Think\Controller;
class ManageController extends Controller {

    private $evalueType = array('未选择','考研更有用','工作更有用','出国更有用','感觉没啥用');

    public function __construct()  
    {  
        parent::__construct();  
        if (!session("admin")) {
            $this->display("Index/login");
            exit;
        }

        $admin = session('admin');
        $this->assign('admin_right',$admin[0]['admin_right']);
        $this->assign('admin_name',$admin[0]['admin_name']);
    }  

    public function indexAction() {
        
        

        $this->table_indexAction();
    }

    public function table_indexAction() {
        $onopen = M('onopen')->field('*')->select();

        //print_r($onopen);
        $this->assign("onopen",$onopen);

        $this->display('table_index');
    }

    public function table_commentAction() {

        $Comment = M('Comment');


        $count = $Comment->alias('c')->fetchSql(true)->join('jwc_department d on c.comment_department_id=d.department_code')->join('jwc_student s on s.student_code=c.comment_student_id')->field('c.*,d.*,s.student_name')->order('c.comment_time desc')->count();

        $Page = new \Think\Page($count,10);
        $Page->setConfig('header','条评论');
        $show = $Page->show();
        $list = $Comment->alias('c')->fetchSql(false)->join('jwc_department d on c.comment_department_id=d.department_code')->join('jwc_student s on s.student_code=c.comment_student_id')->field('c.*,d.*,s.student_name')->order('c.comment_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('list',$list);


        $this->assign('page',$show);
        $this->assign('auto',1);

        $this->display('table_comment');
    }

    public function table_excelAction($type) {
        import("Org.Util.PHPExcel");

        $catalog = array(
            "yskcbg" => "原始课程表格",
            "ysjsbg" => "原始教师表格",
            "kctj" => "课程统计",
            "jstj" => "教师统计",
            "pltj" => "主观信息及校友信息",
            "cydtj" => "参与度统计"
            );

        

         header('Content-Type: application/vnd.ms-excel');
         header('Content-Disposition: attachment;filename="'.$catalog[$type].'-'.time().'.xlsx"');
         header('Cache-Control: max-age=0');
         
         $this->$type();
    }

    function yskcbg() {
        $PHPExcel = new \PHPExcel();

        $PHPExcel->setActiveSheetIndex(0)
                          ->setCellValue('A1', '学号')    
                          ->setCellValue('B1', '姓名')
                          ->setCellValue('C1', '学院')
                          ->setCellValue('D1', '课程编号')
                          ->setCellValue('E1', '课名')
                          ->setCellValue('F1', '评分')
                          ->setCellValue('G1', '使用价值')
                          ->setCellValue('H1', '毕业年份')
                          ->setCellValue('I1', '评价时间');

        $result = M('Evalue')->alias('e')->join('jwc_student s on e.student_code=s.student_code')->join('jwc_department d on s.student_department=d.department_code')->join('jwc_course c on e.evalue_choose_id=c.course_unicode')->field('e.student_code,s.student_name,d.department_name,c.course_unicode,c.course_name,e.evalue_grade,e.comment_typechoose,graduate_year,e.evalue_date')->where("e.evalue_type=1")->select();

     
        $num = 2;
        foreach ($result as $item) {
            $PHPExcel->setActiveSheetIndex(0)
                          ->setCellValue('A'.$num, $item['student_code'])    
                          ->setCellValue('B'.$num, $item['student_name'])
                          ->setCellValue('C'.$num, $item['department_name'])
                          ->setCellValue('D'.$num, $item['course_unicode'])
                          ->setCellValue('E'.$num, $item['course_name'])
                          ->setCellValue('F'.$num, $item['evalue_grade'])
                          ->setCellValue('G'.$num, $this->evalueType[$item['comment_typechoose']])
                          ->setCellValue('H'.$num, $item['graduate_year'])
                          ->setCellValue('I'.$num, date("Y-m-d H:m:s", $item['evalue_date']));
            $num++;
        }

        

         import("Org.Util.PHPExcel.IOFactory");
         $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
         $objWriter->save('php://output');
         exit;
    }


    function ysjsbg() {
        $PHPExcel = new \PHPExcel();

        $PHPExcel->setActiveSheetIndex(0)
                          ->setCellValue('A1', '学号')    
                          ->setCellValue('B1', '姓名')
                          ->setCellValue('C1', '学院')
                          ->setCellValue('D1', '教师编号')
                          ->setCellValue('E1', '教师姓名')
                          ->setCellValue('F1', '评分')
                          ->setCellValue('G1', '毕业年份')
                          ->setCellValue('H1', '评价时间');

        $result = M('Evalue')->alias('e')->join('jwc_student s on e.student_code=s.student_code')->join('jwc_department d on s.student_department=d.department_code')->join('jwc_teacher t on e.evalue_choose_id=t.teacher_uuid')->where("e.evalue_type=2")->field('e.student_code,s.student_name,d.department_name,t.teacher_uuid,t.teacher_name,e.evalue_grade,graduate_year,e.evalue_date')->select();

        $num = 2;
        foreach ($result as $item) {
            $PHPExcel->setActiveSheetIndex(0)
                          ->setCellValue('A'.$num, $item['student_code'])    
                          ->setCellValue('B'.$num, $item['student_name'])
                          ->setCellValue('C'.$num, $item['department_name'])
                          ->setCellValue('D'.$num, "'".$item['teacher_uuid'])
                          ->setCellValue('E'.$num, $item['teacher_name'])
                          ->setCellValue('F'.$num, $item['evalue_grade'])
                          ->setCellValue('G'.$num, $item['graduate_year'])
                          ->setCellValue('H'.$num, date("Y-m-d H:m:s", $item['evalue_date']));
            $num++;
        }

        

         import("Org.Util.PHPExcel.IOFactory");
         $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
         $objWriter->save('php://output');
         exit;
    }
}