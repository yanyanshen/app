<?php
namespace Ment\Controller;
use Think\Controller;
use Think\Page;
use Ment\Model\SchoolModel;
class SchoolController extends CommonController {
    function jx_list(){
        $Dao = M("school");
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
        $field="id,account,img,userid,nickname,score,ntime,connectteacher,address,cityid,verify,lastupdate,priority";
        $p = new Page($count,14);
        $page = $p->show();
        $list = $Dao->field($field)->where($where)->order("id asc")
            ->limit($p->firstRow.','.$p->listRows)
            ->select();
        foreach($list as $k=>$v){
            $userid=$v['userid'];
            $cityid=$v['cityid'];
            //订单个数
            $list[$k]['listcount']=M('list')->where("objectid='$userid'")->count();
            //预约个数
            $list[$k]['rescount']=M('reservation')->where("objectid='$userid'")->count();
            //学员个数
            $list[$k]['stucount']=M('student')->where("masterid='$userid'")->count();
            //课程个数
            $list[$k]['classcount']=M('trainclass')->where("masterid='$userid'")->count();
//            M('school')->where("userid='{$v['userid']}'")->save(array('class_num'=>$list[$k]['classcount']));

            //教练个数
            $list[$k]['coachcount']=M('coach')->where("masterid='$userid'")->count();
            M('school')->where("userid='{$v['userid']}'")->save(array('coach_num'=>$list[$k]['coachcount']));
//            M('school')->where("masterid='$userid'")->save(array('class_num'=>$list[$k]['classcount']));
            //城市
//            $list[$k]['city']=M('citys')->field('cityname')->where("id=$cityid")->find()['cityname'];
            $list[$k]['city']=M('citys')->field('cityname')->where("id=$cityid")->find();
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
    //删除驾校
    function del_school($id,$p){
        if(M('school')->delete($id)){
            $message='';
            $tt=0;
        }else{
            $message="<script>alert('删除失败')</script>";
            $tt=0.1;
        }
        $this->redirect("jx_list",array('p'=>$p),$tt,$message);
    }
    //更新
    function jx_info($id,$p){
        if(!empty($_POST)){
            if(M('school')->save($_POST)){
                $message="<script>alert('更新成功');</script>";
                $this->redirect('jx_list',array('p'=>$p),0.1,$message);
            }else{
                echo "<script>alert('更新失败');</script>";
            }
        }
	$field="account,pass,nickname,fullname,priority,logo,timeflag,ntime,signature,score,hotflag,recommendflag,evalutioncount,praisecount,allcount,passedcount,connectteacher,address,cityid,introduction";
        $jx=M('school')->field($field)->where("id=$id")->find();
        $city=M('citys')->field('id,cityname')->where("flag=1")->select();
        $this->assign('city',$city);
        $this->assign('jx',$jx);
        $this->assign('p',$p);
        $this->assign('id',$id);
        $this->display();
    }
    //添加驾校
    function add_jx(){
        if(!empty($_POST)){
            $jx=new SchoolModel();
            $school=$jx->create();
            if($school){
                $school['userid']=guid();
                $school['ntime']=date('Y-m-d H:i:s');
                if($jx->add($school)){
                    $this->redirect("jx_list",array(),0.1,"<script>alert('添加成功')</script>");
                }else{
                    echo "<script>alert('添加失败')</script>";
                }
            }else{
                $this->assign("errorInfo",$jx->getError());
            }
        }
        $city=M('citys')->field("id,cityname")->where("flag=1")->select();
        $this->assign("city",$city);
        $this->display();
    }
}
