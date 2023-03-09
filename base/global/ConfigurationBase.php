<?php
abstract class ConfigurationBase extends Base {
	public $PathKey = "path";
	public $CheckVersionSourcePath = "http://aseqbase.ir/api/information/version.php";
	public $LatestVersionSourcePath = "http://aseqbase.ir/api/information/link.php?req=download&tag=latest";

	public $AllowCache = true;
	public $CachePeriod = "s";

	public $AllowReduceSize = true;
	public $AllowEncryptNames = true;

	/**
     * The status of all server response: 400, 404, 500, etc.
	 * @var mixed
	 */
	public $StatusMode = null;
	/**
     * The accessibility mode: 1 for whitelisted IPs, -1 for blacklisted IPs
	 * @var mixed
	 */
	public $AccessMode = null;
	/**
	 * Patterns to detect IPs
	 * @var array
	 */
	public $AccessPatterns = array();
	public $RestrictionContent = "Unfortunately you have no access to the site now!<br>Please try a few minute later...";

	public $Encoding = "utf-8";
	public $SecretKey = null;
	/**
     * 0: Not show Errors; 1: To show Errors
	 * @var int
	 */
	public $DisplayError = null;
	/**
     * 0: Not show startup Errors; 1: To show startup Errors
	 * @var int
	 */
	public $DisplayStartupError = null;
	/**
	 * E_ error flags
	 * @var int
	 */
	public $ReportError = null;

	public $DataBaseEncoding = "utf-8";
	public $DataBaseType = 'mysqli';
	public $DataBaseHost = 'localhost';
	public $DataBaseUser = null;
	public $DataBasePassword = null;
	public $DataBaseName = 'localhost';
	public $DataBasePrefix = 'base_';
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