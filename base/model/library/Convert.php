<?php namespace MiMFa\Library;
class Convert{
	public static function ToParagraphs($text){
		$out = null;
		preg_match_all("/[^\\.]+(\\.+)|\\S$/i",$text,$out, PREG_PATTERN_ORDER);
		return $out[0];
	}
}
?>