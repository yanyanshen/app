<?php
namespace Ment\Controller;
use Think\Controller;
use Think\MUpload;
class TeaManageController extends CommonController {
    //教学管理
    function img_Manage($id,$t,$p){
        switch($t){
            case 'jx':
                $url='jx_list';
                $table='school';
                $c = C('conf.schoolupimg');
                $url='School/jx_list';
                break;
            case 'jl':
                $url='jl_list';
                $table='coach';
                $c = C('conf.coachupimg');
                $url='Coach/jl_list';
                break;
            default:
                $url='zdy_list';
                $table='guider';
                $c = C('conf.guiderupimg');
                $url='Guider/zdy_list';
                break;
        }
        $user=M($table)->field('userid,nickname')->where("id=$id")->find();
        $userid=$user['userid'];
        if(!empty($_POST)){
            $img = new MUpload();
            $mm = $img->dirup($c);
            foreach ($mm as $k => $v) {
                $mm[$k]['userid'] = $userid;
                $mm[$k]['imgtime'] = date("Y-m-d H:i:s");
            }
            $c = count($mm);
            M('img')->addAll($mm);
            M($table)->where("userid='$userid'")->setInc("piccount", $c);
        }
        $img=M('img')->field('id,imgname,imgtime')->where("userid='$userid'")->select();
        $this->assign('img',$img);
        $this->assign('t',$t);
        $this->assign('nickname',$user['nickname']);
        $this->assign('p',$p);
        $this->assign('id',$id);
        $this->assign('url',$url);
        $this->display();
    }
    //删除教学环境
    function del_img($id,$iid,$p,$t){
        $imgname=M('img')->field('imgname')->where("id=$iid")->find()['imgname'];
        if(M('img')->delete($iid)){
            unlink('.'.$imgname);
            unlink('.'.preg_replace('/small\//','',$imgname));
            $message='';
            $tt=0;
        }else{
            $message="<script>alert('删除失败')</script>";
            $tt=0.1;
        }
        $this->redirect('img_Manage',array('id'=>$id,'p'=>$p,'t'=>$t),$tt,$message);
    }
    //证件管理
    function zhengjian($id,$p,$t){
         $table=returntable($t);
         $user=M($table['table'])->field('userid,nickname')->where("id=$id")->find();
         $userid=$user['userid'];
         $img=M('papersimg')->field('id,imgurl,imgtime,paperstype')->where("userid='$userid'")->select();
         $this->assign('img',$img);
         $this->assign('id',$id);
         $this->assign('user',$user);
         $this->assign('p',$p);
         $this->assign('t',$t);
         $this->assign('url',$table['url']);
         $this->display();
    }
    //删除证件
    function del_zhengjian($id,$iid,$p,$t){
        $imgname=M('papersimg')->field('imgurl')->where("id=$iid")->find()['imgname'];
        if(M('papersimg')->delete($iid)){
            unlink('.'.$imgname);
            $message='';
            $tt=0;
        }else{
            $message="<script>alert('删除失败')</script>";
            $tt=0.1;
        }
        $this->redirect('zhengjian',array('id'=>$id,'p'=>$p,'t'=>$t),$tt,$message);
    }
    //地标管理
    function land_Manage($id,$p,$t){
        $table=returntable($t);
        $user=M($table['table'])->field('userid,nickname,cityid')->where("id=$id")->find();
        $userid=$user['userid'];
        $cityid=$user['cityid'];
        $county=M('countys')->field("id,countyname")->where("masterid=$cityid")->select();
        //找到该用户已经有的地标
        $land=returnland($t, $userid);
        //遍历找各自区、县的地标
        foreach ($county as $k=>$v){
            $cid=$v['id'];
            $county[$k]['land']=M('landmark')->field("id,landname")->where("masterid=$cid")->select();
        }
        $this->assign('county',$county);
        $this->assign('ids',2);
        $this->assign('land',$land);
        $this->assign('id',$id);
        $this->assign('user',$user);
        $this->assign('p',$p);
        $this->assign('t',$t);
        $this->assign('url',$table['url']);
        $this->display();
    }
    //更新地标
    function landsave($id,$p,$t,$userid){
        unset($_POST['id']);
        unset($_POST['p']);
        unset($_POST['t']);
        unset($_POST['userid']);
        switch ($t){
            case 'jx':
                $table="schoollands";
                $field="schoolid";
                break;
            case 'jl':
                $table="coachlands";
                $field="coachid";
                break;
            case 'zdy':
                $table="guiderlands";
                $field="guiderid";
                break;
        }
        foreach($_POST as $k=>$v){
            $land['landmarkid'].=$v.',';
        }
        $find=M($table)->where("$field='$userid'")->find();
        if($find){
            $result=M($table)->where("$field='$userid'")->setField('landmarkid',$land['landmarkid']);
        }else{
            $land[$field]=$userid;
            $result=M($table)->add($land);
        }
        if($result){
            $message="<script>alert('更新成功')</script>";
        }else{
            $message="<script>alert('更新失败')</script>";
        }
        $this->redirect("land_Manage",array('id'=>$id,'p'=>$p,'t'=>$t),0.1,$message);
    }
    //基地管里
    function train_Manage($id,$p,$t){
        switch ($t){
            case 'jx':
                $table="school";
                $table2="schooltrains";
                $field="schoolid";
                $url='School/jx_list';
                break;
            case 'jl':
                $table="coach";
                $table2="coachtrains";
                $field="coachid";
                $url='Coach/jl_list';
                break;
            case 'zdy':
                $table="guider";
                $table2="guidertrains";
                $field="guiderid";
                $url='Guider/zdy_list';
                break;
        }
        $user=M($table)->field('userid,nickname,cityid')->where("id=$id")->find();
        $userid=$user['userid'];
        //已经有的
        $trainid=M($table2)->field("trainid")->where("$field='$userid'")->find()['trainid'];
        $trains=explode(',',rtrim($trainid,','));
        $cityid=$user['cityid'];
        $cityname=M('citys')->field("cityname")->where("id=$cityid")->find()['cityname'];
        //总的
        $train=M('train')->field("id,trname,address")->where("cityid=$cityid")->select();
        $this->assign("id",$id);
        $this->assign("t",$t);
        $this->assign("p",$p);
        $this->assign("url",$url);
        $this->assign("cityname",$cityname);
        $this->assign("user",$user);
        $this->assign("trains",$trains);
        $this->assign("train",$train);
        $this->display();
    }
    //更新基地
    function trainsave($id,$p,$t,$userid){
        unset($_POST['id']);
        unset($_POST['p']);
        unset($_POST['t']);
        unset($_POST['userid']);
        switch ($t){
            case 'jx':
                $table="schooltrains";
                $field="schoolid";
                break;
            case 'jl':
                $table="coachtrains";
                $jltype=M('coach')->field('jltype,masterid,boss')->where("userid='$userid'")->find();
                if($jltype['jltype']=='0'){
                    $land['schoolid']=$jltype['masterid'];
                }else{
                    $land['boss']=$jltype['boss'];
                }
	        $field="coachid";
                break;
            case 'zdy':
                $table="guidertrains";
                $field="guiderid";
                break;
        }
        foreach($_POST as $k=>$v){
            $land['trainid'].=$v.',';
        }
        $find=M($table)->where("$field='$userid'")->find();
        if($find){
            $result=M($table)->where("$field='$userid'")->setField('trainid',$land['trainid']);
        }else{
            $land[$field]=$userid;
            $result=M($table)->add($land);
        }
        if($result){
	    if($t=='jl'){
                M('coach')->where("userid='$userid'")->setField("trainid",substr($land['trainid'],0,1));
            }
            $message="<script>alert('更新成功')</script>";
        }else{
            $message="<script>alert('更新失败')</script>";
        }
        $this->redirect("train_Manage",array('id'=>$id,'p'=>$p,'t'=>$t),0.1,$message);
    }
    //改变认证状态
    function verify($id,$p,$t,$flag){
        switch($t){
            case 'jx':
                $table="school";
                $url='School/jx_list';
                break;
            case 'jl':
                $table="coach";
                $url='Coach/jl_list';
                break;
            case 'zdy':
                $table="guider";
                $url='Guider/zdy_list';
                break;
        }
        $result=M($table)->where("id=$id")->setField('verify',$flag);
        if($result){
            $message='';
            $arr=array('p'=>$p);
            $tt=0;
        }else{
            $url="zhengjian";
            $tt=0.1;
            $arr=array('id'=>$id,'p'=>$p,'t'=>$t);
            $message="<script>alert('修改失败')</script>";
        }
        $this->redirect($url,$arr,$tt,$message);
    }
    //课程管理
    function class_Manage($id,$t,$p){
       $table=returntable($t);
       $user=M($table['table'])->field("userid,nickname")->find($id);
       $userid=$user['userid'];
       $field="id,tcid,name,carname,traintype,officialprice,whole517price,prepay517deposit,linetime,classtime,include";
       $class=M('trainclass')->field($field)->where("masterid='$userid'")->select();
       $this->assign('user',$user);
       $this->assign('class',$class);
       $this->assign('id',$id);
       $this->assign('t',$t);
       $this->assign('url',$table['url']);
       $this->assign('p',$p);
       $this->display();
    }
    //删除课程
    function del_class($id,$t,$p,$iid){
        if(M('trainclass')->delete($iid)){
            $message='';
            $tt=0;
        }else{
            $tt=0.1;
            $message="<script>alert('删除成功')</script>";
        }
        $this->redirect("class_Manage",array('id'=>$id,'p'=>$p,'t'=>$t),$tt,$message);
    }
    //添加课程
    function add_class($id,$t,$p){
        if(!empty($_POST)){
            unset($_POST['id']);
            $_POST['tcid']=M("trainclass")->field("max(id) as mid")->find()['mid']+1;
            if(M('trainclass')->add($_POST)){
                $message="<script>alert('添加成功')</script>";
                $this->redirect('class_Manage',array('id'=>$id,'p'=>$p,'t'=>$t),0.1,$message);
            }else{
                echo "<script>alert('添加失败')</script>";
            }
        }
        $table=returntable($t);
        $user=M($table['table'])->field("userid,nickname")->find($id);
        $this->assign("id",$id);
        $this->assign("user",$user);
        $this->assign("t",$t);
        $this->assign("p",$p);
        $this->display();
    }
    function edit_class($id,$t,$p,$iid){
        if(!empty($_POST)){
            if(M('trainclass')->save($_POST)){
                $message="<script>alert('更新成功')</script>";
                $this->redirect("class_Manage",array('id'=>$iid,'t'=>$t,'p'=>$p),0.1,$message);
            }else{
                echo "<script>alert('更新失败')</script>";
            }
        }
        $table=returntable($t);
        $user=M($table['table'])->field("userid,nickname")->find($iid);//1
        $field="id,name,carname,traintype,officialprice,whole517price,prepay517deposit,linetime,classtime,include";
        $class=M('trainclass')->field($field)->where("tcid=$id")->find();//3
        $this->assign('user',$user);
        $this->assign('class',$class);
        $this->assign('id',$id);
        $this->assign('iid',$iid);
        $this->assign('t',$t);
        $this->assign('p',$p);
        $this->display();
    }
    //学时价格
    function price_Manage($id,$t,$p){
         if(isset($_GET['iid'])){
             $iid=$_GET['iid'];
             if(!M('price')->delete($iid)){
                 echo "<script>alert('删除失败')</script>";
             }
         }
         $table=returntable($t);
         $user=M($table['table'])->field("userid,nickname")->find($id);
         $userid=$user['userid'];
         $field="id,weekdays,timebucket,price,jtype,subjects";
         $price=M('price')->field($field)->where("masterid='$userid'")->select();
         $this->assign("price",$price);
         $this->assign("id",$id);
         $this->assign("t",$t);
         $this->assign("p",$p);
         $this->assign('url',$table['url']);
         $this->assign("user",$user);
         $this->display();
    }
    //编辑价格
    function edit_price($id,$t,$p,$iid){
        if(!empty($_POST)){
            if(M('price')->save($_POST)){
                $message="<script>alert('更新成功')</script>";
                $this->redirect("price_Manage",array('id'=>$iid,'t'=>$t,'p'=>$p),0.1,$message);
            }else{
                echo "<script>alert('更新失败')</script>";
            }
        }
        $table=returntable($t);
        $user=M($table['table'])->field("userid,nickname")->find($iid);//1
        $price=M('price')->field('weekdays,timebucket,price,jtype,subjects')->find($id);
        $this->assign("id",$id);
        $this->assign("user",$user);
        $this->assign("iid",$iid);
        $this->assign("t",$t);
        $this->assign("p",$p); 
        $this->assign("price",$price);
        $this->display();
    }
    //添加课时
    function add_price($id,$t,$p){
        if(!empty($_POST)){
            unset($_POST['id']);
            if(M('price')->add($_POST)){
                $message="<script>alert('添加成功')</script>";
                $this->redirect('price_Manage',array('id'=>$id,'p'=>$p,'t'=>$t),0.1,$message);
            }else{
                echo "<script>alert('添加失败')</script>";
            }
        }
        $table=returntable($t);
        $user=M($table['table'])->field("userid,nickname")->find($id);
        $this->assign("id",$id);
        $this->assign("user",$user);
        $this->assign("t",$t);
        $this->assign("p",$p);
        $this->display();
    }
}
