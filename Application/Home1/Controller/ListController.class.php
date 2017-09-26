<?php
namespace Home1\Controller;
use Think\Controller;
use Org\Util\Page;
class ListController extends CommonController {
   public function orderList(){
      
       $Data=M('list l'); // 实例化Data数据对象
       $count=$Data->count();// 查询满足要求的总记录数 $map表示查询条件
       $Page=new Page($count,10);// 实例化分页类 传入总记录数
       $show=$Page->show();// 分页显示输出
       // 进行分页数据查询
       $field="u.nickname,l.objectid,l.phone,l.returndate,l.lastupdate,l.note,l.customer,l.liststate,l.paymethod,l.paytime,l.masterid,l.id,l.masname,l.stucount,l.applymode,l.price,l.address,l.remark,l.mode,l.description,l.paidnum,l.couponmode,l.listid,l.listname,l.listtime,l.state,l.classid,l.description,l.flag,t.name";
       if(isset($_GET['type'])){
           if($_GET['type']==1){
               $query=$_POST['query'];
               $where=$this->questQuery($query);
               $count=$Data->join("xueche1_user u on u.userid=l.masterid")->where($where)->count();// 查询满足要求的总记录数 $map表示查询条件
               $Page=new Page($count,10);// 实例化分页类 传入总记录数
               $show=$Page->show();// 分页显示输出
               $list = $Data->field($field)->join("xueche1_user u on u.userid=l.masterid")->join("xueche1_trainclass t on t.tcid=l.classid")->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
           }
       }else{
           $list = $Data->field($field)->join("xueche1_user u on u.userid=l.masterid")->join("xueche1_trainclass t on t.tcid=l.classid")->limit($Page->firstRow.','.$Page->listRows)->select();
       }
       $this->assign('list',$list);// 赋值数据集
       $this->assign('count',$count);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
   }
   //快速查询
   public function questQuery($t){
       //判断是姓名/联系方式/订单号
       //用户名是2-6个汉字
       $p="/^1[34578]\\d{9}$/";
       $pp="/^2\\d{21}$/";
       if(preg_match($p,$t)){
           return "l.phone='$t'";
       }else if(preg_match($pp,$t)){
           return "l.listid='$t'";
       }else{
           return "u.nickname like '%$t%'";
       }
   }
}