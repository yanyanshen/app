<?php
return array(
	'YLID'=>'802310054110791',
'IMAGE_THUMB_SCALE'     =>   1, //等比例缩放类型
    'URL_DENY_SUFFIX' => 'pdf|ico|png|gif|jpg',// URL禁止访问的后缀设置
    'URL_CASE_INSENSITIVE'  =>  true,//url不区分大小写
    //'SHOW_PAGE_TRACE'=>true,
    //'SHOW_PAGE_TRACE'=>true,
    // 开启路由
    //'URL_ROUTER_ON'   => true,
    //'URL_MODEL' => 2,
	//'配置项'=>'配置值'
    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'demo', // 数据库名
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
    'coachpass'=>'244ac348537069c3bfe9d633349b7334',
    //
    //支付宝配置参数
    'alipay_config'=>array(
        //这里是你在成功申请支付宝接口后获取到的PID；
        'partner' =>'2088111222703104',
        'key'=>'',//这里是你在成功申请支付宝接口后获取到的Key
        'sign_type'=>strtoupper('rsa'),
        'input_charset'=> strtolower('utf-8'),
        'cacert'=> getcwd().'\\cacert.pem',
        'transport'=> 'http',
    ),
    //以上配置项，是从接口包中alipay.config.php 文件中复制过来，进行配置；
    
    'alipay'   =>array(
        //这里是卖家的支付宝账号，也就是你申请接口时注册的支付宝账号
        'seller_email'=>'pay@xxx.com',
    
        //这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
        'notify_url'=>'http://www.xxx.com/Pay/notifyurl',
    
        //这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
        'return_url'=>'http://www.xxx.com/Pay/returnurl',
    
        //支付成功跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参payed（已支付列表）
        'successpage'=>'User/myorder?ordtype=payed',
    
        //支付失败跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参unpay（未支付列表）
        'errorpage'=>'Member/myorder?ordtype=unpay',
    ),
);
