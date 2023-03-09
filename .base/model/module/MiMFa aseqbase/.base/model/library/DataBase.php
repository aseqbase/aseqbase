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
		$conn = new \PDO(\_::$CONFIG->DataBaseType.":host=".\_::$CONFIG->DataBaseHost.";dbname=".\_::$CONFIG->DataBaseName, \_::$CONFIG->DataBaseUser, \_::$CONFIG->DataBasePassword);
		$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		return $conn;
	}

	public static function Query($query,$params=[]){
		foreach($params as $key => $val)
			$query = str_replace("$key","'$val'",$query);
		return $query;
	}

	public static function SelectValue($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$stmt->execute($params);
		return $stmt->fetchColumn();
	}
	public static function TrySelectValue($query, $params=[], $defaultValue = null)
	{
		try{
			return self::SelectValue($query,$params)??$defaultValue;
        }
        catch(\Exception $ex){ self::Error($ex); return $defaultValue; }
	}
	public static function DoSelectValue($tableName, $columns = "ID", $condition=null, $params=[], $defaultValue = null)
	{
		$query = "SELECT ".$columns." FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition);
		return self::TrySelectValue($query,$params,$defaultValue);
	}

	public static function Select($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$stmt->execute($params);
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		return $stmt->fetchAll();
	}
	public static function TrySelect($query, $params=[], $defaultValue = array())
	{
		try{
			return self::Select($query,$params)??$defaultValue;
        }catch(\Exception $ex){ self::Error($ex); return $defaultValue; }
	}
	public static function DoSelect($tableName, $columns = "*", $condition=null, $params=[], $defaultValue = array())
	{
		$query = "SELECT ".($columns??"*")." FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition);
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
			return self::Insert($query,$params)??$defaultValue;
        }catch(\Exception $ex){ self::Error($ex); return $defaultValue; }
	}
	public static function DoInsert($tableName, $condition=null, $params=[], $defaultValue = false){
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

	public static function Replace($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($params);
		return $isdone;
	}
	public static function TryReplace($query, $params=[], $defaultValue = false)
	{
		try{
			return self::Replace($query,$params)??$defaultValue;
        }catch(\Exception $ex){ self::Error($ex); return $defaultValue; }
	}
	public static function DoReplace($tableName, $condition=null, $params=[], $defaultValue = false){
		$vals = array();
		$sets = array();
		foreach($params as $key => $value)
		{
			$sets[] = "`".ltrim($key,":")."`";
			$vals[] = $key;
		}

		$query = "REPLACE INTO `$tableName` (".implode(", ",$sets).") VALUES (".implode(", ",$vals).")".(is_null($condition)?"":" WHERE ".$condition);
		return self::TryReplace($query,$params,$defaultValue);
	}

	public static function Update($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($params);
		return $isdone;
	}
	public static function TryUpdate($query, $params=[], $defaultValue = false)
	{
		try{
			return self::Update($query,$params)??$defaultValue;
        }catch(\Exception $ex){ self::Error($ex); return $defaultValue; }
	}
	public static function DoUpdate($tableName, $condition=null, $params=[], $defaultValue = false){
		$sets = array();
		foreach($params as $key => $value)
			$sets[] = "`".ltrim($key,":")."`=".$key;

		$query = "UPDATE `$tableName` SET ".implode(", ",$sets).(is_null($condition)?"":" WHERE ".$condition);
		return self::TryUpdate($query, $params,$defaultValue);
	}

	public static function Delete($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($params);
		return $isdone;
	}
	public static function TryDelete($query, $params=[], $defaultValue = false)
	{
		try{
			return self::Delete($query,$params)??$defaultValue;
        }catch(\Exception $ex){ self::Error($ex); return $defaultValue; }
	}
	public static function DoDelete($tableName, $condition=null, $params=[], $defaultValue = false){
		$query = "DELETE FROM `$tableName`".(is_null($condition)?"":" WHERE ".$condition);
        return self::TryDelete($query, $params,$defaultValue);
	}

	public static function GetCount($tableName, $col = "ID",$condition =null, $params=[])
	{
		$query = "SELECT COUNT(".$col.") FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition).";";
		return self::TrySelectValue($query, $params);
	}
	public static function GetSum($tableName, $col = "ID",$condition =null, $params=[])
	{
		$query = "SELECT SUM(".$col.") FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition).";";
		return self::TrySelectValue($query, $params);
	}
	public static function GetAverage($tableName, $col = "ID", $condition =null, $params=[])
	{
		$query = "SELECT AVG(".$col.") FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition).";";
		return self::TrySelectValue($query, $params);
	}
	public static function GetMax($tableName, $col = "ID", $condition =null, $params=[])
	{
		$query = "SELECT MAX(".$col.") FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition).";";
		return self::TrySelectValue($query, $params);
	}
	public static function GetMin($tableName, $col = "ID", $condition =null, $params=[])
	{
		$query = "SELECT MIN(".$col.") FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition).";";
		return self::TrySelectValue($query, $params);
	}

	public static function Exists($tableName, $col = null, $condition =null, $params=[])
	{
		$query = "SELECT ".(is_null($col)?"1":$col)." FROM `$tableName` ".(is_null($condition)?"":" WHERE ".$condition).";";
		$result = null;
		try{
			$result =  self::SelectValue($query,$params);
        } catch(\Exception $ex){ }
		return !is_null($result);
	}

	public static function Error(\Exception $ex){
        switch (\_::$CONFIG->DataBaseError)
        {
            case null:
            case 0:
				break;
            case 1:
				echo $ex->getMessage();
				break;
        	default:
				echo $ex->getMessage();
				break;
        }
    }
}

