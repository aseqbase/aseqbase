<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
class TemplateButton extends Module{
	public $LightIcon = "sun";
	public $DarkIcon = "moon";
	public $LightLabel = "";
	public $DarkLabel = "";
	public $LightRequest = "LightMode";
	public $DarkRequest = "DarkMode";
    public $Printable = false;

	public function GetStyle(){
		return parent::GetStyle().Html::Style("
            .{$this->Name} i {
				cursor: pointer;
				padding: 8px;
            }
		");
	}

	public function Get(){
		return $this->GetTitle().$this->GetDescription().
		(\_::$Front->DarkMode?
			Html::Media($this->LightLabel, $this->LightIcon,["onclick"=>"setMemo(`{$this->LightRequest}`,true);setMemo(`{$this->DarkRequest}`,false);reload();"])
			:Html::Media($this->DarkLabel, $this->DarkIcon,["onclick"=>"setMemo(`{$this->DarkRequest}`,true);setMemo(`{$this->LightRequest}`,false);reload();"])
	    ).
		$this->GetContent();
    }
}
?>