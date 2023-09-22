<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
class PrePage extends Module{
	public $Capturable = true;
	public $Class = "container";
	public $Image = null;
	public $TitleTag = "h1";

	public function GetStyle(){
		return parent::GetStyle().HTML::Style("
			.{$this->Name} .description{
				font-size: var(--Size-1);
				text-align: justify;
				padding: 3vmax 3vmax;
			}
			.{$this->Name} .media{
				background-size: cover;
				background-position: center;
				background-repeat: no-repeat;
				font-size: var(--Size-1);
			}
		");
	}

	public function Get(){
		return $this->GetTitle().HTML::Rack(
			$this->GetDescription("class='col-md description'").
			HTML::Media("",$this->Image,["class"=>"blackwhite col-md-4"])
		).$this->GetContent("class='content'");
	}

	public function Capture(){
        if(RECEIVE(\_::$CONFIG->ViewHandlerKey,"GET","page") !== "page") return null;
        return parent::Capture();
    }
}
?>