<?php
namespace MiMFa\Module;

use MiMFa\Library\Struct;
class PrePage extends Module
{
	public $Tag = "section";
	public $Class = "prepage container";
	public $Image = null;
	public $TitleTag = "h1";

	public function GetStyle()
	{
		return parent::GetStyle() . Struct::Style("
			.{$this->Name}{
				padding: var(--size-max) var(--size-3) var(--size-1);
			}
			.{$this->Name}>.rack{
				align-items: center;
				gap: var(--size-max);
			}
			.{$this->Name}>:is(.title,.rack>.title){
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
			return $this->GetTitle() . Struct::Rack(
				$this->GetDescription(["class" => 'description col-md']) .
				($this->Image ? Struct::Image(preg_replace("/\<([\w:]+)[^<>]*\>[\w\W]*\<\/\1\>/i"," ", $this->Title??""), $this->Image, ["class" => "col-md-4"]) : "")
			) . $this->GetContent(["class"=>"content"]);
		else
			return Struct::Rack(
				($this->Image ? Struct::Image($this->Title, $this->Image, ["class" => "col-md"]) : "")
			) . $this->GetTitle() . $this->GetContent(["class"=>"content"]);
	}
}