<?php
/**
 *All the basic website and libraries configurations
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/mimfa/aseqbase
 *@link https://github.com/mimfa/aseqbase/wiki/Structures See the Structures Documentation
 */
abstract class ConfigurationBase extends Base {
    /**
     * The website Encoding
     * @var string
     * @category General
     */
    public $Encoding = "utf-8";
	/**
     * A key to use for sending the requested virtual path of website
     * @var string
     * @category General
     */
	public $PathKey = "path";

    /**
     * The website view name
     * @var string
     * @category General
     */
	public $ViewName = "main";
    /**
     * The view name to manage the Root url of website
     * @var string
     * @category General
     */
	public $HomeViewName = "main";
    /**
     * The view name to show pages
     * @var string
     * @category General
     */
	public $DefaultViewName = "main";
    /**
     * An array of all patterns=>handler view names to handle the virtual pathes
     * @var array<string,string>
     * @category General
     */
	public $Handlers = array(
        "/^post(\/|\?|$)/i"=>"post",
        "/^page(\/|\?|$)/i"=>"page",
        "/^query(\/|\?|$)/i"=>"query",
        "/^search(\/|\?|$)/i"=>"search",
        "/^tag(\/|\?|$)/i"=>"tag",
        "/^sign(\/|\?|$)/i"=>"sign",
        "/^category(\/|\?|$)/i"=>"category"
    );


	/**
     * Source to get the version of latest aseqbase release
     * @var string
     * @category Update
     */
	public $CheckVersionSourcePath = "http://aseqbase.ir/api/information/version.php";

	/**
     * Source to get the latest version of aseqbase path
     * @var string
     * @category Update
     */
	public $LatestVersionSourcePath = "http://aseqbase.ir/api/information/link.php?req=download&tag=latest";

	/**
	 * Allow cache data for increasing loading speed
	 * @var bool
     * @category Optimization
     */
	public $AllowCache = true;
	/**
	 * The period of caching, Type
     * u:   for each 1 millisecond of each second
     * s:   for each 1 second of each minute
     * i:   for each 1 minute of each hour
     * H:   for each 1 hour of each day
     * j:   for each 1 day of each month
     * m:   for each 1 month of each year
     * Y:   for each 1 year
     *
     * z:   for each 1 day of each year
     * W:   for each 1 week of each year
	 * @var string
     * @category Optimization
     */
	public $CachePeriod = "Y-z-H-i-s";

	/**
     * Allow to reduce size of documents for increasing site speed
     * @var bool
     * @category Optimization
     */
	public $AllowReduceSize = true;
	/**
     * Allow to analyze all text and signing them, to improve the website SEO
     * @var bool
     * @category Optimization
     */
	public $AllowTextAnalyzing = true;
	/**
     * Allow to translate all text by internal algorithms
     * @var bool
     * @category Optimization
     */
	public $AllowTranslate = false;
    
	/**
     * A special key for yhis website
     * @var string
     * @category Security
     */
	public $SecretKey = '~a!s@e#q$b%a^s&e*';
    /**
     * The prefix to use in sessions and public whare!
     * @var string
     * @category DataBase
     */
	public $PublicPrefix = 'qb';
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
     * Guest Access
     * @var int
     * @category Security
     */
    public $GuestAccess = 0;
	/**
     * The first group the registered user will be on
     * @var int
     * @category Security
     */
    public $RegisteredGroup = 1;
	/**
     * The status of all server response: 400, 404, 500, etc.
	 * @var mixed
     * @category Security
     */
	public $StatusMode = null;
	/**
     * The accessibility mode: 1 for whitelisted IPs, -1 for blacklisted IPs
	 * @var mixed
     * @category Security
     */
	public $AccessMode = null;
	/**
	 * Patterns to detect IPs
	 * @var array<string>
     * @category Security
     */
	public $AccessPatterns = array();
	/**
     * Default message to show when restriction
     * @var string
     * @category Security
     */
	public $RestrictionContent = "Unfortunately you have no access to the site now!<br>Please try a few minute later...";
    /**
     * The default view name to show when restriction
     * @var string
     * @category Security
     */
	public $RestrictionViewName = "restriction";

	/**
     * 0: Not show Errors; 1: To show Errors
	 * @var int|null
     * @category Debug
     */
	public $DisplayError = null;
	/**
     * 0: Not show startup Errors; 1: To show startup Errors
     * @var int|null
     * @category Debug
     */
	public $DisplayStartupError = null;
	/**
     * E_ error flags
     * @var int|null
     * @category Debug
	 */
	public $ReportError = null;
	/**
     * Database Errors
     * @var int|null
     * @category Debug
	 */
	public $DataBaseError = null;

    /**
     * The database default Encoding
     * @var string
     * @category DataBase
     */
	public $DataBaseEncoding = "utf-8";
    /**
     * The database Type
     * @var string
     * @category DataBase
     */
	public $DataBaseType = 'mysql';
    /**
     * The database HostName
     * @var string
     * @category DataBase
     */
	public $DataBaseHost = 'localhost';
    /**
     * The database UserName
     * @var string
     * @category DataBase
     */
	public $DataBaseUser = null;
    /**
     * The database Password
     * @var string
     * @category DataBase
     */
	public $DataBasePassword = null;
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
	public $DataBasePrefix = 'qb_';
    /**
     * Add the websie name to the selected DataBasePrefix for strongest privacy
     * @var string
     * @category DataBase
     */
	public $DataBaseAddNameToPrefix = true;

	public function __construct(){
        if($this->DataBaseAddNameToPrefix) $this->DataBasePrefix .= preg_replace("/\W/i","_",$GLOBALS["ASEQBASE"])."_";
    }


	public function IsLatestVersion():bool|null{
		return \_::$VERSION >= $this->GetLatestVersion();
    }
	public function GetLatestVersion():float|null{
		return floatval(GET($this->CheckVersionSourcePath));
    }
	public function GetLatestVersionPath():string|null{
		return GET($this->LatestVersionSourcePath);
    }
}
?>