<?php
namespace Think;
class  Upload1{
    public $size=20;
    public $flag=true;
    //文件上传函数
    public function dirup($dir,$c){
		$dir=".".$dir;
        $arr=['jpg','png','php','gif'];
        $j=0;
        if(array_sum($_FILES['file']['size'])<1048576*$this->size){
            $small=new img_small(150);
           // $img_world=new shuiyin_word();
            $img_img=new shui_img();
            foreach($_FILES as $v){
                for($i=0;$i<$c;$i++){
                    $push=strtolower(pathinfo($v['name'][$i],PATHINFO_EXTENSION));
                    $file_name=pathinfo($v['name'][$i],PATHINFO_FILENAME);
                    if(in_array($push,$arr)){
                        if($this->flag){
                            $en=range('a','z');
                            $newname=time().mt_rand(-9,9).$en[mt_rand(0,25)].'.'.$push;
                            move_uploaded_file($v['tmp_name'][$i],$dir.'/'.$newname);
                            $small->smail($dir.'/'.$newname);
                            $img_img->flag=true;
                            $img_img->p=9;
                            $img_img->sytubiao($dir.'/'.$newname);
                            $j++;
                            //保存相对路径
                            $nname[$i]['imgname']=trim($dir.'/small/'.$newname,'.');
                        }
                    }
                }
            }if($j>0){
                return $nname;
            }
        }
    }
}

class  img_small {
    public $newDir='small';
    public $w=200;
    public $flag=true;
    public 	$arr=['gif','png','jpg'];
    public function __construct($w,$flag=true){
        $this->w=$w;
        $this->flag=$flag;
    }
    public function smail($dir){
        if(is_dir($dir)){
            $n=scandir($dir);
            foreach($n as $v){
                if($v=='.' || $v=='..'){
                    continue;
                }else if(is_dir($dir.'/'.$v)){
                    $this->smail($dir.'/'.$v);
                }else{
                    $this->img_cl($dir.'/'.$v);
                    return true;
                }
            }
        }else if(is_file($dir)){
            $this->img_cl($dir);
            return true;
        }
    }
    public function img_cl($dir){
        $path=strtolower(pathinfo($dir,PATHINFO_EXTENSION));
        $dirr=pathinfo($dir,PATHINFO_DIRNAME);
        if(in_array($path,$this->arr)){
			if($path=='png'){
				 $i=imagecreatefrompng($dir);
			}else{
				 $i=imagecreatefromjpeg($dir);
			}
            $ww=imagesx($i);
            $hh =imagesy($i);
            $h=($this->w/$ww)*$hh;
            $img=imagecreatetruecolor($this->w,$h);
           // imagecopyresized($img,$i,0,0,0,0,$this->w,$h,$ww,$hh);
			imagecopyresampled($img,$i,0,0,0,0,$this->w,$h,$ww,$hh);
            if(!file_exists($dirr.'/'.$this->newDir)){
                mkdir($dirr.'/'.$this->newDir,'0777',true);
            }
            if($this->flag){
				if($path=='png'){
					   header("content-type:image/png");
				}else{
				    header("content-type:image/jpeg");
				}
                $filename=pathinfo($dir,PATHINFO_BASENAME);//返回文件名.拓展名
                imagejpeg($img,$dirr.'/'.$this->newDir.'/'.$filename);
                //imagedestroy($img);
            }else{
				if($path=='png'){
					   header("content-type:image/png");
				}else{
				    header("content-type:image/jpeg");
				}
                imagejpeg($img,$dirr.'/'.$filename);
                //imagedestroy($img);
            }
            	
        }
    }
}
//水印文字类
class  Shuiyin_word {
    public $p=5;
    public $f=15;
    public $j=0;
    public $newDir='images';
    public $word="我要去学车";
    public $ff="./public/font/fzz.ttf";
    public $flag=true;
    public $arr=['gif','png','jpg'];
    public function shuiyin($dir){
        if(is_dir($dir)){
            $n=scandir($dir);
            foreach($n as $v){
                if($v=='.' || $v=='..'){
                    continue;
                }else if(is_dir($dir.'/'.$v)){
                    $this->shuiyin($dir.'/'.$v);
                }else{
                    $this->img_cl($dir.'/'.$v);
                }
            }
        }else if(is_file($dir)){
            $this->img_cl($dir);
        }
    }
    public function img_cl($dir){
        $path=strtolower(pathinfo($dir,PATHINFO_EXTENSION));
        $filename=pathinfo($dir,PATHINFO_FILENAME);
        $dirr=pathinfo($dir,PATHINFO_DIRNAME);
        if(in_array($path,$this->arr)){
            $ii=imagecreatefrompng($dir);
            $w=imagesx($ii);
            $h=imagesy($ii);
            $info=imagettfbbox($this->f,$this->j,$this->ff,$this->word);
            $ww=$info[4];
            $hh=abs($info[5]);
            $brr=[1=>[10,20],2=>[($w-$ww)/2,20],3=>[$w-$ww-10,20],
                4=>[10,($h-$hh)/2],5=>[($w-$ww)/2,($h-$hh)/2],6=>[$w-$ww-10,($h-$hh)/2],
                7=>[10,$h-$hh-10],8=>[($w-$ww)/2,$h-$hh-10],9=>[$w-$ww-10,$h-$hh-10],
            ];
            if($this->p>9 || $this->p<1){
                $this->p=5;
            }
            $x=$brr[$this->p][0];
            $y=$brr[$this->p][1];

           // $c=imagecolorallocate($i,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
            //$c=imagecolorallocate(255,0,0);
            $c=imagecolorallocatealpha ($ii,255,255,255,0);
            imagettftext($ii,$this->f,$this->j,$x,$y,$c,$this->ff,$this->word);
            if($this->flag){
                if(!file_exists($dirr.'/'.$this->newDir)){
                    mkdir($dirr.'/'.$this->newDir,0777,true);
                }
                header('content-type:image/png');
                imagepng($ii,$dirr.'/'.$this->newDir.'/'.$filename.'.'.$path);
                imagedestroy($ii);
            }else{
                header('content-type:image/png');
                imagepng($ii,$dirr.'/'.$filename.'.'.$path);
                imagedestroy($ii);
            }
        }
    }
}

