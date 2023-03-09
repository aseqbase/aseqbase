<?php namespace MiMFa\Library;
/**
 * A simple library to convert datatypes to eachother
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/mimfa/aseqbase
 *@link https://github.com/mimfa/aseqbase/wiki/Libraries#convert See the Library Documentation
*/
class Convert{
	public static function ToParagraphs($text){
		$out = null;
		preg_match_all("/[^\\.]+(\\.+)|\\S$/i",$text,$out, PREG_PATTERN_ORDER);
		return $out[0];
	}
}
?>