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
	public \MiMFa\Library\DataBase $DataBase;

	public function __construct(\MiMFa\Library\DataBase $dataBase, $name)
	{
		$this->DataBase = $dataBase;
		foreach (\_::$Config->DataTableNameConvertors as $key => $value)
			$name = preg_replace($key, $value, $name);
		$this->Name = $name;
	}

	public function SelectValue($columns = "*", $condition = null, $params = [], $defaultValue = null)
	{
		return $this->DataBase->SelectValue($this->Name, $columns, $condition, $params, $defaultValue);
	}
	public function SelectValueQuery($columns = "*", $condition = null)
	{
		return $this->DataBase->SelectValueQuery($this->Name, $columns, $condition);
	}

	public function Select($columns = "*", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->DataBase->Select($this->Name, $columns, $condition, $params, $defaultValue);
	}
	public function SelectQuery($columns = "*", $condition = null)
	{
		return $this->DataBase->SelectQuery($this->Name, $columns, $condition);
	}

	public function SelectRow($columns = "*", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->DataBase->SelectRow($this->Name, $columns, $condition, $params, $defaultValue);
	}
	public function SelectRowQuery($columns = "*", $condition = null)
	{
		return $this->DataBase->SelectRowQuery($this->Name, $columns, $condition);
	}

	public function SelectColumn($columns = "*", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->DataBase->SelectColumn($this->Name, $columns, $condition, $params, $defaultValue);
	}
	public function SelectColumnQuery($columns = "*", $condition = null)
	{
		return $this->DataBase->SelectColumnQuery($this->Name, $columns, $condition);
	}

	public function SelectPairs($key = "`Id`", $value = "`Name`", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->DataBase->SelectPairs($this->Name, $key, $value, $condition, $params, $defaultValue);
	}
	public function SelectPairsQuery($key = "`Id`", $value = "`Name`", $condition = null)
	{
		return $this->DataBase->SelectPairsQuery($this->Name, $key, $value, $condition);
	}

	public function Insert($params = [], $defaultValue = false)
	{
		return $this->DataBase->Insert($this->Name, $params, $defaultValue);
	}
	public function InsertQuery(&$params)
	{
		return $this->DataBase->InsertQuery($this->Name, $params);
	}

	public function Replace($condition = null, $params = [], $defaultValue = false)
	{
		return $this->DataBase->Replace($this->Name, $condition, $params, $defaultValue);
	}
	public function ReplaceQuery($condition, &$params)
	{
		return $this->DataBase->ReplaceQuery($this->Name, $condition, $params);
	}

	public function Update($condition = null, $params = [], $defaultValue = false)
	{
		return $this->DataBase->Update($this->Name, $condition, $params, $defaultValue);
	}
	public function UpdateQuery($condition, &$params)
	{
		return $this->DataBase->UpdateQuery($this->Name, $condition, $params);
	}

	public function Delete($condition = null, $params = [], $defaultValue = false)
	{
		return $this->DataBase->Delete($this->Name, $condition, $params, $defaultValue);
	}
	public function DeleteQuery($condition = null)
	{
		return $this->DataBase->DeleteQuery($this->Name, $condition);
	}

	public function Count($col = "`Id`", $condition = null, $params = [])
	{
		return $this->DataBase->Count($this->Name, $col, $condition, $params);
	}
	public function Sum($col = "`Id`", $condition = null, $params = [])
	{
		return $this->DataBase->Sum($this->Name, $col, $condition, $params);
	}
	public function Average($col = "`Id`", $condition = null, $params = [])
	{
		return $this->DataBase->Average($this->Name, $col, $condition, $params);
	}
	public function Max($col = "`Id`", $condition = null, $params = [])
	{
		return $this->DataBase->Max($this->Name, $col, $condition, $params);
	}
	public function Min($col = "`Id`", $condition = null, $params = [])
	{
		return $this->DataBase->Min($this->Name, $col, $condition, $params);
	}

	public function Exists($col = null, $condition = null, $params = [])
	{
		return $this->DataBase->Exists($this->Name, $col, $condition, $params);
	}
}

?>