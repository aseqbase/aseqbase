<?php
namespace MiMFa\Library;
/**
 * A simple library to convert datatypes to eachother
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#convert See the Library Documentation
*/
class Convert{
	/**
     * Get an Excerpt text of a bigger one
     * @param string $text
     * @return string
     */
	public static function ToExcerpt($text, $from = 0, $maxlength = 100, $excerptedSign = "...", $reverse = false){
        if (!isValid($text)) return $text;
        $text = trim(self::ToText($text));
		$len = strlen($text);
        if ($len <= $maxlength) return $text;
        if($reverse) return $excerptedSign.substr($text, max(0,$len - $from - $maxlength), max(0,$maxlength - strlen($excerptedSign))).($from>strlen($excerptedSign)?$excerptedSign:"");
        else return ($from>strlen($excerptedSign)?$excerptedSign:"").substr($text,$from, $maxlength - strlen($excerptedSign)).$excerptedSign;
	}

	/**
	 * Separate a text to multiple paragraphs
	 * @param string $text
	 * @return array<string>
	 */
	public static function ToParagraphs($html, $getText = false){
        if(!$getText) $html = strip_tags($html, '<a><input><button><ul><ol><li><select><option>');
		else $html = self::ToText($html);
		$out = null;
		preg_match_all("/((<(br|hr|vr|img|input)(\s.)*>)\s*[\w\W]*([\\.\\?\\!\\:]+|(\r\n)+|\n+|[\s\S]$))|((<(\w+)(\s.)*>\s*)[\w\W]*(\s*<\/\9(\s.)*>))|(^[\W\d]+((<(\w+)(\s.)*>\s*)|(\s*<\/\16(\s.)*>)|.)+((\r\n)+|\n+|[\s\S]$))|(((<(\w+)(\s.)*>\s*)|(\s*<\/\25(\s.)*>)|.)+([\\.\\?\\!\\:]+|(\r\n)+|\n+|[\s\S]$))/mi",
			$html,$out, PREG_PATTERN_ORDER);
		return $out[0];
	}

	/**
     * Convert html document to text
     * @param string $html
     * @return string
     */
	public static function ToText($html){
        $html = strip_tags($html, '<br><hr><section><content><main><header><footer><p>li><tr><h1><h2><h3><h3><h4><h5><h6>');
        return preg_replace ('/(\s*<[^>]*>\s*)+/', PHP_EOL, $html);
	}

	/**
	 * Convert everything to a simple string format
	 * @param mixed $value
	 * @return string
	 */
	public static function ToString($value, $separator = PHP_EOL, $spacer = ":", $assignFormat = "{0}:{\t{1}},"){
		if(!is_null($value)){
			if(is_string($value)) return $value;
			if(is_subclass_of($value,"\Base")) return $value->ToString();
			if(is_countable($value) || is_iterable($value)){
				$texts = array();
				foreach ($value as $key => $val){
                    $item = self::ToString($val, $separator, $spacer);
					if(is_numeric($key)) array_push($texts, $item);
					elseif(is_countable($val) || is_iterable($val))
                        array_push($texts, str_replace(["{0}","{1}"],[$key,$item],$assignFormat));
                    else{
                        $sp;
                        if(str_contains($item,'"')){
                            if(str_contains($item,"'")){
                                $item = str_replace("'","`",$item);
                                $sp ="'";
                            }
                            else $sp = "'";
                        }
                        else $sp ='"';
                        array_push($texts, "$key$spacer$sp$item$sp");
                    }
                }
                return join($separator,$texts);
            }
			if(is_callable($value)) return self::ToString($value(), $separator, $spacer);
            return $value."";
        }
		return "";
    }

	/**
     * Convert a text to normal Name
     * @param string $text
     * @return string
     */
	public static function ToName($text, $normalize = false, $invalid = '/\W/'){
        if(!($normalize || preg_match($invalid, $text))) return $text;
        return preg_replace($invalid, "", ucwords($text??""));
	}
	/**
     * Convert a text to normal Title
     * @param string[] $texts Parts of title
     * @return string
     */
	public static function ToTitle(){
        $ls=[];
        foreach (self::ToIteration(func_get_args()) as $text)
            if(!is_null($text)) $ls[] = ucwords(preg_replace('/\W/', " ", $text));
        return join(" - ", array_unique($ls));
	}

