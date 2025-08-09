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
	public $LightRequest = "LightMode";
	public $DarkRequest = "DarkMode";
    public $Printable = false;

	public function __construct(){
		parent::__construct();
		if(!$this->Class) 
			if($this->LightLabel || $this->DarkLabel) $this->Class = "button";
			else $this->Class = "icon";
		$invertation = "// reload();
		invertStyleId = '{$this->Name}-invert-styles';
		invertStyle = document.getElementById(invertStyleId);
		invertContent = ".Script::Convert(\_::$Front->DarkMode?
			Html::Media($this->DarkLabel, $this->DarkIcon)
			:Html::Media($this->LightLabel, $this->LightIcon)
	    ).";
		content = ".Script::Convert(\_::$Front->DarkMode?
			Html::Media($this->LightLabel, $this->LightIcon)
			:Html::Media($this->DarkLabel, $this->DarkIcon)
	    ).";
		if(invertStyle) {
			document.querySelector('.{$this->Name} .media').outerHTML = content;
			invertStyle.remove();
		} else {
			document.querySelector('.{$this->Name} .media').outerHTML = invertContent;
			invertStyle = document.createElement('style');
			invertStyle.id = invertStyleId;
			invertStyle.innerHTML = `".GlobalStyle::InvertVariables()."`;
			document.head.append(invertStyle);
		}";
		if(\_::$Front->DarkMode) $this->Attributes["onclick"]="
			setMemo(`{$this->LightRequest}`,true);
			setMemo(`{$this->DarkRequest}`,false);
			$invertation
		";
		else $this->Attributes["onclick"]="
			setMemo(`{$this->DarkRequest}`,true);
			setMemo(`{$this->LightRequest}`,false);
			$invertation
		";
	}
	public function Get(){
		return $this->GetTitle().$this->GetDescription().
		(\_::$Front->DarkMode?
			Html::Media($this->LightLabel, $this->LightIcon)
			:Html::Media($this->DarkLabel, $this->DarkIcon)
	    ).
		$this->GetContent();
    }
}
?>