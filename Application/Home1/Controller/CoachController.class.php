<?php
namespace Home1\Controller;
use Think\Controller;
use Org\Util\Page;
/**
 * 
 * @author 陈龙龙
 *  2016.7.21始 对教练的操作
 */
class CoachController extends CommonController {
     public function coachList(){
         $Data=M("coach s"); // 实例化Data数据对象
         $count=$Data->count();// 查询满足要求的总记录数 $map表示查询条件
         $Page=new Page($count,8);// 实例化分页类 传入总记录数
         $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
         $show=$Page->show($Page->nowPage);//分页显示输出
         //根据$schoolid
         $coachid=$Data->field("s.userid,s.masterid,s.cityid")->order("s.id")->limit($Page->firstRow.','.$Page->listRows)->select();
         // 进行分页数据查询
         $list = $Data->field("s.id,s.sex,s.userid,s.nickname,s.img,s.phone,s.birthday,s.type,s.timeflag,s.address,count(t.id) as attcount,s.jltype,s.cityid")
         ->join("left join xueche1_attention t on t.objectid=s.userid")->order("s.id")->limit($Page->firstRow.','.$Page->listRows)->group("s.userid")->select();
         foreach($coachid as $k=>$v){
             $userid=$v['userid'];
             $schoolid=$v['masterid'];
             $cityid=$v['cityid'];
             $list[$k]['newscount']=M("news")->where("userid='$userid'")->count();//动态数
             $list[$k]['schoolname']=M("school")->field("nickname")->where("userid='$schoolid'")->find()['nickname'];//动态数
             $list[$k]['trainname']=M("coachtrain c")->field("t.trname")->join("xueche1_train t on t.id=c.trainid and coachid='$userid'")->select();//关注数
             //$list[$k]['classcount']=M("trainclass")->where("masterid='$userid'")->count();//课程数
             $list[$k]['cityname']=M("citys")->field("cityname")->where("id='$cityid'")->find()['cityname'];//所在城市
         }
         $this->assign('coachlist',$list);// 赋值数据集
         $this->assign('page',$show);// 赋值分页输出
         $this->assign('p',$Page->nowPage);// 第几页
         $this->assign('rowscount',$count);// 总得记录数
         $this->display(); //输出模板
     }
     //添加教练界面
     public function addjl(){
         $city=$this->returncity();
         $this->assign("city",$city);
         $this->assign("pass",C('con.pass'));
         $this->display();
     }
     //添加教练信息
     public function addjlinfo(){
         $account=I('post.account');
         if(M('coach')->where("account='$account'")->find()){
             echo 3;return;
         }
         $_POST['userid']=$this->guid();
         foreach($_POST as $k=>$v){
             if($v==''){
                 echo 2;
                 return;
             }
         }
         if(M('coach')->add($_POST)){
             echo $_POST['userid'];
         }else{
             echo 0;
         }
     }
 //--------------------------------------------------------7.22------------------------------------------------
 /**
  * 对教练的修改，及地标的的添加
  */
   //教练信息及修改的界面
   public function jlinfo1(){
       $userid=I('get.userid');
       $p=I('get.p');
       $info=M("coach")->field('account,userid,img,nickname,phone,pickrange,score,address,jltype,allcount,timeflag,passedcount,evalutioncount,praisecount,introduction,cityid')->where("userid='$userid'")->find();
       $city=$this->returncity();
       //通过率和好评率
       if($info['passedcount']==0){
           $tgl=0;
       }else{
           $tgl=$info['passedcount']/$info['allcount'];
       }
       if($info['praisecount']==0){
           $hpl=0;
       }else{
           $hpl=$info['praisecount']/$info['evalutioncount'];
       }
       $this->assign("info",$info);
       $this->assign("city",$city);
       $this->assign("passrate",$this->percent($tgl));//通过率
       $this->assign("goodrate",$this->percent($hpl));//好评率
       $this->assign("p",$p);//好评率
       $this->display();
   }
   //实现更新教练信息
   public function jlinfoupdate(){
       $userid=I("post.userid");
       if(M('coach')->where("userid='$userid'")->save($_POST)){
           echo 1;
       }else{
           echo 0;
       }
   }
   //教练详情
   public function jlinfo(){
       $userid=I('get.userid');
       if(isset($_GET['sid'])){
           $sid=$_GET['sid'];
           if(M('coachland')->where("id=$sid")->delete()){
               echo "<script>alert('删除成功')</script>";
           }else{
               echo "<script>alert('删除失败')</script>";
           }
       }
       $nowpage=I('get.p');
       //驾校表
       $info=M("coach")->field("account,userid,masterid,img,nickname,sex,phone,birthday,pickrange,score,address,allcount,passedcount,evalutioncount,praisecount,introduction,cityid,timeflag,jltype")->where("userid='$userid'")->find();
       $land=M("coachland s")->field("s.id as sid,l.id,l.landname,l.masterid,c.countyname")->join("xueche1_landmark l on s.landmarkid=l.id and s.coachid='$userid'")->join("xueche1_countys c on c.id=l.masterid")->select();
       foreach($land as $k=>$v){
           $masterid=$v['masterid'];
           $county[$k]=M("countys")->field("countyname")->where("id=$masterid")->find()['countyname'];
       }
       $county=array_unique($county);
       //地区、地标
       foreach($county as $k=>$v){
           foreach($land as $kk=>$vv){
               if($vv['countyname']==$v){
                   $countys[$v][$kk]['landname']=$vv['landname'];
                   $countys[$v][$kk]['id']=$vv['id'];
                   $countys[$v][$kk]['sid']=$vv['sid'];
               }
           }
       }
       //城市
       $city=$this->returncity();
       //每一个城市的地标默认是数据库保存的城市
       $cityid=$info['cityid'];
       //根据用户选择的城市来显示县和区
       $count=M("countys")->field("id,countyname")->where("masterid=$cityid")->select();
       $landid=$count[0]['id'];
       //地标
       $lands=M("landmark")->field("id,landname")->where("masterid=$landid")->select();
       foreach ($lands as $k=>$v){
           foreach ($countys as $kk=>$vv){
               foreach($vv as $kkk=>$vvv){
                   if($vvv['landname']==$v['landname']){
                       unset($lands[$k]);
                   }
               }
           }
       }
       //通过率和好评率
       if($info['passedcount']==0){
           $tgl=0;
       }else{
           $tgl=$info['passedcount']/$info['allcount'];
       }
       if($info['praisecount']==0){
           $hpl=0;
       }else{
           $hpl=$info['praisecount']/$info['evalutioncount'];
       }
       $this->assign("info",$info);
       $this->assign("city",$city);
       $this->assign("count",$count);
       $this->assign("lands",$lands);//还未添加的地标
       $this->assign("county",$county);
       $this->assign("countys",$countys);
       $this->assign("userid",$userid);
       $this->assign("p",$nowpage);
       $this->assign("passrate",$this->percent($tgl));//通过率
       $this->assign("goodrate",$this->percent($hpl));//好评率
       $this->assign("cityid",$info['cityid']);
       $this->display();
   }
   //给驾校添加地标
   function addjlland(){
       $userid=I("request.userid");
       unset($_REQUEST['userid']);
       foreach($_REQUEST  as $k=>$v){
           if(is_numeric($k)){
               $arr[$k]['landmarkid']=$v;
               $arr[$k]['coachid']=$userid;
           }
       }if(M('coachland')->addAll($arr)){
           echo 1;
       }else{
           echo 0;
       }
   }
   //
   public function team(){
       $type=I('type');
       if($type==''){
           return;
       }
       $mm=M($type)->field("userid,nickname")->select();
       echo $this->result($mm);
   }
   //删除教练
   public function delcoach(){
       $userid=I('get.userid');
       $p=I('get.p');
       M('coach')->where("userid='$userid'")->delete();
       M('trainclass')->where("masterid='$userid'")->delete();
       M('news')->where("userid='$userid'")->delete();
       M('img')->where("userid='$userid'")->delete();
       M('newsimg')->where("userid='$userid'")->delete();//删除tupian信息
       M('coachland')->where("coachid='$userid'")->delete();
       M('coachtrain')->where("coachid='$userid'")->delete();
       $u=U('coachList?userid='.$userid.'&p='.$p);
       header("location:$u");
   }
   
}