<?php
class AseqBack extends BackBase
{
	public $EncryptNames = true;

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