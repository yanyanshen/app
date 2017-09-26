<?php
namespace Think;
class  Upload{
    //文件上传函数
    public function dirup($dir){
        $arr=['jpg','png','php','gif'];
        $j=0;
        foreach($_FILES as $v){
            if($v['size']>10485760){
                break;
            }
            $push=strtolower(pathinfo($v['name'],PATHINFO_EXTENSION));
            $file_name=pathinfo($v['name'],PATHINFO_FILENAME);
            if(in_array($push,$arr)){
		$en=range('a','z');
                $newname=time().mt_rand(-9,9).$en[mt_rand(0,25)].'.'.$push;
 move_uploaded_file($v['tmp_name'],$dir.'/'.$newname);
		 $img = $dir.'/'.$newname;//获取文件上传目录
		$this->img_create_small($dir.'/'.$newname,200,$dir.'/small/'.$newname);
                $image = new \Think\Image();;
               $image->open($img);    //打开上传图片
              $image->water('/var/www/517.png')->save($img);
              //$image->thumb(150,150,\Think\Image::IMAGE_THUMB_FIXED)->save($dir.'/small/'.$newname);//生成缩略图
                $j++;
                $nname[]['imgname']=trim($dir.'/small/'.$newname,'.');
            }
        }if($j>0){
            return $nname;
        }
    }
	function img_create_small($big_img, $width,$small_img) {//原始大图地址，缩略图宽度，缩略图地址
		$imgage = getimagesize($big_img); //得到原始大图片
		switch ($imgage[2]) { // 图像类型判断
			case 1:
				$im = imagecreatefromgif($big_img);
			break;
			case 2:
				$im = imagecreatefromjpeg($big_img);
			break;
			case 3:
				$im = imagecreatefrompng($big_img);
			break;
		}
		$src_W = $imgage[0]; //获取大图片宽度
		$src_H = $imgage[1]; //获取大图片高度
		$height=$width/$src_W*$src_H;
		$tn = imagecreatetruecolor($width, $height); //创建缩略图
		imagecopyresampled($tn, $im, 0, 0, 0, 0, $width, $height, $src_W, $src_H); //复制图像并改变大小
		imagejpeg($tn, $small_img); //输出图像
	}
}

 
