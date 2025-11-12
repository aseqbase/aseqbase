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
		return parent::GetStyle() . Struct::Style("
			.{$this->Name} ul.contacts{
				margin:0px !important;
			}
			.{$this->Name} ul.contacts li{
				padding: 10px;
				margin:0px !important;
			}
			.{$this->Name} a.badge, a.badge:visited {
				background-color: var(--back-color-input);
				color: var(--fore-color-input);
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} a.badge:hover {
				background-color: var(--fore-color-input);
				color: var(--back-color-input);
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .map{
				border: 10px solid var(--back-color-input);
				box-shadow: var(--shadow-2);
				border-radius: 5px;
			}
			.{$this->Name} .map>iframe{
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
	public function Get()
	{
		return parent::Get() . join(PHP_EOL, iterator_to_array((function () {
			$count = count($this->Items);
			if ($count > 0) {
				yield '<div class="row">';
				yield '<ul class="contacts col-lg">';
				for ($i = 0; $i < $count; $i++) {
					$item = $this->Items[$i];
					yield '<li class="d-flex justify-content-between align-items-center">';
					yield Struct::Image(" " . getBetween($item, 'Name', 'Title'), getBetween($item, 'Icon', 'Image'));
					yield Struct::Link(
						getBetween($item, 'Value', 'Title', 'Path', 'Name'),
						get($item, 'Path'),
						["target"=>"_blank", "class"=>"badge badge-pill"]);
					yield '</li>';
				}
				yield '</ul>';
				if (isValid($this->Location)) {
					yield Struct::Division(
						Struct::Embed(null,$this->Location,[
							'data-aos'=>'filp-left',
							'data-src'=>'$this->Location',
							'frameborder'=>'0',
							'allowfullscreen'=>'true'
					]), ["class"=>"col-lg-8 map"]);
				}
				yield '</div>';
			}
		})()));
	}
}
?>