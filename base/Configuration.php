<?php
class Configuration extends ConfigurationBase {
	public $Encoding = "utf-8";
	public $SecretKey = null;
	public $DisplayError = 1;
	public $DisplayStartupError = 1;
	public $ReportError = E_ALL;

	public $AllowCache = false;
	public $AllowReduceSize = true;
	public $AllowEncryptNames = true;

	//The status of all server response: 400, 404, 500, etc.
	public $StatusMode = null;
	//The accessibility mode: 1 for whitelist IPs, -1 for blacklisted IPs
	public $AccessMode = 0;
	public $AccessPatterns = array();
	public $RestrictionContent = "Unfortunately you have no access to the site now!<br>Please try a few minute later...";


	public $DataBaseEncoding = "utf-8";
	public $DataBaseType = 'mysqli';
	public $DataBaseHost = 'localhost';
	public $DataBaseUser = null;
	public $DataBasePassword = null;
	public $DataBaseName = 'localhost';
	public $DataBasePrefix = 'base_';
}
?>