<?php
return array(
	//'配置项'=>'配置值'
    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'xueche2', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => 'root', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PARAMS' =>  array(), // 数据库连接参数
    'DB_PREFIX' => 'xueche1_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'conf'=>array(
        'big'=>'./Upload/big',//用户头像原图的位置
        'small'=>'./Upload/small',////用户头像原图缩略图的位置
        'rq'=>'./Upload/rq',
        'coach'=>'./Upload/coach/',//教练的照片的路径
        'guider'=>'./Upload/guider/',//指导员的照片的位置
        'school'=>'./Upload/yingye/',//存放驾校的营业执照
        'schoolupimg'=>'./Upload/school',//存放驾校的展示图片
        'coachupimg'=>'./Upload/coach/me',//教练上传个人展示图片的路径
        'guiderupimg'=>'./Upload/guider/me',//指导员的個人展示照片的位置
        'url'=>"http://Sms.1898cn.com/WS/Send.aspx",//给手机发短信的链接
        'udynamicurl'=>"./Upload/userdynamic",
        'uid'=>'517xc',
        'pwd'=>'517xueche?',
        'ip'=>"http://www.517xc.com"
    ),
  // 'PASS'=>'244ac348537069c3bfe9d633349b7334'
);
