<?php
namespace MiMFa\Library;

use ReturnTypeWillChange;

require_once "DataTable.php";
require_once "Session.php";
/**
 * A simple library to Translate texts and documents
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#translate See the Library Documentation
 */
class Translate
{
	public $Cache = null;
	public DataTable $DataTable;
	/**
	 * A short version of language name (EN|SP|...)
	 * @var string
	 */
	public $Language = "en";
	public $Encoding = "utf-8";
	/**
	 * The language default direction (ltr|rtl)
	 * @var string
	 */
	public $Direction = "ltr";
	public $CodeStart = "<";
	public $CodeEnd = ">";
	public $CodeLimit = 160;
	public $CodePattern = "/(([\"'`])\S+[\w\W]*\\2)|(\<\S+[\w\W]*\>)|(\d*\.?\d+)/U";
	public $ValidPattern = "/^[\s\d\-*\/\\\\+\.?=_\\]\\[{}()&\^%\$#@!~`'\"<>|]*[A-z]/m";
	public $InvalidPattern = '/^((\s+)|(\s*\<\w+[\s\S]*\>[\s\S]*\<\/\w+\>\s*)|([A-z0-9\-\.\_]+\@([A-z0-9\-\_]+\.[A-z0-9\-\_]+)+)|(([A-z0-9\-]+\:)?([\/\?\#]([^:\/\{\}\|\^\[\]\"\`\'\r\n\t\f]*)|(\:\d))+))$/';
	public $AllowCache = true;
	public $CaseSensitive = false;
	public $AutoUpdate = false;
	public $GetValueQuery = null;

	public function __construct(DataTable $dataTable)
	{
		$this->DataTable = $dataTable;
	}

	/**
	 * Change the Default Language of translator
	 * Default language is EN
	 */
	public function Initialize(Session $session, ?string $defaultLang = null, ?string $defaultDirection = null, ?string $defaultEncoding = null,  bool $caching = true)
	{
		$session->SetCookie("Lang", $this->Language = strtolower(\Req::Grab("lang", "get") ?? $session->GetCookie("Lang") ?? $defaultLang ?? $this->Language));
		$session->SetCookie( "Direction", $this->Direction = strtolower(\Req::Grab("direction", "get") ?? $session->GetCookie("Direction") ?? $defaultDirection ?? $this->Direction));
		$session->SetCookie("Encoding", $this->Encoding = strtolower(\Req::Grab("encoding", "get") ?? $session->GetCookie("Encoding") ?? $defaultEncoding ?? $this->Encoding));
		$this->GetValueQuery = $this->DataTable->SelectValueQuery("ValueOptions", $this->CaseSensitive ? "KeyCode=:KeyCode" : "LOWER(KeyCode)=:KeyCode");
		if ($this->AllowCache = $caching) $this->CacheAll();
	}

	/**
	 * Restruct data for a new language
	 * @param string $lang
	 */
	public function Restruct($lang)
	{
		$rows = $this->DataTable->Select();
		$query = "";
		$args = [];
		$len = count($rows);
		for ($i = 0; $i < $len; $i++) {
			$data = Convert::FromJson($rows[$i]["ValueOptions"]);
			if (!isset($data[$lang])) {
				$data[$lang] = $data["x"];
				$args[":KeyCode$i"] = $rows[$i]["KeyCode"];
				$args[":ValueOptions$i"] = Convert::ToJson($data);
				$query .= "UPDATE " . $this->DataTable->Name . " SET ValueOptions=:ValueOptions$i WHERE KeyCode=:KeyCode$i;";
			}
		}
		$this->DataTable->DataBase->Execute($query, $args);
		$this->Language = $lang;
	}

	public function GetHybrid($text, $replacements = [], $lang = null)
	{
		if (self::IsRootLanguage($text))
			return $text;
		$dic = array();
		$text = Code($text, $dic, $this->CodeStart, $this->CodeEnd, $this->CodePattern);
		$code = $this->CreateCode($text);
		$data = $this->Cache !== null ? ($this->Cache[$code] ?? null) : $this->DataTable->DataBase->FetchValueExecute($this->GetValueQuery, [":KeyCode" => $code]);
		if ($data) {
			$data = json_decode($data, flags: JSON_OBJECT_AS_ARRAY);
			$text = isset($data[$lang ?? $this->Language]) ? $data[$lang ?? $this->Language] : $data["x"];
		} elseif ($this->AutoUpdate)
			$this->DataTable->Insert([":KeyCode" => $code, ":ValueOptions" => Convert::ToJson(array("x" => $text))]);
		foreach ($replacements as $key => $val)
			$text = str_replace($key, $val, $text);
		return Decode($text, $dic);
	}
	public function Get($text, $lang = null)
	{
		if (self::IsRootLanguage($text))
			return $text;
		$dic = array();
		$ntext = Code($text, $dic, $this->CodeStart, $this->CodeEnd, $this->CodePattern);
		$code = $this->CreateCode($ntext);
		$data = $this->Cache !== null ? ($this->Cache[$code] ?? null) : $this->DataTable->DataBase->FetchValueExecute($this->GetValueQuery, [":KeyCode" => $code]);
		if ($data) {
			$data = json_decode($data, flags: JSON_OBJECT_AS_ARRAY);
			return Decode($data[$lang ?? $this->Language]?? $data["x"], $dic);
		} elseif ($this->AutoUpdate)
			$this->DataTable->Replace([":KeyCode" => $code, ":ValueOptions" => Convert::ToJson(array("x" => $ntext))]);
		return $text;
	}

