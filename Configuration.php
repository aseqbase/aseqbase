<?php
class Configuration extends ConfigurationBase {
	public $Encoding = "utf-8";
	public $SecretKey = null;
	public $DisplayError = 1;
	public $DisplayStartupError = 1;
	public $ReportError = E_ALL;

	public $AllowCache = false;

	public $DataBaseEncoding = "utf8";
	public $DataBaseType = 'mysql';
	public $DataBaseHost = 'localhost';
	public $DataBaseUser = null;
	public $DataBasePassword = null;
	public $DataBaseName = 'localhost';
	public $DataBasePrefix = 'qb_';
}
?>