<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
class Contacts extends Module
{
	public $Class = "container";
	public $Items = null;
	public $Location = null;

	public function GetStyle()
	{
		yield parent::GetStyle();
		yield Struct::Style("
			.{$this->MainClass} ul.contacts{
				margin:0px !important;
			}
			.{$this->MainClass} ul.contacts li{
				padding: 10px;
				margin:0px !important;
			}
			.{$this->MainClass} a.badge, a.badge:visited {
				padding: calc(var(--size-0) / 5) var(--size-0);
				border-radius: var(--radius-3);
				background-color: var(--back-color-input);
				color: var(--fore-color-input);
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass} a.badge:hover {
				background-color: var(--fore-color-input);
				color: var(--back-color-input);
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass} .map{
				border: 10px solid var(--back-color-input);
				box-shadow: var(--shadow-2);
				border-radius: var(--radius-2);
			}
			.{$this->MainClass} .map>iframe{
				display: inline-block;
				height: 100%;
				width:100%;
				min-height: 200px;
				padding:0px;
				margin:0px;
				" . (\_::$Front->GetMode() < 0 ? \MiMFa\Library\Style::UniversalProperty("filter", "invert(90%)") : "") . "
			}
		");
	}
	public function GetInner()
	{
		yield parent::GetInner();
		$count = count($this->Items);
		if ($count > 0) {
			yield '<div class="row">';
			yield '<ul class="contacts col-lg">';
			for ($i = 0; $i < $count; $i++) {
				$item = $this->Items[$i];
				yield '<li class="be flex justify align center middle">';
				yield Struct::Image(" " . getBetween($item, 'Name', 'Title'), getBetween($item, 'Icon', 'Image'));
				yield Struct::Link(
					getBetween($item, 'Value', 'Title', 'Path', 'Name'),
					get($item, 'Path'),
					["target" => "_blank", "class" => "badge"]
				);
				yield '</li>';
			}
			yield '</ul>';
			if (isValid($this->Location)) {
				yield Struct::Division(
					Struct::Embed(null, $this->Location, [
						'data-aos' => 'filp-left',
						'data-src' => '$this->Location',
						'frameborder' => '0',
						'allowfullscreen' => 'true'
					]),
					["class" => "col-lg-8 map"]
				);
			}
			yield '</div>';
		}
	}
}