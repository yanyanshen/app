<?php
namespace Home1\Controller;
use Think\Controller;
use Org\Util\Page;
class AdminController extends CommonController {

   public function admin_add(){     
       $this->display();
   }
   /**
    * 获得管理员列表
    */
   public function adminList(){
       $Data=M('admin'); // 实例化Data数据对象
       $count=$Data->count();// 查询满足要求的总记录数 $map表示查询条件
       $Page=new Page($count);// 实例化分页类 传入总记录数
       $show=$Page->show();// 分页显示输出
       // 进行分页数据查询
       $list = $Data->field("id,account,username,phone,department,role,ntime,flag,note")->limit($Page->firstRow.','.$Page->listRows)->select();
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
   }
   /**
    * 编辑管理员信息
    */
    public function admininfo(){
        $id=I('get.id');
        $adinfo=M('admin')->field('account,username,department,phone,email,role,flag,note')->where("id=$id")->select();
        $this->assign("admininfo",$adinfo[0]);
        $this->display();
    }
}