//水印图标类
class  shui_img {
    public $p=5;
    public $newDir='images';
    public $flag=true;
	public $arr=['jpg','png'];
    function sytubiao($dir,$dirr="./public/font/517.png"){//文件路径，图标路径
        if(is_dir($dir)){
            $n=scandir($dir);
            foreach($n as $v){
                if($v=='.' || $v=='..'){
                    continue;
                }else if(is_dir($dir.'/'.$v)){
                    $this->sytubiao($dir.'/'.$v,$dirr);
                }else{
                    $this->img_cl($dir.'/'.$v,$dirr);
                }
            }
        }else if(is_file($dir)){
            $this->img_cl($dir,$dirr);
        }
    }
    public function img_cl($dir,$dirr){
        $path=strtolower(pathinfo($dir,PATHINFO_EXTENSION ));
        $dirj=pathinfo($dir,PATHINFO_DIRNAME);
        if(in_array($path,$this->arr)){
			if($path=='png'){
				$iii=imagecreatefrompng($dir);//文件
			}else{
				$iii=imagecreatefromjpeg($dir);//文件
			}
            $img=imagecreatefrompng($dirr);//图标
            $w=imagesx($iii);
            $h=imagesy($iii);
            $ww=imagesx($img);
            $hh=imagesy($img);
		   if($h<$hh*3){
				$ww=$ww*0.3;
				$hh=$hh*0.3;
		   }
            $info=[1=>[10,10],2=>[($w-$ww)/2,10],3=>[$w-$ww-10,10],
                4=>[10,($h-$hh)/2],5=>[($w-$ww)/2,($h-$hh)/2],6=>[$w-$ww-10,($h-$hh)/2],
                7=>[10,$h-$hh],8=>[($w-$ww)/2,$h-$hh],9=>[$w-$ww-10,$h-$hh-10]
            ];
            if($this->p>9 || $this->p<1){
                $this->p=5;
            }
            $x=$info[$this->p][0];
            $y=$info[$this->p][1];
            imagecopy($iii,$img,$x,$y,0,0,$ww,$hh);
            $filename=pathinfo($dir,PATHINFO_BASENAME );
            if($this->flag){
				if($path=='png'){
					    header('content-type:image/png');
				}else{
					 header('content-type:image/jpeg');
				}
                imagepng($iii,$dirj.'/'.$filename);
                imagedestroy($iii);
            }else{
                if(!file_exists($dirj.'/'.$this->newDir)){
                    mkdir($dirj.'/'.$this->newDir,'0777',true);
                }
				if($path=='png'){
					    header('content-type:image/png');
				}else{
					 header('content-type:image/jpeg');
				}
                imagepng($iii,$dirj.'/'.$this->newDir.'/'.$filename);
                imagedestroy($iii);
            }
        }
    }
}
class ImageFilter
{                              #R  G  B
    var $colorA = 7944996;     #79 3B 24
    var $colorB = 16696767;    #FE C5 BF
    var $arA = array();
    var $arB = array(); 
    function ImageFilter()
    {
        $this->arA['R'] = ($this->colorA >> 16) & 0xFF;
        $this->arA['G'] = ($this->colorA >> 8) & 0xFF;
        $this->arA['B'] = $this->colorA & 0xFF;
        $this->arB['R'] = ($this->colorB >> 16) & 0xFF;
        $this->arB['G'] = ($this->colorB >> 8) & 0xFF;
        $this->arB['B'] = $this->colorB & 0xFF;
    }
    function GetScore($image)
    {
        $x = 0; $y = 0;
        $img = $this->_GetImageResource($image, $x, $y);
        if(!$img) return false;
        $score = 0;       
        $xPoints = array($x/8, $x/4, ($x/8 + $x/4), $x-($x/8 + $x/4), $x-($x/4), $x-($x/8));
        $yPoints = array($y/8, $y/4, ($y/8 + $y/4), $y-($y/8 + $y/4), $y-($y/8), $y-($y/8));
        $zPoints = array($xPoints[2], $yPoints[1], $xPoints[3], $y);
        for($i=1; $i<=$x; $i++)
        {
            for($j=1; $j<=$y; $j++)
            {
                $color = imagecolorat($img, $i, $j);
                if($color >= $this->colorA && $color <= $this->colorB)
                {
                    $color = array('R'=> ($color >> 16) & 0xFF, 'G'=> ($color >> 8) & 0xFF, 'B'=> $color & 0xFF);
                    if($color['G'] >= $this->arA['G'] && $color['G'] <= $this->arB['G'] && $color['B'] >= $this->arA['B'] && $color['B'] <= $this->arB['B'])
                    {
                        if($i >= $zPoints[0] && $j >= $zPoints[1] && $i <= $zPoints[2] && $j <= $zPoints[3])
                        {
                            $score += 3;
                        }
                        elseif($i <= $xPoints[0] || $i >=$xPoints[5] || $j <= $yPoints[0] || $j >= $yPoints[5])
                        {
                            $score += 0.10;
                        }
                        elseif($i <= $xPoints[0] || $i >=$xPoints[4] || $j <= $yPoints[0] || $j >= $yPoints[4])
                        {
                            $score += 0.40;
                        }
                        else
                        {
                            $score += 1.50;
                        }
                    }
                }
            }
        }
        imagedestroy($img);
        $score = sprintf('%01.2f', ($score * 100) / ($x * $y));
        if($score > 100) $score = 100;
        return $score;
    }
    
