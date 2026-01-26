<?php
class AseqBack extends BackBase
{
	/**
	 * The Date Time Format
	 * @var string
	 * @example: "Y-m-d H:i:s" To show like 2018-08-10 14:46:45
	 * @field value
	 * @category Time
	 */
	public $DateTimeFormat = 'H:i, d M y';

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
	 * @options
	 * 0:"No Report"
	 * 32767:"E_ALL"
	 * 8:"E_NOTICE"
	 * 1:"E_ERROR"
	 * @var int|null
	 * @category Debug
	 */
	public $ReportError = null;

	public $AllowReduceSize = true;
	public $AllowTextAnalyzing = true;
	public $AllowContentReferring = true;
	public $AllowCategoryReferring = false;
	public $AllowTagReferring = true;
	public $AllowUserReferring = false;

	public $EncryptNames = true;
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

	/**
	 * Database Errors
	 * @field int
	 * @var int|null
	 * @category Debug
	 */
	public $DataBaseError = null;
		/**
	 * A special key for the website, be sure to change this
	 * @field password
	 * @var string
	 * @category Security
	 */
	public $SecretKey = '~a!s@e#q$b%a^s&e*';
	/**
	 * A special soft key for the default cryption, be sure to change this
	 * A special key generator for the website, override this for more security
	 * @field password
	 * @var string
	 * @category Security
	 */
	public $SoftKey = null;
}