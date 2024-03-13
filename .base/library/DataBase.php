<?php
namespace MiMFa\Library;
/**
 * A simple library to connect the database and run the most uses SQL queries
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#database See the Library Documentation
 */
class DataBase {
	public static function TableNameNormalization($tablesName)
	{
		if(is_null($tablesName)) return null;
		if(is_string($tablesName))
			if(preg_find('/[\*\=\+\-\\\\\/\`"\'\(\)\[\]\{\}\,\.]|(^\d+$)/',$tablesName)) return $tablesName;
			else return "`$tablesName`";
		elseif(is_array($tablesName) || is_iterable($tablesName))
			return join(", ", array_filter(loop($tablesName,
					function($k,$v){return self::TableNameNormalization($v);}),
					function($v){ return !is_null($v); }
				)
			);
		elseif(!is_string($tablesName) && (is_callable($tablesName) || $tablesName instanceof \Closure))
		return self::TableNameNormalization($tablesName($tablesName));
        return null;
	}
	public static function ColumnNameNormalization($columns = "*")
	{
		if(is_null($columns)) return null;
        if(is_string($columns))
			if(preg_find('/[\*\=\+\-\\\\\/\`"\'\(\)\[\]\{\}\,\.]|(^\d+$)/',$columns)) return $columns;
			else return "`$columns`";
		elseif(is_array($columns) || is_iterable($columns))
            return join(", ", array_filter(
								loop($columns,
									function($k,$v){return self::ColumnNameNormalization($v);}
								),
                                function($v){ return !is_null($v); }
                            )
                        );
		elseif(!is_string($columns) && (is_callable($columns) || $columns instanceof \Closure))
				return self::ColumnNameNormalization($columns($columns));
        return null;
	}
	public static function ConditionNormalization($conditions=null)
	{
		if(is_null($conditions)) return null;
		if(is_string($conditions))
			return startsWith(strtolower(trim($conditions)),"where")?$conditions:"WHERE $conditions";
		elseif(is_array($conditions) || is_iterable($conditions))
            return join(" AND ", array_filter(loop($conditions,
                                function($k,$v){return self::ConditionNormalization($v);}),
                                function($v){ return !is_null($v); }
                            )
                        );
		elseif(!is_string($conditions) && (is_callable($conditions) || $conditions instanceof \Closure))
			return self::ConditionNormalization($conditions($conditions));
        return null;
	}
	public static function ParametersNormalization($params = [])
	{
		if(!\_::$CONFIG->DataBaseValueNormalization) return $params;
		if(isEmpty($params)) return null;
		if(is_string($params)) return json_decode($params);
		elseif(is_array($params) || is_iterable($params)){
			foreach ($params as $key=>$value) $params[$key] = self::ParameterNormalization($key, $params[$key]);
            return $params;
        }
		elseif(!is_string($params) && (is_callable($params) || $params instanceof \Closure))
			return self::ParametersNormalization($params($params));
        return null;
	}
	public static function ParameterNormalization($key, $value)
	{
		if(is_null($value)) return null;
		elseif(is_array($value) || is_iterable($value) || is_object($value))
			return json_encode($value);
		elseif(!is_string($value) && (is_callable($value) || $value instanceof \Closure))
			return self::ParameterNormalization($key, $value($key, $value));
		elseif(is_numeric($value)) return floatval($value);
        else return $value;
	}

	public static function Connection()
	{
		$conn = new \PDO(\_::$CONFIG->DataBaseType.":host=".\_::$CONFIG->DataBaseHost.";dbname=".\_::$CONFIG->DataBaseName.";charset=".preg_replace("/\W/","",\_::$CONFIG->DataBaseEncoding), \_::$CONFIG->DataBaseUser, \_::$CONFIG->DataBasePassword);
		$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		return $conn;
	}

	public static function Query($query, $params=[]){
		foreach(self::ParametersNormalization($params) as $key => $val)
			$query = str_replace("$key","'$val'",$query);
		return $query;
	}

	public static function ReturnArray($result, $defaultValue = [])
	{
		return is_array($result) && count($result) > 0 ? $result : $defaultValue;
	}
	public static function ReturnValue($result, $defaultValue = null)
	{
		return isEmpty($result)? $defaultValue : $result;
	}

