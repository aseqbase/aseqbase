<?php
namespace MiMFa\Library;

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
	 * A short version of language name (en|es|...)
	 * @var string
	 */
	public $Language = "en";
	public $Encoding = "utf-8";
	/**
	 * The language default direction (ltr|rtl)
	 * @var string
	 */
	public $CodeLimit = 160;
	public $Direction = "ltr";
	public $WrapStart = "<";
	public $WrapEnd = ">";
	public $WrapPattern = "/(\"\S+[^\"]*\")|('\S+[^']*')|(`\S+[^`]*`)|(\<\S+[\w\W]*\>)|(\d*\.?\d+)/U";
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
	public function Initialize(?string $defaultLang = null, ?string $defaultDirection = null, ?string $defaultEncoding = null,  bool $caching = true)
	{
		setMemo("Lang", $this->Language = strtolower(snapReceive("lang", null, "get") ?? getMemo("Lang") ?? $defaultLang ?? $this->Language));
		setMemo( "Direction", $this->Direction = strtolower(snapReceive("direction", null, "get") ?? getMemo("Direction") ?? $defaultDirection ?? $this->Direction));
		setMemo("Encoding", $this->Encoding = strtolower(snapReceive("encoding", null, "get") ?? getMemo("Encoding") ?? $defaultEncoding ?? $this->Encoding));
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
		if ($this->IsRootLanguage($text))
			return $text;
		$dic = array();
		$text = encode($text, $dic, $this->WrapStart, $this->WrapEnd, $this->WrapPattern);
		$code = $this->CreateCode($text);
		$data = $this->Cache !== null ? ($this->Cache[$code] ?? null) : $this->DataTable->DataBase->FetchValueExecute($this->GetValueQuery, [":KeyCode" => $code]);
		if ($data) {
			$data = json_decode($data, flags: JSON_OBJECT_AS_ARRAY);
			$data = $data[$lang ?? $this->Language]?? $data["x"];
		} elseif ($this->AutoUpdate)
			$this->DataTable->Insert([":KeyCode" => $code, ":ValueOptions" => Convert::ToJson(array("x" => $data))]);
		foreach ($replacements as $key => $val)
			$data = str_replace($key, $val, $data);
		$data = decode($data, $dic);
		if($this->CaseSensitive) return $data;
		return self::SetCaseStatus($data, $data);
	}
	public function Get($text, $lang = null)
	{
		if ($this->IsRootLanguage($text))
			return $text;
		$dic = array();
		$ntext = encode($text, $dic, $this->WrapStart, $this->WrapEnd, $this->WrapPattern);
		$code = $this->CreateCode($ntext);
		$data = $this->Cache !== null ? ($this->Cache[$code] ?? null) : $this->DataTable->DataBase->FetchValueExecute($this->GetValueQuery, [":KeyCode" => $code]);
		if ($data) {
			$data = json_decode($data, flags: JSON_OBJECT_AS_ARRAY);
			$data = Decode($data[$lang ?? $this->Language]?? $data["x"], $dic);
			if($this->CaseSensitive) return $data;
			return self::SetCaseStatus($data, $text);
		} elseif ($this->AutoUpdate)
			$this->DataTable->Replace([":KeyCode" => $code, ":ValueOptions" => Convert::ToJson(array("x" => $ntext))]);
		return $text;
	}

	public function GetAll($condition = null, $params = [], $hasKey = false)
	{
		$rows = $this->DataTable->Select("*", $condition, $params);
		$row = [];
		if($hasKey) foreach ($rows as $value) {
			foreach ($row as $k => $v) $row[$k] = "";
			$row["KEY"] = $value["KeyCode"];
			foreach (Convert::FromJson($value["ValueOptions"]) as $k => $v)
				$row[$k] = $v;
			yield $row;
		}
		else foreach ($rows as $value) {
			foreach ($row as $k => $v) $row[$k] = "";
			foreach (Convert::FromJson($value["ValueOptions"]) as $k => $v)
				$row[$k] = $v;
			yield $row;
		}
	}

	public function Set($text, $val = null, $lang = null)
	{
		if ($this->IsRootLanguage($text))
			return false;
		$dic = array();
		$text = encode($text, $dic, $this->WrapStart, $this->WrapEnd, $this->WrapPattern);
		$code = $this->CreateCode($text);
		$data = $this->Cache !== null ? ($this->Cache[$code] ?? null) : $this->DataTable->DataBase->FetchValueExecute($this->GetValueQuery, [":KeyCode" => $code]);
		if (!$data) $data = array("x" => $text);
		if (!is_null($val))
			$data[$lang ?? $this->Language] = encode($val, $dic, $this->WrapStart, $this->WrapEnd, $this->WrapPattern);
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
	/**
	 * Convert the normal text to a suitable an maximum CodeLimit counted key
	 * @param mixed $text The normal text
	 * @return array|string|null
	 */
	public function CreateCode($text)
	{
		if ($text === null)
			return "Null";
		$key = preg_replace("/\s+|((?<=[\w\W]{4})\.)/", " ", trim($text, " \n\r\t\v\x00~`!@#$%^&*()-+=?/,|\\'\":;]}[{"));
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
				"Image" => getBetween($value, "Image", "Icon")??"https://unpkg.com/language-icons/icons/$key.svg",
				"Direction" => $dir = get($value, "Direction")??preg_match("/(ar|fa|ur|he|ps|sd|ug|dv|ku|yi|nqo|syr|ckb|ks|bal|brh|bgn|haz|khw|lrc|mzn|pnb|prs|uz_AF|tt|ota)/i", $key??"")?"rtl":"ltr",
				"Encoding" => $enc = get($value, "Encoding")??"utf-8",
				"Query" => "lang=$key&direction=$dir&encoding=$enc"
			);
		}
		return $arr;
	}

	/**
	 * Get the data of the language suitable for a url query
	 * @param null|string $key The language key of null for default
	 */
	public function GetQuery($key = null)
	{
		if($key) return get($this->GetLanguages(), $key, "Query");
		else return "lang=".$this->Language."&direction=".$this->Direction."&encoding=".urlencode($this->Encoding);
	}

	public function GetAccessCondition($tablePrefix = "")
	{
		return "{$tablePrefix}MetaData IS NULL OR {$tablePrefix}MetaData NOT REGEXP '\\\\s*[\"'']?lang[\"'']?\\\\s*:' OR {$tablePrefix}MetaData REGEXP '\\\\s*[\"'']?lang[\"'']?\\\\s*:\\\\s*[\"'']" . $this->Language . "[\"'']'";
	}

	
	/**
	 * To check if the string is in a RTL language or LTR
	 * @param null|string $text The text string
	 */
	public static function GetDirection($text)
	{
		if (isEmpty($text))
			return null;
		return preg_match("/^[\s\d\-*\/\\\\+\.?=_\]\[{}()&\^%\$#@!~`'\"<>|]*[\p{Arabic}\p{Hebrew}]/u", $text) ? "rtl" : "ltr";
	}

	/**
	 * To get the case status of the normal text,
	 * @param null|string $text The text string
	 * @return int It will return 3 if all the world be Capital, 2 for Propper case, 1 for all Lower case and 0 for unknown case
	 */
	public static function GetCaseStatus($text)
	{
		if ($text === strtolower($text))
			return 1;
		if ($text === strToProper($text))
			return 2;
		if ($text === strtoupper($text))
			return 3;
		return 0;
	}
	/**
	 * To set the case status of the text as the model,
	 * @param null|string $text The text string
	 * @param null|string $model The case model string for example "A" to set all upper "b"  for all lower
	 * @return string The sat case text
	 */
	public static function SetCaseStatus($text, $model)
	{
		if ($model === strtolower($model))
			return strtolower($text);
		if ($model === strToProper($model))
			return strToProper($text);
		if ($model === strtoupper($model))
			return strtoupper($text);
		return $text;
	}
}