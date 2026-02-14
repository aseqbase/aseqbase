<?php
namespace MiMFa\Module;
class Panel extends Module{
	public string|null $ContentTagName = null;
	public $Class = "panel";

	public function GetInner(){
		return $this->GetTitle().$this->GetDescription().$this->GetContent();
    }
}
?>
