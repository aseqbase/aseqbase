<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
class Copyright extends Module{
	public $Title = " MiMFa ";
	public $Description = "Designed By:";
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
		return Html::Span($this->Description,styling:false).Html::Link($this->Title, $this->Source);
	}
}
?>
