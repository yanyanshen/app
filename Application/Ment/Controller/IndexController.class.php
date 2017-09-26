<?php
namespace Ment\Controller;
use Think\Controller;
use Think\Verify;
class IndexController extends CommonController {
    //验证码
    public function verify_img(){
        $img=new Verify();
        $img->length=6;
        $img->fontSize=10;
        $img->imageW=100;
        $img->imageH=30;
        $img->useNoise=false;
        $img->entry();
    }
    //验证登录
    public function checklogin($account,$pass,$code){
        $img=new Verify();
        if($img->check($code)){
            $ad=M('admin');
            $admin=$ad->field('id,pass,username,ntime,masgroup,flag,username,role,logintimes,permissid')->where("account='$account'")->find();

//file_put_contents('/a/x.txt',mym($pass,$account).'--'.$admin['pass']);
            if($admin){
                if($admin['flag']=='y'){
//file_put_contents('/a/x.txt',mym($pass,$account).'--'.$admin['pass']);
                    if(mym($pass, $account)==$admin['pass']){
                        $ad->where("account='$account'")->setInc("logintimes",1);
                        session('account',$account);
                        session('id',$admin['id']);
                        session('username',$admin['username']);
                        session('ntime',$admin['ntime']);
                        session('masgroup',$admin['masgroup']);
                        session('logintimes',$admin['logintimes']+1);
                        session('role',$admin['role']);
                        session('logintime',date("Y-m-d H:i:s"));
                        session('permissid',$admin['permissid']);
                        managelog($account,$admin['username'],'登录');
                        echo 0;
                    }else{
                        $info='密码错误';
                    }
                }else{
                    $info='该帐号已被禁用';
                }
            }else{
                $info='该帐号不存在';
            }  
        }else{
            $info='验证码错误';
        }
        echo $info;
    }
    public function right(){
        $this->assign('time',date('Y-m-d H:i:s'));
        $this->display();
    }
    //退出
    public function logout(){
        session('[destroy]');   //thinkphp中清除session方法
        $this->redirect('login',array(),3,'您已安全退出...');   //完成后跳转到相应的页
    }
}
