<?php namespace MiMFa\Library;
/**
 * A simple library to connect the database and run the most uses SQL queries
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#database See the Library Documentation
 */
class DataBase
{
	protected $DefaultConnection = null;
	protected $UserName = null;
	protected $Password = null;
	public function __construct($connection = null, $userName = null, $password = null)
	{
		$this->DefaultConnection = $connection??\_::$Config->DataBaseType . ":host=" . \_::$Config->DataBaseHost . ";dbname=" . \_::$Config->DataBaseName . ";charset=" . preg_replace("/\W/", "", \_::$Config->DataBaseEncoding);
		$this->UserName = $userName??\_::$Config->DataBaseUser;
		$this->Password = $password??\_::$Config->DataBasePassword;
	}

	public function TableNameNormalization($tablesName)
	{
		if (is_null($tablesName))
			return null;
		if (is_string($tablesName))
			if (preg_find('/[\*\=\+\-\\\\\/\`"\'\(\)\[\]\{\}\,\.]|(^\d+$)/', $tablesName))
				return $tablesName;
			else
				return "`$tablesName`";
		elseif (is_array($tablesName) || is_iterable($tablesName))
			return join(
				", ",
				array_filter(
					loop(
						$tablesName,
						function ($k, $v) {
							return $this->TableNameNormalization($v); }
					),
					function ($v) {
						return !is_null($v); }
				)
			);
		elseif (!is_string($tablesName) && (is_callable($tablesName) || $tablesName instanceof \Closure))
			return $this->TableNameNormalization($tablesName($tablesName));
		return null;
	}
	public function ColumnNameNormalization($columns = "*")
	{
		if (is_null($columns))
			return null;
		if (is_string($columns))
			if (preg_find('/[\*\=\+\-\\\\\/\`"\'\(\)\[\]\{\}\,\.]|(^\d+$)/', $columns))
				return $columns;
			else
				return "`$columns`";
		elseif (is_array($columns) || is_iterable($columns))
			return join(
				", ",
				array_filter(
					loop(
						$columns,
						function ($k, $v) {
							return $this->ColumnNameNormalization($v); }
					),
					function ($v) {
						return !is_null($v); }
				)
			);
		elseif (!is_string($columns) && (is_callable($columns) || $columns instanceof \Closure))
			return $this->ColumnNameNormalization($columns($columns));
		return null;
	}
	public function ConditionNormalization($conditions = null)
	{
		if (is_null($conditions))
			return null;
		if (is_string($conditions))
			return preg_match("/^\s*(where|order|limit|union|join|group)\s*/im", $conditions) ? $conditions : "WHERE $conditions";
		elseif (is_array($conditions) || is_iterable($conditions))
			return join(
				" AND ",
				array_filter(
					loop(
						$conditions,
						function ($k, $v) {
							return $this->ConditionNormalization($v); }
					),
					function ($v) {
						return !is_null($v); }
				)
			);
		elseif (!is_string($conditions) && (is_callable($conditions) || $conditions instanceof \Closure))
			return $this->ConditionNormalization($conditions($conditions));
		return null;
	}
	public function ParametersNormalization($params = [])
	{
		if (!\_::$Config->DataBaseValueNormalization)
			return $params;
		if (isEmpty($params))
			return null;
		if (is_string($params))
			return Convert::FromJson($params);
		elseif (is_array($params) || is_iterable($params)) {
			foreach ($params as $key => $value)
				$params[$key] = $this->ParameterNormalization($key, $params[$key]);
			return $params;
		} elseif (!is_string($params) && (is_callable($params) || $params instanceof \Closure))
			return $this->ParametersNormalization($params($params));
		return null;
	}
	public function ParameterNormalization($key, $value)
	{
		if (is_null($value))
			return null;
		elseif (is_array($value) || is_iterable($value) || is_object($value))
			return Convert::ToJson($value);
		elseif (!is_string($value) && (is_callable($value) || $value instanceof \Closure))
			return $this->ParameterNormalization($key, $value($key, $value));
		elseif (is_numeric($value))
			return floatval($value);
		else
			return $value;
	}

