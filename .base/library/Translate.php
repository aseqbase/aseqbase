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
	public $Language = "EN";
	public $Encoding = "UTF-8";
	/**
	 * The language default direction (LTR|RTL)
	 * @var string
	 */
	public $Direction = "LTR";
	public $CodeStart = "<";
	public $CodeEnd = ">";
	public $CodeLimit = 160;
	public $CodePattern = "/(([\"'`])\S+[\w\W]*\\2)|(\<\S+[\w\W]*\>)|(\d*\.?\d+)/U";
	public $ValidPattern = "/[A-z]+/";
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
	public function Initialize(Session $session, string $lang = null, string $direction = null, string $encoding = null){
		$session->SetCookie("Lang", $this->Language = strtoupper($lang??\Req::Grab("lang", "get")??$session->GetCookie("Lang")??$this->Language));
		$session->SetCookie("Direction", $this->Direction = strtoupper($direction??\Req::Grab("direction", "get")??$session->GetCookie("Direction")??$this->Direction));
		$session->SetCookie("Encoding", $this->Encoding = strtoupper($encoding??\Req::Grab("encoding", "get")??$session->GetCookie("Encoding")??$this->Encoding));
	}

	/**
	 * Restruct data for a new language
	 * @param string $lang
	 */
	public function Restruct($lang){
		$rows = $this->DataTable->DoSelect();
		$query = "";
		$args = [];
		$len = count($rows);
		for($i = 0; $i < $len; $i++){
			$data = Convert::FromJson($rows[$i]["ValueOptions"]);
			if(!isset($data[$lang])){
				$data[$lang] = $data["x"];
				$args[":KeyCode$i"]= $rows[$i]["KeyCode"];
				$args[":ValueOptions$i"]= Convert::ToJson($data);
				$query .= "UPDATE ".$this->DataTable->Name." SET `ValueOptions`=:ValueOptions$i WHERE `KeyCode`=:KeyCode$i;";
			}
        }
		$this->DataTable->DataBase->Update($query,$args);
		$this->Language = $lang;
	}

	public function Get($text, $replacements=[], $lang = null){
		if(
			is_null($text) ||
			!preg_match($this->ValidPattern, $text) ||
			preg_match($this->InvalidPattern, $text)
			) return $text;
		$dic = array();
		$text = Code($text, $dic, $this->CodeStart, $this->CodeEnd, $this->CodePattern);
		$code = $this->CreateCode($text);
		$data = $this->DataTable->DoSelectValue(
			"ValueOptions", 
			$this->CaseSensitive?"`KeyCode`=:KeyCode":"LOWER(`KeyCode`)=LOWER(:KeyCode)",
			[":KeyCode"=>$code]);
        if($data){
			$data = Convert::FromJson($data);
			$text = isset($data[$lang??$this->Language])? $data[$lang??$this->Language] : $data["x"];
		}else {
			if($this->AutoUpdate)
				$this->DataTable->DoInsert(
				null,
				[":KeyCode"=>$code,":ValueOptions"=>Convert::ToJson(array('x'=>$text))]);
        }
		foreach($replacements as $key=>$val) $text = str_replace($key,$val,$text);
		return Decode($text, $dic);
	}

	public function GetAll($condition = null, $params=[]){
		$rows = $this->DataTable->DoSelect("*", $condition, $params);
		foreach ($rows as $value){
			$row = [];
			$row["Key" ]=$value["KeyCode"];
			foreach(Convert::FromJson($value["ValueOptions"]) as $k => $v)
				$row[$k]=$v;
			yield $row;
		}
    }

	public function Set($text,$val=null, $lang = null) {
		if(
			is_null($text) ||
			!preg_match($this->ValidPattern, $text) ||
			preg_match($this->InvalidPattern, $text)
			) return false;
		$dic = array();
		$text = Code($text, $dic, $this->CodeStart, $this->CodeEnd, $this->CodePattern);
		$code = $this->CreateCode($text);
		$data = $this->DataTable->DoSelectValue(
			"ValueOptions", 
			$this->CaseSensitive?"`KeyCode`=:KeyCode":"LOWER(`KeyCode`)=LOWER(:KeyCode)",
			[":KeyCode"=>$code]);
		$data = array('x'=>$text);
		if(!is_null($val)) $data[$lang??$this->Language] = Code($val, $dic, $this->CodeStart, $this->CodeEnd, $this->CodePattern);
		return $this->DataTable->DoReplace(
			null, 
			[":KeyCode"=>$code,":ValueOptions"=>Convert::ToJson($data)]);
	}

	public function SetAll($values) {
		$queries = [];
		$args = [];
		foreach ($values as $i=>$value){
            $queries[] = "REPLACE INTO ".$this->DataTable->Name." (`KeyCode`, `ValueOptions`) VALUES(:KeyCode$i,:ValueOptions$i);";
			$args[":KeyCode$i"] = $value["Key" ];
			$vals = [];
			foreach ($value as $key=>$val)
				if($key !== "Key" && !isEmpty($key))
					$vals[$key] = $val;
			$args[":ValueOptions$i"] = Convert::ToJson($vals);
        }
        return $this->DataTable->DataBase->Replace(join(PHP_EOL, $queries), $args);
	}

	public function ClearAll($condition = null, $params=[]) {
		return $this->DataTable->DoDelete($condition, $params);
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
}
?>