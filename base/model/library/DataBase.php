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
	public bool $Active = true;
	public bool $Procedural = false;
	/**
	 * function ($database, $query, $params) => return false to cancel executing
	 * @var array
	 */
	public array $ExecutingHandlers = [];
	/**
	 * function ($database, $query, $params, $succeed, $statements) => return $statements
	 * @var array
	 */
	public array $ExecutedHandlers = [];
	/**
	 * function ($database, $query, $params, $results, $defaultValue) =>  return $results or $defaultValue
	 * @var array
	 */
	public array $ResultedHandlers = [];
	/**
	 * function ($database, $query, $params, $ex, $defaultValue) => return $defaultValue
	 * @var array
	 */
	public array $ExceptedHandlers = [];
	public $Result = null;
	public $Fault = null;
	public $Name = null;
	public $StartWrap = "`";
	public $EndWrap = "`";
	public $PreQuery = null;
	public $MidQuery = null;
	public $PostQuery = null;
	public $Timeout = null;
	public $ReportLevel = null;
	public $AllowNormalization = null;

	public function __construct($type = "mysql", $host = "localhost", $port = null, $name = "localhost", $userName = "root", $password = "root", $encoding = "utf8")
	{
		$this->DefaultConnection = "$type:host=$host" . ($port ? ";port=$port" : "") . ";dbname=" . ($this->Name = $name) . ";charset=" . preg_replace("/\W/", "", $encoding);
		$this->UserName = $userName;
		$this->Password = $password;
	}

	public function Connection(): \PDO
	{
		$this->Fault = null;
		$conn = new \PDO($this->DefaultConnection, $this->UserName, $this->Password);
		if ($this->ReportLevel)
			$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		if ($this->Timeout)
			$conn->setAttribute(\PDO::ATTR_TIMEOUT, $this->Timeout / 1000);
		return $conn;
	}
	public function Reset()
	{
		$this->Active = true;
		$this->Fault =
			$this->PreQuery =
			$this->MidQuery =
			$this->PostQuery =
			$this->Timeout = null;
		return $this;
	}

	public function OnExecuting($query, $params){
		foreach ($this->ExecutingHandlers as $key => $value)
			if(($value)($this, $query, $params)  === false)
				return false;
		return true;
	}
	public function OnExecuted($query, $params, $succeed, $statements){
		foreach ($this->ExecutedHandlers as $key => $value)
			$statements = ($value)($this, $query, $params, $succeed, $statements)??$statements;
		return $statements;
	}
	public function OnResulted($query, $params, $results, $defaultValue){
		foreach ($this->ResultedHandlers as $key => $value)
			$results = ($value)($this, $query, $params, $results, $defaultValue);
		return $results;
	}
	public function OnExcepted($query, $params, $exception, $defaultValue){
		foreach ($this->ExceptedHandlers as $key => $value)
			$exception = ($value)($this, $query, $params, $exception, $defaultValue)??$exception;
		return $exception;
	}

	public function SessionTimeout($millisecond = 30000)
	{
		$millisecond /= 1000;
		$this->PreQuery = "SET SESSION wait_timeout = $millisecond; SET SESSION interactive_timeout = $millisecond;" . $this->PreQuery;
		return $this;
	}
	public function Timeout($millisecond = 30000)
	{
		$this->Timeout = $millisecond;
		// $millisecond /= 1000;
		// $this->PreQuery = "SET GLOBAL wait_timeout = $millisecond; SET GLOBAL interactive_timeout = $millisecond;".$this->PreQuery;
		return $this;
	}

	public function NameNormalization($name)
	{
		if (is_null($name))
			return null;
		if (is_string($name))
			if (preg_find('/[\*\=\+\-\\\\\/\`"\'\(\)\[\]\{\}\,\.]|(^\d+$)/', $name))
				return $name;
			else
				return "$this->StartWrap$name$this->EndWrap";
		elseif (is_array($name) || is_iterable($name))
			return join(
				", ",
				array_filter(
					loop(
						$name,
						function ($v) {
							return $this->NameNormalization($v);
						}
					),
					function ($v) {
						return !is_null($v);
					}
				)
			);
		elseif (!is_string($name) && (is_callable($name) || $name instanceof \Closure))
			return $this->NameNormalization($name($name));
		return null;
	}
	public function ColumnNameNormalization($columns = "*")
	{
		if (isEmpty($columns))
			return "*";
		if (is_string($columns))
			if (preg_find('/[\*\=\+\-\\\\\/`"\'\(\)\[\]\{\}\,\.]|(^\d+$)/', $columns))
				return $columns;
			else
				return "$this->StartWrap$columns$this->EndWrap";
		elseif (is_array($columns) || is_iterable($columns))
			return join(
				", ",
				array_filter(
					loop(
						$columns,
						fn($v, $k) => is_int($k) ? $this->ColumnNameNormalization($v) : ($this->ColumnNameNormalization($v) . " AS \"$k\"")
					),
					fn($v) => !is_null($v)
				)
			);
		elseif (is_callable($columns) || $columns instanceof \Closure)
			return $this->ColumnNameNormalization($columns($columns));
		return null;
	}
	public function ConditionNormalization($conditions = null, $statementCheck = true)
	{
		if (!$conditions)
			return null;
		if (is_string($conditions))
			return !$statementCheck || preg_match("/(\bwhere\b)|(^\s*(where|order|limit|union|having|((((right|left|full)\s+)?((outer|inner)\s+)?)join)|group)\b)/im", $conditions) ? $conditions : "WHERE $conditions";
		elseif (is_array($conditions) || is_iterable($conditions))
			return $this->ConditionNormalization(
				join(
					" AND ",
					array_filter(
						loop(
							$conditions,
							function ($v) {
								return $this->ConditionNormalization($v, false);
							}
						),
						function ($v) {
							return !is_null($v);
						}
					)
				),
				$statementCheck
			);
		elseif (!is_string($conditions) && (is_callable($conditions) || $conditions instanceof \Closure))
			return $this->ConditionNormalization($conditions($conditions), $statementCheck);
		return null;
	}
	/**
	 * Summary of OrderNormalization
	 * @param mixed $orders An array of ["columnname"=>"desc|asc"] or a simple string of "columnname desc|asc, ..."
	 * @param mixed $statementCheck Check for having ORDER BY statement
	 * @return string|null
	 */
	public function OrderNormalization($orders = null, bool|null $ascending = null, $statementCheck = true)
	{
		$ascending = is_null($ascending) ? null : ($ascending ? " ASC" : " DESC");
		if (is_null($orders))
			return $ascending ? "ORDER$ascending" : null;
		$orders =
			is_string($orders) ?
			$orders . $ascending :
			(
				count($orders) > 0 ?
				join(", ", loop(
					$orders,
					function ($v, $k) use ($ascending) {
						if (preg_match("/^\w*$/", $v ?? ""))
							if (is_numeric($k))
								return $v . $ascending;
							else
								return "$k $v";
						else
							return $v;
					}
				)) :
				""
			);
		return ($statementCheck && !isEmpty($orders) && !preg_match("/\border\s+(by)?\b/i", $orders)) ? "ORDER BY $orders" : $orders;
	}
	/**
	 * Summary of LimitNormalization
	 * @param mixed $limit The limitation number
	 * @param mixed $statementCheck Check for having LIMIT statement
	 */
	public function LimitNormalization($limit = null, $statementCheck = true)
	{
		if (is_null($limit))
			return null;
		$limit = is_numeric($limit) ? ($limit < 0 ? "" : "LIMIT " . intval($limit)) : $limit;
		return ($statementCheck && !isEmpty($limit) && !preg_match("/\blimit\b/i", $limit)) ? "LIMIT $limit" : $limit;
	}
	public function ParametersNormalization($params = [])
	{
		if (!$this->AllowNormalization)
			return $params;
		if (isEmpty($params))
			return [];
		if (is_string($params))
			return Convert::FromJson($params);
		elseif (is_iterable($params)) {
			foreach ($params as $key => $value)
				$params[$key] = $this->ParameterNormalization($key, $params[$key]);
			return $params;
		} elseif (!is_string($params) && (is_callable($params) || $params instanceof \Closure))
			return $this->ParametersNormalization($params($params));
		return [];
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

	public function if($condition = true)
	{
		$this->Procedural = true;
		if (!$condition) $this->Active = false;
		return $this;
	}
	public function else($condition = true)
	{
		$this->Procedural = true;
		if (!$this->Active && $condition)
			$this->Active = true;
		else
			$this->Active = false;
		return $this;
	}
	public function then(callable|null $action = null)
	{
		$this->Procedural = true;
		if (!$this->Active)
			return $this;
		elseif ($this->Fault) {
			$this->Active = false;
			return $this;
		}
		else return $action ? $action($this) : $this;
	}
	public function try(callable|null $action = null)
	{
		$this->Procedural = true;
		if ($this->Fault) {
			$this->Active = false;
			return $this;
		}
		elseif ($this->Active)
			return $action ? $action($this) : $this;
		else return $this;
	}
	public function catch(callable|null $action = null)
	{
		$this->Procedural = true;
		if ($this->Fault)
			if ($this->Active)
				return $action ? $action($this) : $this;
		$this->Active = false;
		return $this;
	}
	public function finally(callable|null $action = null)
	{
		$this->Procedural = true;
		if (!$this->Active && !$this->Fault)
			return $this;
		$this->Reset();
		return $action ? $action($this) : $this;
	}

	public function ReturnArray($result, $query = null, $params = [], $defaultValue = [])
	{
		$result = $this->OnResulted($query, $params, $result, $defaultValue);
		if ($this->Procedural) {
			$this->Result = $result;
			return $this;
		}
		return is_array($result) && count($result) > 0 ? $result : $defaultValue;
	}
	public function ReturnValue($result, $query = null, $params = [], $defaultValue = null)
	{
		$result = $this->OnResulted($query, $params, $result, $defaultValue);
		if ($this->Procedural) {
			$this->Result = $result;
			return $this;
		}
		return isEmpty($result) ? $defaultValue : $result;
	}
	public function ReturnFault(\Exception $ex, $query = null, $params = [], $defaultValue = null)
	{
		$ex = $this->OnExcepted($query, $params, $ex, $defaultValue);
		$this->Fault = $ex;
		switch ($this->ReportLevel) {
			case null:
			case 0:
				break;
			case 1:
				echo Struct::Error($ex->getMessage());
				break;
			case 2:
				error($ex);
				echo $query;
				break;
			case 3:
				error($ex);
				echo $query, "PARAMS{" . ($params ? count($params) : 0) . "}: ", Convert::ToString($params, arrayFormat: "{{0}}");
				break;
			case 4:
				error($ex);
				echo $query, "PARAMS{" . ($params ? count($params) : 0) . "}: ", Convert::ToString($params, arrayFormat: "{{0}}");
				$defaultValue = null;
				break;
			case 5:
				error($ex);
				echo $query, "PARAMS{" . ($params ? count($params) : 0) . "}: ", Convert::ToString($params, arrayFormat: "{{0}}");
				$defaultValue = $ex;
			default:
				throw $ex;
		}
		if ($this->Procedural) {
			$this->Result = $defaultValue;
			return $this;
		}
		return $defaultValue;
	}

	public function Execute($query, &$params = [], &$isDone = null, &$connection = null)
	{
		if (!$this->Active || !$this->OnExecuting($query, $params = $this->ParametersNormalization($params)))
			return false;
		$connection = $this->Connection();
		$stmt = $connection->prepare($query);
		// foreach ($this->ParametersNormalization($params) as $key => $value) 
		// 	 $stmt->bindValue($key, $value, \PDO::PARAM_STR);
		$isDone = $stmt->execute($params);
		$this->Reset();
		return $this->OnExecuted($query, $params, $isDone, $stmt);
	}
	public function TryExecute($query, $params = [], $defaultValue = null, &$connection = null)
	{
		try {
			$res = $this->Execute($query, $params, $isDone, $connection);
			return $this->ReturnValue($isDone ? $res : $defaultValue, $query, $params, $defaultValue);
		} catch (\Exception $ex) {
			return $this->ReturnFault($ex, $query, $params);
		} finally {
			$this->Reset();
		}
	}

	public function TransactionExecute($queries, $params = [], &$connection = null)
	{
		if (!$this->Active)
			return false;
		$connection = $this->Connection();
		try {
			if (is_string($queries))
				$queries = Convert::ToSequence($queries);
			$connection->beginTransaction();
			if (is_array(first($params))) {
				if (($qc = count($queries)) >= ($pc = count($params))) {
					$i = 0;
					$keys  = array_keys($params);
					foreach ($queries as $query) {
						$param = $this->ParametersNormalization($params[$keys[$i++ % $pc]]);
						if(!$this->OnExecuting($query, $param)) continue;
						$stmt = $connection->prepare($query);
						$stmt->execute($param);
						$this->OnExecuted($query, $param, null, $stmt);
					}
				} else {
					$i = 0;
					$keys  = array_keys($queries);
					foreach ($params as $param) {
						$param = $this->ParametersNormalization($param);
						$query = $queries[$keys[$i++ % $qc]];
						if(!$this->OnExecuting($query, $param)) continue;
						$stmt = $connection->prepare($query);
						$stmt->execute($param);
						$this->OnExecuted($query, $param, null, $stmt);
					}
				}
			} else{
				$params = $this->ParametersNormalization($params);
				foreach ($queries as $query) {
					if(!$this->OnExecuting($query, $params)) continue;
					$stmt = $connection->prepare($query);
					$stmt->execute($params);
					$this->OnExecuted($query, $params, null, $stmt);
				}
			}
			return $connection->commit();
		} catch (\Exception $ex) {
			if ($connection->inTransaction())
				$connection->rollBack();
			return false;
		} finally {
			$this->Reset();
		}
	}
	public function TryTransaction($queries, $params = [], $defaultValue = false, &$connection = null)
	{
		try {
			return $this->ReturnValue($this->TransactionExecute($queries, $params, $connection), $queries, $params, $defaultValue);
		} catch (\Exception $ex) {
			return $this->ReturnFault($ex, $this->TransactionQuery($queries), $params, $defaultValue);
		} finally {
			$this->Reset();
		}
	}


	public function FetchRowsExecute($query, $params = [])
	{
		$stmt = $this->Execute($query, $params);
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		return $stmt->fetchAll();
	}
	public function TryFetchRows($query, $params = [], $defaultValue = [])
	{
		try {
			return $this->ReturnArray($this->FetchRowsExecute($query, $params), $query, $params, $defaultValue);
		} catch (\Exception $ex) {
			return $this->ReturnFault($ex, $query, $params,$defaultValue);
		} finally {
			$this->Reset();
		}
	}

	public function FetchRowExecute($query, $params = [])
	{
		$stmt = $this->Execute($query, $params);
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		$result = $stmt->fetch();
		return $result ? $result : null;
	}
	public function TryFetchRow($query, $params = [], $defaultValue = [])
	{
		try {
			return $this->ReturnArray($this->FetchRowExecute($query, $params), $query, $params, $defaultValue);
		} catch (\Exception $ex) {
			return $this->ReturnFault($ex, $query, $params, $defaultValue);
		} finally {
			$this->Reset();
		}
	}

	public function FetchColumnExecute($query, $params = [])
	{
		$stmt = $this->Execute($query, $params);
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		$result = loop($stmt->fetchAll(), function ($v) {
			return first($v);
		}, false);
		return $result ? $result : null;
	}
	public function TryFetchColumn($query, $params = [], $defaultValue = [])
	{
		try {
			return $this->ReturnArray($this->FetchColumnExecute($query, $params), $query, $params, $defaultValue);
		} catch (\Exception $ex) {
			return $this->ReturnFault($ex, $query, $params, $defaultValue);
		} finally {
			$this->Reset();
		}
	}

	public function FetchRowIdExecute($query, $params = [])
	{
		$this->Execute($query, $params, connection: $connection);
		return $connection?$connection->lastInsertId():null;
	}
	public function TryFetchRowId($query, $params = [], $defaultValue = null)
	{
		try {
			return $this->ReturnValue($this->FetchRowIdExecute($query, $params), $query, $params, $defaultValue);
		} catch (\Exception $ex) {
			return $this->ReturnFault($ex, $query, $params, $defaultValue);
		} finally {
			$this->Reset();
		}
	}

	public function FetchPairsExecute($query, $params = [])
	{
		$res = [];
		$k = $v = null;
		foreach ($this->FetchRowsExecute($query, $params) as $i => $row)
			$res[count($row) < 2 ? $i : $row[$k = $k ?? array_key_first($row)]] = $row[$v = $v ?? array_key_last($row)];
		return $res;
	}
	public function TryFetchPairs($query, $params = [], $defaultValue = [])
	{
		try {
			return $this->ReturnArray($this->FetchPairsExecute($query, $params), $query, $params, $defaultValue);
		} catch (\Exception $ex) {
			return $this->ReturnFault($ex, $query, $params, $defaultValue);
		} finally {
			$this->Reset();
		}
	}

	public function FetchValueExecute($query, $params = [])
	{
		$stmt = $this->Execute($query, $params);
		$v = $stmt->fetchColumn();
		return $v === false ? null : $v;
	}
	public function TryFetchValue($query, $params = [], $defaultValue = null)
	{
		try {
			return $this->ReturnValue($this->FetchValueExecute($query, $params), $query, $params, $defaultValue);
		} catch (\Exception $ex) {
			return $this->ReturnFault($ex, $query, $params, $defaultValue);
		} finally {
			$this->Reset();
		}
	}

	public function FetchChangesExecute($query, $params = [])
	{
		$stmt = $this->Execute($query, $params, $isDone);
		return $isDone ? ($stmt->rowCount() ?: ($stmt->columnCount() ?: true)) : false;
	}
	public function TryFetchChanges($query, $params = [], $defaultValue = false)
	{
		try {
			return $this->ReturnValue($this->FetchChangesExecute($query, $params), $query, $params, $defaultValue);
		} catch (\Exception $ex) {
			return $this->ReturnFault($ex, $query, $params, $defaultValue);
		} finally {
			$this->Reset();
		}
	}


	public function Transaction($queries, $params = [], $defaultValue = false)
	{
		return $this->TryTransaction($queries, $params, $defaultValue);
	}
	public function TransactionQuery($queries)
	{
		return "START TRANSACTION;
	{$this->PreQuery}
	" . Convert::ToString($queries, ";" . PHP_EOL) . "
	{$this->MidQuery};
	{$this->PostQuery}
COMMIT;";
		//ROLLBACK;";
	}

	public function Create($name, $configs = "DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci", $defaultValue = null)
	{
		return $this->TryExecute($this->CreateQuery($name, $configs), [], $defaultValue);
	}
	public function CreateQuery($name, $configs = "DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")
	{
		return "{$this->PreQuery} CREATE DATABASE IF NOT EXISTS " . $this->NameNormalization($name) . " {$this->MidQuery} $configs {$this->PostQuery}";
	}

	public function Table(string $name, bool $prefix = true)
	{
		return new DataTable(
			$this,
			$name,
			$prefix ? \_::$Back->DataBasePrefix : null,
			\_::$Back->DataTableNameConvertors
		);
	}
	
	public function TableNames($defaultValue = null)
	{
		return $this->TryFetchColumn($this->TableNamesQuery(), [], $defaultValue);
	}
	public function TableNamesQuery()
	{
		return "{$this->PreQuery} SHOW {$this->MidQuery} TABLES {$this->PostQuery}";
	}
	
	public function CreateTable($tableName, $column_types = [], $configs = "ENGINE = InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_bin", $defaultValue = null)
	{
		return $this->TryExecute($this->CreateTableQuery($tableName, $column_types, $configs), [], $defaultValue);
	}
	public function CreateTableQuery($tableName, $column_types = [], $configs = "ENGINE = InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_bin")
	{
		return "{$this->PreQuery} CREATE TABLE IF NOT EXISTS " . $this->NameNormalization($tableName) .
			" (" .
			join("," . PHP_EOL, [...loop($column_types, fn($v, $k) => is_numeric($k) ? $v : $this->NameNormalization($k) . " $v"), ...(is_numeric($k = array_key_first($column_types)) ? [] : ["PRIMARY KEY (" . $this->NameNormalization($k) . ")"]), ...($this->MidQuery ? [$this->MidQuery] : [])]) .
			") $configs {$this->PostQuery}";
	}
	public function DropTable($tableName, $defaultValue = null)
	{
		return $this->TryExecute($this->DropTableQuery($tableName), [], $defaultValue);
	}
	public function DropTableQuery($tableName)
	{
		return "{$this->PreQuery} DROP TABLE " . $this->NameNormalization($tableName) . " {$this->PostQuery}";
	}

	public function Select($tableName, $columns = "*", $condition = null, $params = [], $defaultValue = [])
	{
		return $this->TryFetchRows($this->SelectQuery($tableName, $columns, $condition), $params, $defaultValue);
	}
	public function SelectQuery($tableName, $columns = "*", $condition = null)
	{
		return "{$this->PreQuery} SELECT " . $this->ColumnNameNormalization($columns ?? "*") . " FROM " . $this->NameNormalization($tableName) . " {$this->MidQuery} " . $this->ConditionNormalization($condition) . " " . $this->PostQuery;
	}

	public function SelectRow($tableName, $columns = "*", $condition = null, $params = [], $defaultValue = [])
	{
		return $this->TryFetchRow($this->SelectRowQuery($tableName, $columns, $condition), $params, $defaultValue);
	}
	public function SelectRowQuery($tableName, $columns = "*", $condition = null)
	{
		return "{$this->PreQuery} SELECT " . $this->ColumnNameNormalization($columns ?? "*") . " FROM " . $this->NameNormalization($tableName) . " {$this->MidQuery} " . $this->ConditionNormalization($condition) . " {$this->PostQuery} " . $this->LimitNormalization(1);
	}

	public function SelectColumn($tableName, $column = "Id", $condition = null, $params = [], $defaultValue = [])
	{
		return $this->TryFetchColumn($this->SelectColumnQuery($tableName, $column, $condition), $params, $defaultValue);
	}
	public function SelectColumnQuery($tableName, $column = "Id", $condition = null)
	{
		return "{$this->PreQuery} SELECT " . $this->ColumnNameNormalization($column ?? "Id") . " FROM " . $this->NameNormalization($tableName) . " {$this->MidQuery} " . $this->ConditionNormalization($condition) . " " . $this->PostQuery;
	}

	public function SelectPairs($tableName, $key = "Id", $value = "Name", $condition = null, $params = [], $defaultValue = [])
	{
		return $this->TryFetchPairs($this->SelectPairsQuery($tableName, $key, $value, $condition), $params, $defaultValue);
	}
	public function SelectPairsQuery($tableName, $key = "Id", $value = "Name", $condition = null)
	{
		return "{$this->PreQuery} SELECT " . $this->ColumnNameNormalization([$key ?? "Id", $value ?? "Name"]) . " FROM " . $this->NameNormalization($tableName) . " {$this->MidQuery} " . $this->ConditionNormalization($condition) . " " . $this->PostQuery;
	}

	public function SelectValue($tableName, $column = "Id", $condition = null, $params = [], $defaultValue = null)
	{
		return $this->TryFetchValue($this->SelectValueQuery($tableName, $column, $condition), $params, $defaultValue);
	}
	public function SelectValueQuery($tableName, $column = "Id", $condition = null)
	{
		return "{$this->PreQuery} SELECT " . $this->ColumnNameNormalization($column ?? "Id") . " FROM " . $this->NameNormalization($tableName) . " {$this->MidQuery} " . $this->ConditionNormalization($condition) . " " . $this->PostQuery;
	}

	public function Insert($tableName, $params = [], $defaultValue = false)
	{
		if (is_array(first($params)))
			return $this->TryTransaction($this->InsertQuery($tableName, $params), $params, $defaultValue);
		return $this->TryFetchRowId($this->InsertQuery($tableName, $params), $params, $defaultValue);
	}
	public function InsertQuery($tableName, &$params)
	{
		if (is_array(first($params)))
			return loop($params, function ($p, $k) use ($tableName, &$params) {
				return self::InsertQuery($tableName, $params[$k]);
			});
		$vals = [];
		$sets = [];
		$args = [];
		foreach ($this->ParametersNormalization($params) as $key => $value) {
			$k = trim($key, ":`[]");
			$sets[] = "$this->StartWrap$k$this->EndWrap";
			$vals[] = ":$k";
			$args[":$k"] = $value;
		}
		$params = $args;
		return "{$this->PreQuery} INSERT INTO " . $this->NameNormalization($tableName) . " {$this->MidQuery} (" . implode(", ", $sets) . ") VALUES (" . implode(", ", $vals) . ") " . $this->PostQuery;
	}

	public function Replace($tableName, $params = [], $defaultValue = false)
	{
		if (is_array(first($params)))
			return $this->TryTransaction($this->ReplaceQuery($tableName, $params), $params, $defaultValue);
		return $this->TryFetchRowId($this->ReplaceQuery($tableName, $params), $params, $defaultValue);
	}
	public function ReplaceQuery($tableName, &$params)
	{
		if (is_array(first($params)))
			return loop($params, function ($p, $k) use ($tableName, &$params) {
				return self::ReplaceQuery($tableName, $params[$k]);
			});
		$vals = [];
		$sets = [];
		$args = [];
		if (is_array(first($params))) {
			$vs = [];
			$i = 0;
			foreach ($this->ParametersNormalization($params[$i]) as $key => $value) {
				$k = trim($key, ":`[]");
				$sets[] = $k;
				$args[$vs[] = ":$k$i"] = $value;
			}
			$vals[] = join(",", $vs);
			for ($i = 1; $i < count($params); $i++) {
				$vs = [];
				foreach ($this->ParametersNormalization($params[$i]) as $key => $value) {
					$k = trim($key, ":`[]");
					$args[$vs[] = ":$k$i"] = $value;
				}
				$vals[] = join(",", $vs);
			}
		} else {
			$vs = [];
			foreach ($this->ParametersNormalization($params) as $key => $value) {
				$k = trim($key, ":`[]");
				$sets[] = $k;
				$args[$vs[] = ":$k"] = $value;
			}
			$vals[] = join(",", $vs);
		}
		$params = $args;
		$c = count($vals);
		//if(!$this->Timeout) $this->Timeout(max(10000, $c*2000));
		if ($c > 100) {
			$queries = [];
			for ($i = 0; $i < $c; $i += 100)
				$queries[] = "{$this->PreQuery} REPLACE INTO " . $this->NameNormalization($tableName) . " {$this->MidQuery} ($this->StartWrap" . implode("$this->EndWrap, $this->StartWrap", $sets) . "$this->EndWrap) VALUES (" . implode("), (", array_slice($vals, $i, min($c, 100))) . ") " . $this->PostQuery;
			return join(";" . PHP_EOL, $queries);
		} else
			return "{$this->PreQuery} REPLACE INTO " . $this->NameNormalization($tableName) . " {$this->MidQuery} ($this->StartWrap" . implode("$this->EndWrap, $this->StartWrap", $sets) . "$this->EndWrap) VALUES (" . implode("), (", $vals) . ") " . $this->PostQuery;
	}

	public function Update($tableName, $condition = null, $params = [], $defaultValue = false)
	{
		if (is_array(first($params)))
			return $this->TryTransaction($this->UpdateQuery($tableName, $condition, $params), $params, $defaultValue);
		return $this->TryFetchChanges($this->UpdateQuery($tableName, $condition, $params), $params, $defaultValue);
	}
	public function UpdateQuery($tableName, $condition, &$params)
	{
		if (is_array(first($params)))
			return loop($params, function ($p, $k) use ($tableName, $condition, &$params) {
				return self::UpdateQuery($tableName, $condition, $params[$k]);
			});
		$sets = [];
		$args = [];
		$condition = $this->ConditionNormalization($condition) . " ";
		foreach ($this->ParametersNormalization($params) as $key => $value) {
			$k = trim($key, ":`[]");
			if (!$condition || !preg_match("/\B\:$k\b/", $condition))
				$sets[] = "$this->StartWrap$k$this->EndWrap=:$k";
			$args[":$k"] = $value;
		}
		$params = $args;
		return "{$this->PreQuery} UPDATE " . $this->NameNormalization($tableName) . " {$this->MidQuery} SET " . implode(", ", $sets) . " " . $condition . " " . $this->PostQuery;
	}

	public function Delete($tableName, $condition = null, $params = [], $defaultValue = false)
	{
		if (is_array(first($params)))
			return $this->TryTransaction($this->DeleteQuery($tableName, $condition, $params), $params, $defaultValue);
		return $this->TryFetchChanges($this->DeleteQuery($tableName, $condition), $params, $defaultValue);
	}
	public function DeleteQuery($tableName, $condition = null, &$params = [])
	{
		if (is_array(first($params)))
			return loop($params, function ($p, $k) use ($tableName, $condition, &$params) {
				return self::DeleteQuery($tableName, $condition, $params[$k]);
			});
		return "{$this->PreQuery} DELETE FROM " . $this->NameNormalization($tableName) . " {$this->MidQuery} " . $this->ConditionNormalization($condition) . " " . $this->PostQuery;
	}
}