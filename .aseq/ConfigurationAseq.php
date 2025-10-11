<?php
class ConfigurationAseq extends ConfigurationBase
{
	public $DateTimeFormat = 'H:i, d M y';

	public $DisplayError = null;
	public $DisplayStartupError = null;
	public $ReportError = null;
	public $DataBaseError = null;

	public $AllowReduceSize = true;
	public $AllowTextAnalyzing = true;
	public $AllowContentReferring = true;
	public $AllowCategoryReferring = false;
	public $AllowTagReferring = true;
	public $AllowUserReferring = false;
	public $AllowTranslate = false;
	public $AutoUpdateLanguage = false;
	public $CacheLanguage = true;
	public $DefaultLanguage = null;
	public $DefaultDirection = null;

	public $SecretKey = '~a!s@e#q$b%a^s&e*';
	public $SoftKey = null;
	public $ClientSession = true;
	public $EncryptSessionKey = false;
	public $EncryptSessionValue = true;
	public $EncryptSampleChars = "4wCpq01Ikl2NVmSDKFPJ7fXYijTzAUbE5WxgRuvGQZ3yBo6ncdeLMrst_HhO89a";
	public $EncryptSampler = 3;
	public $EncryptIndexer = 7;
	public $EncryptNames = true;
	public $AllowSigning = true;
	public $AllowSelecting = true;
	public $AllowContextMenu = true;

	/**
	 * Allow to leave comments on posts
	 * @var bool
	 * @category Content
	 */
	public $AllowWriteComment = true;
	/**
	 * Access level to leave comments on posts
	 * @var int
	 * @category Content
	 */
	public $WriteCommentAccess = 1;
	/**
	 * Allow to read comments on posts
	 * @var bool
	 * @category Content
	 */
	public $AllowReadComment = true;
	/**
	 * Access level to read comments on posts
	 * @var int
	 * @category Content
	 */
	public $ReadCommentAccess = 0;
	/**
	 * Default status of new comments on posts
	 * @var int
	 * @category Content
	 */
	public $DefaultCommentStatus = 0;
}
?>