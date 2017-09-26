<?php
namespace Admin\Controller;
use Org\Util\Page;
use Think\Controller;

class PublicController extends Controller
{
    public function shenfen($type){
        switch($type){
            case 'jl':
                $table="coach";
                break;
            case 'zdy':
                $table="guider";
                break;
            case 'jx':
                $table="school";
                break;
            default:
                $table="user";
                break;
        }
        return $table;
    }
    public function pd($account, $pass, $type, $agent)
    {
        $table=$this->shenfen($type);
        $m = M($table);
        if ($m->where("account='$account'")->count()==1) {
            $mm = $m->field("pass,nickname,userid,jtype,type,verify,subjects,verify,address,img")->where("account='$account'")->find();
            if ($pass == $mm['pass']){
                unset($mm['pass']);
                if ($type ==$mm['type']) {
                    $u = M('conf');
                    $url = $u->field("downloadURL")->find()['downloadURL'];
                    //$url['nickname'] = $mm['nickname'];
                    //$url['userid'] = $mm['userid'];
                  //  $url['verify'] = $mm['verify'];
                   // $url['type'] =$type;
                   // $url['img'] = $mm['img'];
                   // $url['jtype'] = $mm['jtype'];
                    //$url['subjects'] = $mm['subjects'];
                    //$url['address'] = $mm['address'];
					$mm['downloadURL']=$url;
                    $info = $this->type(0, "登录成功", $mm, $this->phone($agent), $type);
                    echo $info;
                    return;
                } else {
                    $info = $this->type(3, '身份错误', null);
                    echo $info;
                    return;
                }
            } else {
                $info = $this->type(2, '密码错误', null);
            }
        } else {
            $info = $this->type(1, '账号不存在', null);
        }
        echo $info;
    }
    //处理高并发注册产生唯一不重复的字符串
  public function guid(){
        if (!function_exists('com_create_guid')){
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

    /**
     * @会话已过时
     */
    public function over()
    {
        $arr = array(
            'code' => 10,
            'message' => "会话已超时请重新登录"
        );
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }
    /**
     * @函数：获得手机类型
     */
    public function phone($agent)
    {
        $iphone = (strpos($agent, 'cfnetwork')) ? true : false;
        $ipad = (strpos($agent, 'cfnetwork')) ? true : false;
        $android = (strpos($agent, 'android')) ? true : false;
        if ($iphone || $ipad) {
            $v = 'ios';
        }
        if ($android) {
            $v = 'an';
        }
        return $v;
    }
    // 为登录提供服务
    public function type($code, $message, $data, $v = 'ios', $t)
    {
        if (! is_numeric($code)) {
            return '';
        }
        // 版本问题
        $vv = $this->ver($v, $t);
        // 处理中文乱码
        foreach ($data as $key => $value) {
            $date[$key] = urlencode($value);
        }
        $message = urlencode($message);
        $arr = array(
            'code' => $code,
            'latest' => $vv,
            'session_id' => session_id(),
            'result' => $date
        );
        $arr1 = array(
            'code' => $code,
            'message' => $message
        );
        // 'result'=>null
        
        $arr2 = array(
            'code' => $code,
            'message' => $message
        );
        $arr3 = array(
            'result' => $date
        );
        if ($code == - 2) {
            return urldecode(json_encode($arr3));
        }
        if (is_array($date)) {
            return stripslashes(urldecode(json_encode($arr)));
        } else 
            if ($date == - 1) {
                return urldecode(json_encode($arr2));
            } else {
                return urldecode(json_encode($arr1));
            }
    }

    public function ver($v, $t)
    {
        $vv = M('version')->select();
        if ($v == 'ios') {
            if ($t == 'jl') {
                $max = $vv[1]['maxversion'];
            } else {
                $max = $vv[3]['maxversion'];
            }
        } else 
            if ($v == 'an') {
                if ($t == 'jl') {
                    $max = $vv[0]['maxversion'];
                } else {
                    $max = $vv[2]['maxversion'];
                }
            } else {
                $max = 120;
            }
        return $max;
    }

    /**
     * @该函数作为公共函数，返回系统的最新版本
     * @当软件刚打开的时候就返回最新版本信息
     */
    public function goback($code = 0, $v = 'ios', $t = 'jl')
    {
        // 版本问题
        $vv = M('version')->select();
        if ($v == 'ios') {
            if ($t == 'jl') {
                $vs = $vv[1]['minversion'];
                $max = $vv[1]['maxversion'];
            } else {
                $vs = $vv[3]['minversion'];
                $max = $vv[3]['maxversion'];
            }
        } else 
            if ($v == 'an') {
                if ($t == 'jl') {
                    $vs = $vv[0]['minversion'];
                    $max = $vv[0]['maxversion'];
                } else {
                    $vs = $vv[2]['minversion'];
                    $max = $vv[2]['maxversion'];
                }
            } else {
                $max = 120;
                $vs = 120;
            }
        $arr = array(
            'code' => $code,
            'latest' => $vs, // 返回该手机所使用的最新版本
            'max' => $max
        );
        return json_encode($arr);
    }
    // result()为用户登录后提供服务
    public function result($code, $data)
    {
        if (! is_numeric($code)) {
            return '';
        }
        // 处理中文乱码
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $data[$key][$k] = $v;
                }
            }
        }
        $arr = array(
            'code' => $code,
            'result' => $data
        );
        if ($code == 0) {
            return stripslashes(json_encode($arr, JSON_UNESCAPED_UNICODE));
        }
    }
    /*
     * 文件上传结果,只返回code和提示
     */
    public function zimg($code, $message)
    {
        $arr = array(
            "code" => $code,
            "message" => $message
        );
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }
    // result()为用户修改自己的信息提供服务
    public function update($code, $message, $data)
    {
        if (! is_numeric($code)) {
            return '';
        }
        $message = urlencode($message);
        // 处理中文乱码
        foreach ($data as $key => $value) {
            $date[$key] = urlencode($value);
        }
        $arr = array(
            'code' => $code,
            'message' => $message,
            'result' => $date
        );
        if ($code == 0) {
            return urldecode(json_encode($arr));
        }
    }
	/**
     * @我的教练里面点击详情-》培训价格
     */

    public function trainprice($masterid){
            $data=M("price")->field("id,weekdays,timebucket,price,jtype")->where("masterid='$masterid'")->select();
            $subject['two']=array_slice($data,0,6);
            $subject['three']=array_slice($data,6,11);
            return $subject;
    }
    /**
     * 文件上传
     */
    public function dirup($account, $dir, $push)
    {
        $j = 0;
		$arr=['jpg','png'];
        if ($_FILES['ImageField']['size'] < 1024 * 1024 * 2) {
            if (! file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            if (in_array($push,$arr)) {
                move_uploaded_file($_FILES['ImageField']['tmp_name'], $dir . '/' . $account . '.' . $push);
                 ++$j;
            }
            if ($j > 0) {
                    $this->img_cl($account, "./upload/big", 100,$push);
                return "上传成功";
            }
        } else {
            return '所选文件过大，请更改设置或重新选择！';
        }
    }

    /**
     * 生成缩略图
     */
    public function img_cl($userid, $dir, $w,$push)
    {
		
        $dirname = $dir . '/' . $userid . '.'.$push;
      //  $path = pathinfo($dirname, PATHINFO_EXTENSION);
        $dirr = pathinfo($dirname, PATHINFO_DIRNAME);
		if($push==png){
			 $i = imagecreatefrompng($dirname);
		}else{
			$i = imagecreatefromjpeg($dirname);
		}
       
        $ww = imagesx($i);
        $hh = imagesy($i);
        $h = floor($hh * ($w / $ww));
        $img = imagecreatetruecolor($w, $h);
        imagecopyresized($img, $i, 0, 0, 0, 0, $w, $h, $ww, $hh);
		if($push=='png'){
			header("content-type:image/png");
		}else{
		    header("content-type:image/jpeg");
		}
       // $filename = pathinfo($dir . '/' . $userid. '.'.$push, PATHINFO_BASENAME); // 返回文件名.拓展名
        imagejpeg($img, './upload/small/' . $userid . '.' . $push);
    }

    /**
     * 删除session
     */
    public function unsession($phone)
    {
   
        unset($_SESSION[$phone . 'a']);
        unset($_SESSION[$phone . 'b']);
    }

    /**
     * 获取列表及评论内容
     */
    /*
     * 订单中心
     */
    public function getMyList($mode,$userid,$start){
        $m=M('list');
        /*
         * mode 订单类型 1--驾校  2--教练 3--指导员 4 预约
         * state 订单状态 0 --代支付 1 待确认 2 待评价 3 已完成   4 已取消
         */
        $modetype=$m->field("mode,objectid,listid")->where("masterid='$userid' and mode=$mode and flag=1")->select();
        if($mode==1){
            $fields="s.name,s.img,l.objectid,l.phone,l.masterid,l.stucount,l.applymode,l.price,l.address,l.remark,l.mode,l.paidnum,l.masname,l.couponmode,l.listid,l.listtime,l.state,l.classid,l.description,t.name as classname";
            $data1=M("school s")->field($fields)->join("xueche1_list l on s.userid=l.objectid and l.masterid='$userid' and mode=$mode")->join("xueche1_trainclass t on l.classid=t.tcid")->order("l.id desc")->limit("$start,10")->select();
            return $data1;
        }else if($mode==2 || $mode==3){
            foreach($modetype as $v){
                $fields="s.nickname,s.img,l.objectid,l.phone,l.masterid,l.stucount,l.applymode,l.price,l.address,l.remark,l.mode,l.paidnum,l.masname,l.couponmode,l.listid,l.listtime,l.state";
                $data2=M("user s")->field($fields)->join("xueche1_list l on s.userid=l.objectid and l.masterid='$userid' and mode=$mode")->order("l.id desc")->limit("$start,10")->select();
            }
            return $data2;
        } else if($mode==4){
            foreach($modetype as $k=>$v){
                $lid=$v["listid"];
                $fields="s.nickname,s.img,l.objectid,l.phone,l.masterid,l.stucount,l.applymode,l.price,l.address,l.remark,l.mode,l.paidnum,l.masname,l.couponmode,l.listid,l.listtime,l.state";
                $data3=M("user s")->field($fields)->join("xueche1_list l on s.userid=l.objectid and l.masterid='$userid' and mode=$mode")->order("l.id desc")->limit("$start,10")->select();     
                $d[$k]['duration']= M("reservation")->field("duration")->where("listid=$lid")->find()['duration'];
                $data4[]=array_merge($d[$k],$data3[$k]);
            }
            return $data4;
        }
    }
    /**
     * 订单分类
     * 
     */
    public function getMyListClass($mode,$userid,$start,$class){
        $m=M('list');
        /*
         * mode 订单类型 1--驾校  2--教练 3--指导员 4 预约
         * state 订单状态 0 --代支付 1 待确认 2 待评价 3 已完成   4 已取消
         */
        $modetype=$m->field("mode,objectid,listid")->where("masterid='$userid' and mode=$mode and flag=1")->select();
        if($mode==1){
            $fields="s.nickname,s.img,l.objectid,l.phone,l.masterid,l.id,l.stucount,l.applymode,l.price,l.address,l.remark,l.mode,l.paidnum,l.masname,l.couponmode,l.listid,l.listtime,l.state,l.classid,l.description,t.name as classname";
            $data1=M("school s")->field($fields)->join("xueche1_list l on s.userid=l.objectid and l.masterid='$userid' and mode=$mode and state=$class ")->join("xueche1_trainclass t on l.classid=t.tcid")->order("l.id desc")->limit("$start,10")->select();
            return $data1;
        }else if($mode==2 || $mode==3){
            foreach($modetype as $v){
                $fields="s.nickname,s.img,l.objectid,l.phone,l.masterid,l.stucount,l.applymode,l.price,l.address,l.remark,l.mode,l.paidnum,l.masname,l.couponmode,l.listid,l.listtime,l.state";
                $data2=M("user s")->field($fields)->join("xueche1_list l on s.userid=l.objectid and l.masterid='$userid' and mode=$mode")->order("l.id desc")->limit("$start,10")->select();
            }
            return $data2;
        } else if($mode==4){
            foreach($modetype as $k=>$v){
                $lid=$v["listid"];
                $fields="s.nickname,s.img,l.objectid,l.phone,l.masterid,l.stucount,l.applymode,l.price,l.address,l.remark,l.mode,l.paidnum,l.masname,l.couponmode,l.listid,l.listtime,l.state";
                $data3=M("user s")->field($fields)->join("xueche1_list l on s.userid=l.objectid and l.masterid='$userid' and mode=$mode and state=$class ")->order("l.id desc")->limit("$start,10")->select();
                $d[$k]['duration']= M("reservation")->field("duration")->where("listid=$lid")->find()['duration'];
                $data4[]=array_merge($d[$k],$data3[$k]);
            }
            return $data4;
        }
    }
    /**
     * 
     */
    public function jh($mm,$latitude,$longitude){
        $t='';
        for($i=0;$i<count($mm);$i++){
            for($j=0;$j<count($mm);$j++){
                if(!empty($mm[$j]['location']) && !empty($mm[$j+1]['location'])){
                    if($this->getDistance($mm[$j]['location']['latitude'],$mm[$j]['location']['longitude'],$latitude,$longitude)>$this->getDistance($mm[$j+1]['latitude'],$mm[$j+1]['longitude'],$latitude,$longitude)){
                        $t=$mm[$j];
                        $mm[$j]=$mm[$j+1];
                        $mm[$j+1]=$t;
                    }
                }
            }
        }
        return $mm;
    }
    /**
     * 根据两个经纬度计算两点距离
     */
    function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000; //approximate radius of earth in meters
        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;
        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance);
    }
	/**
	*/
	public function getAround($lat,$lon,$raidus){
	$PI = 3.14159265;
	$latitude = $lat;
	$longitude = $lon;
	$degree = (24901*1609)/360.0;
	$raidusMile = $raidus;
	$dpmLat = 1/$degree;
	$radiusLat = $dpmLat*$raidusMile;
	$minLat = $latitude -$radiusLat;
	$maxLat = $latitude + $radiusLat;
	$mpdLng = $degree*cos($latitude * ($PI/180));
	$dpmLng = 1 / $mpdLng;
	$radiusLng = $dpmLng*$raidusMile;
	$minLng = $longitude -$radiusLng;
	$maxLng = $longitude + $radiusLng;
	$a[]=$minLat;
	$a[]=$maxLat;
	$a[]=$minLng;
	$a[]=$maxLng;
	return $a;
}
    /**
     * 生成一个经纬度
     */
    function curlGetWeb($area,$address)
    {//96980ac7cf166499cbbcc946687fb414
        $Url="http://api.map.baidu.com/geocoder?address=".trim($area).trim($address)."&output=json&key=ASESdpTH1lcn6HbficDjIxGbpHyVAs3P";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出
        $result=curl_exec($ch);
        curl_close($ch);
        $infolist=json_decode($result);
        $array=array();
        if(isset($infolist->result->location) && !empty($infolist->result->location)){
            $array=array(
                'lng'=>$infolist->result->location->lng,
                'lat'=>$infolist->result->location->lat,
            );
            return $array;
        }
        else
        {
            return false;
        }
    }
	 //注册时一次统计多张表看是否存在
    public function issetAccount($account){
        $user=M("user")->where("account='$account'")->count();
        $coach=M("coach")->where("account='$account'")->count();
        $guider=M("guider")->where("account='$account'")->count();
        $school=M("school")->where("account='$account'")->count();
        $count=$user+$coach+$guider+$school;
        return $count;
    }
	 public function getSchoolList($start,$cityid,$latitude,$longitude)
    {
        $field = "id,userid,nickname,pickrange,score,allcount,price,img";
        $erro = "驾校";
        $m = M("school");
        if ($m->count()> 0) {
            $mm = $m->field($field)
            ->where("cityid=$cityid")
            ->limit("$start,5")
            ->select();
			   for($k=0;$k<count($mm);$k++){
                $mas=$mm[$k]['userid'];
                $mm[$k]['minprice']=M('trainclass')->field("min(whole517price)as minprice")->where("masterid='$mas'")->find()['minprice'];
                $stu=M('sign')->field("userid,stime")->where("masterid='$mas'")->order("id desc")->find();
                $stuid=$stu['userid'];
                $mm[$k]['student']=M('user')->field("nickname")->where("userid='$stuid'")->find()['nickname'];
                $mm[$k]['signtime']=$stu['stime'];
				$mm[$k]['landmark']=M("schoolland s")->field("l.id,l.longitude,l.latitude")->join("xueche1_landmark l on l.id=s.landmarkid and s.schoolid='$mas'")->order("rand()")->limit(20)->select();
		   }
            //$mm=$this->jh($mm,$latitude,$longitude);
            $info=$this->result(0, $mm);
        } else {
            $info = $this->type(1, "没有任何驾校" . $erro, - 1);
        }
        echo $info;
    }
    public function getCoachList($start,$cityid,$latitude,$longitude)
    {
        $field = "id,userid,nickname,img,sex,birthday,masterid,teachedage,score,passedcount,allcount,evalutioncount,praisecount";
        $m = M("coach");
        if ($m->count() > 0) {
               $mm = $m->field($field)->where("cityid=$cityid")->limit("$start,5")->select();
            foreach($mm as $k=>$v){
                $id=$v['userid'];
                $mas=$mm[$k]['masterid'];
                $mm[$k]['location']=M('address')->field("latitude,longitude")->where("masterid='$id'")->find();
                $mm[$k]['minprice']=M('trainclass')->field("min(whole517price)as minprice")->where("masterid='$mas'")->find()['minprice'];
				$mm[$k]['landmark']=M("coachland s")->field("l.id,l.longitude,l.latitude")->join("xueche1_landmark l on l.id=s.landmarkid and s.coachid='$id'")->order("rand()")->limit(20)->select();
            }
            $mm=$this->jh($mm,$latitude,$longitude);
            $info=$this->result(0, $mm);
        } else {
            $info = $this->type(1, "没有任何教练", - 1);
        }
        echo $info;
    }
    public function getGuiderList($start,$cityid,$latitude,$longitude)
    {
        $field = "id,userid,nickname,img,sex,birthday,teachedage,score,passedcount,allcount,evalutioncount,praisecount";
        $erro = "指导员";
        $m = M("guider");
        if ($m->count() > 0) {
            $mm = $m->field($field)->where("cityid=$cityid")->limit("$start,5")->select();
            foreach($mm as $k=>$v){
                $mas=$mm[$k]['userid'];
                $mm[$k]['location']=M('address')->field("latitude,longitude")->where("masterid='$mas'")->find();
                $mm[$k]['minprice']=M('trainclass')->field("min(whole517price)as minprice")->where("masterid='$mas'")->find()['minprice'];
				$mm[$k]['landmark']=M("guiderland s")->field("l.id,l.longitude,l.latitude")->join("xueche1_landmark l on l.id=s.landmarkid and s.guiderid='$mas'")->order("rand()")->limit(20)->select();
            }
            $mm=$this->jh($mm,$latitude,$longitude);
            $info=$this->result(0, $mm);
        } else {
            $info = $this->type(1, "没有任何指导员", - 1);
        }
        echo $info;
    }
    //语言过滤
    public function Filter_word($str)
    {
        $words=file("./bd.txt");
        $string = strtolower($str);
        foreach($words as $k=>$v){
            $word = preg_replace("/\r\n|\r\n/i", '', $v);
            if(false != strtr($string,$word)){
                return 1;
            }
        }
        return $string;
    }

}