	public static function SelectValue($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$stmt->execute(self::ParametersNormalization($params));
		return $stmt->fetchColumn();
	}
	public static function TrySelectValue($query, $params=[], $defaultValue = null)
	{
		try{
			return self::ReturnValue(self::SelectValue($query,$params),$defaultValue);
        }
        catch(\Exception $ex){ self::Error($ex); return $defaultValue; }
	}
	public static function DoSelectValue($tableName, $columns = "`ID`", $condition=null, $params=[], $defaultValue = null)
	{
		return self::TrySelectValue(self::MakeSelectValueQuery($tableName, $columns, $condition),$params,$defaultValue);
	}
	public static function MakeSelectValueQuery($tableName, $columns = "`ID`", $condition=null)
	{
		return "SELECT ".self::ColumnNameNormalization($columns??"`ID`")." FROM ".self::TableNameNormalization($tableName)." ".self::ConditionNormalization($condition);
	}

	public static function Select($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$stmt->execute(self::ParametersNormalization($params));
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		return $stmt->fetchAll();
	}
	public static function TrySelect($query, $params=[], $defaultValue = array())
	{
		try{
			return self::ReturnArray(self::Select($query,$params),$defaultValue);
        }catch(\Exception $ex){ self::Error($ex); return $defaultValue; }
	}
	public static function DoSelect($tableName, $columns = "*", $condition=null, $params=[], $defaultValue = array())
	{
		return self::TrySelect(self::MakeSelectQuery($tableName, $columns, $condition),$params,$defaultValue);
	}
	public static function MakeSelectQuery($tableName, $columns = "*", $condition=null)
	{
		return "SELECT ".self::ColumnNameNormalization($columns??"*")." FROM ".self::TableNameNormalization($tableName)." ".self::ConditionNormalization($condition);
	}

	public static function SelectRow($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$stmt->execute(self::ParametersNormalization($params));
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		$result = $stmt->fetch();
		if($result) return $result;
		else return null;
	}
	public static function TrySelectRow($query, $params=[], $defaultValue = array())
	{
		try{
			return self::ReturnArray(self::SelectRow($query,$params),$defaultValue);
        }catch(\Exception $ex){ self::Error($ex); return $defaultValue; }
	}
	public static function DoSelectRow($tableName, $columns = "*", $condition=null, $params=[], $defaultValue = array())
	{
		return self::TrySelectRow(self::MakeSelectRowQuery($tableName, $columns, $condition),$params,$defaultValue);
	}
	public static function MakeSelectRowQuery($tableName, $columns = "*", $condition=null)
	{
		return "SELECT ".self::ColumnNameNormalization($columns??"*")." FROM ".self::TableNameNormalization($tableName)." ".self::ConditionNormalization($condition)." LIMIT 1";
	}

	public static function SelectColumn($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$stmt->execute(self::ParametersNormalization($params));
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		$result = loop($stmt->fetchAll(),function($k,$v){ return first($v); },false);
		if($result) return $result;
		else return null;
	}
	public static function TrySelectColumn($query, $params=[], $defaultValue = array())
	{
		try{
			return self::ReturnArray(self::SelectColumn($query,$params),$defaultValue);
        }catch(\Exception $ex){ self::Error($ex); return $defaultValue; }
	}
	public static function DoSelectColumn($tableName, $column = "ID", $condition=null, $params=[], $defaultValue = array())
	{
		return self::TrySelectColumn(self::MakeSelectColumnQuery($tableName, $column, $condition),$params,$defaultValue);
	}
	public static function MakeSelectColumnQuery($tableName, $column = "ID", $condition=null)
	{
		return "SELECT ".self::ColumnNameNormalization($column??"ID")." FROM ".self::TableNameNormalization($tableName)." ".self::ConditionNormalization($condition);
	}

	public static function SelectPairs($query, $params=[]){
		$res = [];
		$k = $v = null;
		foreach (self::Select($query, $params) as $i=>$row)
			$res[count($row)<2?$i:$row[$k=$k??array_key_first($row)]] = $row[$v=$v??array_key_last($row)];
		return $res;
	}
	public static function TrySelectPairs($query, $params=[], $defaultValue = array())
	{
		try{
			return self::ReturnArray(self::SelectPairs($query,$params), $defaultValue);
        }catch(\Exception $ex){ self::Error($ex); return $defaultValue; }
	}
	public static function DoSelectPairs($tableName, $key = "`ID`", $value = "`Name`", $condition=null, $params=[], $defaultValue = array())
	{
		return self::TrySelectPairs(self::MakeSelectPairsQuery($tableName, $key, $value, $condition),$params,$defaultValue);
	}
	public static function MakeSelectPairsQuery($tableName, $key = "`ID`", $value = "`Name`", $condition=null)
	{
		return "SELECT ".self::ColumnNameNormalization([$key??"`ID`", $value??"`Name`"])." FROM ".self::TableNameNormalization($tableName)." ".self::ConditionNormalization($condition);
	}

