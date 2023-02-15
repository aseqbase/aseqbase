<?php namespace MiMFa\Library;
require_once "db.php";

class Translate
{
	public static $TableName = "rainlab_translate_messages";
	public static $Lang = "en";

	public static function Lang($lang){
		self::$Lang = $lang;
	}

	public static function FillLang($lang){
		$rows = DataBase::Select("SELECT * FROM ".self::$TableName);
		$query = "";
		$args = [];
		for($i = 0;$i<count($rows);$i++){
			$data = json_decode($rows[$i]["message_data"]);
			if(!isset($data->$lang)){
				$data->$lang = $data->x;
				$args[":code$i"]= $rows[$i]["code"];
				$args[":message_data$i"]= json_encode($data);
				$query .= "UPDATE ".self::$TableName." SET message_data=:message_data$i WHERE code=:code${i};";
			}
 
		}
		DB::Update($query,$args);
		self::$Lang = $lang;
	}

	public static function Get($text,$params=[]){
		$code = self::CreateKey($text);
		$col = DataBase::Select("SELECT message_data FROM ".self::$TableName." WHERE code=:code",[":code"=>$code]);
		$data = json_encode(array('x'=>$text));
		if(count($col)==0) {
			$args = [":code"=>$code,":message_data"=>$data];
			DB::Insert("INSERT INTO ".self::$TableName." (code,message_data) VALUES(:code,:message_data)",$args);
		}
		else {
			$data = json_decode($col[0]["message_data"]);
			$text = isset($data->{self::$Lang})? $data->{self::$Lang} : $data->x;
		}
		foreach($params as $key=>$val) $text = str_replace($key,$val,$text);
		return $text;
	}
	
	public static function Set($text,$val=null){
		$code = self::CreateKey($text);
		$col = DataBase::Select("SELECT message_data FROM ".self::$TableName." WHERE code=:code",[":code"=>$code]);
		if(count($col)> 0) $data = $col[0]["message_data"];
		$data = json_encode(array('x'=>$text));
		if(!is_null($val))$data->{self::$Lang} = $val; 
		$args = [":code"=>$code,":message_data"=>$data];
		if(count($col)==0) DataBase::Insert("INSERT INTO ".self::$TableName." (code,message_data) VALUES(:code,:message_data)",$args);
		else DataBase::Update("UPDATE ".self::$TableName." SET message_data=:message_data WHERE code=:code",$args);
		return $text;
	}

	public static function CreateKey($text){
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