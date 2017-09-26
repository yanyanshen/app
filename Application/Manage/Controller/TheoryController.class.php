<?php
namespace Manage\Controller;
use Think\Controller;
use Org\Util\Page;
use Manage\Model\TheoryModel;
/**
 * 
 * @author 陈龙龙
 *  2016.7.25始 对学员的操作
 */
class TheoryController extends CommonController {
    public function speedlist(){
		 $Data=M("speed"); // 实例化Data数据对象
         $count=$Data->count();// 查询满足要求的总记录数 $map表示查询条件
         $Page=new Page($count,12);// 实例化分页类 传入总记录数
         $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
         $show=$Page->show($Page->nowPage);//分页显示输出
         // 进行分页数据查询
         $list = $Data->field("id,userid,tid,subjectid,chapterid,indexs,dotime")->order("id")->limit($Page->firstRow.','.$Page->listRows)->select();
		 foreach($list as $k=>$v){
			    $userid=$v['userid'];
				$list[$k]['nickname']=M("user")->field("nickname")->where("userid='$userid'")->find()['nickname'];
		 }
		 $this->assign('list',$list);// 赋值数据集
         $this->assign('page',$show);// 赋值分页输出
         $this->assign('p',$Page->nowPage);// 第几页
         $this->assign('rowscount',$count);// 总得记录数
         $this->display(); //输出模板
	}
	public function theoryList($p=1){
	    $where='';
	     if(!empty($_POST)){
	        if(isset($_POST['answer'])){
	            $th=new TheoryModel();
	            $theory=$th->create();
	            if($theory){
	                if($th->add($theory)){
				 $post['tid']=$th->field("max(id) as id")->find()['id'];
	                    $post['analysis']=$_POST['analysis'];
	                    M('reply')->add($post);
	                    $this->redirect("theoryList",array(),0.1,"<script>alert('添加成功')</script>");
		        }else{
	                    $this->redirect("theoryList",array(),0.1,"<script>alert('添加失败')</script>");
	                }
	            }else{
                    $this->assign("errorInfo",$th->getError());
			$this->display('addTheory'); //输出模板
                    return;
                }
	        }else{
	            $question=$_POST['question'];
	            $where="question like '%$question%'";
	        }
	    }
	    $Data=M("theory"); // 实例化Data数据对象
	    $count=$Data->where($where)->count();// 查询满足要求的总记录数 $map表示查询条件
	    $Page=new Page($count,12);// 实例化分页类 传入总记录数
	    $Page->nowPage=$p;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
	
	    $show=$Page->show($Page->nowPage);//分页显示输出
	    // 进行分页数据查询
	    $list = $Data->field("id,question,a,b,c,d,ChapterId,Classid,subjects,imgurl,answer")->where($where)->order("id")->limit($Page->firstRow.','.$Page->listRows)->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
	    $this->assign('p',$Page->nowPage);// 第几页
	    $this->assign('rowscount',$count);// 总得记录数
	    $this->display(); //输出模板
	}
	public function updatetheory($id,$p){
	    if(!empty($_POST)){
	        if(M('theory')->save($_POST)){
	            $mess= "<script>alert('修改成功')</script>";
	            $this->redirect("theoryList",array('p'=>$p),0.1,$mess);return;
	        }else{
	            echo "<script>alert('修改失败')</script>";
	        }
	    }
        $theory=M('theory')->field("id,question,a,b,c,d,ChapterId,Classid,subjects,imgurl,answer")->find($id);
        $this->assign('theory',$theory);// 赋值分页输出
        $this->assign('p',$p);// 赋值分页输出
        $this->display(); //输出模板
	}	
	//获取试题分析
	public function reply($tid,$p){
	    if(!empty($_POST)){
	        $analysis=$_POST['analysis'];
	        if(M('reply')->where("tid=$tid")->setField('analysis',$analysis)){
	            $mess= "<script>alert('修改成功')</script>";
	            $this->redirect("theoryList",array('p'=>$p),0.1,$mess);return;
	        }else{
	            echo "<script>alert('修改失败')</script>";
	        }
	    }
	    $reply=M('reply')->field("analysis")->where("tid=$tid")->find()['analysis'];
	    $this->assign('reply',$reply);
	    $this->assign('tid',$tid);
	    $this->assign("p",$p);
	    $this->display();
	}
}
