<?php
run("global/AseqBack");
class Back extends AseqBack {
	/**
	 * 0: Not show Errors; 1: To show Errors
	 * @field int
	 * @var int|null
	 * @category Debug
	 */
	public $DisplayError = 1;
	/**
	 * 0: Not show startup Errors; 1: To show startup Errors
	 * @field int
	 * @var int|null
	 * @category Debug
	 */
	public $DisplayStartupError = 1;
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
	public $ReportError = E_ALL;

	
	/**
	 * DataBase exception handling
	 * @default 2
	 */
	public $DataBaseError = 3;
}