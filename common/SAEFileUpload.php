<?php
//暂时不用，没有测试
class SAEFileUpload{
	function upload($write)
	{
	    $filename = "pic_" . time() . ".jpg";//要生成的图片名字
	    //以下为sae的sotrage
	    $domain = 'img';
	    $s = new SaeStorage();
	    $imgurl = $s->write($domain, $filename, $write);
	    return $imgurl;
	}
}