	public static function Insert($query,$params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute(self::ParametersNormalization($params));
		return $isdone;
	}
	public static function TryInsert($query,$params=[], $defaultValue = false)
	{
		try{
			return self::Insert($query,$params)??$defaultValue;
        }catch(\Exception $ex){ self::Error($ex); return $defaultValue; }
	}
	public static function DoInsert($tableName, $condition=null, $params=[], $defaultValue = false){

		return self::TryInsert(self::MakeInsertQuery($tableName, $condition, $params),$params,$defaultValue);
	}
	public static function MakeInsertQuery($tableName, $condition, &$params)
	{
        $vals = array();
		$sets = array();
		$args = array();
		foreach(self::ParametersNormalization($params) as $key => $value){
			$k = ltrim($key,":");
			$sets[] = "`$k`";
			$vals[] = ":$k";
			$args[":$k"] = $value;
        }
		$params = $args;
		return "INSERT INTO ".self::TableNameNormalization($tableName)." (".implode(", ",$sets).") VALUES (".implode(", ",$vals).") ".self::ConditionNormalization($condition);
	}

	public static function Replace($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute(self::ParametersNormalization($params));
		return $isdone;
	}
	public static function TryReplace($query, $params=[], $defaultValue = false)
	{
		try{
			return self::Replace($query,$params)??$defaultValue;
        }catch(\Exception $ex){ self::Error($ex); return $defaultValue; }
	}
	public static function DoReplace($tableName, $condition=null, $params=[], $defaultValue = false){
		return self::TryReplace(self::MakeReplaceQuery($tableName, $condition, $params),$params, $defaultValue);
	}
	public static function MakeReplaceQuery($tableName, $condition, &$params)
	{
        $vals = array();
		$sets = array();
		$args = array();
		foreach(self::ParametersNormalization($params) as $key => $value){
			$k = ltrim($key,":");
			$sets[] = "`$k`";
			$vals[] = ":$k";
			$args[":$k"] = $value;
        }
		$params = $args;
		return "REPLACE INTO ".self::TableNameNormalization($tableName)." (".implode(", ",$sets).") VALUES (".implode(", ",$vals).") ".self::ConditionNormalization($condition);
	}

	public static function Update($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute(self::ParametersNormalization($params));
		return $isdone;
	}
	public static function TryUpdate($query, $params=[], $defaultValue = false)
	{
		try{
			return self::Update($query,$params)??$defaultValue;
        }catch(\Exception $ex){ self::Error($ex); return $defaultValue; }
	}
	public static function DoUpdate($tableName, $condition=null, $params=[], $defaultValue = false){
		return self::TryUpdate(self::MakeUpdateQuery($tableName, $condition, $params), $params, $defaultValue);
	}
	public static function MakeUpdateQuery($tableName, $condition, &$params)
	{
        $sets = array();
		$args = array();
		foreach(self::ParametersNormalization($params) as $key => $value){
			$k = ltrim($key,":");
			$sets[] = "`$k`=:$k";
			$args[":$k"] = $value;
        }
		$params = $args;
		return "UPDATE ".self::TableNameNormalization($tableName)." SET ".implode(", ",$sets)." ".self::ConditionNormalization($condition);
	}

	public static function Delete($query, $params=[]){
		$Connection = self::Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute(self::ParametersNormalization($params));
		return $isdone;
	}
	public static function TryDelete($query, $params=[], $defaultValue = false)
	{
		try{
			return self::Delete($query,$params)??$defaultValue;
        }catch(\Exception $ex){ self::Error($ex); return $defaultValue; }
	}
	public static function DoDelete($tableName, $condition=null, $params=[], $defaultValue = false){
        return self::TryDelete(self::MakeDeleteQuery($tableName, $condition), $params,$defaultValue);
	}
	public static function MakeDeleteQuery($tableName, $condition=null)
	{
		return "DELETE FROM ".self::TableNameNormalization($tableName)." ".self::ConditionNormalization($condition);
	}

	public static function GetCount($tableName, $column = "`ID`",$condition =null, $params=[])
	{
		return self::TrySelectValue(self::MakeSelectValueQuery($tableName, "COUNT($column)", $condition), $params);
	}
	public static function GetSum($tableName, $column = "`ID`",$condition =null, $params=[])
	{
		return self::TrySelectValue(self::MakeSelectValueQuery($tableName, "SUM($column)", $condition), $params);
	}
	public static function GetAverage($tableName, $column = "`ID`", $condition =null, $params=[])
	{
		return self::TrySelectValue(self::MakeSelectValueQuery($tableName, "AVG($column)", $condition), $params);
	}
	public static function GetMax($tableName, $column = "`ID`", $condition =null, $params=[])
	{
		return self::TrySelectValue(self::MakeSelectValueQuery($tableName, "MAX($column)", $condition), $params);
	}
	public static function GetMin($tableName, $column = "`ID`", $condition =null, $params=[])
	{
		return self::TrySelectValue(self::MakeSelectValueQuery($tableName, "MIN($column)", $condition), $params);
	}

