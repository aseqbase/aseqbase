<?php
namespace MiMFa\Library;
/**
 * A simple library to prepare css styles and apply them on the elements
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#style See the Library Documentation
 */
class Style extends \ArrayObject
{
	public function __get($name)
	{
		return get($this, self::PropertyName($name));
	}
	public function __set($name, $value)
	{
		$this[self::PropertyName($name)] = $value;
	}
	public static function PropertyName($name)
	{
		return strtolower(preg_replace_callback("/(?<=[^A-Z\-\b^])[A-Z]/", fn($mts) => "-" . strtolower($mts[0]), $name));
	}

	public function Get()
	{
		$styles = [];
		foreach ($this as $key => $val)
			$styles[] = "$key:$val;";
		return join(" ", $styles);
	}

	/**
	 * To convert everything to a simple css styles
	 * @param mixed $object
	 * @return string
	 */
	public static function Convert($object, ...$args)
	{
		$styles = [];
		if (is_iterable($object))
			foreach ($object as $key => $val)
				if (is_numeric($key))
					$styles[] = "$val;";
				else
					$styles[] = self::PropertyName($key) . ":$val;";
		elseif (isStatic($object))
			return $object . "";
		else
			return Convert::ToStatic($object, ...$args);
		return join(" ", $styles);
	}

	public static function UniversalProperty($prop, $val)
	{
		return
			"-webkit-$prop: $val;
		-moz-$prop: $val;
		-ms-$prop: $val;
		-o-$prop: $val;
		$prop: $val;";
	}
	public static function UniversalValue()
	{
		$prop = func_get_arg(0);
		$argn = func_num_args();
		$res = $prop . ":";
		for ($i = 1; $i < $argn; $i++) {
			$res .= "
			-webkit-" . func_get_arg($i) . ",";
		}
		$res = rtrim($res, ",");
		$res .= ";
		" . $prop . ":";
		for ($i = 1; $i < $argn; $i++) {
			$res .= "
			-moz-" . func_get_arg($i) . ",";
		}
		$res = rtrim($res, ",");
		$res .= ";
		" . $prop . ":";
		for ($i = 1; $i < $argn; $i++) {
			$res .= "
			-ms-" . func_get_arg($i) . ",";
		}
		$res = rtrim($res, ",");
		$res .= ";
		" . $prop . ":";
		for ($i = 1; $i < $argn; $i++) {
			$res .= "
			-o-" . func_get_arg($i) . ",";
		}
		$res = rtrim($res, ",");
		$res .= ";
		" . $prop . ":";
		for ($i = 1; $i < $argn; $i++) {
			$res .= "
			" . func_get_arg($i) . ",";
		}
		$res = rtrim($res, ",");

		return $res . ";";
	}

	public static function DoProperty($prop, $val, $isUniversalProperty = false, $isUniversalValue = false)
	{
		return is_null($val) ? null :
			(
				$isUniversalProperty ? self::UniversalProperty($prop, $val) :
				(
					$isUniversalValue ? self::UniversalValue($prop, $val) :
					"$prop: $val;"
				)
			);
	}

	public static function DoStyle($text, $keyWords = null)
	{
		return self::DoStrong(Html::Convert($text), $keyWords);
	}

	/**
	 * Specify the keywords in the tag content automatically
	 * @param string|null $text
	 * @param array<string>|null $keyWords
	 * @param bool $caseSensitive
	 * @param bool $multiline
	 * @param bool $both If you want to process both of key and value of your $keyWords
	 * @return string|null
	 */
	public static function DoStrong($text, $keyWords = null, $caseSensitive = false, $multiline = true, $both = false, $standardization = false)
	{
		if ($standardization)
			return self::DoProcess($text, function ($v) {
				return "<strong>$v</strong>";
			}, $keyWords, $caseSensitive, $multiline, $both);
		else
			return self::DoProcess($text, function ($v) {
				return "<strong>$v</strong>";
			}, $keyWords, $caseSensitive, $multiline, $both);
	}
	/**
	 * Specify the keywords in the tag content automatically by a special process
	 * @param callable $process function($value, $key, $index) {return $value;}
	 * @param string|null $text
	 * @param array|null $keyWords
	 * @param bool $caseSensitive
	 * @param bool $multiline
	 * @param bool $both If you want to process both of key and value of your $keyWords
	 * @return string|null
	 */
	public static function DoProcess($text, $process, $keyWords = null, $caseSensitive = false, $multiline = true, $both = false)
	{
		if ($text === null)
			return $text;
		if ($keyWords === null)
			$keyWords = \_::$Info->KeyWords;
		$dic = array();
		$text = encode(
			$text,
			$dic,
			wrapStart: "<",
			wrapEnd: ">",
			pattern: '/(`\S[^`]*`)|("\S[^"]*")|(\'\S[^\']*\')|(\<\/?[A-z]+[^>]*[^\\\\]?\>)/iU'
		);
		$start = "/(?<=[ \.,;]|^)(?<!\<)(";
		$end = ")(?!\>)(?=[ \.,;]|^)/" . ($caseSensitive ? "" : "i") . ($multiline ? "m" : "");
		$c = count($dic);
		$i = 0;
		if ($both)
			foreach ($keyWords as $key => $value) {
				if (!($nk = array_search($nv = $process($key, $key, $i++), $dic)))
					$dic[$nk = "<" . $c++ . ">"] = $nv;
				$text = preg_replace($start . preg_quote($key, "/") . $end, $nk, $text);
				if ($key != $value && !is_null($value)) {
					if (!($nk = array_search($nv = $process($value, $key, $i++), $dic)))
						$dic[$nk = "<" . $c++ . ">"] = $nv;
					$text = preg_replace($start . preg_quote($value, "/") . $end, $nk, $text);
				}
			} else
			foreach ($keyWords as $key => $value) {
				if (!($nk = array_search($nv = $process($value, $key, $i++), $dic)))
					$dic[$nk = "<" . $c++ . ">"] = $nv;
				if (!is_null($value))
					$text = preg_replace($start . preg_quote($value, "/") . $end, $nk, $text);
			}
		return Decode($text, $dic);
	}

	public static function DropColor($color)
	{
		return self::UniversalProperty("filter", "brightness(1000%) grayscale(100%) opacity(0.1)
													drop-shadow(0 0 0 $color)
													drop-shadow(0 0 0 $color)
													drop-shadow(0 0 0 $color)
													drop-shadow(0 0 0 $color)
													drop-shadow(0 0 0 $color)
		");
	}

	public static function ToggleFilter(int|null $mode = null)
	{
		return ($mode ?? \_::$Front->GetMode() ?? 0) < 0 ? self::LightFilter() : self::DarkFilter();
	}
	public static function DarkFilter()
	{
		return self::UniversalProperty("filter", "brightness(-1000%) opacity(1) grayscale(100%)");
	}
	public static function LightFilter()
	{
		return self::UniversalProperty("filter", "brightness(1000%) opacity(1) grayscale(100%)");
	}
}