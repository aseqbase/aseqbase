<?php namespace MiMFa\Library;

use Illuminate\Database\DatabaseManager;

class DataBase
{
	public static function Connection()
	{
		$conn = new PDO("mysql:host=".\_:;$CONFIG->DataBaseHost.";dbname=".\_:;$CONFIG->DataBaseName, \_:;$CONFIG->DataBaseUser, \_:;$CONFIG->DataBasePassword);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;
	}

	public static function Query($query,$params=[]){
		foreach($params as $key => $val)
			$query = str_replace("$key","'$val'",$query);
		return $query;
	}

	
	public static function Select($query,$params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($params);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		return $stmt->fetchAll(); 
	}

	public static function SelectValue($query,$params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($params);
		return $stmt->fetchColumn(); 
	}

	public static function DoSelect($tableName, $params=[], $condition=null)
	{
		$query = "SELECT * FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition);
		$result = self::Select($query,$params);
		return count($result) > 0 ? $result[0] : null;
	}

	public static function GetMax($tableName, $col = "id",$condition =null)
	{
		$query = "SELECT MAX(".$col.") FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition).";";
		$result = self::SelectValue($query);
		return $result;
	}

	public static function GetMin($tableName, $col = "id",$condition =null)
	{
		$query = "SELECT MIN(".$col.") FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition).";";
		$result = self::SelectValue($query);
		return $result;
	}
	
	public static function Insert($query,$params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($params);
		return $isdone; 
	}

	public static function DoInsert($tableName, $params=[], $condition=null){
		$vals = array();
		$sets = array();
		foreach($params as $key => $value)
		{
			$sets[] = "`".ltrim($key,":")."`";
			$vals[] = $key;
		}

		$query = "INSERT INTO `$tableName` (".implode(", ",$sets).") VALUES (".implode(", ",$vals).")".(is_null($condition)?"":" WHERE ".$condition);
		return self::Insert($query,$params);
	}

	public static function Update($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($params);
		return $isdone;
	}

	public static function DoUpdate($tableName, $params=[], $condition=null){
		$sets = array();
		foreach($params as $key => $value)
			$sets[] = "`".ltrim($key,":")."`=".$key;

		$query = "UPDATE `$tableName` SET ".implode(", ",$sets).(is_null($condition)?"":" WHERE ".$condition);
		return self::Update($query,$params);
	}

	public static function Delete($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($params);
		return $isdone;
	}

	public static function DoDelete($tableName,  $params=[], $condition=null){
		$query = "DELETE FROM `$tableName`".(is_null($condition)?"":" WHERE ".$condition);
		return self::Delete($query, $params);
	}
}
?>