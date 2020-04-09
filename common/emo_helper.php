<?php
//@header("Content-type: text/html; charset=utf-8");
//$test_words='测试一下[可爱]，emo语句的表情转化功能[吐舌][发呆][发呆][发呆]';
// $test_words='测试一下，emo语句的表情转化功能';
//echo Emo::ProcessEmoMsg($test_words);
//表情转化功能
class Emo{
	private static $emo_arr=array("[笑脸]"=>'1.png', 
	"[感冒]"=>'2.png', 
	"[流泪]"=>'3.png', 
	"[发怒]"=>'4.png', 
	"[爱慕]"=>'5.png', 
	"[吐舌]"=>'6.png', 
	"[发呆]"=>'7.png', 
	"[可爱]"=>'8.png', 
	"[调皮]"=>'9.png', 
	"[寒冷]"=>'10.png', 
	"[呲牙]"=>'11.png', 
	"[闭嘴]"=>'12.png', 
	"[害羞]"=>'13.png', 
	"[苦闷]"=>'14.png', 
	"[难过]"=>'15.png', 
	"[流汗]"=>'16.png', 
	"[犯困]"=>'17.png', 
	"[惊恐]"=>'18.png', 
	"[咖啡]"=>'19.png', 
	"[炸弹]"=>'20.png', 
	"[西瓜]"=>'21.png', 
	"[爱心]"=>'22.png', 
	"[心碎]"=>'23.png');
	private static function getemopic($text){
		return isset(self::$emo_arr[$text])?'<img src="/mobile/template/app/emo/'.self::$emo_arr[$text].'" />':$text;
	}
	public  static function ProcessEmoMsg($msg){
		preg_match_all('/\[.*?\]/i',$msg,$match);
		$keywords=array_unique($match[0]);
		for($i=0,$l=count($keywords);$i<$l;$i++){
			$msg=str_replace($keywords[$i], self::getemopic($keywords[$i]), $msg);
		}
		return $msg;
	}
}