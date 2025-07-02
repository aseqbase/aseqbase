<?php
library("Revise");
/**
 *All the basic website and libraries configurations
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Structures See the Structures Documentation
 */
abstract class ConfigurationBase extends ArrayObject
{
     /**
      * The website Encoding
      * @var string
      * @category Language
      */
     public $Encoding = "utf-8";
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
      * The website view name
      * @var string
      * @default "main"
      * @category General
      */
     public $DefaultViewName = "main";
     /**
      * The view name to show pages
      * @var string
      * @default "main"
      * @category General
      */
     public $DefaultRouteName = "main";

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
     public $AllowTextAnalyzing = true;
     /**
      * Allow to analyze all text and linking contents to their called names or titles, to improve the website's SEO
      * @var bool
      * @category Optimization
      */
     public $AllowContentRefering = true;
     /**
      * Allow to analyze all text and linking categories to their called names or titles, to improve the website's SEO
      * @var bool
      * @category Optimization
      */
     public $AllowCategoryRefering = false;
     /**
      * Allow to analyze all text and linking tags to their called names or titles, to improve the website's SEO
      * @var bool
      * @category Optimization
      */
     public $AllowTagRefering = true;
     /**
      * Allow to analyze all text and linking users to their called names, to improve the website's SEO
      * @var bool
      * @category Optimization
      */
     public $AllowUserRefering = false;
     /**
      * Allow to translate all text by internal algorithms
      * @var bool
      * @category Language
      */
     public $AllowTranslate = false;
     /**
      * Allow to update the language by translator automatically
      * @var bool
      * @category Language
      */
     public $AutoUpdateLanguage = false;
     /**
      * Default language to translate all text by internal algorithms
      * @var string
      * @category Language
      */
     public $DefaultLanguage = null;
     /**
      * The website default Direction
      * @var string
      * @category Language
      */
     public $DefaultDirection = null;

     /**
      * A special key for the website, be sure to change this
      * @field password
      * @var string
      * @category Security
      */
     public $SecretKey = '~a!s@e#q$b%a^s&e*';
      /**
       * A special soft key for the default cryption, be sure to change this
       * @field password
       * @var string
       * @category Security
       */
     public $SoftKey = null;
     /**
      * A special key generator for the website, override this for more security
      */
     /**
      * Allow to set sessions on the client side (false for default)
      * @var bool
      * @category Security
      */
     public $ClientSession = true;
     /**
      * Encrypt all session keys (true for default)
      * @var bool
      * @category Security
      */
     public $EncryptSessionKey = false;
     /**
      * Encrypt all session values (true for default)
      * @var bool
      * @category Security
      */
     public $EncryptSessionValue = true;
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
      * Encrypt the name of elements and everything to reduce the possibility of scraping data by robots
      * @var bool
      * @category Security
      */
     public $EncryptNames = true;
     /**
      * Allow signing in and up to the guests
      * @var bool
      * @category Security
      */
     public $AllowSigning = true;
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
      * Default site key for ReCaptcha
      * @var string
      * @category Security
      */
     public $ReCaptchaSiteKey = null;
     /**
      * The minimum group id available to choice by user
      * @default 100
      * @var int
      * @category Security
      */
     public $MinimumGroupId = 100;
     /**
      * The maximum group id available to choice by user
      * @default 999999999
      * @var int
      * @category Security
      */
     public $MaximumGroupId = 999999999;
     /**
      * The minimum group of banned user
      * @default -1
      * @var int
      * @category Security
      */
     public $BanAccess = -1;
     /**
      * Default accessibility for the guests
      * @default 0
      * @var int
      * @category Security
      */
     public $GuestAccess = 0;
     /**
      * The lowest group that registered user will be on
      * @default 1
      * @var int
      * @category Security
      */
     public $UserAccess = 1;
     /**
      * The lowest group of administrators
      * @default 988888888
      * @var int
      * @category Security
      */
     public $AdminAccess = 988888888;
     /**
      * The highest group of administrators
      * @default 999999999
      * @var int
      * @category Security
      */
     public $SuperAccess = 999999999;
     /**
      * Minimum accessibility needs to visit the website
      * @default 0
      * @var int
      * @category Security
      */
     public $VisitAccess = 0;
     /**
      * The status of all server response: 400, 404, 500, etc.
      * @default null
      * @var mixed
      * @category Security
      */
     public $StatusMode = null;
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
      * Default message to show when restriction
      * @var string
      * @category Security
      */
     public $RestrictionContent = "Unfortunately, you have no access to the site now!<br>Please try a few minutes later...";
     /**
      * The default view name to show when restriction
      * @var string
      * @category Security
      */
     public $RestrictionRouteName = "403";

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
     public $DataBasePassword = 'root';
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
	 * An array of RegExPattern=>Replacement to convert all requested table names
      * @var array
	 */
	public $DataTableNameConvertors = [];

     public function __construct()
     {
          \MiMFa\Library\Revise::Load($this);
		ini_set('display_errors', $this->DisplayError);
		ini_set('display_startup_errors', $this->DisplayStartupError);
		error_reporting($this->ReportError);
		if($this->DataBaseAddNameToPrefix) $this->DataBasePrefix .= preg_replace("/\W/i", "_", \_::$Aseq->Name ?? "qb") . "_" ;
		if(!$this->SoftKey) $this->SoftKey = $this->SecretKey;
		elseif(!$this->SecretKey) $this->SecretKey = $this->SoftKey;
     }
	public function __get($name) {
        return $this[$this->PropertyName($name)]??null;
    }
    public function __set($name, $value) {
        $this[$this->PropertyName($name)] = $value;
    }
    public function PropertyName($name) {
        return preg_replace("/\W+/", "", strToProper($name));
    }

     public function HardKey($seed) { return $seed.$this->SecretKey.$seed; }

     public function IsLatestVersion(): bool|null
     {
          return \_::$Version >= $this->GetLatestVersion();
     }
     public function GetLatestVersion(): float|null
     {
          return floatval(\Req::ReceiveGet($this->CheckVersionSourcePath));
     }
     public function GetLatestVersionPath(): string|null
     {
          return \Req::ReceiveGet($this->LatestVersionSourcePath);
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
                    return [...$this->AcceptableImageFormats, ...$this->AcceptableAudioFormats, ...$this->AcceptableVideoFormats, ...$this->AcceptableDocumentFormats, ...$this->AcceptableFileFormats];
          }
     }
}