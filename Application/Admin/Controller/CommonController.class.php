<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller{
    public function _initialize(){
		$actions=['getpubliclist1','bannercount','sinaauth','cancelsinaauth','alipaycallback','upload1','userisset','mobile','login','check','send','getusername','sends','passreset','verification','adduser','version','getalldynamic','getpubliclist','addconsult','yinlian'];
		$action=ACTION_NAME;
        if(in_array($action,$actions)||(isset($_POST['session_id']) && $_POST['session_id']!='')){        
		 $userid=$_POST['userid'];
                $do='访问';
                $action_name=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                userLogs($userid,$do,$action_name);	
		 return true;
        }else{// return true;
           echo over();
           exit;
        }
    }
}


