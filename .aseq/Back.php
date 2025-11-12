<?php
run("global/AseqBack");
class Back extends AseqBack {
	/**
	 * DataBase exception handling
	 * @default 2
	 */
	public $DataBaseError = 3;

	public $AllowTranslate = false;
}