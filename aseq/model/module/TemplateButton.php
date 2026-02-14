<?php
namespace MiMFa\Module;

use MiMFa\Component\GeneralStyle;
use MiMFa\Library\Struct;
use MiMFa\Library\Script;
class TemplateButton extends Module{
	public string|null $TagName = "button";
	public $Class = null;
	public $LightIcon = "sun";
	public $DarkIcon = "moon";
	public $LightLabel = "";
	public $DarkLabel = "";
    public $Printable = false;

	public function __construct(){
		parent::__construct();
		if(!$this->Class) 
			if($this->LightLabel || $this->DarkLabel) $this->Class = "button";
			else $this->Class = "icon";
		$isDark = \_::$Front->GetMode() < 0 && !\_::$Front->SwitchMode;
		$this->Attributes["onclick"] = "
		switchStyleId = '{$this->MainClass}-switch-styles';
		switchStyle = document.getElementById(switchStyleId);
		switchContent = ".Script::Convert($isDark?
			Struct::Media($this->DarkLabel, $this->DarkIcon):
			Struct::Media($this->LightLabel, $this->LightIcon)
		).";
		content = ".Script::Convert($isDark?
			Struct::Media($this->LightLabel, $this->LightIcon):
			Struct::Media($this->DarkLabel, $this->DarkIcon)
		).";
		if(switchStyle) switchStyle.remove();
		else {
			switchStyle = document.createElement('style');
			switchStyle.id = switchStyleId;
			switchStyle.innerHTML = `".GeneralStyle::SwitchVariables()."`;
			document.head.append(switchStyle);
		}
		if({$this->MainClass}_SwitchMode) {
			document.querySelector('.{$this->MainClass} .media').outerHTML = content;
			setMemo('".\_::$Front->SwitchRequest."', {$this->MainClass}_SwitchMode = false);
		} else {
			document.querySelector('.{$this->MainClass} .media').outerHTML = switchContent;
			setMemo('".\_::$Front->SwitchRequest."', {$this->MainClass}_SwitchMode = true);
		}";
	}
	public function GetInner(){
		return $this->GetTitle().$this->GetDescription().
		(\_::$Front->GetMode() < 0?
			Struct::Media($this->LightLabel, $this->LightIcon):
			Struct::Media($this->DarkLabel, $this->DarkIcon)
	    ).
		$this->GetContent();
    }
	public function GetScript(){
		yield parent::GetScript();
		yield Struct::Script("var {$this->MainClass}_SwitchMode = ".(\_::$Front->SwitchMode?"true":"false").";");
    }
}
?>