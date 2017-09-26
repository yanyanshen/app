<?php namespace Org\Util;?>
<style>
.page a{
	padding:3px 6px;
	min-width:10px;
	background:#ec6942;
	display:inline-block;
	margin-left:10px;
	color:white;
	border-radius:3px;
	text-align:center;
	line-height:18px;
}

</style>
<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// |         lanfengye <zibin_5257@163.com>
// +----------------------------------------------------------------------

class Page {
    // 分页栏每页显示的页数
    public $rollPage = 10;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 分页URL地址
    public $url     =   '';
    // 默认列表每页显示行数
    public $listRows=10;
    // 起始行数
    public $firstRow    ;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    public $nowPage=1    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页显示定制
    protected $config=array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页','theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
    // 默认分页变量名
    protected $varPage;

    /**
     * 架构函数
     * @access public
     * @param array $totalRows  总的记录数
     * @param array $listRows   每页显示记录数
     * @param array $parameter  分页跳转的参数
     */
    public function __construct($totalRows,$listRows='',$parameter='',$url='') {
        $this->totalRows    =   $totalRows;
        $this->parameter    =   $parameter;
        $this->varPage      =   C('VAR_PAGE') ? C('VAR_PAGE') :'p';//p是返回列表时需要传递的当前页数
        if(!empty($listRows)) {
            $this->listRows =   intval($listRows);
        }
        $this->totalPages   =   ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages    =   ceil($this->totalPages/$this->rollPage);
        $this->nowPage      =   !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;
        if($this->nowPage<1){
            $this->nowPage  =   1;
        }elseif(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage  =   $this->totalPages;
        }
        $this->firstRow     =   $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    /**
     * 分页显示输出
     * @access public
     */
    public function show($p=1) {
//         if(0 == $this->totalRows) return '';
//         file_put_contents("d:/p.txt",$p);
//         $nowCoolPage    =   ceil($this->nowPage/$this->rollPage);
//         file_put_contents("d:/nowcoolpage.txt", $nowCoolPage);
//         // 分析分页参数
//         if($this->url){
//             $depr       =   C('URL_PATHINFO_DEPR');
//             $url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
//         }else{
//             if($this->parameter && is_string($this->parameter)) {
//                 parse_str($this->parameter,$parameter);
//             }elseif(is_array($this->parameter)){
//                 $parameter      =   $this->parameter;
//             }elseif(empty($this->parameter)){
//                 unset($_GET[C('VAR_URL_PARAMS')]);
//                 $var =  !empty($_POST)?$_POST:$_GET;
//                 if(empty($var)) {
//                     $parameter  =   array();
//                 }else{
//                     $parameter  =   $var;
//                 }
//             }
//             $parameter[$p]  =   '__PAGE__';
//             $url            =   U('',$parameter);
//         }
//         //上下翻页字符串
//         $upRow          =   $this->nowPage-1;
//         $downRow        =   $this->nowPage+1;
//         if ($upRow>0){
//             $upPage     =   "<a href='".str_replace('__PAGE__',$upRow,$url)."'>".$this->config['prev']."</a>";
//         }else{
//             $upPage     =   '';
//         }

//         if ($downRow <= $this->totalPages){
//             $downPage   =   "<a href='".str_replace('__PAGE__',$downRow,$url)."'>".$this->config['next']."</a>";
//         }else{
//             $downPage   =   '';
//         }
//         // << < > >>
//         if($nowCoolPage == 1){
//             $theFirst   =   '';
//             $prePage    =   '';
//         }else{
//             $preRow     =   $this->nowPage-$this->rollPage;//当前页-10
//             $prePage    =   "<a href='".str_replace('__PAGE__',$preRow,$url)."' >上".$this->rollPage."页</a>";
//             $theFirst   =   "<a href='".str_replace('__PAGE__',1,$url)."' >".$this->config['first']."</a>";
//         }
//         if($nowCoolPage == $this->coolPages){
//             $nextPage   =   '';
//             $theEnd     =   '';
//         }else{
//             $nextRow    =   $this->nowPage+$this->rollPage;
//             $theEndRow  =   $this->totalPages;
//             $nextPage   =   "<a href='".str_replace('__PAGE__',$nextRow,$url)."' >下".$this->rollPage."页</a>";
//             $theEnd     =   "<a href='".str_replace('__PAGE__',$theEndRow,$url)."' >".$this->config['last']."</a>";
//         }
//         // 1 2 3 4 5
//         $linkPage = "";
// //         for($i=1;$i<=$this->rollPage;$i++){
// //             $page       =   ($nowCoolPage-1)*$this->rollPage+$i;
// //             if($page!=$this->nowPage){
// //                 if($page<=$this->totalPages){
// //                     $linkPage .= "&nbsp;<a href='".str_replace('__PAGE__',$page,$url)."' >&nbsp;".$page."&nbsp;</a>";
// //                 }else{
// //                     break;
// //                 }
// //             }else{
// //                 if($this->totalPages != 1){
// //                     $linkPage .= "&nbsp;<span class='current'>".$page."</span>";
// //                 }
// //             }
// //         }
        $pageStr="<div class='page'>";
        $start=1;
        if($p<=1){
            $p=1;
        }
        if($p>$this->totalPages){
            $p=$this->totalPages;
        }
        if($p>=5){
            $start=$p-4;
            $this->rollPage=$p+5;
            $pageStr.=sprintf("<a href='?p=%d'>首页</a>　",1);
        }
        $pageStr.=sprintf("<a href='?p=%d'>上一页</a>　",$p-1);
        for($i=$start;$i<=$this->rollPage;$i++){
            if($i>$this->totalPages){break;}
            $page=$i;
             if($page==$p){
                 $pageStr.= sprintf("<a href='javascript:void(0)' style='border:0;background:#9cc;color:red'>$page</a>　",$page);
                 continue;
             }
             $pageStr.= sprintf("<a href='?p=%d'>$page</a>　",$page);
         }$pageStr.=sprintf("<a href='?p=%d'>下一页</a>　",$p+1);
        if($p<$this->totalPages+1){
            $pageStr.=sprintf("<a href='?p=%d'>尾页</a>　",$this->totalPages);
        }
         $pageStr.=sprintf("当前页%s/总页数%s",$p,$this->totalPages);
         $pageStr.="</div>";
        return  $pageStr;
    }
}
?>
