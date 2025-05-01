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
	public $CaseSensitive = true;
	public $AutoUpdate = false;

	public function __construct(DataTable $dataTable){
		$this->DataTable = $dataTable;
	}

	/**
     * Change the Default Language of translator
     * Default language is EN
	 */
	public function Initialize(Session $session, ?string $lang = null, ?string $direction = null, ?string $encoding = null){
		$session->SetCookie("Lang", $this->Language = strtolower($lang??\Req::Grab("lang", "get")??$session->GetCookie("Lang")??$this->Language));
		$session->SetCookie("Direction", $this->Direction = strtolower($direction??\Req::Grab("direction", "get")??$session->GetCookie("Direction")??$this->Direction));
		$session->SetCookie("Encoding", $this->Encoding = strtolower($encoding??\Req::Grab("encoding", "get")??$session->GetCookie("Encoding")??$this->Encoding));
	}

	/**
	 * Restruct data for a new language
	 * @param string $lang
	 */
	public function Restruct($lang){
		$rows = $this->DataTable->Select();
		$query = "";
		$args = [];
		$len = count($rows);
		for($i = 0; $i < $len; $i++){
			$data = Convert::FromJson($rows[$i]["ValueOptions"]);
			if(!isset($data[$lang])){
				$data[$lang] = $data["X"];
				$args[":KeyCode$i"]= $rows[$i]["KeyCode"];
				$args[":ValueOptions$i"]= Convert::ToJson($data);
				$query .= "UPDATE ".$this->DataTable->Name." SET `ValueOptions`=:ValueOptions$i WHERE `KeyCode`=:KeyCode$i;";
			}
        }
		$this->DataTable->DataBase->ExecuteUpdate($query,$args);
		$this->Language = $lang;
	}

	public function Get($text, $replacements=[], $lang = null){
		if(self::IsRootLanguage($text) ) return $text;
		$dic = array();
		$text = Code($text, $dic, $this->CodeStart, $this->CodeEnd, $this->CodePattern);
		$code = $this->CreateCode($text);
		$data = $this->DataTable->SelectValue(
			"ValueOptions", 
			$this->CaseSensitive?"`KeyCode`=:KeyCode":"LOWER(`KeyCode`)=LOWER(:KeyCode)",
			[":KeyCode"=>$code]);
        if($data){
			$data = Convert::FromJson($data);
			$text = isset($data[$lang??$this->Language])? $data[$lang??$this->Language] : $data["X"];
		}else {
			if($this->AutoUpdate)
				$this->DataTable->Insert([":KeyCode"=>$code,":ValueOptions"=>Convert::ToJson(array("X"=>$text))]);
        }
		foreach($replacements as $key=>$val) $text = str_replace($key,$val,$text);
		return Decode($text, $dic);
	}

	public function GetAll($condition = null, $params=[]){
		$rows = $this->DataTable->Select("*", $condition, $params);
		foreach ($rows as $value){
			$row = [];
			$row["KEY" ]=$value["KeyCode"];
			foreach(Convert::FromJson($value["ValueOptions"]) as $k => $v)
				$row[$k]=$v;
			yield $row;
		}
    }

	public function Set($text,$val=null, $lang = null) {
		if(self::IsRootLanguage($text)) return false;
		$dic = array();
		$text = Code($text, $dic, $this->CodeStart, $this->CodeEnd, $this->CodePattern);
		$code = $this->CreateCode($text);
		$data = $this->DataTable->SelectValue(
			"ValueOptions", 
			$this->CaseSensitive?"`KeyCode`=:KeyCode":"LOWER(`KeyCode`)=LOWER(:KeyCode)",
			[":KeyCode"=>$code]);
		$data = array("X"=>$text);
		if(!is_null($val)) $data[$lang??$this->Language] = Code($val, $dic, $this->CodeStart, $this->CodeEnd, $this->CodePattern);
		return $this->DataTable->Replace(
			null, 
			[":KeyCode"=>$code,":ValueOptions"=>Convert::ToJson($data)]);
	}

	public function SetAll($values) {
		$queries = [];
		$args = [];
		foreach ($values as $i=>$value){
            $queries[] = "REPLACE INTO ".$this->DataTable->Name." (`KeyCode`, `ValueOptions`) VALUES(:KeyCode$i,:ValueOptions$i);";
			$args[":KeyCode$i"] = $value["KEY"];
			$vals = [];
			foreach ($value as $key=>$val)
				if($key !== "KEY" && !isEmpty($key))
					$vals[$key] = $val;
			$args[":ValueOptions$i"] = Convert::ToJson($vals);
        }
        return $this->DataTable->DataBase->ExecuteReplace(join(PHP_EOL, $queries), $args);
	}

	public function ClearAll($condition = null, $params=[]) {
		return $this->DataTable->Delete($condition, $params);
    }

	public function CreateCode($text){
		if($text===null) return "Null";
		$key =
				str_replace(
					array("\r","\n","\t"),
					" ",
						str_replace(array("   ","  ","   ")," ",
							trim($text)
						)
				);
		if(strlen($key)>$this->CodeLimit) $key = md5($key);
		return $key;
	}

	/**
	 * To check if the string is on the website main language or not
	 * @param null|string $text The text string
	 * @return bool
	 */
	public function IsRootLanguage($text)
	{
		if (isEmpty($text)) return true;
		return !preg_match($this->ValidPattern, $text) ||
		preg_match($this->InvalidPattern, $text);
	}
	/**
	 * To check if the string is in a RTL language or LTR
	 * @param null|string $text The text string
	 */
	public function GetDirection($text)
	{
		if (isEmpty($text)) return null;
		return  preg_match("/^[\s\d\-*\/\\\\+\.?=_\]\[{}()&\^%\$#@!~`'\"<>|]*[\p{Arabic}\p{Hebrew}]/u", $text)?"rtl":"ltr";
	}
}
?>