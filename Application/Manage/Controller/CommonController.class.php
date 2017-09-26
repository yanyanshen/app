<?php
namespace Manage\Controller;
use Think\Controller;
header("Cache-Control: no-cache, must-revalidate"); 
class CommonController extends Controller {
    public function _initialize(){
        if(ACTION_NAME=='login' ||ACTION_NAME=='checkuser'||ACTION_NAME=='a'|| ACTION_NAME=='verify' ||session('?account')){
            return true;
        }else{ 
            $this->redirect('index/login');exit;
        }
    }
    //自定义
    public function returncity(){
        $city=M('citys')->field("id,cityname")->select();
        return $city;
    }
    //生成一个userid
    function guid(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = // "
            substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            ;// "
            return $uuid;
        }
    }
    public function noYet($arr,$arr1,$str){
        foreach ($arr as $k=>$v){
            foreach ($arr1 as $kk=>$vv){
                foreach($vv as $kkk=>$vvv){
                    if($vvv==$v[$str]){
                        unset($arr[$k]);
                    }
                }
            }
        }
        return $arr;
    }
    //将小数转化成百分数
    public function percent($n){
        return sprintf("%.2f", ($n*100)).'%';
    }
    public function result($data)
    {
        // 处理中文乱码
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $data[$key][$k] = $v;
                }
            }
        }
         
        return stripslashes(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    //根据出生日期来计算年龄
    public function birthday($birthday){
        list($year,$month,$day) = explode("-",$birthday);
        $year_diff = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff  = date("d") - $day;
        if ($day_diff < 0 || $month_diff < 0)
            $year_diff--;
            return $year_diff;
    }
    public function addlog($info){
    $data['account']=session('account');
    $data['ntime']=time();
    $data['nip']=sprintf('%u',ip2long($_SERVER['REMOTE_ADDR']));
    $data['info']=$info;
    M('adminlog')->add($data);
}
public function shenfen($type){
    switch($type){
        case 'jx':
            $table='school';
            break;
        case 'jl':
            $table='coach';
            break;
        case 'zdy':
            $table='guider';
            break;
            default:$table='user';break;
    }
    return $table;
}
 

//     public function search(){
//         try {
//             $city=$_POST['city'];
//             $county=$_POST['county'];
//             $address=$_POST['address'];
//             $cityid=M('citys')->field("id")->where("cityname like '%$city%'")->find()['id'];
//             $countyid=M('countys')->field("id")->where("countyname like '%$county%' and masterid=$cityid")->find()['id'];
//             $count=M('school')->field("userid,nickname,score,allcount")->where("nickname like '%$address%'")->find();
//             $uid=$count['userid'];
//             if($uid==''){//如果用户输入的不是驾校名，而是一个具体的位置
//                 $jwd=$this->curlGetWeb($city, $address);
//                 $land=$this->getAround($jwd['lat'],$jwd['lng'],10000);
//                 $minlat=$land[0];
//                 $maxlat=$land[1];
//                 $minlng=$land[2];
//                 $maxlng=$land[3];
//                 $landmark=M("landmark")->field("id,landname,longitude,latitude")->where("latitude>=$minlat and latitude<=$maxlat and longitude>=$minlng and longitude>=$maxlng")->select();
//                 foreach($landmark as $k=>$v){
//                     $id=$v['id'];
//                     $data=M('school s')->field("s.nickname,s.userid,s.score,s.allcount")->join("xueche1_schoolland l on l.schoolid=s.userid and l.landmarkid=$id")->order("rand()")->limit(5)->select();
//                 }
//                 $data=array_unique($data);
//                 $k = 0;
//                 $t=0;
//                 foreach($landmark as $k=>$v){
//                     foreach($data as $kk=>$vv){
//                         $sid=$vv['userid'];
//                         $dat[$kk]['name']=$vv['nickname'];
//                         $dat[$kk]['userid']=$sid;
//                         $dat[$kk]['score']=$vv['score'];
//                         $dat[$kk]['minprice']=M('trainclass')->field("min(whole517price)as minprice")->where("masterid='$sid'")->find()['minprice'];
//                         $dat[$kk]['landmark'][$k]['id']=$id;
//                         $dat[$kk]['landmark'][$k]['landname']=$v['landname'];
//                         $dat[$kk]['landmark'][$k]['longitude']=$v['longitude'];
//                         $dat[$kk]['landmark'][$k]['latitude']=$v['latitude'];
//                         $t=count($dat[$kk]['landmark']);
//                     }
//                 }
//             }else{
//                 $dat[0]['name']=$count['nickname'];
//                 $dat[0]['userid']=$uid;
//                 $dat[0]['allcount']=$count['allcount'];
//                 $dat[0]['score']=$count['score'];
//                 $dat[0]['minprice']=M('trainclass')->field("min(whole517price)as minprice")->where("masterid='$uid'")->find()['minprice'];
//                 $dat[0]['landmark']=M("schoolland s")->field("l.id,l.landname,l.longitude,l.latitude")->join("xueche1_landmark l on l.id=s.landmarkid and s.schoolid='$uid'")->select();
//             }
//             $info=$this->result(0, $dat);
//         } catch (Exception $e) {
//             $info = $this->zimg(1, "返回错误");
//         }
//         echo $info;
//     }
}
