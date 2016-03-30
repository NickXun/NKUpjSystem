<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function indexAction(){
    	$this->display('login');    
    }

    public function logoutAction() {
    	session("admin",null);
    	$this->display('login');
    }
}