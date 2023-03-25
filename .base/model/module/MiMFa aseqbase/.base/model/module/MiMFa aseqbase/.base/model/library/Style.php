<?php namespace MiMFa\Library;
/**
 * A simple library to prepare css styles and apply them on the elements
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/mimfa/aseqbase
 *@link https://github.com/mimfa/aseqbase/wiki/Libraries#style See the Library Documentation
*/
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

	/**
	 * Specify the keywords in the tag content automatically
	 * @param string|null $text
	 * @param array<string>|null $keyWords
	 * @param bool $caseSensitive
     * @param bool $multiline
	 * @return string|null
	 */
	public static function DoStrong($text, $keyWords=null, $caseSensitive = false, $multiline = true){
		if($text === null) return $text;
		if($keyWords === null) $keyWords = \_::$INFO->KeyWords;
        $start = "/(?<!\\\§)";
        $end = "(?!§\\\>)/".($caseSensitive?"":"i").($multiline?"m":"");
        $length = count($keyWords);
		$keywordPatterns = array();
        for ($i = 0; $i < $length; $i++)
            $keywordPatterns[$i] = $start.preg_quote($keyWords[$i]).$end;
		$dic = array();
		$text = Code($text, $dic, "\\§", "§\\");
        for ($i = 0; $i < $length; $i++)
		    $text = preg_replace($keywordPatterns[$i],"<strong>{$keyWords[$i]}</strong>",$text);
		return Decode($text, $dic);
	}
	public static function DoStyle($text,$keyWords=null){
		return self::DoStrong($text,$keyWords);
	}

	public static function DropColor($color){
		return self::UniversalProperty("filter","brightness(1000%) grayscale(100%) opacity(0.1)
													drop-shadow(0 0 0 $color)
													drop-shadow(0 0 0 $color)
													drop-shadow(0 0 0 $color)
													drop-shadow(0 0 0 $color)
													drop-shadow(0 0 0 $color)
");
	}

}
?>