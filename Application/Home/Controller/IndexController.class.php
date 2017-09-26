<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        // $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $this->display();
    }
	    public function index2(){
        // $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $this->display();
    }

//function search_word_from() {
//    $referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
//    if(strstr( $referer, 'baidu.com')){ //百度
//        preg_match( "|baidu.+wo?r?d=([^\\&]*)|is", $referer, $tmp );
//        $keyword = urldecode( $tmp[1] );
//        $from = 'baidu'; //
//    }elseif(strstr( $referer, 'google.com') or strstr( $referer, 'google.cn')){ //谷歌
//        preg_match( "|google.+q=([^\\&]*)|is", $referer, $tmp );
//        $keyword = urldecode( $tmp[1] );
//        $from = 'google';
//    }elseif(strstr( $referer, 'so.com')){ //360搜索
//        preg_match( "|so.+q=([^\\&]*)|is", $referer, $tmp );
//        $keyword = urldecode( $tmp[1] );
//        $from = '360';
//    }elseif(strstr( $referer, 'sogou.com')){ //搜狗
//        preg_match( "|sogou.com.+query=([^\\&]*)|is", $referer, $tmp );
//        $keyword = urldecode( $tmp[1] );
//        $from = 'sogou';
//    }elseif(strstr( $referer, 'soso.com')){ //搜搜
//        preg_match( "|soso.com.+w=([^\\&]*)|is", $referer, $tmp );
//        $keyword = urldecode( $tmp[1] );
//        $from = 'soso';
//    }else {
//        $keyword ='';
//        $from = '';
//    }
//    return $referer;
//    return array('keyword'=>$keyword,'from'=>$from);
//
//    /*
//     *  $word = search_word_from();
//        if(!empty($word['keyword'])) {
//            echo '关键字：' . $word['keyword'] . ' 来自：' . $word['from'];
//        }*/
//}







}