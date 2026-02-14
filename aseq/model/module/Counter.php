<?php
namespace MiMFa\Module;

use MiMFa\Library\Struct;
use MiMFa\Library\Script;

class Counter extends Module
{
	public $From = 10;
	public $To = 0;
	public $Step = 1;
	/**
	 * A millisecond value for each interval
	 * @var int
	 */
	public $Period = 1000;
	public $Action = null;
	public $ShowFunctionName = null;
	public string|null $ContentTagName = "span";

	public function __construct($from, $to = 0, $action = null, $step = 1)
	{
		parent::__construct();
		$this->From = $from;
		$this->To = $to;
		$this->Step = $step;
		$this->Action = $action;
		$this->Id = $this->MainClass . "_" . getId();
	}

	public function GetContent($attrs = null)
	{
		return Struct::Element($this->From, $this->ContentTagName, ["class" => "content"], $attrs);
	}

	public function GetScript()
	{
		$countDown = $this->From >= $this->To;
		$counter = $this->Id;
		$interval = $this->Id . "_i";
		return Struct::Script(
			"$counter = " . ($countDown ? $this->From : $this->To) . ";" .
			"$interval = setInterval(() => {
			    if($counter " . ($countDown ? "<" : ">") . "= {$this->To}) {" . (
					$this->Action ? (
					(isScript($this->Action) || !isUrl($this->Action)) ?
						$this->Action :
						("load(" . Script::Convert($this->Action) . ");")
					) : ""
					) . "
					clearInterval($interval);
					return;
		        }
				$counter += " . ($countDown ? -$this->Step : $this->Step) . ";
				elem = document.querySelector('#$this->Id>.content');
			    if(elem) elem.innerHTML = {$this->ShowFunctionName}($counter);
			}, {$this->Period});"
		);
	}
}
?>