<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
class PrePage extends Module
{
	public $Class = "container";
	public $Image = null;
	public $TitleTag = "h1";

	public function GetStyle()
	{
		return parent::GetStyle() . Html::Style("
			.{$this->Name}{
				padding: 3vmax;
			}
			.{$this->Name} .title{
				margin-top: var(--size-max);
			}
			.{$this->Name} .description{
				font-size: var(--size-1);
				text-align: justify;
				padding: 3vmax 3vmax;
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
			return $this->GetTitle() . Html::Rack(
				$this->GetDescription(["class" => 'description col-md']) .
				($this->Image ? Html::Image($this->Title, $this->Image, ["class" => "col-md-4"]) : "")
			) . $this->GetContent("class='content'");
		else
			return Html::Rack(
				($this->Image ? Html::Image($this->Title, $this->Image, ["class" => "col-md"]) : "")
			) . $this->GetTitle() . $this->GetContent("class='content'");
	}
}
?>