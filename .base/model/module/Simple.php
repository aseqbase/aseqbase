<?php namespace MiMFa\Module;

use MiMFa\Library\Convert;

class Simple extends Module{
	public $DefaultGet = null;

	public function __construct($defaultGet){
		parent::__construct();
		$this->DefaultGet = $defaultGet;
	}
	
	public function Get(){
		return Convert::ToString($this->DefaultGet);
	}
}
?>
