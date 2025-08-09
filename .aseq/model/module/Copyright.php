<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
class Copyright extends Module{
	public $Title = "Developed By:";
	public $TitleTag = "span";
	public $Description = " MiMFa ";
	public $DescriptionTag = "span";
	public $ContentTag = "div";
	public $Source = "http://mimfa.net";

	public function GetStyle(){
		return parent::GetStyle().Html::Style("
			.{$this->Name}, .{$this->Name} :is(a, a:visited){
				text-align: center;
				font-size: var(--size-0);
				text-decoration: none;
			}
			");
	}

	public function Get(){
		return $this->GetContent().Html::Link($this->GetTitle()." ".$this->GetDescription(), $this->Source);
	}
}
?>