	public static function Exists($tableName, $column = null, $condition =null, $params=[])
	{
		$result = null;
		try{
            $result = self::SelectValue(self::MakeSelectValueQuery($tableName, is_null($column)?"1":$column, $condition), $params);
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
				echo HTML::Error($ex->getMessage());
				break;
            case 2:
				echo HTML::Error($ex);
				break;
        	default:
				throw $ex;
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
	public function MakeSelectValueQuery($columns = "*", $condition=null)
	{
		return DataBase::MakeSelectValueQuery($this->TableName, $columns, $condition);
	}

	public function DoSelect($columns = "*", $condition=null, $params=[], $defaultValue = array())
	{
		return DataBase::DoSelect($this->TableName, $columns, $condition, $params, $defaultValue);
	}
    public function MakeSelectQuery($columns = "*", $condition=null)
	{
		return DataBase::MakeSelectQuery($this->TableName, $columns, $condition);
	}

	public function DoSelectRow($columns = "*", $condition=null, $params=[], $defaultValue = array())
	{
		return DataBase::DoSelectRow($this->TableName, $columns, $condition, $params, $defaultValue);
	}
    public function MakeSelectRowQuery($columns = "*", $condition=null)
	{
		return DataBase::MakeSelectRowQuery($this->TableName, $columns, $condition);
	}

	public function DoSelectColumn($columns = "*", $condition=null, $params=[], $defaultValue = array())
	{
		return DataBase::DoSelectColumn($this->TableName, $columns, $condition, $params, $defaultValue);
	}
    public function MakeSelectColumnQuery($columns = "*", $condition=null)
	{
		return DataBase::MakeSelectColumnQuery($this->TableName, $columns, $condition);
	}

	public function DoSelectPairs($key = "`ID`", $value = "`Name`", $condition=null, $params=[], $defaultValue = array())
	{
		return DataBase::DoSelectPairs($this->TableName, $key, $value, $condition, $params, $defaultValue);
	}
    public function MakeSelectPairsQuery($key = "`ID`", $value = "`Name`", $condition=null)
	{
		return DataBase::MakeSelectPairsQuery($this->TableName, $key, $value, $condition);
	}

	public function DoInsert($condition=null, $params=[], $defaultValue = false){
        return DataBase::DoInsert($this->TableName, $condition, $params, $defaultValue);
	}
    public function MakeInsertQuery($condition, &$params)
	{
		return DataBase::MakeInsertQuery($this->TableName, $condition, $params);
	}

	public function DoReplace($condition=null, $params=[], $defaultValue = false){
        return DataBase::DoReplace($this->TableName, $condition, $params, $defaultValue);
	}
    public function MakeReplaceQuery($condition, &$params)
	{
		return DataBase::MakeReplaceQuery($this->TableName, $condition, $params);
	}

	public function DoUpdate($condition=null, $params=[], $defaultValue = false){
        return DataBase::DoUpdate($this->TableName, $condition, $params, $defaultValue);
	}
    public function MakeUpdateQuery($condition, &$params)
	{
		return DataBase::MakeUpdateQuery($this->TableName, $condition, $params);
	}

	public function DoDelete($condition=null, $params=[], $defaultValue = false){
        return DataBase::DoDelete($this->TableName, $condition, $params, $defaultValue);
	}
    public function MakeDeleteQuery($condition=null)
	{
		return DataBase::MakeDeleteQuery($this->TableName, $condition);
	}

	public function GetCount($col = "`ID`",$condition =null, $params=[])
	{
        return DataBase::GetCount($this->TableName, $col, $condition, $params);
	}
	public function GetSum($col = "`ID`",$condition =null, $params=[])
	{
        return DataBase::GetSum($this->TableName, $col, $condition, $params);
	}
	public function GetAverage($col = "`ID`",$condition =null, $params=[])
	{
        return DataBase::GetAverage($this->TableName, $col, $condition, $params);
	}
	public function GetMax($col = "`ID`",$condition =null, $params=[])
	{
        return DataBase::GetMax($this->TableName, $col, $condition, $params);
	}
	public function GetMin($col = "`ID`",$condition =null, $params=[])
	{
        return DataBase::GetMin($this->TableName, $col, $condition, $params);
	}

	public function Exists($col = null, $condition =null, $params=[])
	{
        return DataBase::Exists($this->TableName, $col, $condition, $params);
	}
}

?>