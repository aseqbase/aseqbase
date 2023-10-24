<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
class TemplateButton extends Module{
	public $Capturable = true;
	public $LightIcon = "sun";
	public $DarkIcon = "moon";
	public $LightLabel = "";
	public $DarkLabel = "";
	public $LightRequest = "LightMode";
	public $DarkRequest = "DarkMode";

	public function GetStyle(){
		return parent::GetStyle().HTML::Style("
            .{$this->Name} i {
				cursor: pointer;
				padding: 8px;
            }
		");
	}

	public function Get(){
		return $this->GetTitle().$this->GetDescription().
		(\_::$TEMPLATE->DarkMode?
			HTML::Media($this->LightLabel, $this->LightIcon,["onclick"=>"load(`?{$this->LightRequest}=true&{$this->DarkRequest}=!`);"])
			:HTML::Media($this->DarkLabel, $this->DarkIcon,["onclick"=>"load(`?{$this->DarkRequest}=true&{$this->LightRequest}=!`);"])
	    ).
		$this->GetContent();
    }
}
?>