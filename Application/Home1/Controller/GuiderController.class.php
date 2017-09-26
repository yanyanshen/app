<?php
namespace Home1\Controller;
use Think\Controller;
use Org\Util\Page;
/**
 * 
 * @author 陈龙龙
 *  2016.7.23始 对指导员的操作
 */
class GuiderController extends CommonController {
     public function guiderList(){
         $Data=M("guider s"); // 实例化Data数据对象
         $count=$Data->count();// 查询满足要求的总记录数 $map表示查询条件
         $Page=new Page($count,8);// 实例化分页类 传入总记录数
         $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
         $show=$Page->show($Page->nowPage);//分页显示输出
         //根据$schoolid
         $coachid=$Data->field("s.userid,s.cityid")->order("s.id")->limit($Page->firstRow.','.$Page->listRows)->select();
         // 进行分页数据查询
         $list = $Data->field("s.id,s.sex,s.userid,s.nickname,s.img,s.phone,s.type,s.timeflag,s.address,count(t.id) as attcount,s.cityid")
         ->join("left join xueche1_attention t on t.objectid=s.userid")->order("s.id")->limit($Page->firstRow.','.$Page->listRows)->group("s.userid")->select();
         foreach($coachid as $k=>$v){
             $userid=$v['userid'];
             $cityid=$v['cityid'];
             $list[$k]['newscount']=M("news")->where("userid='$userid'")->count();//动态数
             //$list[$k]['schoolname']=M("school")->field("nickname")->where("userid='$schoolid'")->find()['nickname'];//动态数
             //$list[$k]['trainname']=M("coachtrain c")->field("t.trname")->join("xueche1_train t on t.id=c.trainid and coachid='$userid'")->select();//关注数
             $list[$k]['classcount']=M("trainclass")->where("masterid='$userid'")->count();//课程数
             $list[$k]['cityname']=M("citys")->field("cityname")->where("id='$cityid'")->find()['cityname'];//所在城市
         }
         $this->assign('coachlist',$list);// 赋值数据集
         $this->assign('page',$show);// 赋值分页输出
         $this->assign('p',$Page->nowPage);// 第几页
         $this->assign('rowscount',$count);// 总得记录数
         $this->display(); //输出模板
     }
     //添加教练界面
     public function addzdy(){
         $city=$this->returncity();
         $this->assign("city",$city);
         $this->assign("pass",C('con.pass'));
         $this->assign("cost",C('con.cost'));
         $this->display();
     }
     //添加教练信息
     public function addzdyinfo(){
         $account=I('post.account');
         if(M('guider')->where("account='$account'")->find()){
             echo 3;return;
         }
         $_POST['userid']=$this->guid();
         foreach($_POST as $k=>$v){
             if($v==''){
                 echo 2;
                 return;
             }
         }
         if(M('guider')->add($_POST)){
             echo $_POST['userid'];
         }else{
             echo 0;
         }
     }
 //--------------------------------------------------------7.22------------------------------------------------
 /**
  * 对教练的修改，及地标的的添加
  */
   //实现更新教练信息
   public function zdyinfoupdate(){
       $userid=I("post.userid");
       if(M('guider')->where("userid='$userid'")->save($_POST)){
           echo 1;
       }else{
           echo 0;
       }
   }
   
   public function zdyinfo(){
       $userid=I('get.userid');
       if(isset($_GET['sid'])){
           $sid=$_GET['sid'];
           if(M('guiderland')->where("id=$sid")->delete()){
               echo "<script>alert('删除成功')</script>";
           }else{
               echo "<script>alert('删除失败')</script>";
           }
       }
       $nowpage=I('get.p');
       //指导员表
       $info=M("guider")->field("account,userid,img,nickname,phone,sex,birthday,pickrange,score,address,allcount,cost,passedcount,evalutioncount,praisecount,introduction,cityid,timeflag")->where("userid='$userid'")->find();
       $land=M("guiderland s")->field("s.id as sid,l.id,l.landname,l.masterid,c.countyname")->join("xueche1_landmark l on s.landmarkid=l.id and s.guiderid='$userid'")->join("xueche1_countys c on c.id=l.masterid")->select();
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
       $this->assign("countys",$countys);//地区和已添加的地标
       $this->assign("userid",$userid);
       $this->assign("p",$nowpage);
       $this->assign("passrate",$this->percent($tgl));//通过率
       $this->assign("goodrate",$this->percent($hpl));//好评率
       $this->assign("cityid",$info['cityid']);
       $this->display();
   }
   //给驾校添加地标
   function addzdyland(){
       $userid=I("request.userid");
       unset($_REQUEST['userid']);
       foreach($_REQUEST  as $k=>$v){
           if(is_numeric($k)){
               $arr[$k]['landmarkid']=$v;
               $arr[$k]['guiderid']=$userid;
           }
       }if(M('guiderland')->addAll($arr)){
           echo 1;
       }else{
           echo 0;
       }
   }
   //删除zhi指导员
   public function delguider(){
       $userid=I('get.userid');
       $p=I('get.p');
       M('guider')->where("userid='$userid'")->delete();//删除指导员
       M('trainclass')->where("masterid='$userid'")->delete();//删除课程信息
       M('news')->where("userid='$userid'")->delete();//删除动态信息
       M('newsimg')->where("userid='$userid'")->delete();//删除tupian信息
       M('img')->where("userid='$userid'")->delete();//删除tupian信息
       M('guiderland')->where("guiderid='$userid'")->delete();//删除地标信息
       //M('coachtrain')->where("coachid='$userid'")->delete();//指导员没有基地信息
       $u=U('guiderList?userid='.$userid.'&p='.$p);
       header("location:$u");
   }
   
}