	public function Connection()
	{
		$conn = new \PDO($this->DefaultConnection, $this->UserName, $this->Password);
		if(\_::$Config->DataBaseError) $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		return $conn;
	}

	public function Query($query, $params = [])
	{
		foreach ($this->ParametersNormalization($params) as $key => $val)
			$query = str_replace("$key", "'$val'", $query);
		return $query;
	}

	public function ReturnArray($result, array|null $defaultValue = []): array|null
	{
		return is_array($result) && count($result) > 0 ? $result : $defaultValue;
	}
	public function ReturnValue($result, $defaultValue = null)
	{
		return isEmpty($result) ? $defaultValue : $result;
	}

	public function SelectValue($query, $params = [])
	{
		$Connection = $this->Connection();
		$stmt = $Connection->prepare($query);
		$stmt->execute($this->ParametersNormalization($params));
		return $stmt->fetchColumn();
	}
	public function TrySelectValue($query, $params = [], $defaultValue = null)
	{
		try {
			return $this->ReturnValue($this->SelectValue($query, $params), $defaultValue);
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function DoSelectValue($tableName, $columns = "`Id`", $condition = null, $params = [], $defaultValue = null)
	{
		return $this->TrySelectValue($this->MakeSelectValueQuery($tableName, $columns, $condition), $params, $defaultValue);
	}
	public function MakeSelectValueQuery($tableName, $columns = "`Id`", $condition = null)
	{
		return "SELECT " . $this->ColumnNameNormalization($columns ?? "`Id`") . " FROM " . $this->TableNameNormalization($tableName) . " " . $this->ConditionNormalization($condition);
	}

	public function Select($query, $params = [])
	{
		$Connection = $this->Connection();
		$stmt = $Connection->prepare($query);
		$stmt->execute($this->ParametersNormalization($params));
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		return $stmt->fetchAll();
	}
	public function TrySelect($query, $params = [], $defaultValue = array())
	{
		try {
			return $this->ReturnArray($this->Select($query, $params), $defaultValue);
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function DoSelect($tableName, $columns = "*", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->TrySelect($this->MakeSelectQuery($tableName, $columns, $condition), $params, $defaultValue);
	}
	public function MakeSelectQuery($tableName, $columns = "*", $condition = null)
	{
		return "SELECT " . $this->ColumnNameNormalization($columns ?? "*") . " FROM " . $this->TableNameNormalization($tableName) . " " . $this->ConditionNormalization($condition);
	}

	public function SelectRow($query, $params = [])
	{
		$Connection = $this->Connection();
		$stmt = $Connection->prepare($query);
		$stmt->execute($this->ParametersNormalization($params));
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		$result = $stmt->fetch();
		if ($result)
			return $result;
		else
			return null;
	}
	public function TrySelectRow($query, $params = [], $defaultValue = array())
	{
		try {
			return $this->ReturnArray($this->SelectRow($query, $params), $defaultValue);
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function DoSelectRow($tableName, $columns = "*", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->TrySelectRow($this->MakeSelectRowQuery($tableName, $columns, $condition), $params, $defaultValue);
	}
	public function MakeSelectRowQuery($tableName, $columns = "*", $condition = null)
	{
		return "SELECT " . $this->ColumnNameNormalization($columns ?? "*") . " FROM " . $this->TableNameNormalization($tableName) . " " . $this->ConditionNormalization($condition) . " LIMIT 1";
	}

	public function SelectColumn($query, $params = []): array|null
	{
		$Connection = $this->Connection();
		$stmt = $Connection->prepare($query);
		$stmt->execute($this->ParametersNormalization($params));
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		$result = loop($stmt->fetchAll(), function ($k, $v) {
			return first($v); }, false);
		if ($result)
			return $result;
		else
			return null;
	}
	public function TrySelectColumn($query, $params = [], array|null $defaultValue = array())
	{
		try {
			return $this->ReturnArray($this->SelectColumn($query, $params), $defaultValue);
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function DoSelectColumn($tableName, $column = "Id" , $condition = null, $params = [], $defaultValue = array())
	{
		return $this->TrySelectColumn($this->MakeSelectColumnQuery($tableName, $column, $condition), $params, $defaultValue);
	}
	public function MakeSelectColumnQuery($tableName, $column = "Id" , $condition = null)
	{
		return "SELECT " . $this->ColumnNameNormalization($column ?? "Id" ) . " FROM " . $this->TableNameNormalization($tableName) . " " . $this->ConditionNormalization($condition);
	}

	public function SelectPairs($query, $params = []): array
	{
		$res = [];
		$k = $v = null;
		foreach ($this->Select($query, $params) as $i => $row)
			$res[count($row) < 2 ? $i : $row[$k = $k ?? array_key_first($row)]] = $row[$v = $v ?? array_key_last($row)];
		return $res;
	}
	public function TrySelectPairs($query, $params = [], array|null $defaultValue = array()): array|null
	{
		try {
			return $this->ReturnArray($this->SelectPairs($query, $params), $defaultValue);
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function DoSelectPairs($tableName, $key = "`Id`", $value = "`Name`", $condition = null, $params = [], array|null $defaultValue = array()): array|null
	{
		return $this->TrySelectPairs($this->MakeSelectPairsQuery($tableName, $key, $value, $condition), $params, $defaultValue);
	}
	public function MakeSelectPairsQuery($tableName, $key = "`Id`", $value = "`Name`", $condition = null)
	{
		return "SELECT " . $this->ColumnNameNormalization([$key ?? "`Id`", $value ?? "`Name`"]) . " FROM " . $this->TableNameNormalization($tableName) . " " . $this->ConditionNormalization($condition);
	}

	public function Insert($query, $params = []): bool|int
	{
		$Connection = $this->Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($this->ParametersNormalization($params));
		return $isdone ? $stmt->rowCount() : false;
	}
	public function TryInsert($query, $params = [], bool|int $defaultValue = false): bool|int
	{
		try {
			return $this->Insert($query, $params) ?? $defaultValue;
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function DoInsert($tableName, $params = [], bool|int $defaultValue = false): bool|int
	{
		return $this->TryInsert($this->MakeInsertQuery($tableName, $params), $params, $defaultValue);
	}
	public function MakeInsertQuery($tableName, &$params)
	{
		$vals = array();
		$sets = array();
		$args = array();
		foreach ($this->ParametersNormalization($params) as $key => $value) {
			$k = trim($key, ":`[]");
			$sets[] = "`$k`";
			$vals[] = ":$k";
			$args[":$k"] = $value;
		}
		$params = $args;
		return "INSERT INTO " . $this->TableNameNormalization($tableName) . " (" . implode(", ", $sets) . ") VALUES (" . implode(", ", $vals) . ") ";
	}

	public function Replace($query, $params = []): bool|int
	{
		$Connection = $this->Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($this->ParametersNormalization($params));
		return $isdone ? $stmt->rowCount() : false;
	}
	public function TryReplace($query, $params = [], bool|int $defaultValue = false): bool|int
	{
		try {
			return $this->Replace($query, $params) ?? $defaultValue;
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function DoReplace($tableName, $condition = null, $params = [], bool|int $defaultValue = false): bool|int
	{
		return $this->TryReplace($this->MakeReplaceQuery($tableName, $condition, $params), $params, $defaultValue);
	}
	public function MakeReplaceQuery($tableName, $condition, &$params)
	{
		$vals = array();
		$sets = array();
		$args = array();
		foreach ($this->ParametersNormalization($params) as $key => $value) {
			$k = trim($key, ":`[]");
			$sets[] = "`$k`";
			$vals[] = ":$k";
			$args[":$k"] = $value;
		}
		$params = $args;
		return "REPLACE INTO " . $this->TableNameNormalization($tableName) . " (" . implode(", ", $sets) . ") VALUES (" . implode(", ", $vals) . ") " . $this->ConditionNormalization($condition);
	}

	public function Update($query, $params = []): bool|int
	{
		$Connection = $this->Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($this->ParametersNormalization($params));
		return $isdone ? $stmt->rowCount() : false;
	}
	public function TryUpdate($query, $params = [], bool|int $defaultValue = false):bool|int
	{
		try {
			return $this->Update($query, $params) ?? $defaultValue;
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function DoUpdate($tableName, $condition = null, $params = [], bool|int $defaultValue = false): bool|int
	{
		return $this->TryUpdate($this->MakeUpdateQuery($tableName, $condition, $params), $params, $defaultValue);
	}
	public function MakeUpdateQuery($tableName, $condition, &$params)
	{
		$sets = array();
		$args = array();
		$condition = $this->ConditionNormalization($condition)." ";
		foreach ($this->ParametersNormalization($params) as $key => $value) {
			$k = trim($key, ":`[]");
			if(strpos($condition, ":$k ")<=0) $sets[] = "`$k`=:$k";
			$args[":$k"] = $value;
		}
		$params = $args;
		return "UPDATE " . $this->TableNameNormalization($tableName) . " SET " . implode(", ", $sets) . " " . $condition;
	}

	public function Delete($query, $params = []): bool|int
	{
		$Connection = $this->Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($this->ParametersNormalization($params));
		return $isdone ? $stmt->rowCount() : false;
	}
	public function TryDelete($query, $params = [], bool|int $defaultValue = false): bool|int
	{
		try {
			return $this->Delete($query, $params) ?? $defaultValue;
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function DoDelete($tableName, $condition = null, $params = [], bool|int $defaultValue = false): bool|int
	{
		return $this->TryDelete($this->MakeDeleteQuery($tableName, $condition), $params, $defaultValue);
	}
	public function MakeDeleteQuery($tableName, $condition = null)
	{
		return "DELETE FROM " . $this->TableNameNormalization($tableName) . " " . $this->ConditionNormalization($condition);
	}

	public function GetCount($tableName, $column = "`Id`", $condition = null, $params = [])
	{
		return $this->TrySelectValue($this->MakeSelectValueQuery($tableName, "COUNT($column)", $condition), $params);
	}
	public function GetSum($tableName, $column = "`Id`", $condition = null, $params = [])
	{
		return $this->TrySelectValue($this->MakeSelectValueQuery($tableName, "SUM($column)", $condition), $params);
	}
	public function GetAverage($tableName, $column = "`Id`", $condition = null, $params = [])
	{
		return $this->TrySelectValue($this->MakeSelectValueQuery($tableName, "AVG($column)", $condition), $params);
	}
	public function GetMax($tableName, $column = "`Id`", $condition = null, $params = [])
	{
		return $this->TrySelectValue($this->MakeSelectValueQuery($tableName, "MAX($column)", $condition), $params);
	}
	public function GetMin($tableName, $column = "`Id`", $condition = null, $params = [])
	{
		return $this->TrySelectValue($this->MakeSelectValueQuery($tableName, "MIN($column)", $condition), $params);
	}

	public function Exists($tableName, $column = null, $condition = null, $params = []): bool
	{
		$result = null;
		try {
			$result = $this->SelectValue($this->MakeSelectValueQuery($tableName, is_null($column) ? "1" : $column, $condition), $params);
		} catch (\Exception $ex) {
		}
		return !is_null($result);
	}

	public function Error(\Exception $ex, $query = null, $params = [])
	{
		switch (\_::$Config->DataBaseError) {
			case null:
			case 0:
				break;
			case 1:
				echo Html::Error($ex->getMessage());
				break;
			case 2:
				echo $query.Html::Error($ex);
				break;
			case 3:
				echo $query, Html::Error($ex), "PARAMS{".($params?count($params):0)."}: ", Convert::ToString($params, arrayFormat:"{{0}}");
				break;
			default:
				throw $ex;
		}
	}
}
?>