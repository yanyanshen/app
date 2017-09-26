<?php
namespace Ment\Controller;
use Think\Controller;
use Think\Page;
use Ment\Model\UserModel;
class StudentController extends CommonController {
    function stu_list(){
        $Dao = M("user");
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
        $count = $Dao->where($where)->count();//表中总得条数
        //id，账号，性别，userid,注册时间，昵称，头像，手机号，地址，科目驾照类型，最后更新人
        $field="id,account,sex,userid,ntime,nickname,img,phone,address,subjects,jtype,verify,lastupdate";
        $p = new Page($count,12);//实例化对象 -----总条数    每页显示多少条
        $page = $p->show();//$page就是下面的数字分页
        //$list是每一页的结果
        $list = $Dao->field($field)->where($where)->limit($p->firstRow.','.$p->listRows)->select();
        foreach($list as $k=>$v){
            $userid=$v['userid'];
            $list[$k]['listcount']=M('list')->where("masterid='$userid'")->count();
            $list[$k]['rescount']=M('reservation')->where("masterid='$userid'")->count();
        }
        $this->assign('page', $page);
        $this->assign('count', $count);
        $this->assign('list', $list);
        $this->assign('p', $p->nowPage);
        $this->display();
    }
    //删除学员
    function del_stu($id,$p){
        if(M('user')->delete($id)){
            $message='';
            $tt=0;
        }else{
            $message="<script>alert('删除成功')</script>";
            $tt=0.1;
        }
        $this->redirect("stu_list",array('p'=>$p),$tt,$message);
    }
    //添加学员
    function add_stu(){
        if(!empty($_POST)){
            $s=new UserModel();
            $stu=$s->create();
            if($stu){
                $stu['userid']=guid();
                $stu['ntime']=date('Y-m-d H:i:s');
                $stu['phone']=$stu['account'];
                if($s->add($stu)){
                    $message="<script>alert('添加成功');</script>";
                }else{
                    $message="<script>alert('添加失败');</script>";
                }
                $this->redirect('stu_list',array(),0.1,$message);
            }else{
                $this->assign("errorInfo",$s->getError());
            }
        }
        $this->display();
    }
    //学员详情
    function stu_info($id,$p){
        if(!empty($_POST)){
            if(M('user')->save($_POST)){
                $message="<script>alert('更新成功');</script>";
                $this->redirect('stu_list',array('p'=>$p),0.1,$message);
            }else{
                echo "<script>alert('更新失败');</script>";
            }
        }
	$field="account,nickname,sex,birthday,ntime,signature,jtype,subjects,phone,address,cityid";
        $stu=M('user')->field($field)->where("id=$id")->find();
        $city=M('citys')->field('id,cityname')->select();
        $this->assign('stu',$stu);
        $this->assign('city',$city);
        $this->assign('p',$p);
        $this->assign('id',$id);
        $this->display();
    }
    //改变启用还是禁用
    function verify($id,$flag,$p){
        if($flag==1){
            $flag=0;
        }else{
            $flag=1;
        }
        if(M('user')->where("id=$id")->setField('verify',$flag)){
            $message='';
            $t=0;
        }else{
            $t=0.1;
            $message="<script>alert('修改失败');</script>";
        }
        $this->redirect('stu_list',array('p'=>$p),$t,$message);
    }
    //订单列表
    public function order_list($masterid,$pp){
        $Dao = M("list");
        $where="masterid='$masterid'";
        $count = $Dao->where($where)->count();
        $p = new Page($count,27);
        $page = $p->show();
        $list = $Dao->where($where)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
        $list=order($list);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('list', $list);
        $this->assign('p', $p->nowPage);
        $this->assign('pp', $pp);
        $this->display();
    }
}
