<?php namespace MiMFa\Module;

use MiMFa\Library\Convert;
use MiMFa\Library\Html;
use MiMFa\Library\Script;

class Counter extends Module{
	public $From = 10;
	public $To = 0;
	public $Step = 1;
	public $Period = 1000;
	public $Action = null;
	public $ShowFunctionName = null;
	public $ContentTag = "span";
	
	public function __construct($from, $to = 0, $action = null, $step = 1){
		parent::__construct();
		$this->From = $from;
		$this->To = $to;
		$this->Step = $step;
		$this->Action = $action;
		$this->Id = $this->Name."_".getId();
	}
	
	public function GetContent($attrs = null){
		return Html::Element($this->From, $this->ContentTag, ["class"=>"content"], $attrs);
	}

	public function GetScript(){
		$countDown = $this->From>=$this->To;
		$counter = $this->Id;
		$interval = $this->Id."_i";
		return Html::Script(
			"let $counter = ".($countDown?$this->From:$this->To).";".
			"$interval = setInterval(() => {
			    if($counter ".($countDown?"<":">")."= {$this->To}) {".(
					$this->Action?(
						(isScript($this->Action) || !isUrl($this->Action))?
							$this->Action:
							("load(".Script::Convert($this->Action).");")
					):""
				)."
					clearInterval($interval);
					return;
		        }
				$counter += ".($countDown?-$this->Step:$this->Step).";
			    document.querySelector('#$this->Id .content').innerHTML = {$this->ShowFunctionName}($counter);
			}, {$this->Period});"
		);
	}
}
?>