    function GetScoreAndFill($image, $outputImage)
    {
        $x = 0; $y = 0;
        $img = $this->_GetImageResource($image, $x, $y);
        if(!$img) return false;

        $score = 0;

        $xPoints = array($x/8, $x/4, ($x/8 + $x/4), $x-($x/8 + $x/4), $x-($x/4), $x-($x/8));
        $yPoints = array($y/8, $y/4, ($y/8 + $y/4), $y-($y/8 + $y/4), $y-($y/8), $y-($y/8));
        $zPoints = array($xPoints[2], $yPoints[1], $xPoints[3], $y);


        for($i=1; $i<=$x; $i++)
        {
            for($j=1; $j<=$y; $j++)
            {
                $color = imagecolorat($img, $i, $j);
                if($color >= $this->colorA && $color <= $this->colorB)
                {
                    $color = array('R'=> ($color >> 16) & 0xFF, 'G'=> ($color >> 8) & 0xFF, 'B'=> $color & 0xFF);
                    if($color['G'] >= $this->arA['G'] && $color['G'] <= $this->arB['G'] && $color['B'] >= $this->arA['B'] && $color['B'] <= $this->arB['B'])
                    {
                        if($i >= $zPoints[0] && $j >= $zPoints[1] && $i <= $zPoints[2] && $j <= $zPoints[3])
                        {
                            $score += 3;
                            imagefill($img, $i, $j, 16711680);
                        }
                        elseif($i <= $xPoints[0] || $i >=$xPoints[5] || $j <= $yPoints[0] || $j >= $yPoints[5])
                        {
                            $score += 0.10;
                            imagefill($img, $i, $j, 14540253);
                        }
                        elseif($i <= $xPoints[0] || $i >=$xPoints[4] || $j <= $yPoints[0] || $j >= $yPoints[4])
                        {
                            $score += 0.40;
                            imagefill($img, $i, $j, 16514887);
                        }
                        else
                        {
                            $score += 1.50;
                            imagefill($img, $i, $j, 512);
                        }
                    }
                }
            }
        }
        imagejpeg($img, $outputImage);

        imagedestroy($img);

        $score = sprintf('%01.2f', ($score * 100) / ($x * $y));
        if($score > 100) $score = 100;
        return $score;
    }
    
    function _GetImageResource($image, &$x, &$y)
    {
        $info = GetImageSize($image);
        
        $x = $info[0];
        $y = $info[1];
        
        switch( $info[2] )
        {
            case IMAGETYPE_GIF:
                return @ImageCreateFromGif($image);
                
            case IMAGETYPE_JPEG:
                return @ImageCreateFromJpeg($image);
                
            case IMAGETYPE_PNG:
                return @ImageCreateFromPng($image);
                
            default:
                return false;
        }
    }
}
 