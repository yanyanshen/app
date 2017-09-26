<?php
namespace Home1\Controller;
use Think\Controller;
use Think\Verify;

class IndexController extends CommonController {
    /*
     * 登录login
     */
    public function login(){
        session_destroy();
        session_unset();
        //dump($_SESSION);
        $this->display();
    }
    public function index(){
//         if(!isset($_SESSION)){
//             $this->redirect('index/login');
//             return;
//         }

        $rule=session("rule")==0?"超级管理员":"普通管理员";
        $uname=session("username");
        $this->assign('rule',$rule);
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
        $this->assign('data',$data);
        $this->assign('ver',THINK_VERSION);
        $this->assign('ww',PHP_OS);//操作系统
        $this->assign('tp',THINK_PATH);
        $m=new \mysqli('127.0.0.1','root','','xueche',3306);
        $this->assign('sql',$m->server_info);
        $this->assign('file',ini_get('upload_max_filesize'));
        $this->assign('php',PHP_VERSION);
        $this->assign('s',ini_get("max_execution_time")."秒");
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
        $mm=$m->field("count(*),pass,department,flag,username")->where("account='$acc'")->select();      
        if($mm[0]['count(*)']==1){
            if($pass==''){
                echo "请输入密码";
                return;
            }
              if($mm[0]['flag']=='y'){
                 if(md5($pass)==$mm[0]['pass']){
                     $_SESSION['account']=$acc;
                     $_SESSION['rule']=$mm[0]['rule'];
                     $_SESSION['username']=$mm[0]['username'];
                     if($code=='验证码:'){
                         echo "请输入验证码";
                     }elseif($Verify->check($code)){
                         $this->addlog("登录");
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