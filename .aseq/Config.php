<?php
run("global/AseqConfig");
class Config extends AseqConfig {
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
}
?>