class DataTable {
	public $TableName = null;

    public	function __construct($tableName){
		$this->TableName = $tableName;
    }

	public function Connection()
	{
		return DataBase::Connection();
	}

	public function DoSelectValue($columns = "*", $condition=null, $params=[], $defaultValue = null)
	{
		return DataBase::DoSelectValue($this->TableName, $columns, $condition, $params, $defaultValue);
	}

	public function DoSelect($columns = "*", $condition=null, $params=[], $defaultValue = array())
	{
		return DataBase::DoSelect($this->TableName, $columns, $condition, $params, $defaultValue);
	}

	public function DoInsert($tableName, $condition=null, $params=[], $defaultValue = false){
        return DataBase::DoInsert($this->TableName, $condition, $params, $defaultValue);
	}

	public function DoReplace($tableName, $condition=null, $params=[], $defaultValue = false){
        return DataBase::DoReplace($this->TableName, $condition, $params, $defaultValue);
	}

	public function DoUpdate($tableName, $condition=null, $params=[], $defaultValue = false){
        return DataBase::DoUpdate($this->TableName, $condition, $params, $defaultValue);
	}

	public function DoDelete($tableName, $condition=null, $params=[], $defaultValue = false){
        return DataBase::DoDelete($this->TableName, $condition, $params, $defaultValue);
	}

	public function GetCount($tableName, $col = "ID",$condition =null, $params=[])
	{
        return DataBase::GetCount($this->TableName, $col, $condition, $params);
	}
	public function GetSum($tableName, $col = "ID",$condition =null, $params=[])
	{
        return DataBase::GetSum($this->TableName, $col, $condition, $params);
	}
	public function GetAverage($tableName, $col = "ID",$condition =null, $params=[])
	{
        return DataBase::GetAverage($this->TableName, $col, $condition, $params);
	}
	public function GetMax($tableName, $col = "ID",$condition =null, $params=[])
	{
        return DataBase::GetMax($this->TableName, $col, $condition, $params);
	}
	public function GetMin($tableName, $col = "ID",$condition =null, $params=[])
	{
        return DataBase::GetMin($this->TableName, $col, $condition, $params);
	}

	public function Exists($tableName, $col = null, $condition =null, $params=[])
	{
        return DataBase::Exists($this->TableName, $col, $condition, $params);
	}
}

?>