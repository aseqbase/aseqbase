<?php
library("Revise");

library("Router");
library("DataBase");
library("DataTable");
library("Query");
library("Session");
library("Translate");
library("Contact");
library("User");
/**
 *All the basic back-end libraries and services 
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/BackBase See the Structures Documentation
 */
abstract class BackBase
{
	/**
	 * An array of all method=>patterns=>handler view names to handle all type request virtual pathes
	 * Empty array causing to pass all patterns to the Handelers
	 * @internal
	 */
	public \MiMFa\Library\Router $Router;
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
	 * The user service
	 * @internal
	 */
	public \MiMFa\Library\User $User;
	/**
	 * A simple library to Session management
	 * @internal
	 */
	public \MiMFa\Library\Translate $Translate;

	public function __construct()
	{
		\MiMFa\Library\Revise::Load($this);

		$this->Router = new \MiMFa\Library\Router();
		$this->DataBase = new \MiMFa\Library\DataBase();
		$this->Cryptograph = new \MiMFa\Library\HashCrypt();
		$this->Session = new \MiMFa\Library\Session(table("Session", source:$this->DataBase), $this->Cryptograph);
		$this->Query = new \MiMFa\Library\Query($this->DataBase);
		$this->User = new \MiMFa\Library\User(table("User", source:$this->DataBase), table("UserGroup", source:$this->DataBase), $this->Session);
		$this->Translate = new \MiMFa\Library\Translate(table("Translate_Lexicon", prefix:false, source:$this->DataBase));

		\MiMFa\Library\Revise::Decode($this, getValid($this->User->GetGroup(), "MetaData" , "[]"));
		\MiMFa\Library\Revise::Decode($this, getValid($this->User->Get(), "MetaData" , "[]"));
	
		if (\_::$Config->AllowTranslate) {
			$this->Translate->Language = \_::$Config->DefaultLanguage;
			$this->Translate->Direction = \_::$Config->DefaultDirection;
			$this->Translate->Encoding = \_::$Config->Encoding;
			$this->Translate->AutoUpdate = \_::$Config->AutoUpdateLanguage;
			$this->Translate->Initialize($this->Session);
		}
	}
}
?>