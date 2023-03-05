<?php namespace MiMFa\Library;

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
	public static function TrySelectValue($query,$params=[])
	{
		try{
			return self::SelectValue($query,$params);
        }catch(\Exception $ex){ return null; }
	}

	public static function Select($query,$params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$stmt->execute($params);
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		return $stmt->fetchAll();
	}
	public static function TrySelect($query,$params=[])
	{
		try{
			return self::Select($query,$params);
        }catch(\Exception $ex){ return null; }
	}
	public static function DoSelect($tableName, $columns = "*", $params=[], $condition=null)
	{
		try{
			$query = "SELECT ".$columns." FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition);
			$result = self::Select($query,$params);
			return count($result) > 0 ? $result : array();
        }catch(\Exception $ex){ return null; }
	}

	public static function Insert($query,$params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($params);
		return $isdone;
	}
	public static function TryInsert($query,$params=[])
	{
		try{
			return self::Insert($query,$params);
        }catch(\Exception $ex){ return null; }
	}
	public static function DoInsert($tableName, $params=[], $condition=null){
		try{
			$vals = array();
			$sets = array();
			foreach($params as $key => $value)
			{
				$sets[] = "`".ltrim($key,":")."`";
				$vals[] = $key;
			}

			$query = "INSERT INTO `$tableName` (".implode(", ",$sets).") VALUES (".implode(", ",$vals).")".(is_null($condition)?"":" WHERE ".$condition);
			return self::Insert($query,$params);
        }catch(\Exception $ex){ return null; }
	}

	public static function Update($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($params);
		return $isdone;
	}
	public static function TryUpdate($query,$params=[])
	{
		try{
			return self::Update($query,$params);
        }catch(\Exception $ex){ return null; }
	}
	public static function DoUpdate($tableName, $params=[], $condition=null){
		try{
			$sets = array();
			foreach($params as $key => $value)
				$sets[] = "`".ltrim($key,":")."`=".$key;

			$query = "UPDATE `$tableName` SET ".implode(", ",$sets).(is_null($condition)?"":" WHERE ".$condition);
			return self::Update($query,$params);
        }catch(\Exception $ex){ return null; }
	}

	public static function Delete($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($params);
		return $isdone;
	}
	public static function TryDelete($query,$params=[])
	{
		try{
			return self::Delete($query,$params);
        }catch(\Exception $ex){ return null; }
	}
	public static function DoDelete($tableName,  $params=[], $condition=null){
		try{
			$query = "DELETE FROM `$tableName`".(is_null($condition)?"":" WHERE ".$condition);
            return self::Delete($query, $params);
        }catch(\Exception $ex){ return null; }
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