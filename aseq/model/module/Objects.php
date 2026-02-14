<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
class Objects extends Module
{
	public $Class = "container";
	public $Items = null;
	public $ColumnsCount = 2;

	public function GetStyle()
	{
		yield parent::GetStyle();
		yield Struct::Style("
		.{$this->MainClass}.col{
			padding: var(--size-0);
		}
		");
	}
	public function GetInner()
	{
		$count = count($this->Items);
		if ($count > 0) {
			for ($i = 0; $i < $count; $i++) {
				$item = $this->Items[$i];
				if ($i % $this->ColumnsCount == 0)
					yield "<div class='row'>";
				yield "<div class='col'>";
				if (isValid($item, 'Image'))
					yield Struct::Image($item['Image']);
				if (isValid($item, 'Name') || isValid($item, 'Icon'))
					yield Struct::Image(get($item, 'Name'), get($item, 'Icon'), ["aria-hidden" => 'true']);
				if (isValid($item, 'Title'))
					yield Struct::Span($item['Title'], null, ["class" => 'title']);
				if (isValid($item, 'Description'))
					yield Struct::Paragraph($item['Description'], ["class" => 'description']);
				if (isValid($item, 'Content'))
					yield $item['Content'];
				if ($v = get($item, 'Path'))
					yield Struct::Link(get($item, 'Value'), $v, ["target" => '_blank', "class" => 'btn block btn outline button']);
				yield "</div>";
				if ($i % $this->ColumnsCount == 0)
					yield "</div>";
			}
		}
	}
}
