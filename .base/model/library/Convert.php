<?php
namespace MiMFa\Library;
/**
 * A simple library to convert datatypes to eachother
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/mimfa/aseqbase
 *@link https://github.com/mimfa/aseqbase/wiki/Libraries#convert See the Library Documentation
*/
class Convert{
	/**
     * Get an Excerpt text of a bigger one
     * @param string $text
     * @return string
     */
	public static function ToExcerpt($text,$from = 0,$maxlength = 100,$excerptedSign = "...", $reverse = false){
        if (isValid($text)) return $text;
        $text = trim(self::ToText($text));
		$len = strlen($text);
        if ($len - $from <= $maxlength) return $text;
        if($reverse) return $excerptedSign.substr($text, max(0,$len - $from - ($maxlength - count($excerptedSign))));
        else return substr($text,$from, $maxlength - count($excerptedSign)).$excerptedSign;
	}

	/**
	 * Separate a text to multiple paragraphs
	 * @param string $text
	 * @return array<string>
	 */
	public static function ToParagraphs($text){
		$out = null;
		preg_match_all("/[^\\.]+(\\.+)|\\S$/i",$text,$out, PREG_PATTERN_ORDER);
		return $out[0];
	}

	/**
     * Convert html document to text
     * @param string $html
     * @return string
     */
	public static function ToText($html){
        $html = strip_tags($html, '<br><p><li><hr><tr>');
        return preg_replace ('/<[^>]*>/', PHP_EOL, $html);
	}
}
?>