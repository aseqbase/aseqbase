<?php namespace MiMFa\Library;
class Style{
	public static function UniversalProperty($prop,$val){
		return 
		"-webkit-".$prop.": ".$val.";
		-moz-".$prop.": ".$val.";
		-ms-".$prop.": ".$val.";
		-o-".$prop.": ".$val.";
		".$prop.": ".$val.";";
	}
	public static function UniversalValue(){
		$prop = func_get_arg(0);
		$argn = func_num_args();
		$res = $prop.":";
		for ($i = 1; $i < $argn; $i++) {
			$res .= "
			-webkit-".func_get_arg($i).",";
		}
		$res = rtrim($res,",");
		$res .= ";
		".$prop.":";
		for ($i = 1; $i < $argn; $i++) {
			$res .= "
			-moz-".func_get_arg($i).",";
		}
		$res = rtrim($res,",");
		$res .= ";
		".$prop.":";
		for ($i = 1; $i < $argn; $i++) {
			$res .= "
			-ms-".func_get_arg($i).",";
		}
		$res = rtrim($res,",");
		$res .= ";
		".$prop.":";
		for ($i = 1; $i < $argn; $i++) {
			$res .= "
			-o-".func_get_arg($i).",";
		}
		$res = rtrim($res,",");
		$res .= ";
		".$prop.":";
		for ($i = 1; $i < $argn; $i++) {
			$res .= "
			".func_get_arg($i).",";
		}
		$res = rtrim($res,",");

		return $res.";";
	}
	
	public static function DoStrong($text,$keyWords=null){
		if($keyWords === null) $keyWords = \_::$INFO->KeyWords;
		foreach($keyWords as $item)
		    $text = str_replace($item,"<strong>$item</strong>",$text??"");
		foreach($keyWords as $item){
			$item = strtoupper($item);
			$text = str_replace($item,"<strong>$item</strong>",$text??"");
		}
		foreach($keyWords as $item){
			$item = strtolower($item);
			$text = str_replace($item,"<strong>$item</strong>",$text??"");
		}
		return $text;
	}
	public static function DoStyle($text,$keyWords=null){
		return DoStrong($text,$keyWords); 
	}
}
?>