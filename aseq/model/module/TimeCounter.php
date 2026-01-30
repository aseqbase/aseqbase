<?php namespace MiMFa\Module;

use MiMFa\Library\Struct;
use MiMFa\Library\Script;

module("Counter");
class TimeCounter extends Counter{
	public $From = 10;
	public $To = 0;
	public $Step = 1;
	public $Period = 1000;
	public $Action = null;
	public $ShowFunctionName = "((sec)=>(new Date(sec * 1000)).toISOString().substring(11, 19))";

	public function __construct($from, $to = 0, $action = null){
		parent::__construct($from, $to, $action, 1);
	}

	public function GetContent($attrs = null){
		return Struct::Element(gmdate("H:i:s", $this->From), $this->ContentTag, ["class"=>"content"], $attrs);
	}
}
?>
