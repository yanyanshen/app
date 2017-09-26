<?php
namespace Ment\Controller;
use Think\Controller;
use Think\Page;
use Ment\Model\CoachModel;
class CoachController extends CommonController {
    function jl_list(){
        $Dao = M("coach");
        $where='';
        if(!empty($_GET)){
            foreach($_GET as $key=>$val) {
                if($key=='p'){
                    continue;
                }elseif($key=='nickname'){
                    $where.="$key like '%".urldecode($val)."%' and ";
                   
                }else{
                    $where.="$key='".urlencode($val)."' and ";
                }
            }$where=rtrim($where,'and ');
        }
        $count = $Dao->where($where)->count();
        $field="id,userid,account,img,sex,nickname,score,ntime,jltype,serialId,carNumber,address,cityid,verify,lastupdate";
	$p = new Page($count,14);
        $page = $p->show();
        $list = $Dao->field($field)->where($where)->order("id asc")->limit($p->firstRow.','.$p->listRows)->select();
        foreach($list as $k=>$v){
            $userid=$v['userid'];
            $cityid=$v['cityid'];
            //订单个数
            $list[$k]['listcount']=M('list')->where("objectid='$userid'")->count();
            //预约个数
            $list[$k]['rescount']=M('reservation')->where("objectid='$userid'")->count();
            //学员个数
            $list[$k]['stucount']=M('student')->where("masterid='$userid'")->count();
            //城市
            $list[$k]['city']=M('citys')->field('cityname')->where("id=$cityid")->find()['cityname'];

        }
        //城市
        $citys=M('citys')->field('id,cityname')->where("flag=1")->select();
        $this->assign('page', $page);
        $this->assign('count', $count);
        $this->assign('city', $citys);
        $this->assign('list', $list);
        $this->assign('p', $p->nowPage);
        $this->display();
    }
    //删除教练
    function del_coach($id,$p){
        if(M('coach')->delete($id)){
            $message='';
            $tt=0;
        }else{
            $message="<script>alert('删除失败')</script>";
            $tt=0.1;
        }
        $this->redirect("jl_list",array('p'=>$p),$tt,$message);
    }
    //更新
    function jl_info($id,$p){
        if(!empty($_POST)){
	//	dump($_POST);exit;
            if(M('coach')->where("id=$id")->save($_POST)){
                $message="<script>alert('更新成功');</script>";
                $this->redirect('jl_list',array('p'=>$p),0.1,$message);
            }else{
                echo "<script>alert('更新失败');</script>";
            }
        }
        $field="id,account,pass,sex,nickname,birthday,numberId,driverId,serialId,signature,timeflag,ntime,driverage,teachedage,evalutioncount,praisecount,allcount,passedcount,jltype,address,masterid,boss,cityid,introduction"; 
        $jl=M('coach')->field($field)->find($id);
	//dump($jl);exit;
        $cityid=$jl['cityid'];
        //选择所属驾校
        $school=M('school')->field("userid,nickname")->where("cityid=$cityid")->select();
        //选择所属教练
        $coach=M('coach')->field("userid,nickname")->where("cityid=$cityid and jltype=2")->select();
        $city=M('citys')->field('id,cityname')->where("flag=1")->select();
        $this->assign('city',$city);
        $this->assign('jl',$jl);
        $this->assign('p',$p);
        $this->assign('id',$id);
        $this->assign('school',$school);
        $this->assign('coach',$coach);
        $this->display();
    }
    //添加教练
    function add_jl(){
        if(!empty($_POST)){
            $jl=new CoachModel();
            $coach=$jl->create();
            if($coach){
                $coach['userid']=guid();
                $coach['ntime']=date('Y-m-d H:i:s');
                if($jl->add($coach)){
                    $this->redirect("jl_list",array(),0.1,"<script>alert('添加成功')</script>");
                }else{
                    echo "<script>alert('添加失败')</script>";
                }
            }else{
                $this->assign("errorInfo",$jl->getError());
            }
        }
        $city=M('citys')->field("id,cityname")->where("flag=1")->select();
        $school=M('school')->field("userid,nickname")->select();
        $coachs=M('coach')->field("userid,nickname")->where("jltype=2")->select();
        $this->assign("city",$city);
        $this->assign("school",$school);
        $this->assign("coachs",$coachs);
        $this->display();
    }
}
