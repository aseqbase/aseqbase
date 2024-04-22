<?php
/**
 *All the basic website and libraries configurations
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Structures See the Structures Documentation
 */
abstract class ConfigurationBase extends Base {
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
     * A key to use for sending the requested virtual path of website, leave null to set as special
     * @var string|null
     * @category General
     */
	public $PathKey = null;

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
     * Empty array causing to pass all patterns to the ViewName or DefaultViewName or "main" view
     * @internal
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
        "/^(category|cat)(\/|\?|$)/i"=>"category",
        "/^user(\/|\?|$)/i"=>"user",
        "/^usergroup(\/|\?|$)/i"=>"usergroup",
        "/^(public|private)(\/|\?|$)/i"=>"run"
    );
    /**
     * The requested view key to handle the virtual pathes, leave null to set as special
     * @var string|null
     * @category General
     */
	public $ViewHandlerKey = null;


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
	public $CachePeriod = "Y";

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
     * A special key for yhis website, be sure to change this
     * @field password
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
     * The minimum group of banned user
     * @var int
     * @category Security
     */
    public $BanAccess = -1;
	/**
     * Default accessibility for the guests
     * @var int
     * @category Security
     */
    public $GuestAccess = 0;
	/**
     * The lowest group that registered user will be on
     * @var int
     * @category Security
     */
    public $UserAccess = 1000000000;
	/**
     * The lowest group of administrators
     * @var int
     * @category Security
     */
    public $AdminAccess = 10;
	/**
     * The highest group of administrators
     * @var int
     * @category Security
     */
    public $SuperAccess = 1;
	/**
     * Minimum accessibility needs to visit the website
     * @var int
     * @category Security
     */
    public $VisitAccess = 0;
	/**
     * The status of all server response: 400, 404, 500, etc.
	 * @var mixed
     * @category Security
     */
	public $StatusMode = null;
	/**
     * The accessibility mode: 1 for whitelisted IPs, -1 for blacklisted IPs
     * @field short
     * @var mixed
     * @category Security
     */
	public $AccessMode = null;
	/**
	 * Patterns to detect IPs
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
	public $RestrictionViewName = "restriction";

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
    public $AcceptableImageFormats = [".png",".jpg",".jpeg",".jiff",".gif",".tif",".tiff",".bmp",".ico",".svg"];
	/**
     * Acceptable audio formats
     * @field array
     * @var array<string>
     * @category Security
     */
    public $AcceptableAudioFormats = [".wav",".mp3",".aac",".amr",".ogg",".flac",".wma",".m4a"];
	/**
     * Acceptable video formats
     * @field array
     * @var array<string>
     * @category Security
     */
    public $AcceptableVideoFormats = [".mpg",".mpeg", ".mp4",".avi",".mkv",".mov",".wmv",".flv",".webm"];
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
	public $DisplayError = null;
	/**
     * 0: Not show startup Errors; 1: To show startup Errors
     * @field int
     * @var int|null
     * @category Debug
     */
	public $DisplayStartupError = null;
	/**
     * E_ error flags
     * @field int
     * @var int|null
     * @category Debug
	 */
	public $ReportError = null;
	/**
     * Database Errors
     * @field int
     * @var int|null
     * @category Debug
	 */
	public $DataBaseError = null;

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
     * The database HostName
     * @var string
     * @category DataBase
     */
	public $DataBaseHost = 'localhost';
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
     * Add the website name to the selected DataBasePrefix for strongest privacy
     * @var string
     * @category DataBase
     */
	public $DataBaseAddNameToPrefix = true;

	public function __construct(){
        parent::__construct(false);
        $sp = str_replace(".","", getClientIP()."");
        if(is_null($this->PathKey)) $this->PathKey = "path_".$sp;
        if(is_null($this->ViewHandlerKey)) $this->ViewHandlerKey = "view_".$sp;
        if($this->DataBaseAddNameToPrefix) $this->DataBasePrefix .= preg_replace("/\W/i","_",$GLOBALS["ASEQBASE"]??"qb")."_";
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
	public function GetAcceptableFormats(string $type = null){
        switch (strtolower($type??""))
        {
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

    public function GetDateTime($dateTime = null, DateTimeZone|null $dateTimeZone = null){
        return (is_string($dateTime) || is_null($dateTime))?new DateTime($dateTime??$this->CurrentDateTime, $dateTimeZone??new DateTimeZone($this->DateTimeZone)):$dateTime;
    }
    public function GetFormattedDateTime(string|null $dateTimeFormat = null, $dateTime = null, DateTimeZone|null $dateTimeZone = null){
        return $this->GetDateTime($dateTime, $dateTimeZone)->format($dateTimeFormat??$this->DateTimeFormat);
    }
    public function ToShownDateTime($dateTime = null, DateTimeZone|null $dateTimeZone = null){
        return (new DateTime())->setTimestamp($this->GetDateTime($dateTime, $dateTimeZone)->getTimestamp()+$this->TimeStampOffset);
    }
    public function FromShownDateTime($dateTime = null, DateTimeZone|null $dateTimeZone = null){
        return (new DateTime())->setTimestamp($this->GetDateTime($dateTime, $dateTimeZone)->getTimestamp()-$this->TimeStampOffset);
    }
    public function ToShownFormattedDateTime($dateTime = null, DateTimeZone|null $dateTimeZone = null, string|null $dateTimeFormat = null){
        return (new DateTime())->setTimestamp($this->GetDateTime($dateTime, $dateTimeZone)->getTimestamp()+$this->TimeStampOffset)
            ->format($dateTimeFormat??$this->DateTimeFormat);
    }
    public function FromShownFormattedDateTime($dateTime = null, DateTimeZone|null $dateTimeZone = null, string|null $dateTimeFormat = null){
        return (new DateTime())->setTimestamp($this->GetDateTime($dateTime, $dateTimeZone)->getTimestamp()-$this->TimeStampOffset)
            ->format($dateTimeFormat??$this->DateTimeFormat);
    }
}
?>