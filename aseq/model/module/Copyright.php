<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
class Copyright extends Module{
	public $Title = "Developed By:";
	public string|null $TitleTagName = "span";
	public $Description = " MiMFa ";
	public string|null $DescriptionTagName = "span";
	public string|null $ContentTagName = "div";
	public $Source = "http://mimfa.net";

	public function GetStyle(){
        yield parent::GetStyle();
        yield Struct::Style("
			.{$this->MainClass}, .{$this->MainClass} :is(a, a:visited){
				text-align: center;
				font-size: var(--size-0);
				text-decoration: none;
			}
			");
	}

	public function GetInner(){
		return $this->GetContent().Struct::Link($this->GetTitle()." ".$this->GetDescription(), $this->Source);
	}
}
?>
