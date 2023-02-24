<?php
abstract class ConfigurationBase extends \MiMFa\Base{
	public $PathKey = "path";

	public $AllowCache = false;
	public $CachePeriod = "s";

	public $AllowReduceSize = true;
	public $AllowEncryptNames = true;

	//The status of all server response: 400, 404, 500, etc.
	public $StatusMode = null;
	//The accessibility mode: 1 for whitelist IPs, -1 for blacklisted IPs
	public $AccessMode = null;
	public $AccessPatterns = array();
	public $RestrictionContent = "Unfortunately you have no access to the site now!<br>Please try a few minute later...";

	public $Encoding = "utf-8";
	public $SecretKey = null;
	public $DisplayError = null;
	public $DisplayStartupError = null;
	public $ReportError = null;

	public $DataBaseEncoding = "utf-8";
	public $DataBaseType = 'mysqli';
	public $DataBaseHost = 'localhost';
	public $DataBaseUser = null;
	public $DataBasePassword = null;
	public $DataBaseName = 'localhost';
	public $DataBasePrefix = 'base_';
}
?>