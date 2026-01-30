<?php
namespace MiMFa\Module;

use MiMFa\Library\Convert;
use MiMFa\Library\Struct;
class PrePage extends Module
{
	public $Tag = "section";
	public $Class = "prepage container";
	public $Image = null;
	public $Path = null;
	public $TitleTag = "h1";

	public function GetStyle()
	{
		return parent::GetStyle() . Struct::Style("
			.{$this->Name}{
				padding: var(--size-0) var(--size-3);
			}
			.{$this->Name}>.rack{
			    text-align: center;
				align-items: center;
				gap: var(--size-max);
			}
			.{$this->Name}>:is(.title,.rack>.title){
				padding: var(--size-1);
				margin: var(--size-0) var(--size-0) 0px;
			}
			.{$this->Name}>.rack>.description{
				font-size: var(--size-1);
				text-align: justify;
			}
			.{$this->Name}>:not(.content)>.image{
				background-size: cover;
				background-position: center;
				background-repeat: no-repeat;
    			text-align: center;
			}
			.{$this->Name}>:not(.content)>.icon{
				font-size: calc(2 * var(--size-max));
			}
		");
	}

	public function Get()
	{
		if (isValid($this->Description))
			return ($this->Path?Struct::Link($this->GetTitle(), $this->Path):$this->GetTitle()) . Struct::Rack(
				$this->GetDescription(["class" => 'description col-md']) .
				($this->Image ? Struct::Image(preg_replace("/\<([\w:]+)[^<>]*\>[\w\W]*\<\/\1\>/i"," ", $this->Title??""), $this->Image, ["class" => "col-md-4"]) : "")
			) . $this->GetContent(["class"=>"content"]);
		else
			return Struct::Rack(
				($this->Image ? Struct::Image($this->Title, $this->Image, ["class" => "col-md"]) : "")
			) . ($this->Path?Struct::Link($this->GetTitle(), $this->Path):$this->GetTitle()) . $this->GetContent(["class"=>"content"]);
	}
}