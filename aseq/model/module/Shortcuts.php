<?php
namespace MiMFa\Module;
use \MiMFa\Library\Struct;
use \MiMFa\Library\Convert;
class Shortcuts extends Module
{
	public $AllowTitle = false;
	public $AllowIcon = true;
	public $AllowImage = false;
	public $Items = null;
	public $Printable = false;

	public function GetStyle()
	{
        yield parent::GetStyle();
        yield Struct::Style("
			.{$this->MainClass}{
				text-align: center;
			}

			.{$this->MainClass} .item{
				font-size:  var(--size-2);
			}
			.{$this->MainClass} .item.active{
				border: var(--border-1) var(--fore-color-input);
				font-size:  var(--size-2);
			}
		");
	}

	public function GetInner()
	{
		yield parent::GetInner();
		if (!isEmpty($this->Items)) {
			$count = count($this->Items);
			yield \MiMFa\Component\Icons::Render("." . $this->MainClass);
			for ($i = 0; $i < $count; $i++) {
				$item = $this->Items[$i];
				yield Struct::Link(
					($this->AllowIcon && ($v = get($item, 'Icon')) ? Struct::Icon($v) : '') .
					($this->AllowTitle ? (getBetween($item, 'Title', 'Name') ?? "") : ""),
					$link = getBetween($item, 'Path'),
					["class" => "item" . (endsWith(\_::$Address->Url, $link) ? ' active' : '')],
					get($item, "Attributes")
				);
			}
		}
	}
}