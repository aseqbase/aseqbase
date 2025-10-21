<?php
class AseqBack extends BackBase
{
	public $DataBaseError = null;
	
	public $AllowTranslate = false;
	public $AutoUpdateLanguage = false;
	public $CacheLanguage = true;
	public $DefaultLanguage = null;
	public $DefaultDirection = null;

	public $SecretKey = '~a!s@e#q$b%a^s&e*';
	public $SoftKey = null;
	public $AccessibleData = true;
	public $EncryptKey = false;
	public $EncryptValue = true;
	public $EncryptSampleChars = "4wCpq01Ikl2NVmSDKFPJ7fXYijTzAUbE5WxgRuvGQZ3yBo6ncdeLMrst_HhO89a";
	public $EncryptSampler = 3;
	public $EncryptIndexer = 7;
}
?>