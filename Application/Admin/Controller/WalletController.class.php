<?php
namespace Admin\Controller;
use Think\Controller;
class WalletController extends CommonController
{
    public function getMyWallet()
    {
        /**
         * @接口：我的钱包
         * 
         * @var unknown
         */
        $userid =$_POST['userid'];
        $m = M('wallet');
        $mm = $m->field("yue,bi,couponcount")
            ->where("userid='$userid'")
            ->select();
        echo result(0,$mm[0]);
    }
/**
 */
}