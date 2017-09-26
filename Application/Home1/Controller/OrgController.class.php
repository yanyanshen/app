<?php
namespace Home1\Controller;
use Think\Controller;
use Think\Think;
class OrgController extends Think {
    public static $wordArr = array();
    public static $content = "";
    public static function filter($content){
        if($content=="") return "空";
        self::$content = $content;
        empty(self::$wordArr)?self::getWord():"";
        foreach ( self::$wordArr as $row){
            if (false !== strstr(self::$content,$row)) return "错误";
        }
        return "正确";
    }
    public static function getWord(){
        self::$wordArr = include "SensitiveThesaurus.php";
    }
    public function a(){
        $aa='';
        dump(self::$wordArr);
        echo $this->filter($aa);
    }
}