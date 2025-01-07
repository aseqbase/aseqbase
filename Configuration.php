<?php
class Configuration extends ConfigurationBase {
	// public $Encoding = "utf-8";
	// public $SecretKey = null;

	public $DisplayError = 1;
	public $DisplayStartupError = 1;
	public $ReportError = E_ALL;
	public $DataBaseError = 1;

	public $AllowCache = false;
	public $AllowSigning = true;
	public $AllowTranslate = false;

    ///**
    // * The status of all server response: 400, 404, 500, etc.
    // * @var mixed
    // */
    //public $StatusMode = null;
    ///**
    // * The accessibility mode: 1 for whitelisted IPs, -1 for blacklisted IPs
    // * @var mixed
    // */
    //public $AccessMode = 0;
    //public $AccessPatterns = array();
    //public $RestrictionContent = "Unfortunately you have no access to the site now!<br>Please try a few minute later...";

    public $DataBaseEncoding = "utf8";
    public $DataBaseType = 'mysql';
    public $DataBaseHost = 'localhost';
    public $DataBaseUser = 'root';
    public $DataBasePassword = null;
    public $DataBaseName = 'localhost';
    public $DataBasePrefix = 'aseq_';
}
?>