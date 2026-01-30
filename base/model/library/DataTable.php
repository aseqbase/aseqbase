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

	public function __construct(\MiMFa\Library\DataBase $dataBase, $name, $prefix = null, $nameConvertors = [])
	{
		$this->DataBase = $dataBase;
		$this->MainName = $name;
		$name = $prefix ? ($prefix . $name) : $name;
		foreach ($nameConvertors as $key => $value)
			$name = preg_replace($key, $value, $name);
		$this->Name = $name;
	}

	public function GetDatabase()
	{
		$this->DataBase->PreQuery = $this->PreQuery;
		$this->DataBase->MidQuery = $this->MidQuery;
		$this->DataBase->PostQuery = $this->PostQuery;
		return $this->DataBase;
	}
	public function Reset()
	{
		$this->AlternativeName = null;
		$this->PreQuery = null;
		$this->MidQuery = null;
		$this->PostQuery = null;
		return $this;
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

	public function Select($columns = "*", $condition = null, $params = [], $defaultValue = [])
	{
		return $this->GetDatabase()->Select($this->Name, $columns, $condition, $params, $defaultValue);
	}
	public function SelectQuery($columns = "*", $condition = null)
	{
		return $this->GetDatabase()->SelectQuery($this->Name, $columns, $condition);
	}

	public function SelectRow($columns = "*", $condition = null, $params = [], $defaultValue = [])
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

	public function SelectColumn($column = "Id", $condition = null, $params = [], $defaultValue = [])
	{
		return $this->GetDatabase()->SelectColumn($this->Name, $column, $condition, $params, $defaultValue);
	}
	public function SelectColumnQuery($column = "Id", $condition = null)
	{
		return $this->GetDatabase()->SelectColumnQuery($this->Name, $column, $condition);
	}

	public function SelectPairs($key = "Id", $value = "Name", $condition = null, $params = [], $defaultValue = [])
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

	/**
	 * To get a record or records
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $defaultValue
	 */
	public function Get(array|int|string|null $id, $defaultValue = [])
	{
		if (is_null($id))
			return loop($this->Select("*", null, [], []), fn($v) => [$v["Id"] => $v], pair: true) ?: $defaultValue;
		elseif (isStatic($id))
			return $this->SelectRow("*", "Id=:Id", [":Id" => $id], $defaultValue);
		elseif (is_iterable($id)) {
			$params = [];
			foreach ($id as $key => $value)
				$params[":Id$key"] = $value;
			return loop($this->Select("*", "Id IN (" . join(",", loop($params, fn($v, $k) => $k)) . ")", $params, []), fn($v) => [$v["Id"] => $v], pair: true) ?: $defaultValue;
		}
		return $defaultValue;
	}
	/**
	 * To update record or records
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $params
	 * @param mixed $defaultValue
	 */
	public function Set(array|int|string|null $id, $params = [], $defaultValue = false)
	{
		if (is_null($id))
			return $this->Update(null, $params, $defaultValue);
		elseif (isStatic($id)) {
			$params[":Id"] = $id;
			return $this->Update("Id=:Id", $params, $defaultValue);
		} elseif (is_iterable($id)) {
			$nparams = [];
			foreach ($id as $key => $value) {
				$params[":Id"] = $value;
				$nparams[] = $params;
			}
			return $this->Update("Id=:Id", $nparams, $defaultValue);
		}
		return $defaultValue;
	}
	/**
	 * To insert or replace record or records
	 * @param mixed $params
	 * @param mixed $defaultValue
	 */
	public function Put($params = [], $defaultValue = false)
	{
		return $this->Replace($params, $defaultValue);
	}
	/**
	 * To delete a record or records
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $defaultValue
	 */
	public function Pop(array|int|string|null $id, $defaultValue = false)
	{
		if (is_null($id))
			return $this->Delete(null, [], $defaultValue);
		elseif (isStatic($id))
			return $this->Delete("Id=:Id", [":Id" => $id], $defaultValue);
		elseif (is_iterable($id)) {
			$params = [];
			foreach ($id as $key => $value)
				$params[":Id$key"] = $value;
			return $this->Delete("Id IN (" . join(",", loop($params, fn($v, $k) => $k)) . ")", $params, $defaultValue);
		}
		return $defaultValue;
	}
	/**
	 * To check if a record or records is exists or not
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @return bool
	 */
	public function Has(array|int|string|null $id)
	{
		return self::Get($id, null) ? true : false;
	}

	/**
	 * To get a specific column value of a record or records
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $key
	 * @param mixed $defaultValue
	 */
	public function GetValue(array|int|string|null $id, $key = "Id", $defaultValue = null)
	{
		if (isStatic($id))
			return getValid($this->Get($id), $key, $defaultValue);
		else
			return loop($this->Get($id), function ($v, $k) use ($key) {
				return [$k => $v[$key]]; }, pair: true) ?: $defaultValue;
	}
	/**
	 * To set a specific column value of a record or records
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $key
	 * @param mixed $value
	 * @param mixed $defaultValue
	 */
	public function SetValue(array|int|string|null $id, $key, $value = null, $defaultValue = false)
	{
		return $this->Set($id, [$key => $value], $defaultValue);
	}
	/**
	 * To forget a specific column value of a record or records
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $key
	 * @param mixed $defaultValue
	 */
	public function PopValue(array|int|string|null $id, $key, $defaultValue = false)
	{
		return $this->Set($id, [$key => null], $defaultValue);
	}
	/**
	 * To check if the record or records has value
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $key
	 * @return bool
	 */
	public function HasValue(array|int|string|null $id, $key)
	{
		return self::GetValue($id, $key, null) ? true : false;
	}
	
	/**
	 * To get the record or records metadata value
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $defaultValue
	 * @return array|null
	 */
	public function GetMetaData(array|int|string|null $id, $defaultValue = [])
	{
		if (isStatic($id))
			return Convert::FromJson($this->GetValue($id, "MetaData", $defaultValue));
		else
			return loop($this->GetValue($id, "MetaData", $defaultValue), function ($v, $k) {
				return [$k => Convert::FromJson($v)]; }, pair: true) ?: $defaultValue;
	}
	/**
	 * To set the record or records metadata value
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $metadata
	 * @param mixed $defaultValue
	 */
	public function SetMetaData(array|int|string|null $id, $metadata, $defaultValue = false)
	{
		return $this->SetValue($id, "MetaData", isStatic($metadata) ? $metadata : Convert::ToJson($metadata), $defaultValue);
	}
	/**
	 * To forget the record or records metadata value
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $defaultValue
	 */
	public function PopMetaData(array|int|string|null $id, $defaultValue = false)
	{
		return $this->PopValue($id, "MetaData", $defaultValue);
	}
	/**
	 * To check if the record or records has metadata value or not
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @return bool
	 */
	public function HasMetaData(array|int|string|null $id)
	{
		return self::HasValue($id, "MetaData");
	}

	/**
	 * To get the metadata value by the specific $key
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $key
	 * @param mixed $defaultValue
	 */
	public function GetMetaValue(array|int|string|null $id, $key, $defaultValue = null)
	{
		if (isStatic($id))
			return getValid($this->GetMetaData($id), $key, $defaultValue);
		else
			return loop($this->GetMetaData($id), function ($v, $k) use ($key) {
				return [$k => $v[$key]]; }, pair: true) ?: $defaultValue;
	}
	/**
	 * To set the metadata value by the specific $key
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $key
	 * @param mixed $value
	 * @param mixed $defaultValue
	 */
	public function SetMetaValue(array|int|string|null $id, $key, $value, $defaultValue = false)
	{
		$metadata = self::GetMetaData($id, [])??[];
		if (isStatic($id)) {
			$metadata[$key] = $value;
			return $this->SetMetaData($id, $metadata, $defaultValue);
		} else {
			$params = [];
			foreach ($metadata as $k => $md) {
				$md[$key] = $value;
				$params[] = [":Id" => $k, "MetaData" => Convert::ToJson($md)];
			}
			return $this->Update("Id=:Id", $params, $defaultValue);
		}
	}
	/**
	 * To forget the metadata value by the specific $key
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $key
	 * @param mixed $defaultValue
	 */
	public function PopMetaValue(array|int|string|null $id, $key, $defaultValue = false)
	{
		$metadata = self::GetMetaData($id, [])??[];
		if (isStatic($id)) {
			unset($metadata[$key]);
			return $this->SetMetaData($id, $metadata, $defaultValue);
		} else {
			$params = [];
			foreach ($metadata as $k => $md) {
				unset($md[$key]);
				$params[] = [":Id" => $k, "MetaData" => Convert::ToJson($md)];
			}
			return $this->Update("Id=:Id", $params, $defaultValue);
		}
	}
	/**
	 * To check if the metadata column has the specific $key or not
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $key
	 * @return bool
	 */
	public function HasMetaValue(array|int|string|null $id, $key)
	{
		return self::GetMetaValue($id, $key) ? true : false;
	}

	
	/**
	 * To get the metadata Array value by the specific $key
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $key
	 * @param mixed $defaultValue
	 */
	public function GetMetaItemValue(array|int|string|null $id, $key, $item, $defaultValue = null)
	{
		if (isStatic($id))
			return getValid(get($this->GetMetaData($id), $key), $item, $defaultValue);
		else
			return loop($this->GetMetaValue($id, $key), function ($v, $k) use ($item) {
				return [$k => $v[$item]]; }, pair: true) ?: $defaultValue;
	}
	/**
	 * To set the metadata Array value by the specific $key
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $key
	 * @param mixed $value
	 * @param mixed $defaultValue
	 */
	public function SetMetaItemValue(array|int|string|null $id, $key, $item, $value, $defaultValue = false)
	{
		$metadata = self::GetMetaData($id, [])??[];
		if (isStatic($id)) {
			if(isset($metadata[$key])) $metadata[$key][$item] = $value;
			else $metadata[$key] = [$item=>$value];
			return $this->SetMetaData($id, $metadata, $defaultValue);
		} else {
			$params = [];
			foreach ($metadata as $k => $md) {
                if(isset($md[$key])) $md[$key][$item] = $value;
                else $md[$key] = [$item=>$value];
				$params[] = [":Id" => $k, "MetaData" => Convert::ToJson($md)];
			}
			return $this->Update("Id=:Id", $params, $defaultValue);
		}
	}
	/**
	 * To forget the metadata Array value by the specific $key
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $key
	 * @param mixed $defaultValue
	 */
	public function PopMetaItemValue(array|int|string|null $id, $key, $item, $defaultValue = false)
	{
		$metadata = self::GetMetaData($id, [])??[];
		if (isStatic($id)) {
			unset($metadata[$key][$item]);
			return $this->SetMetaData($id, $metadata, $defaultValue);
		} else {
			$params = [];
			foreach ($metadata as $k => $md) {
			    unset($md[$key][$item]);
				$params[] = [":Id" => $k, "MetaData" => Convert::ToJson($md)];
			}
			return $this->Update("Id=:Id", $params, $defaultValue);
		}
	}
	/**
	 * To check if the metadata Array column has the specific $key or not
	 * @param array|int|string|null $id An array of specific ids or an specific id, or null to apply globally
	 * @param mixed $key
	 * @return bool
	 */
	public function HasMetaItemValue(array|int|string|null $id, $key, $item)
	{
		return self::GetMetaItemValue($id, $key, $item) ? true : false;
	}

	public function GetColumnName($name)
	{
		return $this->Name . "." . $this->DataBase->StartWrap . $name . $this->DataBase->EndWrap;
	}
}