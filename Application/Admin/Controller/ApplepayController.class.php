<?php
namespace Admin\Controller;
use Think\Controller;
use com\unionpay\acp\sdk\AcpService;
use const com\unionpay\acp\sdk\SDK_App_Request_Url;
use const com\unionpay\acp\sdk\SDK_BACK_NOTIFY_URL;
class ApplepayController extends CommonController {
    function appConsume($price,$listid){
        vendor("sdk.acp_service");
        $params = array(
            //以下信息非特殊情况不需要改动
            'version' => '5.0.0',                 //版本号
            'encoding' => 'utf-8',				  //编码方式
            'txnType' => '01',		      //交易类型
            'txnSubType' => '01',				  //交易子类
            'bizType' => '000000',				  //业务类型
            'signMethod' => '01',	              //签名方法
            'backUrl'=>SDK_BACK_NOTIFY_URL,
	    'channelType' => '08',	              //渠道类型，07-PC，08-手机
            'accessType' => '0',		          //接入类型
            'currencyCode' => '156',	          //交易币种，境内商户固定156
            //TODO 以下信息需要填写
           'merId' =>C('YLID'),		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
           // 'merId' =>'777290058110097',		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
           'orderId' => $listid,	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
            'txnTime' => date('YmdHis'),	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
            'txnAmt' => $price,	//交易金额，单位分，此处默认取demo演示页面传递的参数
            'reqReserved' =>'透传信息'        //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
       );
        AcpService::sign($params);// 签名
        $url = SDK_App_Request_Url;
        $result_arr = AcpService::post ($params,$url);
//	dump($result_arr);
        if(count($result_arr)<=0) { //没收到200应答的情况
            echo type(2,"没收到200应答");
            return;
        } 
        if (!AcpService::validate ($result_arr) ){
            echo type(1,"应答报文验签失败");
            return;
        }
        if ($result_arr["respCode"] == "00"){
            //成功
            echo result(0,$result_arr['tn']);
        } else {
            //其他应答码做以失败处理
           echo result(3,$result_arr["respCode"]);
        }
    }
}
