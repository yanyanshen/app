<?php
use JPush\Client;
/*-------------------------------------------------------*/
//	 function send($alias="22A0A301_8877_C0EE_CB6E_E4D24D46DA3A",$message='您有一条新信息',$data=[],$type='jl'){
//	    Vendor('JPush.Client');
//	    Vendor('JPush.Config');
//	    Vendor('JPush.DevicePayload'); Vendor('JPush.Http');
//	    Vendor('JPush.PushPayload');Vendor('JPush.ReportPayload');
//	    Vendor('JPush.SchedulePayload'); Vendor('JPush.version');
//	    Vendor('JPush.Exceptions.APIConnectionException');Vendor('JPush.Exceptions.JPushException');
//	    Vendor('JPush.Exceptions.APIRequestException');
//	    if($type=='xy'){//说明是向学员端传的
//	        $appKey ='fad71f0a96f4dc6626afe2d2';
//	        $masterSecret='a4870316e6178b8790c5bd93';
//	    }else{
//	        $appKey ='90700c625a86a3feede5f5e4';
//	        $masterSecret='29b4376471512f01f15272c3';
//	    }
//	    $client=new Client($appKey,$masterSecret);
//	    try {
//	    $response = $client->push()
//	    ->setPlatform("all")
//	    ->addAlias($alias)
//	    ->iosNotification($message,
//	        array("alert"=> "我要去学车!",'sound' => 'defult',
//            "badge"=>"+1",
//	         'extras'=>$data
//            ))->androidNotification($message,
//            array("alert"=> "我要去学车!",'sound' => 'defult',
//            "badge"=>"+1",
//	         'extras'=>$data
//	        ))
//	        ->options(array(
//	            'apns_production' => false,
//	        ))->send();
//	        } catch (\JPush\Exceptions\APIConnectionException $e) {
//	           file_put_contents("/a/appJPushlog.txt", $e.'-时间-'.time()."\r\n",FILE_APPEND);
//	        } catch (\JPush\Exceptions\APIRequestException $e) {
//	              file_put_contents("/a/appJPushlog.txt", $e.'-时间-'.time()."\r\n",FILE_APPEND);
//	        }
//	}
//推送查找该用户的未完成的预约个数
function sendres($userid,$type){
     $red=M('reservation')->where("masterid='$userid' and statuss=1")->find();
     if($red){
         send($userid,"您有{$red}条未完成的预约",array('code'=>1001),$type);
     }
 } 
//推送查找该用户的未完成的预约个数
 function sendcon($userid,$type){
     send($userid,"您有1条新的咨询信息",array('code'=>1005,'objectid'=>$userid,'type'=>$type),$type);
 }

