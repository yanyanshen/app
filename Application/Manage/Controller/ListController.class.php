<?php
namespace Manage\Controller;
use Think\Controller;
use Org\Util\Page;
class ListController extends CommonController {
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
   //-----------------------------某个指定学员的订单列表------------------------
   public function orderList_u(){
       $userid=$_GET['userid'];
       $nickname=M("user")->field("nickname")->where("userid='$userid'")->find()['nickname'];
       $Data=M('list'); // 实例化Data数据对象
       $count=$Data->where("masterid='$userid'")->count();// 查询满足要求的总记录数 $map表示查询条件
       $Page=new Page($count,13);// 实例化分页类 传入总记录数
	   $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
       $show=$Page->show($Page->nowPage);// 分页显示输出
       //$field="l.id,l.objectid,l.phone,l.returndate,l.lastupdate,l.note,l.customer,l.liststate,l.paymethod,l.paytime,l.stucount,l.applymode,l.prices,l.address,l.remark,l.mode,l.description,l.listid,l.listtime,l.state,l.classid,l.flag,l.type,l.CL_type,t.name";
	    //$field="l.id,l.listid,l.listtime,l.masterid,l.objectid,l.phone,l.stucount,l.listname,l.paymethod,l.liststate,l.paytime,l.state,l.customer,l.note,l.type,l.returndate,l.lastupdate,l.CL_type,l.flag,t.name";
      // $list=$Data->field($field)->join("xueche1_trainclass t on t.tcid=l.classid and l.masterid='$userid'")->order("l.id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
	  //8.8日修改
	   $field="id,listid,listtime,masterid,objectid,phone,stucount,listname,paymethod,liststate,paytime,state,classid,customer,note,type,returndate,lastupdate,CL_type,flag,preferentialprice";
	   $list=$Data->field($field)->where("masterid='$userid'")->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
       foreach ($list as $k=>$v){
           $type=$v['type'];
           $objid=$v['objectid'];
		   $classid=$v['classid'];
           $table=$this->shenfen($type);
		   $customer=$v['customer'];
           $list[$k]['nickname']=$nickname;
		   $list[$k]['name']=M("trainclass")->field("name")->where("tcid=$classid")->find()['name'];
		   $list[$k]['username']=M("admin")->field("username")->where("id=$customer")->find()['username'];
           $list[$k]['listname']=M($table)->field("nickname")->where("userid='$objid'")->find()['nickname'];
           $list[$k]['typename']=$type=='jx'?'驾校':($type=='jl'?'教练':'指导员');
       }
       $this->assign('list',$list);// 赋值数据集
       $this->assign('count',$count);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->assign('p',$_GET['p']);// 赋值分页输出
       $this->assign("userid",$userid);
       $this->display(); // 输出模板
   }
   //-----------------------------某个指定学员的订单详情------------7.27------------
   public function listInfo(){
       $userid=I('get.userid');
       $p=I('get.p');
       $listid=I("get.listid");
       //取出全部驾校的名字和userid
       $allschool=M("school")->field("userid,nickname")->select();
       //先取出姓名
       $nickname=M('user')->field("nickname")->where("userid='$userid'")->find()['nickname'];
       $field="id,masterid,phone,address,listtime,objectid,remark,mode,prices,total_fee,stucount,state,applymode,listid,masname,couponmode,listname,description,classid,flag,trname,paytime,paymethod,liststate,customer,note,returndate,lastupdate,payment_type,buyer_email,gmt_payment,trade_no,gmt_refund,Cl_type,listtype,type,preferentialprice";
       $listinfo=M("list")->field($field)->where("listid='$listid'")->find();
	   $customer=$listinfo['customer'];
	   $listinfo['username']=M("admin")->field("username")->where("id=$customer")->find()['username'];
       $type=$listinfo['type'];
       if($type=='jx'){
           $schoolid=$listinfo['objectid'];
           $school['schoolname']=M('school')->field("nickname,connectteacher")->where("userid='$schoolid'")->find();
       }else{
           $coachid=$listinfo['objectid'];
           $school=M('coach')->field("nickname,masterid")->where("userid='$coachid'")->find();
           $masterid=$school['masterid'];
           $school['schoolname']=M("school")->field("nickname,connectteacher")->where("userid='$masterid'")->find();
       }
       //跟单记录
       $listdocument=M("listdocumentary")->field("id,documenttime,content,nextreturndate,operator")->where("listid='$listid'")->select();
       //nickname as objectname,t.connectteacher
       $classid=$listinfo['classid'];
       //找出该驾校的联系人，门市价和全包价----去课程里面找
       $class=M("trainclass")->field("name,officialprice,whole517price,prepay517deposit")->where("tcid=$classid")->find();
	   //找出所有的客服
	   $customer=M("admin")->field("id,username")->where("masgroup=4")->select();
       $url=U("orderList?p=".$p);
       if(isset($_GET['t'])){
           $url=U("orderList_u?p=".$p.'&userid='.$userid);
       }
       $this->assign("listinfo",$listinfo);
       $this->assign("customer",$customer);//客服
       $this->assign("nickname",$nickname);//客户名
       $this->assign("class",$class);
       $this->assign("listdocument",$listdocument);
       $this->assign("school",$school);
       $this->assign("date",date("Y-m-d"));
       $this->assign("url",$url);
       $this->assign("allschools",$allschool);
       $this->display();
   }
   public function list_updatestu(){
       $listid=I('post.listid');
	   $_POST['lastupdate']=session("username");
       if(M("list")->where("listid='$listid'")->save($_POST)){
		   addlog("修改{$listid}订单成功");
           echo 1;
       }else{
		     addlog("修改{$listid}订单失败");
           echo 0;
       }
   }
   public function class_updatestu(){
       $listid=I('post.listid');
       $post['classid']=I('post.allclass');
       $post['objectid']=I('post.school');
       $post['trname']=I('post.trname');
       $post['type']='jx';
       $post['lastupdate']=session("username");
       $coach=I('post.allcoach');
       if($coach!=''){$post['objectid']=$coach;$post['type']='jl';}
       if(M("list")->where("listid='$listid'")->save($post)){
		    addlog("修改{$listid}订单的课程成功");
           echo 1;
       }else{
		   addlog("修改{$listid}订单的课程失败");
           echo 0;
       }
   }
   //根据驾校的不同返回不同的课程
   public function returnclass(){
       $userid=I('post.userid');
       $mm['trainclass']=M('trainclass')->field("id,tcid,name,officialprice,whole517price")->where("masterid='$userid'")->select();
       $mm['train']=M('schooltrain s')->field("t.id,t.trname")->join("xueche1_train t on t.id=s.trainid and s.schoolid='$userid'")->select();
       echo $this->result($mm);
   }
   //根据不同的课程显示不同的价钱
   public function returnprice(){
       $listid=I('post.listid');
       $data=M('trainclass')->field("tcid,officialprice,whole517price,prepay517deposit")->where("tcid=$listid")->find();
       echo $this->result($data);
   }
   //----------------------------------------------7.28订单模块列表
  public function orderList(){
       $where="";
       if(isset($_GET['type'])){
          if($_GET['type']==1){
              $str=I('post.query');
              switch(strlen($str)){
                  case 22:$where="listid='$str'";break;
                  case 11:$where="phone='$str'";break;
                  default:$where="masname like '%$str%'";break;
              }
          }else if($_GET['type']==2){
              foreach($_POST as $k=>$v){
                  if($v==''){
                      continue;
                  }else{
                      switch($k){
                          case 'startreturndate':$where.="returndate>='$v' and ";break;
                          case 'endreturndate':$where.="returndate<='$v' and ";break;
                          case 'startgmt_payment':$where.="gmt_payment>='$v' and ";break;
                          case 'endgmt_payment':$where.="gmt_payment<='$v' and ";break;
                          case 'listname':$where.="listname like '%$v%' and ";break;
                          case 'masname':$where.="masname like '%$v%' and ";break;
                          default:  $where.=$k."='$v' and ";break;
                      }
                  }   
               } $where =substr($where,0,strlen($where)-4);
          }else if($_GET['type']==3){
				$where.="gmt_refund  is not null";
		  }
       }
       $Data=M('list l'); // 实例化Data数据对象
       $count=$Data->where($where)->count();// 查询满足要求的总记录数 $map表示查询条件
       $Page=new Page($count,13);// 实例化分页类 传入总记录数
	   $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
       $show=$Page->show($Page->nowPage);// 分页显示输出
       $field="id,listid,listtime,masterid,objectid,phone,stucount,listname,paymethod,liststate,paytime,state,classid,customer,note,type,returndate,lastupdate,CL_type,flag,preferentialprice,gmt_payment";
	   $list=$Data->field($field)->where($where)->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
	   foreach ($list as $k=>$v){
           $type=$v['type'];//订单种类
           $userid=$v['masterid'];//属于谁
           $objid=$v['objectid'];//商家
		   $classid=$v['classid'];
		   $customer=$v['customer'];
           $table=$this->shenfen($type);
		   $list[$k]['name']=M("trainclass")->field("name")->where("tcid=$classid")->find()['name'];
		   $list[$k]['username']=M("admin")->field("username")->where("id=$customer")->find()['username'];
           $list[$k]['nickname']=M("user")->field("nickname")->where("userid='$userid'")->find()['nickname'];
           $list[$k]['listname']=M($table)->field("nickname")->where("userid='$objid'")->find()['nickname'];
           $list[$k]['typename']=$type=='jx'?'驾校':($type=='jl'?'教练':'指导员');
       }
       $this->assign('list',$list);// 赋值数据集
       $this->assign('count',$count);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->assign('p', $Page->nowPage);// 赋值分页输出
       $this->assign("userid",$userid);
       $this->display(); // 输出模板
   }
   //按照驾校和基地去找教练
   public function returncoach(){
       //这个时候传递过来的是data----alltrain=6&schoolid=07C6E768-C98F-6560-92F7-43717534118F
       $post=explode('&', $_POST['data']);
       $trainid=mb_substr($post[0], 9);
       $schoolid=mb_substr($post[1], 9);
       $data=M("coachtrain t")->field("c.userid,c.nickname")->join("xueche1_coach c on c.userid=t.coachid and t.trainid=$trainid and schoolid='$schoolid'")->select();
       echo $this->result($data);
   }
   //---------------------------------------------7.29--------------------------------------------
   public function returnallschool(){
       $nickname=I('post.nickname');
       if($nickname!=''){
           $data=M('school')->field("userid,nickname")->where("nickname like '%$nickname%'")->select();
           echo $this->result($data);
       }
   }
   //添加回访记录
   public function addlistdocument(){
        foreach($_POST as $k=>$v){
            if(empty($v)){
                echo 2;
                return;
            }
        }
        $_POST['operator']=session('username');//跟单时间
        $_POST['documenttime']=date("Y-m-d");//跟单时间
        if(M("listdocumentary")->add($_POST)){
			$listid=$_POST['listid'];
			listlastupdate($listid);
			addlog("修改{$listid}订单的跟单记录成功");
			 //跟单记录
			echo 1;
        }else{
			addlog("修改{$listid}订单的跟单记录失败");
            echo 0;
        }
   } 
   //修改支付方式
   public function zhifu_updatestu(){
	     $listid=$_POST['listid'];
		 listlastupdate($listid);
         $post['stucount']=$_POST['stucount'];
         $post['preferentialprice']= $_POST['preferentialprice'];
		if(M('list')->where("listid='$listid'")->save($post)){
			addlog("修改{$listid}订单的支付方式成功");
			echo 1;
		}else{
			addlog("修改{$listid}订单的支付方式失败");
			echo 0;
		}
   }
   //-------------------------------------------------8.12
   public function addlist(){
       $date=date("Y-m-d");
       $this->assign("date",$date);
       $this->assign("username",session('username'));
       $this->display();
   }
   public function listadd(){
       $_POST['listtype']=1;
       $_POST['listtime']=date("Y-m-d H:i:s");
       $_POST['listid']=date('YmdHis'). substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
       if($_POST['allcoach']!=''){
           $_POST['objectid']=$_POST['allcoach'];
           $_POST['listname']=$_POST['coachname'];
           $_POST['type']='jl';
       }else{
           $_POST['objectid']=$_POST['allschool'];
           $_POST['listname']=$_POST['schoolname'];
           $_POST['type']='jx';
       }
       unset($_POST['coachname']);unset($_POST['schoolname']);
       if(M('list')->add($_POST)){
		   addlog("添加订单$_POST{'listid'}成功");
           echo "<script>alert('添加成功');location.href='orderlist'</script>";
       }else{
		   addlog("添加订单$_POST{'listid'}失败");
           echo "<script>alert('添加失败');location.href='orderlist'</script>";
       }
   }
   //更新客服
   public function updatecustomer(){
		$id=I('post.id');
		$listid=I('post.listid');
		if(M("list")->where("listid='$listid'")->setField("customer",$id)){
			listlastupdate($listid);
			echo 1;
		}else{
			echo 0;
		}
   }
   //获取订单的评论
   public function getListeval(){
		
   }
}
