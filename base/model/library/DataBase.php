<?php namespace MiMFa\Library;
/**
 * A simple library to connect the database and run the most uses SQL queries
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/mimfa/aseqbase
 *@link https://github.com/mimfa/aseqbase/wiki/Libraries#database See the Library Documentation
 */
class DataBase {
	public static function Connection()
	{
		$conn = new \PDO("mysql:host=".\_::$CONFIG->DataBaseHost.";dbname=".\_::$CONFIG->DataBaseName, \_::$CONFIG->DataBaseUser, \_::$CONFIG->DataBasePassword);
		$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		return $conn;
	}

	public static function Query($query,$params=[]){
		foreach($params as $key => $val)
			$query = str_replace("$key","'$val'",$query);
		return $query;
	}

	public static function SelectValue($query,$params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$stmt->execute($params);
		return $stmt->fetchColumn();
	}
	public static function TrySelectValue($query,$params=[], $defaultValue = null)
	{
		try{
			return self::SelectValue($query,$params);
        }catch(\Exception $ex){ return $defaultValue; }
	}

	public static function Select($query,$params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$stmt->execute($params);
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		return $stmt->fetchAll();
	}
	public static function TrySelect($query,$params=[], $defaultValue = array())
	{
		try{
			return self::Select($query,$params);
        }catch(\Exception $ex){ return $defaultValue; }
	}
	public static function DoSelect($tableName, $columns = "*", $params=[], $condition=null, $defaultValue = array())
	{
		$query = "SELECT ".$columns." FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition);
		$result = self::TrySelect($query,$params,$defaultValue);
		return is_array($result) && count($result) > 0 ? $result : $defaultValue;
	}

	public static function Insert($query,$params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($params);
		return $isdone;
	}
	public static function TryInsert($query,$params=[], $defaultValue = false)
	{
		try{
			return self::Insert($query,$params);
        }catch(\Exception $ex){ return $defaultValue; }
	}
	public static function DoInsert($tableName, $params=[], $condition=null, $defaultValue = false){
		$vals = array();
		$sets = array();
		foreach($params as $key => $value)
		{
			$sets[] = "`".ltrim($key,":")."`";
			$vals[] = $key;
		}

		$query = "INSERT INTO `$tableName` (".implode(", ",$sets).") VALUES (".implode(", ",$vals).")".(is_null($condition)?"":" WHERE ".$condition);
		return self::TryInsert($query,$params,$defaultValue);
	}

	public static function Update($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($params);
		return $isdone;
	}
	public static function TryUpdate($query,$params=[], $defaultValue = false)
	{
		try{
			return self::Update($query,$params);
        }catch(\Exception $ex){ return $defaultValue; }
	}
	public static function DoUpdate($tableName, $params=[], $condition=null, $defaultValue = false){
		$sets = array();
		foreach($params as $key => $value)
			$sets[] = "`".ltrim($key,":")."`=".$key;

		$query = "UPDATE `$tableName` SET ".implode(", ",$sets).(is_null($condition)?"":" WHERE ".$condition);
		return self::TryUpdate($query,$params,$defaultValue);
	}

	public static function Delete($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($params);
		return $isdone;
	}
	public static function TryDelete($query,$params=[], $defaultValue = false)
	{
		try{
			return self::Delete($query,$params);
        }catch(\Exception $ex){ return $defaultValue; }
	}
	public static function DoDelete($tableName,  $params=[], $condition=null, $defaultValue = false){
		$query = "DELETE FROM `$tableName`".(is_null($condition)?"":" WHERE ".$condition);
        return self::TryDelete($query, $params,$defaultValue);
	}

	public static function GetCount($tableName, $col = "ID",$condition =null)
	{
		$query = "SELECT COUNT(".$col.") FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition).";";
		return self::TrySelectValue($query);
	}
	public static function GetSum($tableName, $col = "ID",$condition =null)
	{
		$query = "SELECT SUM(".$col.") FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition).";";
		return self::TrySelectValue($query);
	}
	public static function GetAverage($tableName, $col = "ID",$condition =null)
	{
		$query = "SELECT AVG(".$col.") FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition).";";
		return self::TrySelectValue($query);
	}
	public static function GetMax($tableName, $col = "ID",$condition =null)
	{
		$query = "SELECT MAX(".$col.") FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition).";";
		return self::TrySelectValue($query);
	}
	public static function GetMin($tableName, $col = "ID",$condition =null)
	{
		$query = "SELECT MIN(".$col.") FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition).";";
		return self::TrySelectValue($query);
	}

	public static function Exists($tableName, $col = null, $condition =null)
	{
		$query = "SELECT ".(is_null($col)?"1":$col)." FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition).";";
		$result = self::TrySelectValue($query);
		return !is_null($result);
	}
}
?>