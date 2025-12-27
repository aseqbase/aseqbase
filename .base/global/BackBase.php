<?php
library("Revise");
library("HashCrypt");
library("DataBase");
library("DataTable");
library("Query");

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
	 * A special key for the website, be sure to change this
	 * @field password
	 * @var string
	 * @category Security
	 */
	public $SecretKey = '~a!s@e#q$b%a^s&e*';
	/**
	 * A special soft key for the default cryption, be sure to change this
	 * A special key generator for the website, override this for more security
	 * @field password
	 * @var string
	 * @category Security
	 */
	public $SoftKey = null;
	/**
	 * Salt and pepper for more strong encryptions, Shake them!
	 * @var string
	 * @category Security
	 */
	public $EncryptSampleChars = "4wCpq01Ikl2NVmSDKFPJ7fXYijTzAUbE5WxgRuvGQZ3yBo6ncdeLMrst_HhO89a";
	/**
	 * Salt and pepper picker
	 * @var int
	 * @category Security
	 */
	public $EncryptSampler = 3;
	/**
	 * Insert indexer for salt and pepper
	 * @var int
	 * @category Security
	 */
	public $EncryptIndexer = 7;


	/**
	 * A simple library to Session management
	 * @internal
	 */
	public \MiMFa\Library\DataBase $DataBase;
	/**
	 * Database Errors
	 * @field int
	 * @var int|null
	 * @category Debug
	 */
	public $DataBaseError = 3;

	/**
	 * The database default Encoding
	 * @var string
	 * @category DataBase
	 */
	public $DataBaseEncoding = "utf8";
	/**
	 * The database Type
	 * @var string
	 * @category DataBase
	 */
	public $DataBaseType = 'mysql';
	/**
	 * Checking and somewhere changing and/or normalizing the values before set on database
	 * @var bool
	 * @category DataBase
	 */
	public $DataBaseValueNormalization = true;
	/**
	 * The database HostName or IP
	 * @var string
	 * @category DataBase
	 */
	public $DataBaseHost = 'localhost';
	/**
	 * The database Port or null for default
	 * @var string
	 * @category DataBase
	 */
	public $DataBasePort = null;
	/**
	 * The database UserName
	 * @field password
	 * @var string
	 * @category DataBase
	 */
	public $DataBaseUser = 'root';
	/**
	 * The database Password
	 * @field password
	 * @var string
	 * @category DataBase
	 */
	public $DataBasePassword = '';
	/**
	 * The database Name
	 * @var string
	 * @category DataBase
	 */
	public $DataBaseName = 'localhost';
	/**
	 * The database tables Prefix
	 * @var string
	 * @category DataBase
	 */
	public $DataBasePrefix = 'aseq_';
	/**
	 * Add the website name to the selected DataBasePrefix for strongest privacy
	 * @var string
	 * @category DataBase
	 */
	public $DataBaseAddNameToPrefix = false;

	/**
	 * A simple library to Query management
	 * @internal
	 */
	public \MiMFa\Library\Query $Query;

	public function __construct()
	{
		\MiMFa\Library\Revise::Load($this);

		if (!$this->SoftKey)
			$this->SoftKey = $this->SecretKey;
		elseif (!$this->SecretKey)
			$this->SecretKey = $this->SoftKey;

		if ($this->DataBaseAddNameToPrefix)
			$this->DataBasePrefix .= preg_replace("/\W/i", "_", \_::$Address->Name ?? "qb") . "_";
		$this->DataBase = new \MiMFa\Library\DataBase(
			$this->DataBaseType,
			$this->DataBaseHost,
			$this->DataBasePort,
			$this->DataBaseName,
			$this->DataBaseUser,
			$this->DataBasePassword,
			$this->DataBaseEncoding
		);
		$this->DataBase->ReportLevel = $this->DataBaseError;
		$this->DataBase->AllowNormalization = $this->DataBaseValueNormalization;
		$this->Cryptograph = new \MiMFa\Library\HashCrypt();
		$this->Query = new \MiMFa\Library\Query($this->DataBase);
	}

	public function HardKey($seed)
	{
		return $seed . $this->SecretKey . $seed;
	}

}