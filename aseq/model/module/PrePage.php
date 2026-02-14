<?php
namespace MiMFa\Module;

use MiMFa\Library\Convert;
use MiMFa\Library\Struct;
class PrePage extends Module
{
	public string|null $TagName = "section";
	public $Class = "prepage container";
	public $Image = null;
	public $Path = null;
	public string|null $TitleTagName = "h1";
	public $AllowCover = false;

	public function GetStyle()
	{
        yield parent::GetStyle();
        yield Struct::Style("
			.{$this->MainClass}{
				padding: var(--size-0) var(--size-3);
			}".($this->AllowCover?"
			.{$this->MainClass}>.cover{
				padding: var(--size-max);
				color: var(--color-white);
				text-align: center;
			}
			.{$this->MainClass}>.cover>.media::after{
               content: '';
               display: block;
               width: 100%;
               height: 100%;
               background-blend-mode: multiply;
               background: linear-gradient(to top, rgba(24, 24, 24, .9) 0%, rgba(43, 43, 43, 0) 100%);
               position: absolute;
               top: 0;
               left: 0;
               z-index: 0;
          	}
			":"
			.{$this->MainClass}>.rack{
			    text-align: center;
				align-items: center;
				gap: var(--size-max);
			}
			.{$this->MainClass}>:is(.title,.rack>.title){
				padding: var(--size-1);
				margin: var(--size-0) var(--size-0) 0px;
			}
			.{$this->MainClass}>.rack>.description{
				font-size: var(--size-1);
				text-align: justify;
			}
			.{$this->MainClass}>:not(.content)>.image{
				background-size: cover;
				background-position: center;
				background-repeat: no-repeat;
    			text-align: center;
			}
			.{$this->MainClass}>:not(.content)>.icon{
				font-size: calc(2 * var(--size-max));
			}
		"));
	}

	public function GetInner()
	{
		if($this->AllowCover && $this->Image)
			return Struct::Cover(
				($this->Path?Struct::Link($this->GetTitle(), $this->Path):$this->GetTitle()) .
				$this->GetDescription(),
				$this->Image
			). $this->GetContent(["class"=>"content"]);
		elseif (isValid($this->Description))
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