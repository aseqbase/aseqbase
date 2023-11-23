<?php
class AseqConfiguration extends ConfigurationBase {
	public $Encoding = "utf-8";
	public $SecretKey = '~a!s@e#q$b%a^s&e*';
	public $DisplayError = 0;
	public $DisplayStartupError = 0;
	public $ReportError = null;
	public $DataBaseError = 0;

	public $AllowCache = true;
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
	public $AccessMode = 0;
	public $AccessPatterns = array();
	public $RestrictionContent = "Unfortunately you have no access to the site now!<br>Please try a few minute later...";


	public $DataBaseEncoding = "utf8";
	public $DataBaseType = 'mysql';
	public $DataBaseHost = 'localhost';
	public $DataBaseUser = 'root';
	public $DataBasePassword = null;
	public $DataBaseName = 'localhost';
	public $DataBasePrefix = 'aseq_';
}
?>