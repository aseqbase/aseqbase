<?php
namespace MiMFa\Library;
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
		$this->DefaultConnection = $connection ?? \_::$Config->DataBaseType . ":host=" . \_::$Config->DataBaseHost . ";dbname=" . \_::$Config->DataBaseName . ";charset=" . preg_replace("/\W/", "", \_::$Config->DataBaseEncoding);
		$this->UserName = $userName ?? \_::$Config->DataBaseUser;
		$this->Password = $password ?? \_::$Config->DataBasePassword;
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
							return $this->TableNameNormalization($v);
						}
					),
					function ($v) {
						return !is_null($v);
					}
				)
			);
		elseif (!is_string($tablesName) && (is_callable($tablesName) || $tablesName instanceof \Closure))
			return $this->TableNameNormalization($tablesName($tablesName));
		return null;
	}
	public function ColumnNameNormalization($columns = "*")
	{
		if (isEmpty($columns))
			return "*";
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
							return is_int($k)?$this->ColumnNameNormalization($v):$this->ColumnNameNormalization($v)." AS \"$k\"";
						}
					),
					function ($v) {
						return !is_null($v);
					}
				)
			);
		elseif (is_callable($columns) || $columns instanceof \Closure)
			return $this->ColumnNameNormalization($columns($columns));
		return null;
	}
	public function ConditionNormalization($conditions = null, $statementCheck = true)
	{
		if (!$conditions) return null;
		if (is_string($conditions))
			return !$statementCheck || preg_match("/(\bwhere\b)|(^\s*(where|order|limit|union|having|((((right|left|full)\s+)?((outer|inner)\s+)?)join)|group)\b)/im", $conditions) ? $conditions : "WHERE $conditions";
		elseif (is_array($conditions) || is_iterable($conditions))
			return $this->ConditionNormalization(join(
				" AND ",
				array_filter(
					loop(
						$conditions,
						function ($k, $v) {
							return $this->ConditionNormalization($v, false);
						}
					),
					function ($v) {
						return !is_null($v);
					}
				)
			), $statementCheck
		);
		elseif (!is_string($conditions) && (is_callable($conditions) || $conditions instanceof \Closure))
			return $this->ConditionNormalization($conditions($conditions), $statementCheck);
		return null;
	}
	public function OrderNormalization($orders = null, $statementCheck = true)
	{
		if (is_null($orders))
			return null;
		$orders =
			is_string($orders) ?
			$orders :
			(
				count($orders) > 0 ?
				join(", ", loop(
					$orders,
					function ($k, $v, $i) {
						if (preg_match("/^\w*$/", $v ?? ""))
							if ($k !== $i)
								return "`$k` $v";
							else
								return $v;
					}
				)) :
				""
			);
		return ($statementCheck && !isEmpty($orders) && !preg_match("/\border\s+by\b/i", $orders)) ? "ORDER BY $orders" : $orders;
	}
	public function LimitNormalization($limit = null, $statementCheck = true)
	{
		if (is_null($limit))
			return null;
		$limit = is_numeric($limit)?($limit < 0 ? "" : "LIMIT " . intval($limit)):$limit;
		return ($statementCheck && !isEmpty($limit) && !preg_match("/\blimit\b/i", $limit)) ? "LIMIT $limit" : $limit;
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
		if (\_::$Config->DataBaseError)
			$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		return $conn;
	}

	public function ReturnArray($result, array|null $defaultValue = []): array|null
	{
		return is_array($result) && count($result) > 0 ? $result : $defaultValue;
	}
	public function ReturnValue($result, $defaultValue = null)
	{
		return isEmpty($result) ? $defaultValue : $result;
	}

	public function ExecuteSelectValue($query, $params = [])
	{
		$Connection = $this->Connection();
		$stmt = $Connection->prepare($query);
		$stmt->execute($this->ParametersNormalization($params));
		return $stmt->fetchColumn();
	}
	public function TrySelectValue($query, $params = [], $defaultValue = null)
	{
		try {
			return $this->ReturnValue($this->ExecuteSelectValue($query, $params), $defaultValue);
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function SelectValue($tableName, $columns = "`Id`", $condition = null, $params = [], $defaultValue = null)
	{
		return $this->TrySelectValue($this->SelectValueQuery($tableName, $columns, $condition), $params, $defaultValue);
	}
	public function SelectValueQuery($tableName, $columns = "`Id`", $condition = null)
	{
		return "SELECT " . $this->ColumnNameNormalization($columns ?? "`Id`") . " FROM " . $this->TableNameNormalization($tableName) . " " . $this->ConditionNormalization($condition);
	}

	public function ExecuteSelect($query, $params = [])
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
			return $this->ReturnArray($this->ExecuteSelect($query, $params), $defaultValue);
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function Select($tableName, $columns = "*", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->TrySelect($this->SelectQuery($tableName, $columns, $condition), $params, $defaultValue);
	}
	public function SelectQuery($tableName, $columns = "*", $condition = null)
	{
		return "SELECT " . $this->ColumnNameNormalization($columns ?? "*") . " FROM " . $this->TableNameNormalization($tableName) . " " . $this->ConditionNormalization($condition);
	}

	public function ExecuteSelectRow($query, $params = [])
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
			return $this->ReturnArray($this->ExecuteSelectRow($query, $params), $defaultValue);
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function SelectRow($tableName, $columns = "*", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->TrySelectRow($this->SelectRowQuery($tableName, $columns, $condition), $params, $defaultValue);
	}
	public function SelectRowQuery($tableName, $columns = "*", $condition = null)
	{
		return "SELECT " . $this->ColumnNameNormalization($columns ?? "*") . " FROM " . $this->TableNameNormalization($tableName) . " " . $this->ConditionNormalization($condition) . " LIMIT 1";
	}

	public function ExecuteSelectColumn($query, $params = []): array|null
	{
		$Connection = $this->Connection();
		$stmt = $Connection->prepare($query);
		$stmt->execute($this->ParametersNormalization($params));
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		$result = loop($stmt->fetchAll(), function ($k, $v) {
			return first($v);
		}, false);
		if ($result)
			return $result;
		else
			return null;
	}
	public function TrySelectColumn($query, $params = [], array|null $defaultValue = array())
	{
		try {
			return $this->ReturnArray($this->ExecuteSelectColumn($query, $params), $defaultValue);
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function SelectColumn($tableName, $column = "Id", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->TrySelectColumn($this->SelectColumnQuery($tableName, $column, $condition), $params, $defaultValue);
	}
	public function SelectColumnQuery($tableName, $column = "Id", $condition = null)
	{
		return "SELECT " . $this->ColumnNameNormalization($column ?? "Id") . " FROM " . $this->TableNameNormalization($tableName) . " " . $this->ConditionNormalization($condition);
	}

	public function ExecuteSelectPairs($query, $params = []): array
	{
		$res = [];
		$k = $v = null;
		foreach ($this->ExecuteSelect($query, $params) as $i => $row)
			$res[count($row) < 2 ? $i : $row[$k = $k ?? array_key_first($row)]] = $row[$v = $v ?? array_key_last($row)];
		return $res;
	}
	public function TrySelectPairs($query, $params = [], array|null $defaultValue = array()): array|null
	{
		try {
			return $this->ReturnArray($this->ExecuteSelectPairs($query, $params), $defaultValue);
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function SelectPairs($tableName, $key = "`Id`", $value = "`Name`", $condition = null, $params = [], array|null $defaultValue = array()): array|null
	{
		return $this->TrySelectPairs($this->SelectPairsQuery($tableName, $key, $value, $condition), $params, $defaultValue);
	}
	public function SelectPairsQuery($tableName, $key = "`Id`", $value = "`Name`", $condition = null)
	{
		return "SELECT " . $this->ColumnNameNormalization([$key ?? "`Id`", $value ?? "`Name`"]) . " FROM " . $this->TableNameNormalization($tableName) . " " . $this->ConditionNormalization($condition);
	}

	public function ExecuteInsert($query, $params = []): bool|int
	{
		$Connection = $this->Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($this->ParametersNormalization($params));
		return $isdone ? $stmt->rowCount() : false;
	}
	public function TryInsert($query, $params = [], bool|int $defaultValue = false): bool|int
	{
		try {
			return $this->ExecuteInsert($query, $params) ?? $defaultValue;
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function Insert($tableName, $params = [], bool|int $defaultValue = false): bool|int
	{
		return $this->TryInsert($this->InsertQuery($tableName, $params), $params, $defaultValue);
	}
	public function InsertQuery($tableName, &$params)
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

	public function ExecuteReplace($query, $params = []): bool|int
	{
		$Connection = $this->Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($this->ParametersNormalization($params));
		return $isdone ? $stmt->rowCount() : false;
	}
	public function TryReplace($query, $params = [], bool|int $defaultValue = false): bool|int
	{
		try {
			return $this->ExecuteReplace($query, $params) ?? $defaultValue;
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function Replace($tableName, $condition = null, $params = [], bool|int $defaultValue = false): bool|int
	{
		return $this->TryReplace($this->ReplaceQuery($tableName, $condition, $params), $params, $defaultValue);
	}
	public function ReplaceQuery($tableName, $condition, &$params)
	{
		$vals = array();
		$sets = array();
		$args = array();
		foreach ($this->ParametersNormalization($params) as $key => $value) {
			$k = trim($key, ":`[]");
			if (!$condition || !preg_match("/\B\:$k\b/", $condition)) {
				$sets[] = "`$k`";
				$vals[] = ":$k";
			}
			$args[":$k"] = $value;
		}
		$params = $args;
		return "REPLACE INTO " . $this->TableNameNormalization($tableName) . " (" . implode(", ", $sets) . ") VALUES (" . implode(", ", $vals) . ") " . $this->ConditionNormalization($condition);
	}

	public function ExecuteUpdate($query, $params = []): bool|int
	{
		$Connection = $this->Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($this->ParametersNormalization($params));
		return $isdone ? $stmt->rowCount() : false;
	}
	public function TryUpdate($query, $params = [], bool|int $defaultValue = false): bool|int
	{
		try {
			return $this->ExecuteUpdate($query, $params) ?? $defaultValue;
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function Update($tableName, $condition = null, $params = [], bool|int $defaultValue = false): bool|int
	{
		return $this->TryUpdate($this->UpdateQuery($tableName, $condition, $params), $params, $defaultValue);
	}
	public function UpdateQuery($tableName, $condition, &$params)
	{
		$sets = array();
		$args = array();
		$condition = $this->ConditionNormalization($condition) . " ";
		foreach ($this->ParametersNormalization($params) as $key => $value) {
			$k = trim($key, ":`[]");
			if (!$condition || !preg_match("/\B\:$k\b/", $condition))
				$sets[] = "`$k`=:$k";
			$args[":$k"] = $value;
		}
		$params = $args;
		return "UPDATE " . $this->TableNameNormalization($tableName) . " SET " . implode(", ", $sets) . " " . $condition;
	}

	public function ExecuteDelete($query, $params = []): bool|int
	{
		$Connection = $this->Connection();
		$stmt = $Connection->prepare($query);
		$isdone = $stmt->execute($this->ParametersNormalization($params));
		return $isdone ? $stmt->rowCount() : false;
	}
	public function TryDelete($query, $params = [], bool|int $defaultValue = false): bool|int
	{
		try {
			return $this->ExecuteDelete($query, $params) ?? $defaultValue;
		} catch (\Exception $ex) {
			$this->Error($ex, $query, $params);
			return $defaultValue;
		}
	}
	public function Delete($tableName, $condition = null, $params = [], bool|int $defaultValue = false): bool|int
	{
		return $this->TryDelete($this->DeleteQuery($tableName, $condition), $params, $defaultValue);
	}
	public function DeleteQuery($tableName, $condition = null)
	{
		return "DELETE FROM " . $this->TableNameNormalization($tableName) . " " . $this->ConditionNormalization($condition);
	}

	public function Count($tableName, $column = "`Id`", $condition = null, $params = [])
	{
		return $this->TrySelectValue($this->SelectValueQuery($tableName, "COUNT($column)", $condition), $params);
	}
	public function Sum($tableName, $column = "`Id`", $condition = null, $params = [])
	{
		return $this->TrySelectValue($this->SelectValueQuery($tableName, "SUM($column)", $condition), $params);
	}
	public function Average($tableName, $column = "`Id`", $condition = null, $params = [])
	{
		return $this->TrySelectValue($this->SelectValueQuery($tableName, "AVG($column)", $condition), $params);
	}
	public function Max($tableName, $column = "`Id`", $condition = null, $params = [])
	{
		return $this->TrySelectValue($this->SelectValueQuery($tableName, "MAX($column)", $condition), $params);
	}
	public function Min($tableName, $column = "`Id`", $condition = null, $params = [])
	{
		return $this->TrySelectValue($this->SelectValueQuery($tableName, "MIN($column)", $condition), $params);
	}

	public function Exists($tableName, $column = null, $condition = null, $params = []): bool
	{
		$result = null;
		try {
			$result = $this->ExecuteSelectValue($this->SelectValueQuery($tableName, is_null($column) ? "1" : $column, $condition), $params);
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
				echo $query . Html::Error($ex);
				break;
			case 3:
				echo $query, Html::Error($ex), "PARAMS{" . ($params ? count($params) : 0) . "}: ", Convert::ToString($params, arrayFormat: "{{0}}");
				break;
			default:
				throw $ex;
		}
	}
}
?>