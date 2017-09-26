<?php
namespace Manage\Controller;
use Think\Controller;
use Org\Util\Page;
/**
 * 
 * @author 陈龙龙
 *  2016.7.25始 对学员的操作
 */
class EvalController extends CommonController {
    public function evallist(){
		 $where='';
		 if(isset($_POST['jxname'])){
			 $schoolname=$_POST['jxname'];
			 $schoolid=M("school")->field("userid")->where("nickname like '%$schoolname%'")->find()['userid'];
			 $where="objecthingid='$schoolid'";
		 }
		 $Data=M("evaluating"); // 实例化Data数据对象
         $count=$Data->where($where)->count();// 查询满足要求的总记录数 $map表示查询条件
         $Page=new Page($count,12);// 实例化分页类 传入总记录数
         $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
         $show=$Page->show($Page->nowPage);//分页显示输出
         // 进行分页数据查询
         $list = $Data->field("id,time,content,objecthingid,lastupdate")->where($where)->order("id")->limit($Page->firstRow.','.$Page->listRows)->select();
		 foreach($list as $k=>$v){
			    $schoolid=$v['objecthingid'];
				$list[$k]['schoolname']=M("school")->field("nickname")->where("userid='$schoolid'")->find()['nickname'];
		 }
		 $this->assign('list',$list);// 赋值数据集
         $this->assign('page',$show);// 赋值分页输出
         $this->assign('p',$Page->nowPage);// 第几页
         $this->assign('rowscount',$count);// 总得记录数
         $this->display(); //输出模板
	}
	public function evalinfo(){
		 $id=I("get.id");
		 $schoolid=I("get.schoolid");
		 $p=I("get.p");
		 $info=M("evaluating")->field("id,time,content")->where("id=$id")->find();
		 $schoolname=M("school")->field("nickname")->where("userid='$schoolid'")->find()['nickname'];
		 $this->assign('info',$info);// 总得记录数
		 $this->assign('p',$p);// 总得记录数
		 $this->assign('infojoin',$this->result($info));// 总得记录数
		 $this->assign('schoolname',$schoolname);// 总得记录数
		 $this->display(); //输出模板
	}
	public function evalupdate(){
		 $id=I("post.id");
       if(M('evaluating')->where("id=$id")->save($_POST)){		  
		   lastupdate('eval', $id);
           echo 1;
       }else{
           echo 0;
       }
	}
	public function deleval(){
		$id=I("get.id");
		$p=I("get.p");
		M("evaluating")->where("id=$id")->delete();
			  $u=U('evallist?&p='.$p);
              header("location:$u");
	
	}
}