<?php namespace MiMFa\Library;
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

	protected function GetDatabase(){
		$this->DataBase->PreQuery = $this->PreQuery;
		$this->DataBase->MidQuery = $this->MidQuery;
		$this->DataBase->PostQuery = $this->PostQuery;
		return $this->DataBase;
	}
	
	public function Create($column_types = [], $configs = "ENGINE = InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_bin", $defaultValue = null)
	{
		return $this->GetDatabase()->CreateTable($this->Name, $column_types, $configs,$defaultValue);
	}
	public function CreateQuery($column_types = [], $configs = "ENGINE = InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_bin")
	{
		return $this->GetDatabase()->CreateTableQuery($this->Name, $column_types,  $configs);
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

	public function Replace($condition = null, $params = [], $defaultValue = false)
	{
		return $this->GetDatabase()->Replace($this->Name, $condition, $params, $defaultValue);
	}
	public function ReplaceQuery($condition, &$params)
	{
		return $this->GetDatabase()->ReplaceQuery($this->Name, $condition, $params);
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
		$this->MidQuery .= " AS ". $name;
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
		$this->MidQuery .= PHP_EOL."LEFT OUTER JOIN $dataTable->Name $dataTable->MidQuery ON ". ($on??(($this->AlternativeName??$this->Name).".{$dataTable->MainName}Id=".($dataTable->AlternativeName??$dataTable->Name).".Id"));
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
		$this->MidQuery .= PHP_EOL."INNER JOIN $dataTable->Name $dataTable->MidQuery ON ". ($on??(($this->AlternativeName??$this->Name).".{$dataTable->MainName}Id=".($dataTable->AlternativeName??$dataTable->Name).".Id"));
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
		$this->MidQuery .= PHP_EOL."RIGHT OUTER JOIN $dataTable->Name $dataTable->MidQuery ON ". ($on??(($this->AlternativeName??$this->Name).".{$dataTable->MainName}Id=".($dataTable->AlternativeName??$dataTable->Name).".Id"));
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
		$this->MidQuery .= PHP_EOL."CROSS JOIN $dataTable->Name $dataTable->MidQuery ON ". ($on??(($this->AlternativeName??$this->Name).".{$dataTable->MainName}Id=".($dataTable->AlternativeName??$dataTable->Name).".Id"));
		return $this;
	}

	/**
	 * To order all table rows
	 * @param mixed $columns An array of ["columnname"=>"desc|asc"] or a simple string of "columnname desc|asc, ..."
	 * @return DataTable
	 */
	public function OrderBy($columns = null, bool|null $ascending = null)
	{
		$this->PostQuery .= PHP_EOL.$this->GetDatabase()->OrderNormalization($columns, $ascending);
		return $this;
	}

	/**
	 * To limit the numbers of retrieved table rows
	 * @param string|int $limit The limitation number
	 * @return DataTable
	 */
	public function Limit($limit = 1)
	{
		$this->PostQuery .= PHP_EOL.$this->GetDatabase()->LimitNormalization($limit);
		return $this;
	}


	public function Exists($column = null, $condition = null, $params = [])
	{
		$result = null;
		try {
			$result = $this->GetDatabase()->FetchValueExecute($this->SelectValueQuery(is_null($column) ? "1" : $column, $condition), $params);
		} catch (\Exception $ex) {
		}
		return !is_null($result);
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
	public function First($columns = "*", $condition = null, $params = [])
	{
		return $this->GetDatabase()->TryFetchRow($this->SelectRowQuery($columns, $condition), $params);
	}
	public function Last($columns = "*", $condition = null, $params = [])
	{
		return $this->OrderBy(ascending:false)->First($columns, $condition, $params);
	}
	public function FirstValue($column = "Id", $condition = null, $params = [])
	{
		return $this->GetDatabase()->TryFetchValue($this->SelectValueQuery("FIRST($column)", $condition), $params);
	}
	public function LastValue($column = "Id", $condition = null, $params = [])
	{
		return $this->GetDatabase()->TryFetchValue($this->SelectValueQuery("LAST($column)", $condition), $params);
	}
	
	
	public function GetMetaValue($key, $condition = null, $params = [], $defaultValue = null)
	{
		$metadata = Convert::FromJson($this->SelectValue("MetaData", $condition, $params));
		return get($metadata, $key) ?? $defaultValue;
	}
	public function SetMetaValue($key, $value, $condition = null, $params = [], $defaultValue = false)
	{
		$metadata = Convert::FromJson($this->SelectValue("MetaData", $condition, $params));
		if(!$metadata) $metadata = [];
		$metadata[$key] = $value;
		$params["MetaData"] = Convert::ToJson($metadata);
		return $this->Update($condition, $params, $defaultValue);
	}
	public function ForgetMetaValue($key, $condition = null, $params = [], $defaultValue = false)
	{
		$metadata = Convert::FromJson($this->SelectValue("MetaData", $condition, $params));
		if(!$metadata) $metadata = [];
		unset($metadata[$key]);
		$params["MetaData"] = Convert::ToJson($metadata);
		return $this->Update($condition, $params, $defaultValue);
	}
}

?>