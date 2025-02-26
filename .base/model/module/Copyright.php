<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
class Copyright extends Module{
	public $Title = "MiMFa";
	public $Description = "Powered By: ";
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
		return "<span>".__($this->Description,styling:false)."</span><a href=\"".$this->Source."\">".__($this->Title,styling:false)."</a>";
	}
}
?>
