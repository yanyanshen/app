<?php
namespace Admin\Controller;

use Think\Controller;

class SearchController extends Controller
{
	 public function newSearch($city='上海',$county='',$address){
        try {
            $cityid=M('citys')->field("id")->where("cityname like '%$city%'")->find()['id'];
            $countyid=M('countys')->field("id")->where("countyname like '%$county%' and masterid=$cityid")->find()['id'];
            $count['school']=M('school')->field("userid,nickname,score,allcount")->where("nickname like '%$address%' and cityid=$cityid")->select();
            if(empty($count['school'])){
                $jwd=curlGetWeb($city, $address);
                $land=getAround($jwd['lat'],$jwd['lng'],10000);
                $minlat=$land[0]; $maxlat=$land[1];$minlng=$land[2];$maxlng=$land[3];
                $landmark['land']=M("landmark")->field("id,landname,longitude,latitude")->where("latitude>=$minlat and latitude<=$maxlat and longitude>=$minlng and longitude<=$maxlng")->order("rand()")->limit(33)->select();
                foreach($landmark['land'] as $k=>$v){
                    $id=$v['id'];
                    $data[$k]=M('school s')->field("s.nickname,s.userid,s.score,s.allcount")->Distinct(true)->join("xueche1_schoolland l on l.schoolid=s.userid and l.landmarkid=$id")->order("rand()")->limit(3)->select();
					  if(empty($data[$k]) ||$data[$k]==null){
							 $landmark['land'][$k]['school']=[];
					 }else{
					         $landmark['land'][$k]['school']=$data[$k];
					 }
                }
					$coun['flag']=0;
            }else{
				$coun['flag']=1;
				foreach($count['school'] as $k=>$v){
					$uid=$v['userid'];
					$count['school'][$k]['landmark']=M("schoolland s")->field("l.id,l.landname,l.longitude,l.latitude")->join("xueche1_landmark l on l.id=s.landmarkid and s.schoolid='$uid'")->order("rand()")->limit(50)->select();
				}
				echo  result(0, array_merge($count,$coun));
				return;
            }
            $info=result(0, array_merge($landmark,$coun));
        } catch (Exception $e) {
            $info = zimg(1, "返回错误");
        }
        echo $info;
    }
	 public function newSearchCoach1($city,$county,$address){
        try {
            $cityid=M('citys')->field("id")->where("cityname like '%$city%'")->find()['id'];
            $countyid=M('countys')->field("id")->where("countyname like '%$county%' and masterid=$cityid")->find()['id'];
			$jwd=curlGetWeb($city, $address);
			$land=getAround($jwd['lat'],$jwd['lng'],10000);
			$minlat=$land[0];$maxlat=$land[1];$minlng=$land[2];$maxlng=$land[3];
		     $landmark['land']=M("landmark")->field("id,landname,longitude,latitude")->where("latitude>=$minlat and latitude<=$maxlat and longitude>=$minlng and longitude<=$maxlng")->order("rand()")->limit(50)->select();
			foreach($landmark['land'] as $k=>$v){
				$id=$v['id'];
				$data[$k]=M('coach s')->field("s.nickname,s.userid,s.score,s.allcount")->Distinct(true)->join("xueche1_coachland l on l.coachid=s.userid and l.landmarkid=$id")->select();
				   if(empty($data[$k]) ||$data[$k]==null){
							$landmark['land'][$k]['coach']=[];
					 }else{
						  $landmark['land'][$k]['coach']=$data[$k];
					 }
			}
            $info=result(0, $landmark);
        } catch (Exception $e) {
            $info = zimg(1, "返回错误");
        }
        echo $info;
    }
	public function newSearchGuider2($city,$county,$address){
        try {
            $cityid=M('citys')->field("id")->where("cityname like '%$city%'")->find()['id'];
            $countyid=M('countys')->field("id")->where("countyname like '%$county%' and masterid=$cityid")->find()['id'];
			 $jwd=curlGetWeb($city, $address);
                $land=getAround($jwd['lat'],$jwd['lng'],10000);
                $minlat=$land[0]; $maxlat=$land[1];$minlng=$land[2];$maxlng=$land[3];
                $landmark['land']=M("landmark")->field("id,landname,longitude,latitude")->where("latitude>=$minlat and latitude<=$maxlat and longitude>=$minlng and longitude<=$maxlng")->order("rand()")->limit(33)->select();
			foreach($landmark['land'] as $k=>$v){
				$id=$v['id'];
				$data[$k]=M('guider s')->field("s.nickname,s.userid,s.score,s.allcount")->Distinct(true)->join("xueche1_guiderland l on l.guiderid=s.userid and l.landmarkid=$id")->select();
				  if(empty($data[$k]) ||$data[$k]==null){
							 $landmark['land'][$k]['guider']=[];
				  }else{
							$landmark['land'][$k]['guider']=$data[$k];
				  }
			}
            $info=result(0, $landmark);
        } catch (Exception $e) {
            $info = zimg(1, "返回错误");
        }
        echo $info;
    }
	  //搜索
	 public function newSearchCoach($city,$county,$address){
        try {
            $cityid=M('citys')->field("id")->where("cityname like '%$city%'")->find()['id'];
            $countyid=M('countys')->field("id")->where("countyname like '%$county%' and masterid=$cityid")->find()['id'];
            $count['coach']=M('coach')->field("userid,nickname,score,allcount")->where("nickname like '%$address%'  and cityid=$cityid")->select();
            if(empty($count['coach'])){
                $jwd=curlGetWeb($city, $address);
                $land=getAround($jwd['lat'],$jwd['lng'],10000);
                $minlat=$land[0]; $maxlat=$land[1];$minlng=$land[2];$maxlng=$land[3];
                $landmark['land']=M("landmark")->field("id,landname,longitude,latitude")->where("latitude>=$minlat and latitude<=$maxlat and longitude>=$minlng and longitude<=$maxlng")->order("rand()")->limit(33)->select();
                foreach($landmark['land'] as $k=>$v){
                    $id=$v['id'];
                    $data[$k]=M('coach s')->field("s.nickname,s.userid,s.score,s.allcount")->Distinct(true)->join("xueche1_coachland l on l.coachid=s.userid and l.landmarkid=$id")->order("rand()")->limit(3)->select();
					  if(empty($data[$k]) ||$data[$k]==null){
							 $landmark['land'][$k]['coach']=[];
					 }else{
					         $landmark['land'][$k]['coach']=$data[$k];
					 }
                }
					$coun['flag']=0;
            }else{
				$coun['flag']=1;
				foreach($count['coach'] as $k=>$v){
					$uid=$v['userid'];
					$count['coach'][$k]['landmark']=M("coachland s")->field("l.id,l.landname,l.longitude,l.latitude")->join("xueche1_landmark l on l.id=s.landmarkid and s.coachid='$uid'")->order("rand()")->limit(50)->select();
				}
				echo  result(0, array_merge($count,$coun));
				return;
            }
            $info=result(0, array_merge($landmark,$coun));
        } catch (Exception $e) {
            $info = zimg(1, "返回错误");
        }
        echo $info;
    }
	 public function newSearchGuider($city,$county,$address){
        try {
            $cityid=M('citys')->field("id")->where("cityname like '%$city%'")->find()['id'];
            $countyid=M('countys')->field("id")->where("countyname like '%$county%' and masterid=$cityid")->find()['id'];
            $count['guider']=M('guider')->field("userid,nickname,score,allcount")->where("nickname like '%$address%'  and cityid=$cityid")->select();
            if(empty($count['guider'])){
                $jwd=curlGetWeb($city, $address);
                $land=getAround($jwd['lat'],$jwd['lng'],10000);
                $minlat=$land[0]; $maxlat=$land[1];$minlng=$land[2];$maxlng=$land[3];
                $landmark['land']=M("landmark")->field("id,landname,longitude,latitude")->where("latitude>=$minlat and latitude<=$maxlat and longitude>=$minlng and longitude<=$maxlng")->order("rand()")->limit(33)->select();
                foreach($landmark['land'] as $k=>$v){
                    $id=$v['id'];
                    $data[$k]=M('guider s')->field("s.nickname,s.userid,s.score,s.allcount")->Distinct(true)->join("xueche1_guiderland l on l.guiderid=s.userid and l.landmarkid=$id")->order("rand()")->limit(3)->select();
					  if(empty($data[$k]) ||$data[$k]==null){
							 $landmark['land'][$k]['guider']=[];
					 }else{
					         $landmark['land'][$k]['guider']=$data[$k];
					 }
                }
					$coun['flag']=0;
            }else{
				$coun['flag']=1;
				foreach($count['guider'] as $k=>$v){
					$uid=$v['userid'];
					$count['guider'][$k]['landmark']=M("guiderland s")->field("l.id,l.landname,l.longitude,l.latitude")->join("xueche1_landmark l on l.id=s.landmarkid and s.guiderid='$uid'")->order("rand()")->limit(50)->select();
				}
				echo  result(0, array_merge($count,$coun));
				return;
            }
            $info=result(0, array_merge($landmark,$coun));
        } catch (Exception $e) {
            $info = zimg(1, "返回错误");
        }
        echo $info;
    }
	public function newschoolsearch_2($city='上海',$county='杨浦',$address='陆家嘴',$latitude=31.232292,$longitude=121.52254){
        try {
            $cityid=M('citys')->field("id")->where("cityname like '%$city%'")->find()['id'];
            $countyid=M('countys')->field("id")->where("countyname like '%$county%' and masterid=$cityid")->find()['id'];
            $data['school']=M('school')->field("userid,nickname,score,allcount")->where("nickname like '%$address%' and cityid=$cityid and verify=3")->select();
            if($data['school']){
		 foreach ($data['school'] as $k=>$v){
                    $userid=$v['userid'];
                    $land[$k]=M('schoollands')->field('landmarkid')->where("schoolid='$userid'")->find()['landmarkid'];
                    if($land[$k]){
                        $land[$k]=explode(',',rtrim($land[$k],','));
                        foreach($land[$k] as $kk=>$vv){
                            $data['school'][$k]['landmark'][$kk]=M('landmark')->field('id,landname,longitude,latitude')->where("id=$vv")->find();
                            if(empty($data['school'][$k]['landmark'][$kk])){
				unset($data['school'][$k]['landmark'][$kk]);	
			    }
				shuffle($data['school'][$k]['landmark']);
			} 
                    }else{
                        $data['school'][$k]['landmark']=[];
                        continue;
                    }
                }$data['flag']=1;
                echo result(0,$data);
            }else{
    //           $lands=getAround($latitude,$longitude,10000);
      //         $minlat=$lands[0]; $maxlat=$lands[1];$minlng=$lands[2];$maxlng=$lands[3];
		$land=returnSquarePoint($longitude,$latitude,10);
	//	dump($land);
	           $minlat=$land['left-bottom']['lat']; $maxlat=$land['left-top']['lat'];$minlng=$land['left-top']['lng'];$maxlng=$land['right-top']['lng'];
               $landd['land']=M('landmark')->field('id,longitude,latitude,landname')->where("cityid=$cityid and latitude>=$minlat and latitude<=$maxlat and longitude>=$minlng and longitude<=$maxlng")->select();
               foreach ($landd['land'] as $k=>$v){
                   $id=$v['id'];
                   $find=M('schoollands')->where("locate($id,landmarkid)")->select();
                   if($find){
                       $landd['land'][$k]['school']=M('schoollands l')->field("s.userid,s.nickname,s.score,s.allcount")->join("xueche1_school s on s.userid=l.schoolid and locate($id,l.landmarkid)")->order("rand()")->limit(3)->select();
                   }else{
                       unset($landd['land'][$k]);
                   }
               }
               $landd['land']=array_reverse($landd['land']);
		$landd['flag']=0;
              echo result(0,$landd);
            }
        } catch (Exception $e) {
            $info = zimg(1, "返回错误");
        }
        echo $info;
    }
    public function newcoachsearch_2($city='上海',$county='杨浦',$address='长阳路',$latitude='31.274964',$longitude='121.539427'){
        try {
            $cityid=M('citys')->field("id")->where("cityname like '%$city%'")->find()['id'];
            $countyid=M('countys')->field("id")->where("countyname like '%$county%' and masterid=$cityid")->find()['id'];
            $data['coach']=M('coach')->field("userid,nickname,score,allcount")->where("nickname like '%$address%' and cityid=$cityid and verify=3")->select();
            if($data['coach']){
            	 foreach ($data['coach'] as $k=>$v){
                    $userid=$v['userid'];
                    $land[$k]=M('coachlands')->field('landmarkid')->where("coachid='$userid'")->find()['landmarkid'];
                    if($land[$k]){
                        $land[$k]=explode(',',rtrim($land[$k],','));
                        foreach($land[$k] as $kk=>$vv){
                            $data['coach'][$k]['landmark'][$kk]=M('landmark')->field('id,landname,longitude,latitude')->where("id=$vv")->find();
                            if(empty($data['coach'][$k]['landmark'][$kk])){
                                unset($data['coach']['landmark'][$kk]);      
                            }
			   shuffle($data['coach'][$k]['landmark']);   
			} 
                    }else{
                        $data['coach'][$k]['landmark']=[];
                        continue;
                    }
                }$data['flag']=1;
                echo result(0,$data);
	    }else{
              // $lands=getAround($latitude,$longitude,10000);
              // $minlat=$lands[0]; $maxlat=$lands[1];$minlng=$lands[2];$maxlng=$lands[3];
		$land=returnSquarePoint($longitude,$latitude,10);
               $minlat=$land['left-bottom']['lat']; $maxlat=$land['left-top']['lat'];$minlng=$land['left-top']['lng'];$maxlng=$land['right-top']['lng'];
               $landd['land']=M('landmark')->field('id,longitude,latitude,landname')->where("cityid=$cityid and latitude>=$minlat and latitude<=$maxlat and longitude>=$minlng and longitude<=$maxlng")->select();
               foreach ($landd['land'] as $k=>$v){
                   $id=$v['id'];
                   $find=M('coachlands')->where("locate($id,landmarkid)")->select();
                   if($find){
                       $landd['land'][$k]['coach']=M('coachlands l')->field("s.userid,s.nickname,s.score,s.allcount")->join("xueche1_coach s on s.userid=l.coachid and locate($id,l.landmarkid)")->order("rand()")->limit(3)->select();
                   }else{
                       unset($landd['land'][$k]);
                   }
               }
		$landd['flag']=0;
               $landd['land']=array_reverse($landd['land']);
              echo result(0,$landd);
            }
        } catch (Exception $e) {
            $info = zimg(1, "返回错误");
        }
        echo $info;
    }
    public function newguidersearch_2($city='上海',$county='宝山',$address='市中心驾校',$latitude='31.2996',$longitude='121.536'){
        try {
            $cityid=M('citys')->field("id")->where("cityname like '%$city%'")->find()['id'];
            $countyid=M('countys')->field("id")->where("countyname like '%$county%' and masterid=$cityid")->find()['id'];
            $data['guider']=M('guider')->field("userid,nickname,score,allcount")->where("nickname like '%$address%' and cityid=$cityid and verify=3")->select();
            if($data['guider']){
           	 foreach ($data['guider'] as $k=>$v){
                    $userid=$v['userid'];
                    $land[$k]=M('guiderlands')->field('landmarkid')->where("guiderid='$userid'")->find()['landmarkid'];
                    if($land[$k]){
                        $land[$k]=explode(',',rtrim($land[$k],','));
                        foreach($land[$k] as $kk=>$vv){
                            $data['guider'][$k]['landmark'][$kk]=M('landmark')->field('id,landname,longitude,latitude')->where("id=$vv")->find();
			    if(empty($data['guider'][$k]['landmark'][$kk])){
                                unset($data['guider'][$k]['landmark'][$kk]);      
                            }   
			   shuffle($data['guider'][$k]['landmark']);
                        } 
                    }else{
                        $data['guider'][$k]['landmark']=[];
                        continue;
                    }
                }$data['flag']=1;
                echo result(0,$data);
            }else{
              // $lands=getAround($latitude,$longitude,10000);
               //$minlat=$lands[0]; $maxlat=$lands[1];$minlng=$lands[2];$maxlng=$lands[3];
		$land=returnSquarePoint($longitude,$latitude,10);
	        $minlat=$land['left-bottom']['lat']; $maxlat=$land['left-top']['lat'];$minlng=$land['left-top']['lng'];$maxlng=$land['right-top']['lng'];
               $landd['land']=M('landmark')->field('id,longitude,latitude,landname')->where("cityid=$cityid and latitude>=$minlat and latitude<=$maxlat and longitude>=$minlng and longitude<=$maxlng")->select();
               foreach ($landd['land'] as $k=>$v){
                   $id=$v['id'];
                   $find=M('guiderlands')->where("locate($id,landmarkid)")->select();
                   if($find){
                       $landd['land'][$k]['guider']=M('guiderlands l')->field("s.userid,s.nickname,s.score,s.allcount")->join("xueche1_guider s on s.userid=l.guiderid and locate($id,l.landmarkid)")->order("rand()")->limit(3)->select();
                   }else{
                       unset($landd['land'][$k]);
                   }
               }
		$landd['flag']=0;
               $landd['land']=array_reverse($landd['land']);
              echo result(0,$landd);
            }
        } catch (Exception $e) {
            $info = zimg(1, "返回错误");
        }
        echo $info;
    }
}
