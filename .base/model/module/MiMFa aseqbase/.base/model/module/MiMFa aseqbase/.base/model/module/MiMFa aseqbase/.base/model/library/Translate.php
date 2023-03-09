<?php
namespace MiMFa\Library;
require_once "DataBase.php";
/**
 * A simple library to Translate texts and documents
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/mimfa/aseqbase
 *@link https://github.com/mimfa/aseqbase/wiki/Libraries#translate See the Library Documentation
 */
class Translate
{
	public static $TableName = "Translate_Lexicon";
	public static $Language = "EN";
	public static $Direction = "LTR";
	public static $CodeStart = "<";
	public static $CodeEnd = ">";

	/**
     * Change the Default Language of translator
     * Default language is EN
	 * @param string $lang
	 */
	public static function Initialize(string $lang, string $direction = "ltr"){
		self::$Language = strtoupper($lang);
		self::$Direction = strtoupper($direction);
	}

	/**
	 * Restruct data for a new language
	 * @param string $lang
	 */
	public static function Restruct($lang){
		$rows = DataBase::Select("SELECT * FROM ".self::$TableName);
		$query = "";
		$args = [];
		$len = count($rows);
		for($i = 0; $i < $len; $i++){
			$data = json_decode($rows[$i]["ValueOptions"]);
			if(!isset($data->$lang)){
				$data->$lang = $data["x"];
				$args[":KeyCode$i"]= $rows[$i]["KeyCode"];
				$args[":ValueOptions$i"]= json_encode($data);
				$query .= "UPDATE ".self::$TableName." SET ValueOptions=:ValueOptions$i WHERE KeyCode=:KeyCode$i;";
			}
        }
		DataBase::Update($query,$args);
		self::$Language = $lang;
	}

	public static function Get($text,$params=[]){
		$dic = array();
		$text = Code($text, $dic, self::$CodeStart, self::$CodeEnd);
		$code = self::CreateCode($text);
		$col = DataBase::Select("SELECT ValueOptions FROM ".self::$TableName." WHERE KeyCode=:KeyCode",[":KeyCode"=>$code]);
		if(count($col)==0)
			DataBase::Insert("INSERT INTO ".self::$TableName." (KeyCode,ValueOptions) VALUES(:KeyCode,:ValueOptions)",
				[":KeyCode"=>$code,":ValueOptions"=>json_encode(array('x'=>$text))]);
		else {
			$data = json_decode($col[0]["ValueOptions"]);
			$text = isset($data->{self::$Language})? $data->{self::$Language} : $data->x;
		}
		foreach($params as $key=>$val) $text = str_replace($key,$val,$text);
		return Decode($text, $dic);
	}

	public static function Set($text,$val=null){
		$dic = array();
		$text = Code($text, $dic, self::$CodeStart, self::$CodeEnd);
		$code = self::CreateCode($text);
		$col = DataBase::Select("SELECT ValueOptions FROM ".self::$TableName." WHERE KeyCode=:KeyCode",[":KeyCode"=>$code]);
		if(count($col)> 0) $data = $col[0]["ValueOptions"];
		$data = json_encode(array('x'=>$text));
		if(!is_null($val))$data->{self::$Language} = Code($val, $dic, self::$CodeStart, self::$CodeEnd);
		$args = [":KeyCode"=>$code,":ValueOptions"=>$data];
		return DataBase::Insert("REPLACE INTO ".self::$TableName." (KeyCode,ValueOptions) VALUES(:KeyCode,:ValueOptions)",$args);
	}

	public static function CreateCode($text){
		if($text===null) return "Null";
		$key =
				str_replace(
					array("\r","\n","\t","|",":",";","`","'",'"', ")","(","*","&","^","%", "#","@","!","~",".",",","/","\\","}","]","{","["),
					" ",
					str_replace(array(" ","-"),".",
						str_replace(array("   ","  ","   ")," ",
							strtolower(trim($text))
						)
					)
				);
		if(strlen($key)>160) $key = md5($key);
		return $key;
	}
}
?>