	public function GetAll($condition = null, $params = [])
	{
		$rows = $this->DataTable->Select("*", $condition, $params);
		foreach ($rows as $value) {
			$row = [];
			$row["KEY"] = $value["KeyCode"];
			foreach (Convert::FromJson($value["ValueOptions"]) as $k => $v)
				$row[$k] = $v;
			yield $row;
		}
	}

	public function Set($text, $val = null, $lang = null)
	{
		if (self::IsRootLanguage($text))
			return false;
		$dic = array();
		$text = Code($text, $dic, $this->CodeStart, $this->CodeEnd, $this->CodePattern);
		$code = $this->CreateCode($text);
		$data = $this->Cache !== null ? ($this->Cache[$code] ?? null) : $this->DataTable->DataBase->FetchValueExecute($this->GetValueQuery, [":KeyCode" => $code]);
		if (!$data) $data = array("x" => $text);
		if (!is_null($val))
			$data[$lang ?? $this->Language] = Code($val, $dic, $this->CodeStart, $this->CodeEnd, $this->CodePattern);
		return $this->DataTable->Replace(
			[":KeyCode" => $code, ":ValueOptions" => Convert::ToJson($data)]
		);
	}

	public function SetAll($values)
	{
		$args = [];
		foreach ($values as $value) {
			$row = [];
			$vals = [];
			if($value["KEY"]??null) {
				$row[":KeyCode"] = $vals["x"] = $value["KEY"];
				unset($value["KEY"]);
			}
			else $row[":KeyCode"] = $this->CreateCode($vals["x"] = first($value));
			foreach ($value as $key => $val) if ($key && $val) $vals[strtolower($key)] = $val;
			$row[":ValueOptions"] = Convert::ToJson($vals);
			$args[] = $row;
		}
		return $this->DataTable->Replace($args);
	}

	public function CacheAll($condition = null, $params = [])
	{
		$this->Cache = [];
		if ($this->CaseSensitive)
			foreach ($this->DataTable->Select("*", $condition, $params) as $value)
				$this->Cache[$value["KeyCode"]] = $value["ValueOptions"];
		else
			foreach ($this->DataTable->Select("*", $condition, $params) as $value)
				$this->Cache[strtolower($value["KeyCode"])] = $value["ValueOptions"];
	}

	public function ClearAll($condition = null, $params = [])
	{
		$this->Cache = null;
		return $this->DataTable->Delete($condition, $params);
	}

	public function CreateCode($text)
	{
		if ($text === null)
			return "Null";
		$key = preg_replace("/\s+/", " ", trim($text));
		// str_replace(
		// 	array("\r", "\n", "\t"),
		// 	" ",
		// 	str_replace(
		// 		array("   ", "  ", "   "),
		// 		" ",
		// 		trim($text)
		// 	)
		// );
		if (strlen($key) > $this->CodeLimit)
			$key = md5($key);
		if(!$this->CaseSensitive) $key = strtolower($key);
		return $key;
	}

	/**
	 * To check if the string is on the website main language or not
	 * @param null|string $text The text string
	 * @return bool
	 */
	public function IsRootLanguage($text)
	{
		if (isEmpty($text))
			return true;
		return !preg_match($this->ValidPattern, $text) ||
			preg_match($this->InvalidPattern, $text);
	}

	/**
	 * To check if the string is in a RTL language or LTR
	 * @param null|string $text The text string
	 */
	public function GetDirection($text)
	{
		if (isEmpty($text))
			return null;
		return preg_match("/^[\s\d\-*\/\\\\+\.?=_\]\[{}()&\^%\$#@!~`'\"<>|]*[\p{Arabic}\p{Hebrew}]/u", $text) ? "rtl" : "ltr";
	}
	/**
	 * To get all supported languages based on lexicon
	 * @param mixed $condition
	 * @param mixed $params
	 * @return array{Direction: string, Encoding: mixed, Image: mixed, Title: mixed[]}
	 */
	public function GetLanguages($condition = null, $params = [])
	{
		$arr = [];
		foreach ((Convert::FromJson(
			$this->DataTable->First("ValueOptions", ["KeyCode=''", $condition], $params) ??
			$this->DataTable->First("ValueOptions", $condition, $params)) ?? []) as $key=>$value){
			$value = Convert::FromJson($value);
			if($key=="x") $key = strtolower(\_::$Config->DefaultLanguage??$key);
			$arr[$key] = array(
				"Title" => getBetween($value, "Title", "Name")??strtoupper($key),
				"Image" => getBetween($value, "Image", "Icon")??"https://flagcdn.com/16x12/$key.png",
				"Direction" => get($value, "Direction")??preg_match("/(ar|fa|ur|he|ps|sd|ug|dv|ku|yi|nqo|syr|ckb|ks|bal|brh|bgn|haz|khw|lrc|mzn|pnb|prs|uz_AF|tt|ota)/i", $key??"")?"rtl":"ltr",
				"Encoding" => get($value, "Encoding")??"utf-8"
			);
		}
		return $arr;
	}

	public function GetAccessCondition($tablePrefix = "")
	{
		return "{$tablePrefix}MetaData IS NULL OR {$tablePrefix}MetaData NOT REGEXP '\\\\s*[\"'']?lang[\"'']?\\\\s*:' OR {$tablePrefix}MetaData REGEXP '\\\\s*[\"'']?lang[\"'']?\\\\s*:\\\\s*[\"'']" . $this->Language . "[\"'']'";
	}
}