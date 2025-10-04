<?php
namespace MiMFa\Library;
/**
 * A simple library to connect the database and run the most uses SQL queries
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#datatable See the Library Documentation
 */
class DataTable
{
	public $Name = null;
	public $MainName = null;
	public $AlternativeName = null;
	public \MiMFa\Library\DataBase $DataBase;

	public $PreQuery = null;
	public $MidQuery = null;
	public $PostQuery = null;

	public function __construct(\MiMFa\Library\DataBase $dataBase, $name, bool $addPrefix = true)
	{
		$this->DataBase = $dataBase;
		$this->MainName = $name;
		$name = $addPrefix ? (\_::$Config->DataBasePrefix . $name) : $name;
		foreach (\_::$Config->DataTableNameConvertors as $key => $value)
			$name = preg_replace($key, $value, $name);
		$this->Name = $name;
	}

	protected function GetDatabase()
	{
		$this->DataBase->PreQuery = $this->PreQuery;
		$this->DataBase->MidQuery = $this->MidQuery;
		$this->DataBase->PostQuery = $this->PostQuery;
		return $this->DataBase;
	}

	public function SessionTimeout($millisecond = 30000)
	{
		return $this->GetDatabase()->SessionTimeout($millisecond);
	}
	public function Timeout($millisecond = 30000)
	{
		return $this->GetDatabase()->Timeout($millisecond);
	}

	public function Create($column_types = [], $configs = "ENGINE = InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_bin", $defaultValue = null)
	{
		return $this->GetDatabase()->CreateTable($this->Name, $column_types, $configs, $defaultValue);
	}
	public function CreateQuery($column_types = [], $configs = "ENGINE = InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_bin")
	{
		return $this->GetDatabase()->CreateTableQuery($this->Name, $column_types, $configs);
	}

	public function SelectValue($column = "Id", $condition = null, $params = [], $defaultValue = null)
	{
		return $this->GetDatabase()->SelectValue($this->Name, $column, $condition, $params, $defaultValue);
	}
	public function SelectValueQuery($column = "Id", $condition = null)
	{
		return $this->GetDatabase()->SelectValueQuery($this->Name, $column, $condition);
	}

	public function Select($columns = "*", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->GetDatabase()->Select($this->Name, $columns, $condition, $params, $defaultValue);
	}
	public function SelectQuery($columns = "*", $condition = null)
	{
		return $this->GetDatabase()->SelectQuery($this->Name, $columns, $condition);
	}

	public function SelectRow($columns = "*", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->GetDatabase()->SelectRow($this->Name, $columns, $condition, $params, $defaultValue);
	}
	public function SelectRowQuery($columns = "*", $condition = null)
	{
		return $this->GetDatabase()->SelectRowQuery($this->Name, $columns, $condition);
	}
	public function SelectFirstRow($columns = "*", $condition = null, $params = [])
	{
		return $this->GetDatabase()->TryFetchRow($this->SelectRowQuery($columns, $condition), $params);
	}
	public function SelectLastRow($columns = "*", $condition = null, $params = [])
	{
		return $this->OrderBy("Id", ascending: false)->SelectFirstRow($columns, $condition, $params);
	}

	public function SelectColumn($column = "Id", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->GetDatabase()->SelectColumn($this->Name, $column, $condition, $params, $defaultValue);
	}
	public function SelectColumnQuery($column = "Id", $condition = null)
	{
		return $this->GetDatabase()->SelectColumnQuery($this->Name, $column, $condition);
	}

	public function SelectPairs($key = "Id", $value = "Name", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->GetDatabase()->SelectPairs($this->Name, $key, $value, $condition, $params, $defaultValue);
	}
	public function SelectPairsQuery($key = "Id", $value = "Name", $condition = null)
	{
		return $this->GetDatabase()->SelectPairsQuery($this->Name, $key, $value, $condition);
	}

	public function Insert($params = [], $defaultValue = false)
	{
		return $this->GetDatabase()->Insert($this->Name, $params, $defaultValue);
	}
	public function InsertQuery(&$params)
	{
		return $this->GetDatabase()->InsertQuery($this->Name, $params);
	}

	public function Replace($params = [], $defaultValue = false)
	{
		return $this->GetDatabase()->Replace($this->Name, $params, $defaultValue);
	}
	public function ReplaceQuery(&$params)
	{
		return $this->GetDatabase()->ReplaceQuery($this->Name, $params);
	}

	public function Update($condition = null, $params = [], $defaultValue = false)
	{
		return $this->GetDatabase()->Update($this->Name, $condition, $params, $defaultValue);
	}
	public function UpdateQuery($condition, &$params)
	{
		return $this->GetDatabase()->UpdateQuery($this->Name, $condition, $params);
	}

