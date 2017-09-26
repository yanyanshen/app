<?php
namespace Manage\Controller;
use Think\Controller;
use Think\Verify;

class IndexController extends CommonController {
    /*
     * 登录login
     */
    public function login(){
	if(isset($_GET['end'])){
            addlog("退出");
        }
        session_destroy();
        session_unset();
        $this->display();
    }
    public function index(){
        $rule=session("role")==0?"管理员":"普通用户";
       // $department=session('department');
        $group=session('masgroup');
		$account=session("account");
        $permiss=M("admin")->field("permissId")->where("account='$account'")->find()['permissid'];
        $per=rtrim($permiss,',');//权限
		switch($group){
			case '1':$masgroup="管理组";break;
			case '2':$masgroup="技术组";break;
			case '3':$masgroup="销售组";break;
			case '4':$masgroup="客服组";break;
			case '5':$masgroup="市场组";break;
		}
        $uname=session("username");
        $this->assign('rule',$rule);
		$this->assign('per',$per);
        $this->assign('masgroup',$masgroup);
        $this->assign('uname',$uname);
        $this->display();
    }
    /*
     * verify生成验证码
     */
    public function verify(){
        $Verify = new Verify();
        $Verify->length=4;
        $Verify->fontSize=18;
        $Verify->imageW=130;
        $Verify->imageH=40;
        $Verify->entry();
    }
    //欢迎页面
    public function welcome(){
        $account=session("account");
        $data=M("adminlog")->field("ntime,nip")->where("account='$account'")->order("id desc")->find();
		 $countlogin=M("adminlog")->where("account='$account'")->count();
        $this->assign('data',$data);
        $this->assign('countlogin',$countlogin);
        //$this->assign('ver',THINK_VERSION);
       // $this->assign('ww',PHP_OS);//操作系统
       // $this->assign('tp',THINK_PATH);
        //$m=new \mysqli('127.0.0.1','root','','xueche',3306);
        //求统计量
        //今日
        $today=date("Y-m-d",time());
        //昨日
        $yesday=date("Y-m-d",strtotime('-1 day'));
        //本月
        $month=date("Y-m",time());
        //上一月
        $yesmonth=date("Y-m",strtotime('-1 month'));
        //当天的分组统计
        $todaycount=M('list')->field("state,count(id) as id")->where("listtime like '%$today%'")->group("state")->select();
        //昨天的分组统计
        $yesdaycount=M('list')->field("state,count(id) as id")->where("listtime like '%$yesday%'")->group("state")->select();
        //当月的分组统计
        $tomonthcount=M('list')->field("state,count(id) as id")->where("listtime like '%$month%'")->group("state")->select();
        //上月的分组统计
        $yesmonthcount=M('list')->field("state,count(id) as id")->where("listtime like '%$yesmonth%'")->group("state")->select();
        $countlist[0]=M('list')->field("count(id) as id")->group("state")->select();
        //总得统计数
        for ($i=0;$i<=4;$i++){
            $countlist[0][$countlist[0][$i]['state']]=(int)$countlist[0][$i]['id'];
            $count+=(int)$countlist[0][$i]['id'];
            $todaystate[$todaycount[$i]['state']]=(int)$todaycount[$i]['id'];
            $yesdaystate[$yesdaycount[$i]['state']]=(int)$yesdaycount[$i]['id'];
            $monthstate[$tomonthcount[$i]['state']]=(int)$tomonthcount[$i]['id'];
            $yesmonthstate[$yesmonthcount[$i]['state']]=(int)$yesmonthcount[$i]['id'];
        }
        $countlist[1]=M('list')->field("count(id) as id")->where("listtime like '%$today%'")->find()['id'];
        $countlist[2]=M('list')->field("count(id) as id")->where("listtime like '%$yesday%'")->find()['id'];
        $countlist[3]=M('list')->field("count(id) as id")->where("listtime like '%$month%'")->find()['id'];
        $countlist[4]=M('list')->field("count(id) as id")->where("listtime like '%$yesmonth%'")->find()['id'];
        //$this->assign('sql',$m->server_info);
        //$this->assign('file',ini_get('upload_max_filesize'));
       // $this->assign('php',PHP_VERSION);
      //  $this->assign('s',ini_get("max_execution_time")."秒");
        $this->assign('todaystate',$todaystate);
        $this->assign('yesdaystate',$yesdaystate);
        $this->assign('monthstate',$monthstate);
        $this->assign('yesmonthstate',$yesmonthstate);
        $this->assign('countlist',$countlist);
        $this->assign('count',$count);
        $this->display();
    }
    /*
     * checkuser判断验证码
     */
    public function checkuser(){
        $Verify=new Verify();
        $acc=$_POST['account'];
        if($acc==''){
          echo "请输入账号";
          return;
        }
        $pass=$_POST['pass'];
        $code=$_POST['code'];
        $m=M("admin");
        $mm=$m->field("count(*),pass,masgroup,flag,username,role")->where("account='$acc'")->find();      
        if($mm['count(*)']==1){
            if($pass==''){
                echo "请输入密码";
                return;
            }
              if($mm['flag']=='y'){
                 if(mym($pass,$acc)==$mm['pass']){
                     $_SESSION['account']=$acc;
                     $_SESSION['role']=$mm['role'];
                     $_SESSION['username']=$mm['username'];
					 $_SESSION['masgroup']=$mm['masgroup'];
                     if($code=='验证码:'){
                         echo "请输入验证码";
                     }elseif($Verify->check($code)){
                         addlog("登录");
                         echo "登录成功";
                     }else{
                         echo "验证码错误！";
                     }
                 }else{
                     echo "密码错误";
                 }
              }else{
                  echo "该帐号已被禁用";
                 
              }
        }else{
           echo "该帐号不存在";
           
       }
    }
}
