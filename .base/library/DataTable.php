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
		foreach (\_::$Config->DataTableNameConvertors as $key => $value) $name = preg_replace($key, $value, $name);
		$this->Name = $name;
	}

	public function DoSelectValue($columns = "*", $condition = null, $params = [], $defaultValue = null)
	{
		return $this->DataBase->DoSelectValue($this->Name, $columns, $condition, $params, $defaultValue);
	}
	public function MakeSelectValueQuery($columns = "*", $condition = null)
	{
		return $this->DataBase->MakeSelectValueQuery($this->Name, $columns, $condition);
	}

	public function DoSelect($columns = "*", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->DataBase->DoSelect($this->Name, $columns, $condition, $params, $defaultValue);
	}
	public function MakeSelectQuery($columns = "*", $condition = null)
	{
		return $this->DataBase->MakeSelectQuery($this->Name, $columns, $condition);
	}

	public function DoSelectRow($columns = "*", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->DataBase->DoSelectRow($this->Name, $columns, $condition, $params, $defaultValue);
	}
	public function MakeSelectRowQuery($columns = "*", $condition = null)
	{
		return $this->DataBase->MakeSelectRowQuery($this->Name, $columns, $condition);
	}

	public function DoSelectColumn($columns = "*", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->DataBase->DoSelectColumn($this->Name, $columns, $condition, $params, $defaultValue);
	}
	public function MakeSelectColumnQuery($columns = "*", $condition = null)
	{
		return $this->DataBase->MakeSelectColumnQuery($this->Name, $columns, $condition);
	}

	public function DoSelectPairs($key = "`Id`", $value = "`Name`", $condition = null, $params = [], $defaultValue = array())
	{
		return $this->DataBase->DoSelectPairs($this->Name, $key, $value, $condition, $params, $defaultValue);
	}
	public function MakeSelectPairsQuery($key = "`Id`", $value = "`Name`", $condition = null)
	{
		return $this->DataBase->MakeSelectPairsQuery($this->Name, $key, $value, $condition);
	}

	public function DoInsert($condition = null, $params = [], $defaultValue = false)
	{
		return $this->DataBase->DoInsert($this->Name, $condition, $params, $defaultValue);
	}
	public function MakeInsertQuery($condition, &$params)
	{
		return $this->DataBase->MakeInsertQuery($this->Name, $condition, $params);
	}

	public function DoReplace($condition = null, $params = [], $defaultValue = false)
	{
		return $this->DataBase->DoReplace($this->Name, $condition, $params, $defaultValue);
	}
	public function MakeReplaceQuery($condition, &$params)
	{
		return $this->DataBase->MakeReplaceQuery($this->Name, $condition, $params);
	}

	public function DoUpdate($condition = null, $params = [], $defaultValue = false)
	{
		return $this->DataBase->DoUpdate($this->Name, $condition, $params, $defaultValue);
	}
	public function MakeUpdateQuery($condition, &$params)
	{
		return $this->DataBase->MakeUpdateQuery($this->Name, $condition, $params);
	}

	public function DoDelete($condition = null, $params = [], $defaultValue = false)
	{
		return $this->DataBase->DoDelete($this->Name, $condition, $params, $defaultValue);
	}
	public function MakeDeleteQuery($condition = null)
	{
		return $this->DataBase->MakeDeleteQuery($this->Name, $condition);
	}

	public function GetCount($col = "`Id`", $condition = null, $params = [])
	{
		return $this->DataBase->GetCount($this->Name, $col, $condition, $params);
	}
	public function GetSum($col = "`Id`", $condition = null, $params = [])
	{
		return $this->DataBase->GetSum($this->Name, $col, $condition, $params);
	}
	public function GetAverage($col = "`Id`", $condition = null, $params = [])
	{
		return $this->DataBase->GetAverage($this->Name, $col, $condition, $params);
	}
	public function GetMax($col = "`Id`", $condition = null, $params = [])
	{
		return $this->DataBase->GetMax($this->Name, $col, $condition, $params);
	}
	public function GetMin($col = "`Id`", $condition = null, $params = [])
	{
		return $this->DataBase->GetMin($this->Name, $col, $condition, $params);
	}

	public function Exists($col = null, $condition = null, $params = [])
	{
		return $this->DataBase->Exists($this->Name, $col, $condition, $params);
	}
}

?>