	public function Delete($condition = null, $params = [], $defaultValue = false)
	{
		return $this->GetDatabase()->Delete($this->Name, $condition, $params, $defaultValue);
	}
	public function DeleteQuery($condition = null)
	{
		return $this->GetDatabase()->DeleteQuery($this->Name, $condition);
	}


	/**
	 * Set an Alternative name for the table
	 * @param string $name
	 * @return DataTable
	 */
	public function As($name)
	{
		$this->AlternativeName = $name;
		$this->MidQuery .= " AS " . $name;
		return $this;
	}
	/**
	 * An outer left join the table by another table to do other process by the first table
	 * @param DataTable $dataTable
	 * @param mixed $on A condition to join two tables with eachother
	 * @return DataTable
	 */
	public function Join(DataTable $dataTable, $on = null)
	{
		$this->MidQuery .= PHP_EOL . "LEFT OUTER JOIN $dataTable->Name $dataTable->MidQuery ON " . ($on ?? (($this->AlternativeName ?? $this->Name) . ".{$dataTable->MainName}Id=" . ($dataTable->AlternativeName ?? $dataTable->Name) . ".Id"));
		return $this;
	}
	/**
	 * An inner join the table by another table to do other processes on the common rows
	 * @param DataTable $dataTable
	 * @param mixed $on A condition to join two tables with eachother
	 * @return DataTable
	 */
	public function InnerJoin(DataTable $dataTable, $on = null)
	{
		$this->MidQuery .= PHP_EOL . "INNER JOIN $dataTable->Name $dataTable->MidQuery ON " . ($on ?? (($this->AlternativeName ?? $this->Name) . ".{$dataTable->MainName}Id=" . ($dataTable->AlternativeName ?? $dataTable->Name) . ".Id"));
		return $this;
	}
	/**
	 * An outer right join the table by another table to do other process by the second table
	 * @param DataTable $dataTable
	 * @param mixed $on A condition to join two tables with eachother
	 * @return DataTable
	 */
	public function OuterJoin(DataTable $dataTable, $on = null)
	{
		$this->MidQuery .= PHP_EOL . "RIGHT OUTER JOIN $dataTable->Name $dataTable->MidQuery ON " . ($on ?? (($this->AlternativeName ?? $this->Name) . ".{$dataTable->MainName}Id=" . ($dataTable->AlternativeName ?? $dataTable->Name) . ".Id"));
		return $this;
	}
	/**
	 * Join the table by another table to do other process by all tables rows
	 * @param DataTable $dataTable
	 * @param mixed $on A condition to join two tables with eachother
	 * @return DataTable
	 */
	public function CrossJoin(DataTable $dataTable, $on = null)
	{
		$this->MidQuery .= PHP_EOL . "CROSS JOIN $dataTable->Name $dataTable->MidQuery ON " . ($on ?? (($this->AlternativeName ?? $this->Name) . ".{$dataTable->MainName}Id=" . ($dataTable->AlternativeName ?? $dataTable->Name) . ".Id"));
		return $this;
	}

	/**
	 * To order all table rows
	 * @param mixed $columns An array of ["columnname"=>"desc|asc"] or a simple string of "columnname desc|asc, ..."
	 * @return DataTable
	 */
	public function OrderBy($columns = null, bool|null $ascending = null)
	{
		$this->PostQuery .= PHP_EOL . $this->GetDatabase()->OrderNormalization($columns, $ascending);
		return $this;
	}

	/**
	 * To limit the numbers of retrieved table rows
	 * @param string|int $limit The limitation number
	 * @return DataTable
	 */
	public function Limit($limit = 1)
	{
		$this->PostQuery .= PHP_EOL . $this->GetDatabase()->LimitNormalization($limit);
		return $this;
	}


