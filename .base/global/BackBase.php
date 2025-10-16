<?php
library("Revise");
library("DataBase");
library("DataTable");
library("Query");
library("Session");
library("Translate");
library("Contact");
library("HashCrypt");

/**
 *All the basic back-end libraries and services 
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/BackBase See the Structures Documentation
 */
class BackBase
{
	/**
	 * A simple library to Session management
	 * @internal
	 */
	public \MiMFa\Library\Cryptograph $Cryptograph;
	/**
	 * A simple library to Session management
	 * @internal
	 */
	public \MiMFa\Library\DataBase $DataBase;
	/**
	 * A simple library to Session management
	 * @internal
	 */
	public \MiMFa\Library\Session $Session;
	/**
	 * A simple library to Session management
	 * @internal
	 */
	public \MiMFa\Library\Query $Query;
	/**
	 * A simple library to Session management
	 * @internal
	 */
	public \MiMFa\Library\Translate $Translate;

	public function __construct()
	{
		\MiMFa\Library\Revise::Load($this);

		$this->DataBase = new \MiMFa\Library\DataBase();
		$this->Cryptograph = new \MiMFa\Library\HashCrypt();
		$this->Session = new \MiMFa\Library\Session(table("Session", source:$this->DataBase), $this->Cryptograph);
		$this->Query = new \MiMFa\Library\Query($this->DataBase);
		$this->Translate = new \MiMFa\Library\Translate(table("Translate_Lexicon", prefix:false, source:$this->DataBase));
		
		$this->Translate->AutoUpdate = \_::$Config->AutoUpdateLanguage;
		$this->Translate->Initialize(
			\_::$Config->DefaultLanguage,
			\_::$Config->DefaultDirection,
			\_::$Config->Encoding,
			\_::$Config->AllowTranslate && \_::$Config->CacheLanguage
		);
	}
	
	public function GetAccessCondition($checkStatus = true, $checkAccess = true, $tableName = "")
	{
		$tableName = $tableName?$tableName.".":"";
		$cond = [];
		if($checkStatus) $cond[] = "{$tableName}Status IS NULL OR {$tableName}Status IN ('','1',1)";
		if($checkAccess) $cond[] = \_::$User->GetAccessCondition($tableName);
		if(\_::$Config->AllowTranslate) $cond[] = $this->Translate->GetAccessCondition($tableName);
		return " (" . join(") AND (", $cond).")";
	}
}