<?php
namespace MiMFa\Module;

use MiMFa\Component\GlobalStyle;
use MiMFa\Library\Html;
use MiMFa\Library\Script;
class TemplateButton extends Module{
	public $Tag = "button";
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
		switchStyleId = '{$this->Name}-switch-styles';
		switchStyle = document.getElementById(switchStyleId);
		switchContent = ".Script::Convert($isDark?
			Html::Media($this->DarkLabel, $this->DarkIcon):
			Html::Media($this->LightLabel, $this->LightIcon)
		).";
		content = ".Script::Convert($isDark?
			Html::Media($this->LightLabel, $this->LightIcon):
			Html::Media($this->DarkLabel, $this->DarkIcon)
		).";
		if(switchStyle) switchStyle.remove();
		else {
			switchStyle = document.createElement('style');
			switchStyle.id = switchStyleId;
			switchStyle.innerHTML = `".GlobalStyle::SwitchVariables()."`;
			document.head.append(switchStyle);
		}
		if({$this->Name}_SwitchMode) {
			document.querySelector('.{$this->Name} .media').outerHTML = content;
			setMemo('".\_::$Front->SwitchRequest."', {$this->Name}_SwitchMode = false);
		} else {
			document.querySelector('.{$this->Name} .media').outerHTML = switchContent;
			setMemo('".\_::$Front->SwitchRequest."', {$this->Name}_SwitchMode = true);
		}";
	}
	public function Get(){
		return $this->GetTitle().$this->GetDescription().
		(\_::$Front->GetMode() < 0?
			Html::Media($this->LightLabel, $this->LightIcon):
			Html::Media($this->DarkLabel, $this->DarkIcon)
	    ).
		$this->GetContent();
    }
	public function GetScript(){
		return parent::GetScript().Html::Script("var {$this->Name}_SwitchMode = ".(\_::$Front->SwitchMode?"true":"false").";");
    }
}
?>