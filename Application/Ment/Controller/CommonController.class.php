<?php
namespace Ment\Controller;
use Think\Controller;
class CommonController extends Controller {
    public function _initialize(){
        if(ACTION_NAME=='login' ||ACTION_NAME=='checklogin'||ACTION_NAME=='a'|| ACTION_NAME=='verify_img' ||session('?account')){
            return true;
        }else{ //return true;
            $this->redirect('index/login');exit;
        }
    }
}
