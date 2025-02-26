<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
class PrePage extends Module{
	public $Class = "container";
	public $Image = null;
	public $TitleTag = "h1";

	public function GetStyle(){
		return parent::GetStyle().Html::Style("
			.{$this->Name} .description{
				font-size: var(--size-1);
				text-align: justify;
				padding: 3vmax 3vmax;
			}
			.{$this->Name} .media{
				background-size: cover;
				background-position: center;
				background-repeat: no-repeat;
				font-size: var(--size-1);
			}
		");
	}

	public function Get(){
		return $this->GetTitle().Html::Rack(
			$this->GetDescription("class='col-md description'").
			Html::Media("",$this->Image,["class"=>"blackwhite col-md-4"])
		).$this->GetContent("class='content'");
	}
}
?>