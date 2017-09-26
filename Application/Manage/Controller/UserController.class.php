<?php
namespace Manage\Controller;
use Think\Controller;
use Org\Util\Page;
/**
 * 
 * @author 陈龙龙
 *  2016.7.25始 对学员的操作
 */
class UserController extends CommonController {
     public function userList(){
         $Data=M("user"); // 实例化Data数据对象
         $count=$Data->count();// 查询满足要求的总记录数 $map表示查询条件
         $Page=new Page($count,8);// 实例化分页类 传入总记录数
         $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
         $show=$Page->show($Page->nowPage);//分页显示输出
         //$userid=$Data->field("userid")->select();
         // 进行分页数据查询
        // $list = $Data->field("id,sex,userid,nickname,img,phone,address,subjects,jtype,count(l.id) as countlist,u.lastupdate")->join("left join xueche1_list l on l.masterid=u.userid")->order("u.id")->limit($Page->firstRow.','.$Page->listRows)->group("u.userid")->select();
		$list = $Data->field("id,account,sex,userid,ntime,nickname,img,phone,address,subjects,jtype,lastupdate,verify")->order("id asc")->limit($Page->firstRow.','.$Page->listRows)->select();
         foreach($list as $k=>$v){
            $uid=$v['userid'];
			$list[$k]['countlist']=M("list")->where("masterid='$uid'")->count();
            $list[$k]['countreser']=M("reservation")->where("masterid='$uid'")->count();
         }
         $this->assign('userlist',$list);// 赋值数据集
         $this->assign('page',$show);// 赋值分页输出
         $this->assign('p',$Page->nowPage);// 第几页
         $this->assign('rowscount',$count);// 总得记录数
         $this->display(); //输出模板
     }
     //添加学员界面
     public function addxy(){
         $city=$this->returncity();
         $this->assign("city",$city);
         $this->assign("pass",C('con.pass'));
         $this->assign("cost",C('con.cost'));
         $this->display();
     }
     //添加教练信息
     public function addxyinfo(){
         $account=I('post.account');
         if(M('user')->where("account='$account'")->find()){
             echo 3;return;
         }$_POST['ntime']=time();
         $_POST['userid']=$this->guid();
         foreach($_POST as $k=>$v){
             if($v==''){
                 echo 2;
                 return;
             }
         }
         if(M('user')->add($_POST)){
             lastupdate('xy', $_POST['userid']);
			 addlog("添加学员{$account}成功");
             echo $_POST['userid'];
         }else{
			 addlog("添加学员{$account}失败");
             echo 0;
         }
     }
 //--------------------------------------------------------7.25------------------------------------------------
 /**
  * 对教练的修改，及地标的的添加
  */
   //实现更新教练信息
   public function xyinfoupdate(){
      //把传递的参数分成两部分
       $userid=I("post.userid");
       $post['myschoolid']=$_POST['myschoolid'];unset($_POST['myschoolid']);
       $post['mycoachid']=$_POST['mycoachid'];unset($_POST['mycoachid']);
       $post['myguiderid']=$_POST['myguiderid'];unset($_POST['myguiderid']);
       unset($_POST['cityid']);
       if(M('user')->where("userid='$userid'")->save($_POST)){
           M('drisch')->where("userid='$userid'")->save($post);
           lastupdate('xy', $userid);
		   addlog("更新学员{$userid}成功");
           echo 1;
       }else{
		   addlog("更新学员{$userid}失败");
           echo 0;
       }
   }
   public function xyinfo(){
       $userid=I('get.userid');
       $p=I('get.p');
       //指导员表
       $info=M("user")->field("account,userid,img,nickname,phone,sex,birthday,address,jtype,subjects")->where("userid='$userid'")->find();
       $info['drisch']=M("drisch")->field("myschoolid,mycoachid,myguiderid")->where("userid='$userid'")->find();
       $schoolid=$info['drisch']['myschoolid'];
       $coachid=$info['drisch']['mycoachid'];
       $guiderid=$info['drisch']['myguiderid'];
       //驾考学堂
       $info['drisch']['schoolnickname']=M('school')->field("nickname")->where("userid='$schoolid'")->find()['nickname'];
       $info['drisch']['coachnickname']=M('coach')->field("nickname")->where("userid='$coachid'")->find()['nickname'];
       $info['drisch']['guidernickname']=M('guider')->field("nickname")->where("userid='$guiderid'")->find()['nickname'];
       $this->assign("info",$info);
       $this->assign("userid",$userid);
       $this->assign("p",$p);
       $this->assign("city",$this->returncity());
       $this->display();
   }
   //给驾校添加地标
   /*function addzdyland(){
       $userid=I("request.userid");
       unset($_REQUEST['userid']);
       foreach($_REQUEST  as $k=>$v){
           if(is_numeric($k)){
               $arr[$k]['landmarkid']=$v;
               $arr[$k]['guiderid']=$userid;
           }
       }if(M('guiderland')->addAll($arr)){
           lastupdate('xy', $userid);
           echo 1;
       }else{
           echo 0;
       }
   }*/
   //删除学员
   public function deluser(){
       $userid=I('get.userid');
       $p=I('get.p');
       M('user')->where("userid='$userid'")->delete();//删除学员
       M('news')->where("userid='$userid'")->delete();//删除动态信息
       M('newsimg')->where("userid='$userid'")->delete();//删除tupian信息
       M('drisch')->where("userid='$userid'")->delete();//删除驾考学堂信息
       M('errortheory')->where("userid='$userid'")->delete();//删除错题
       M('quebank')->where("userid='$userid'")->delete();//删除题库
	   M('userscore')->where("userid='$userid'")->delete();//删除题库
       $u=U('userList?p='.$p);
       header("location:$u");
   }
   //---------------------------------------7.25-----------------------------------------------------------
   public function returnCoach(){
       $cityid=I('post.cityid');
       //查找驾校
       $coach['school']=M('school')->field("userid,nickname")->where("cityid=$cityid")->select();
       $coach['coach']=M('coach')->field("userid,nickname")->where("cityid=$cityid")->select();
       $coach['guider']=M('guider')->field("userid,nickname")->where("cityid=$cityid")->select();
       echo $this->result($coach);
   }
   //---------------------------------------------------------8.31
   public function userverify(){
		$verify=I("get.verify");
		$p=I("get.p");
		$userid=I("get.userid");
		if($verify==0){
				$verify=1;
		}else{
			$verify=0;
		}
		M('user')->where("userid='$userid'")->setField("verify",$verify);
		lastupdate('xy', $userid);
		$u=U('userList?&p='.$p);
		header("location:$u");
   }
   public function userLog($p=1){
       if(isset($_GET['id'])){
           M('userlog')->delete($_GET['id']);
       }
       $Data=M("userlog"); // 实例化Data数据对象
       $count=$Data->count();// 查询满足要求的总记录数 $map表示查询条件
       $Page=new Page($count,18);// 实例化分页类 传入总记录数
       $Page->nowPage=$p;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
       $show=$Page->show($Page->nowPage);//分页显示输出
       $list = $Data->field("id,userid,ip,dotime,url,phone")->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
       foreach($list as $k=>$v){
           $userid=$v['userid'];
           $list[$k]['nickname']=M('user')->field("nickname")->where("userid='$userid'")->find()['nickname'];
       }
       $this->assign("list",$list);
        $this->assign('page',$show);// 赋值分页输出
         $this->assign('p',$Page->nowPage);// 第几页
         $this->assign('rowscount',$count);// 总得记录数
	$this->assign("p",$p);
       $this->display();
   }
	
}
