<?php
class Configuration extends ConfigurationBase {
	public $Encoding = "utf-8";
	
	public $DisplayError = 1;
	public $DisplayStartupError = 1;
	public $ReportError = E_ALL;

	public $AllowCache = false;

	public $SecretKey = 'vYTAge81WhSLzmZP';

	public $DataBaseType = 'mysqli';
	public $DataBaseHost = 'localhost';
	public $DataBaseUser = 'parsgoco_mimfa';
	public $DataBasePassword = 'Cf5@4!s59L467';
	public $DataBaseName = 'parsgoco_mimfa';
	public $DataBasePrefix = 'oo97c_';
}
?>