<?php
//项目配置文件
return array(
	//'URL_HTML_SUFFIX'=>'shtml',
    
	'URL_DENY_SUFFIX' => 'pdf|ico|png|gif|jpg',
	'ACTION_SUFFIX'         =>  'Action',
    'url_model'          => '1', //URL模式
    'session_auto_start' => true, //是否开启session
    //更多配置参数
    //数据库配置1
    	'db_type'  => 'sqlite',
    	// 'db_user'  => 'root',
    	// 'db_pwd'   => 'root',
    	// 'db_host'  => 'localhost',
    	// 'db_port'  => '3306',
    	'db_name'  => '/Applications/MAMP/db/sqlite/1456322083.db',
        //'db_name'  => DATA_PATH.'/1456322083.db',
    	'db_charset'=>    'utf8',
    	'DB_PREFIX' => 'jwc_', // 数据库表前缀 
    //...
);
