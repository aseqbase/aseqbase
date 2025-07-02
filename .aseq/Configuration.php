<?php
run("ConfigurationAseq");
class Configuration extends ConfigurationAseq {
	public $SecretKey = '~a!s@e#q$b%a^s&e*';
	
	/**
	 * Display exception handling
	 * @default 1
	 */
	public $DisplayError = 1;
	/**
	 * Display Startup exception handling
	 * @default 1
	 */
	public $DisplayStartupError = 1;
	/**
	 * Report exception handling
	 * @default E_ALL
	 */
	public $ReportError = E_ALL;
	/**
	 * DataBase exception handling
	 * @default 2
	 */
	public $DataBaseError = 3;

	public $AllowSigning = true;
	public $AllowTranslate = false;
}
?>