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
	public $Items = [];

	/**
	 * The Date Time Zone
	 * @var string
	 * @category Time
	 */
	public $DateTimeZone = "UTC";
	/**
	 * The Date Time Locale
	 * @var string
	 * @category Time
	 */
	public $DateTimeLocale = "en-US";
	/**
	 * The Date Time Format
	 * @var string
	 * @example: "Y-m-d H:i:s" To show like 2018-08-10 14:46:45
	 * @category Time
	 */
	public $DateTimeFormat = "Y-m-d H:i:s";
	/**
	 * Current Date Time
	 * @var string
	 * @category Time
	 */
	public $CurrentDateTime = "now";
	/**
	 * Date Time Stamp Seconds Offset (TSO)
	 * @var int
	 * @category Time
	 */
	public $TimeStampOffset = 0;

	/**
	 * Source to get the version of latest aseqbase release
	 * @field path
	 * @var string
	 * @category Update
	 */
	public $CheckVersionSourcePath = "http://aseqbase.ir/api/information/version.php";

	/**
	 * Source to get the latest version of aseqbase path
	 * @field path
	 * @var string
	 * @category Update
	 */
	public $LatestVersionSourcePath = "http://aseqbase.ir/api/information/link.php?req=download&tag=latest";

	/**
	 * The period of caching, Type
	 * @template null  To don't remove caches
	 * @template "v"   To remove caches with each kernel updates
	 * @template "u"   To remove caches each 1 millisecond of each second
	 * @template "s"   To remove caches each 1 second of each minute
	 * @template "i"   To remove caches each 1 minute of each hour
	 * @template "H"   To remove caches each 1 hour of each day
	 * @template "j"   To remove caches each 1 day of each month
	 * @template "m"   To remove caches each 1 month of each year
	 * @template "Y"   To remove caches each 1 year
	 * @template "z"   To remove caches each 1 day of each year
	 * @template "W"   To remove caches each 1 week of each year
	 * @var string|null
	 * @category Optimization
	 */
	public $CachePeriod = "v";

	/**
	 * The level of reporting: 0: Not report; 1: Report Errors; 2: Report Warnings; 3: Report Infos
	 * @var bool
	 * @category Optimization
	 */
	public $ReportLevel = 2;

	/**
	 * Allow to reduce size of documents for increasing site speed
	 * @var bool
	 * @category Optimization
	 */
	public $AllowReduceSize = true;
	/**
	 * Allow to analyze all text and signing them, to improve the website's SEO
	 * @var bool
	 * @category Optimization
	 */
	public $AllowTextAnalyzing = false;
	/**
	 * Allow to analyze all text and linking contents to their called names or titles, to improve the website's SEO
	 * @var bool
	 * @category Optimization
	 */
	public $AllowContentReferring = false;
	/**
	 * Allow to analyze all text and linking categories to their called names or titles, to improve the website's SEO
	 * @var bool
	 * @category Optimization
	 */
	public $AllowCategoryReferring = false;
	/**
	 * Allow to analyze all text and linking tags to their called names or titles, to improve the website's SEO
	 * @var bool
	 * @category Optimization
	 */
	public $AllowTagReferring = false;
	/**
	 * Allow to analyze all text and linking users to their called names, to improve the website's SEO
	 * @var bool
	 * @category Optimization
	 */
	public $AllowUserReferring = false;

	/**
	 * Encrypt the name of elements and everything to reduce the possibility of scraping data by robots
	 * @var bool
	 * @category Security
	 */
	public $EncryptNames = true;
	/**
	 * Default site key for ReCaptcha
	 * @var string
	 * @category Security
	 */
	public $ReCaptchaSiteKey = null;

	/**
	 * Allow Selecting on Page
	 * @var bool
	 * @category Security
	 */
	public $AllowSelecting = true;
	/**
	 * Allow ContextMenu on Page
	 * @var bool
	 * @category Security
	 */
	public $AllowContextMenu = true;

	/**
	 * The accessibility mode: 1 for whitelisted IPs, -1 for blacklisted IPs
	 * @default null
	 * @field short
	 * @var mixed
	 * @category Security
	 */
	public $AccessMode = null;
	/**
	 * Patterns to detect IPs
	 * @default []
	 * @field array
	 * @var array<string>
	 * @category Security
	 */
	public $AccessPatterns = array();


	/**
	 * The minimum file size available to uploud
	 * @var int
	 * @category Security
	 */
	public $MinimumFileSize = 1000;
	/**
	 * The minimum file size available to uploud
	 * @var int
	 * @category Security
	 */
	public $MaximumFileSize = 50000000;
	/**
	 * Acceptable image formats
	 * @field array
	 * @var array<string>
	 * @category Security
	 */
	public $AcceptableImageFormats = [".png", ".jpg", ".jpeg", ".jiff", ".gif", ".tif", ".tiff", ".bmp", ".ico", ".svg"];
	/**
	 * Acceptable audio formats
	 * @field array
	 * @var array<string>
	 * @category Security
	 */
	public $AcceptableAudioFormats = [".wav", ".mp3", ".aac", ".amr", ".ogg", ".flac", ".wma", ".m4a"];
	/**
	 * Acceptable video formats
	 * @field array
	 * @var array<string>
	 * @category Security
	 */
	public $AcceptableVideoFormats = [".mpg", ".mpeg", ".mp4", ".avi", ".mkv", ".mov", ".wmv", ".flv", ".webm"];
	/**
	 * Acceptable document formats
	 * @field array
	 * @var array<string>
	 * @category Security
	 */
	public $AcceptableDocumentFormats = [".txt", ".rtf", ".pdf", ".doc", ".docx", ".ppt", ".pptx", ".sls", ".slx", ".csv", ".tsv"];
	/**
	 * Acceptable document formats
	 * @field array
	 * @var array<string>
	 * @category Security
	 */
	public $AcceptableFileFormats = [".zip", ".rar"];

	/**
	 * An array of RegExPattern=>Replacement to convert all requested table names
	 * @var array
	 */
	public $DataTableNameConvertors = [];

	/**
	 * 0: Not show Errors; 1: To show Errors
	 * @field int
	 * @var int|null
	 * @category Debug
	 */
	public $DisplayError = 1;
	/**
	 * 0: Not show startup Errors; 1: To show startup Errors
	 * @field int
	 * @var int|null
	 * @category Debug
	 */
	public $DisplayStartupError = 1;
	/**
	 * E_ error flags
	 * @field int
	 * @var int|null
	 * @category Debug
	 */
	public $ReportError = E_ALL;

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

		ini_set('display_errors', $this->DisplayError);
		ini_set('display_startup_errors', $this->DisplayStartupError);
		error_reporting($this->ReportError);
	}

	public function __get($name)
	{
		return $this->Items[$this->PropertyName($name)] ?? null;
	}
	public function __set($name, $value)
	{
		$this->Items[$this->PropertyName($name)] = $value;
	}
	public function PropertyName($name)
	{
		return preg_replace("/\W+/", "", strToProper($name));
	}


	public function HardKey($seed)
	{
		return $seed . $this->SecretKey . $seed;
	}

	public function IsLatestVersion(): bool|null
	{
		return \_::$Version >= $this->GetLatestVersion();
	}
	public function GetLatestVersion(): float|null
	{
		return floatval(receiveGet($this->CheckVersionSourcePath));
	}
	public function GetLatestVersionPath(): string|null
	{
		return receiveGet($this->LatestVersionSourcePath);
	}
	public function GetAcceptableFormats(?string $type = null)
	{
		switch (strtolower($type ?? "")) {
			case "image":
			case "images":
				return $this->AcceptableImageFormats;
			case "video":
			case "videos":
				return $this->AcceptableVideoFormats;
			case "audio":
			case "audios":
				return $this->AcceptableAudioFormats;
			case "doc":
			case "docs":
			case "document":
			case "documents":
				return $this->AcceptableDocumentFormats;
			case "file":
			case "files":
				return $this->AcceptableFileFormats;
			default:
				return array_merge($this->AcceptableImageFormats, $this->AcceptableAudioFormats, $this->AcceptableVideoFormats, $this->AcceptableDocumentFormats, $this->AcceptableFileFormats);
		}
	}
}