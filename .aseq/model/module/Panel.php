<?php
namespace MiMFa\Module;
class Panel extends Module{
	public $ContentTag = null;
	public $Class = "panel";

	public function Get(){
		return $this->GetTitle().$this->GetDescription().$this->GetContent();
    }
}
?>