	public function Exists($condition = null, $params = [])
	{
		$result = null;
		try {
			$result = $this->SelectRow("*", $condition, $params);
		} catch (\Exception $ex) {
		}
		return !isEmpty($result);
	}
	public function Count($column = "Id", $condition = null, $params = [])
	{
		return $this->GetDatabase()->TryFetchValue($this->SelectValueQuery("COUNT($column)", $condition), $params);
	}
	public function Sum($column = "Id", $condition = null, $params = [])
	{
		return $this->GetDatabase()->TryFetchValue($this->SelectValueQuery("SUM($column)", $condition), $params);

	}
	public function Average($column = "Id", $condition = null, $params = [])
	{
		return $this->GetDatabase()->TryFetchValue($this->SelectValueQuery("AVG($column)", $condition), $params);
	}
	public function Max($column = "Id", $condition = null, $params = [])
	{
		return $this->GetDatabase()->TryFetchValue($this->SelectValueQuery("MAX($column)", $condition), $params);
	}
	public function Min($column = "Id", $condition = null, $params = [])
	{
		return $this->GetDatabase()->TryFetchValue($this->SelectValueQuery("MIN($column)", $condition), $params);
	}
	public function First($column = "Id", $condition = null, $params = [])
	{
		return $this->GetDatabase()->TryFetchValue($this->SelectValueQuery($column, $condition), $params);
	}
	public function Last($column = "Id", $condition = null, $params = [])
	{
		return $this->GetDatabase()->TryFetchValue($this->OrderBy($column, false)->SelectValueQuery($column, $this->DataBase->ConditionNormalization($condition)), $params);
	}


	public function GetValue(array|int|null $id, $key = "Id", $defaultValue = null)
	{
		return getValid($this->Get($id), $key, $defaultValue);
	}
	public function SetValue(array|int|null $id, $key, $value = null, $defaultValue = false)
	{
		return $this->Set($id, [$key=>$value], $defaultValue);
	}
	public function Get(array|int|null $id, $defaultValue = [])
	{
		if (is_int($id))
			return $this->SelectRow("*", "Id=:Id", [":Id"=>$id], $defaultValue);
		elseif(is_iterable($id)) {
			$params = [];
			foreach ($id as $key => $value) 
				$params[":Id$key"] = $value;
			return $this->Select("*", "Id IN (".join(",", loop($params, fn($v, $k)=>$k)).")", $params, $defaultValue);
		}
		return $defaultValue;
	}
	public function Set(array|int|null $id, $params = [], $defaultValue = false)
	{
		if (is_int($id)) {
			$params[":Id"] = $id;
			return $this->Update("Id=:Id", $params, $defaultValue);
		} elseif(is_iterable($id)) {
			$nparams = [];
			foreach ($id as $key => $value) {
				$params[":Id"] = $value;
				$nparams[] = $params;
			}
			return $this->Update("Id=:Id", $nparams, $defaultValue);
		}
		return $defaultValue;
	}
	public function Forget(array|int|null $id, $defaultValue = false)
	{
		if (is_int($id))
			return $this->Delete("Id=:Id", [":Id"=>$id], $defaultValue);
		elseif(is_iterable($id)) {
			$params = [];
			foreach ($id as $key => $value) 
				$params[":Id$key"] = $value;
			return $this->Delete("Id IN (".join(",", loop($params, fn($v, $k)=>$k)).")", $params, $defaultValue);
		}
		return $defaultValue;
	}
	public function Has(array|int|null $id)
	{
		return self::Get($id, null)?true:false;
	}

	public function GetColumnName($name)
	{
		return $this->Name . "." . $this->DataBase->StartWrap . $name . $this->DataBase->EndWrap;
	}

	public function HasMetaData($condition = null, $params = [])
	{
		return self::GetMetaData($condition, $params) ? true : false;
	}
	public function GetMetaData($condition = null, $params = [], $defaultValue = [])
	{
		return Convert::FromJson($this->SelectValue("MetaData", $condition, $params)) ?? $defaultValue;
	}
	public function SetMetaData($metadata, $condition = null, $params = [], $defaultValue = false)
	{
		$params[":MetaData"] = isStatic($metadata) ? $metadata : Convert::ToJson($metadata);
		return $this->Update($condition, $params, $defaultValue);
	}
	public function ForgetMetaData($condition = null, $params = [], $defaultValue = false)
	{
		return $this->SetMetaData(null, $condition, $params, $defaultValue);
	}

	public function HasMetaValue($key, $condition = null, $params = [])
	{
		return has(self::GetMetaData($condition, $params), $key);
	}
	public function GetMetaValue($key, $condition = null, $params = [], $defaultValue = null)
	{
		return get(self::GetMetaData($condition, $params), $key) ?? $defaultValue;
	}
	public function SetMetaValue($key, $value, $condition = null, $params = [], $defaultValue = false)
	{
		$metadata = self::GetMetaData($condition, $params);
		if (!$metadata)
			$metadata = [];
		$metadata[$key] = $value;
		return $this->SetMetaData($metadata, $condition, $params, $defaultValue);
	}
	public function ForgetMetaValue($key, $condition = null, $params = [], $defaultValue = false)
	{
		$metadata = self::GetMetaData($condition, $params);
		if (!$metadata)
			$metadata = [];
		unset($metadata[$key]);
		return $this->SetMetaData($metadata, $condition, $params, $defaultValue);
	}
}