	/**
     * Convert a text to a secreted text
     * @param string $text
     * @return string
     */
	public static function ToSecret($text, $secretChar = "*", $showCharsFromFirst = 0, $showCharsFromLast = 0){
		$txts = str_split($text."");
		$text = "";
		for ($i = 0; $i < $showCharsFromFirst; $i++)
            $text .= $txts[$i];
		for ($i = $showCharsFromFirst; $i < count($txts)-$showCharsFromLast; $i++)
            $text .= $secretChar;
		for ($i = count($txts)-$showCharsFromLast; $i < count($txts); $i++)
            $text .= $txts[$i];
        return $text;
	}

	/**
     * Get items of all input arrays into a generator array
     * @param mixed $arguments
     */
	public static function ToIteration(...$arguments){
        foreach ($arguments as $key=>$val){
			if(is_countable($val) || is_iterable($val))
                if(is_array($val)) yield from call_user_func_array("self::ToIteration", $val);
                else yield from call_user_func_array("self::ToIteration", iterator_to_array($val));
            else yield $key=>$val;
        }
    }
	/**
     * Get items of all input arrays into one array
     * @param mixed $arguments
     * @return array
     */
	public static function ToArray(...$arguments){
		return iterator_to_array(call_user_func_array("self::ToIteration",$arguments));
	}

    public static function FromDynamicString($text, &$additionalKeys = array(), $addDefaultKeys = true){
		if($addDefaultKeys){
            $email = getEmail(null,"info");
            if(!isset($additionalKeys['$HOSTEMAILLINK'])) $additionalKeys['$HOSTEMAILLINK'] = HTML::Link($email, "mailto:$email");
            if(!isset($additionalKeys['$HOSTEMAIL'])) $additionalKeys['$HOSTEMAIL'] = $email;
            if(!isset($additionalKeys['$HOSTLINK'])) $additionalKeys['$HOSTLINK'] = HTML::Link(\_::$SITE, \_::$HOST);
            if(!isset($additionalKeys['$HOST'])) $additionalKeys['$HOST'] = \_::$HOST;
            if(!isset($additionalKeys['$URLLINK'])) $additionalKeys['$URLLINK'] = HTML::Link(\_::$URL, \_::$URL);
            if(!isset($additionalKeys['$URL'])) $additionalKeys['$URL'] =\_::$URL;
            if(isValid(\_::$INFO->User)){
                $person = \_::$INFO->User->Get(getValid($additionalKeys,'$SIGNATURE'));
                if(!isset($additionalKeys['$SIGNATURE'])) $additionalKeys['$SIGNATURE'] = getValid($person,"Signature")??\_::$INFO->User->TemporarySignature;
                if(!isset($additionalKeys['$NAME'])) $additionalKeys['$NAME'] = getValid($person,"Name")??\_::$INFO->User->TemporaryName;
                $email =  getValid($person,"Email")??\_::$INFO->User->TemporaryEmail;
                if(!isset($additionalKeys['$EMAILLINK'])) $additionalKeys['$EMAILLINK'] = HTML::Link($email, "mailto:$email");
                if(!isset($additionalKeys['$EMAIL'])) $additionalKeys['$EMAIL'] = $email;
                if(!isset($additionalKeys['$IMAGE'])) $additionalKeys['$IMAGE'] = getValid($person,"Image")??\_::$INFO->User->TemporaryImage;
                if(!isset($additionalKeys['$IMAGETAG'])) $additionalKeys['$IMAGETAG'] = HTML::Image($additionalKeys['$SIGNATURE'], getValid($person,"Image"));
                if(!isset($additionalKeys['$ADDRESS'])) $additionalKeys['$ADDRESS'] = getValid($person,"Address");
                if(!isset($additionalKeys['$CONTACT'])) $additionalKeys['$CONTACT'] = getValid($person,"Contact");
                if(!isset($additionalKeys['$ORGANIZATION'])) $additionalKeys['$ORGANIZATION'] = getValid($person,"Organization");
            }
            uksort($additionalKeys, function($a, $b){
                return (strlen( $a ) == strlen( $b ))?0:(( strlen( $a ) < strlen( $b ) )?1:-1);
            });
        }
        $text = str_replace(array_keys($additionalKeys), array_values($additionalKeys), $text);
        //foreach ($additionalKeys as $key => $value)
        //    $text = str_replace($key, $value??"", $text);
		return $text;
	}
}
?>