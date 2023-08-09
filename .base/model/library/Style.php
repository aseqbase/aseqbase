<?php namespace MiMFa\Library;
/**
 * A simple library to prepare css styles and apply them on the elements
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#style See the Library Documentation
*/
class Style extends \ArrayObject{
	public null|string $Background = null;
	public null|string $BackgroundColor = null;
	public null|string $BackgroundImage = null;
	public null|string $BackgroundSize = null;
	public null|string $BackgroundRepeat = null;
	public null|string $BackgroundPosition = null;
	public null|string $BackgroundAttachment = null;
	public null|string $BackgroundClip = null;
	public null|string $BackgroundFilter = null;
	public null|string $Content = null;
	public null|string $Color = null;
	public null|string $Opacity = null;
	public null|string $Font = null;
	public null|string $FontFamily = null;
	public null|string $FontSize = null;
	public null|string $FontWeight = null;
	public null|string $Height = null;
	public null|string $Width = null;
	public null|string $Margin = null;
	public null|string $Padding = null;
	public null|string $TextAlign = null;
	public null|string $Display = null;
	public null|string $Position = null;
	public null|string $Overflow = null;
	public null|string $Border = null;
	public null|string $BorderRadius = null;
	public null|string $BoxShadow = null;
	public null|string $TextShadow = null;
	public null|string $Filter = null;
	
	public function IsValid(){
		return !isempty($this->Get());
    }
	public function Get(){
		$styles = 
			self::DoProperty("content",$this->Content).
			self::DoProperty("color",$this->Color).
			self::DoProperty("background",$this->Background).
			self::DoProperty("background-color",$this->BackgroundColor).
			self::DoProperty("background-image",$this->BackgroundImage).
			self::DoProperty("background-size",$this->BackgroundSize).
			self::DoProperty("background-repeat",$this->BackgroundRepeat).
			self::DoProperty("background-attachment",$this->BackgroundAttachment).
			self::DoProperty("background-position",$this->BackgroundPosition).
			self::DoProperty("background-clip",$this->BackgroundClip).
			self::DoProperty("backdrop-filter",$this->BackgroundFilter,false,true).
			self::DoProperty("opacity",$this->Opacity).
			self::DoProperty("font",$this->Font).
			self::DoProperty("font-family",$this->FontFamily).
			self::DoProperty("font-size",$this->FontSize).
			self::DoProperty("font-weight",$this->FontWeight).
			self::DoProperty("height",$this->Height).
			self::DoProperty("width",$this->Width).
			self::DoProperty("margin",$this->Margin).
			self::DoProperty("padding",$this->Padding).
			self::DoProperty("text-align",$this->TextAlign).
			self::DoProperty("display",$this->Display).
			self::DoProperty("position",$this->Position).
			self::DoProperty("overflow",$this->Overflow).
			self::DoProperty("border",$this->Border).
			self::DoProperty("border-radius",$this->BorderRadius).
			self::DoProperty("box-shadow",$this->BoxShadow).
			self::DoProperty("text-shadow",$this->TextShadow).
			self::DoProperty("filter",$this->Filter,false,true);
		foreach($this as $key=>$val) $styles .= self::DoProperty($key,$val);
		return $styles;
	}

	public static function UniversalProperty($prop,$val){
		return
		"-webkit-$prop: $val;
		-moz-$prop: $val;
		-ms-$prop: $val;
		-o-$prop: $val;
		$prop: $val;";
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

	public static function DoProperty($prop,$val,$isUniversalProperty=false,$isUniversalValue=false){
		return is_null($val)?null:
			(
				$isUniversalProperty?self::UniversalProperty($prop,$val):
				(
					$isUniversalValue?self::UniversalValue($prop,$val):
					"$prop: $val;"
				)
			);
	}

	/**
	 * Specify the keywords in the tag content automatically
	 * @param string|null $text
	 * @param array<string>|null $keyWords
	 * @param bool $caseSensitive
     * @param bool $multiline
	 * @return string|null
	 */
	public static function DoStrong($text, $keyWords=null, $caseSensitive = false, $multiline = true, $standardization = false){
		if($text === null) return $text;
		if($keyWords === null) $keyWords = \_::$INFO->KeyWords;
        $start = "/\b(?<!\\\§)(";
        $end = ")(?!§\\\>)\b/".($caseSensitive?"":"i").($multiline?"m":"");
        $length = count($keyWords);
		$keywordPatterns = array();
        for ($i = 0; $i < $length; $i++)
            $keywordPatterns[$i] = $start.preg_quote($keyWords[$i]).$end;
		$dic = array();
		$text = Code($text, $dic, "\\§", "§\\");
        if($standardization)
			for ($i = 0; $i < $length; $i++)
				$text = preg_replace($keywordPatterns[$i],"<strong>{$keyWords[$i]}</strong>",$text);
		else 
			for ($i = 0; $i < $length; $i++)
				$text = preg_replace($keywordPatterns[$i],"<strong>$1</strong>",$text);
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