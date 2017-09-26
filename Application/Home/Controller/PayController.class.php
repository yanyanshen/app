<?php
namespace Home\Controller;
use Think\Controller;
use com\unionpay\acp\sdk\AcpService;
use const com\unionpay\acp\sdk\SDK_FRONT_NOTIFY_URL;
use const com\unionpay\acp\sdk\SDK_BACK_NOTIFY_URL;
use const com\unionpay\acp\sdk\SDK_App_Request_Url;
class PayController extends Controller {
    function appConsume($listid='123456789',$price=1000){
        vendor("sdk.acp_service");
        $params = array(
            //以下信息非特殊情况不需要改动
            'version' => '5.0.0',                 //版本号
            'encoding' => 'utf-8',				  //编码方式
            'txnType' => '01',		      //交易类型
            'txnSubType' => '01',				  //交易子类
            'bizType' => '000000',				  //业务类型
           // 'frontUrl' =>SDK_FRONT_NOTIFY_URL,  //前台通知地址
            //'backUrl' =>  SDK_BACK_NOTIFY_URL,	  //后台通知地址
            'signMethod' => '01',	              //签名方法
            'channelType' => '08',	              //渠道类型，07-PC，08-手机
            'accessType' => '0',		          //接入类型
            'currencyCode' => '156',	          //交易币种，境内商户固定156
            //TODO 以下信息需要填写
            'merId' => '541121607040008',		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
            'orderId' => $listid,	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
            'txnTime' => date('YmdHis'),	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
            'txnAmt' => $price,	//交易金额，单位分，此处默认取demo演示页面传递的参数
            'reqReserved' =>'透传信息',        //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
            //TODO 其他特殊用法请查看 pages/api_05_app/special_use_purchase.php
        );
        AcpService::sign($params);// 签名
        
        $url = SDK_App_Request_Url;
        $result_arr = AcpService::post ($params,$url);
        if(count($result_arr)<=0) { //没收到200应答的情况
            printResult ($url, $params, "" );
            return;
        }
        //printResult ($url, $params, $result_arr ); //页面打印请求应答数据
        if (!AcpService::validate ($result_arr) ){
            echo "应答报文验签失败<br>\n";
            return;
        }
        echo "应答报文验签成功<br>\n";
        if ($result_arr["respCode"] == "00"){
            //成功
//             echo "成功接收tn：" . $result_arr["tn"] . "<br>\n";
//             echo "后续请将此tn传给手机开发，由他们用此tn调起控件后完成支付。<br>\n";
//             echo "手机端demo默认从仿真获取tn，仿真只返回一个tn，如不想修改手机和后台间的通讯方式，【此页面请修改代码为只输出tn】。<br>\n";
            echo "成功接收tn：" . $result_arr["tn"];
        } else {
            //其他应答码做以失败处理
            echo "失败：" . $result_arr["respMsg"] . "。<br>\n";
        }
    }
}