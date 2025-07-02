<?php namespace MiMFa\Module;
use MiMFa\Library\Html;
class Objects extends Module{
	public $Class = "container";
	public $Items = null;
	public $ColumnsCount = 2;

	public function GetStyle(){
		return parent::GetStyle().Html::Style("
		.{$this->Name}.col{
			padding: var(--size-0);
		}
		");
	}
	public function Get(){
		return parent::Get().join(PHP_EOL, iterator_to_array((function(){
			$count = count($this->Items);
			if($count > 0){
				for($i = 0; $i < $count; $i++){
					$item = $this->Items[$i];
					if($i%$this->ColumnsCount == 0) yield "<div class='row'>";
                    yield "<div class='col'>";
                    if(isValid($item,'Image' )) yield Html::Image($item['Image' ]);
                    if(isValid($item,'Name' ) || isValid($item,'Icon'))
                        yield Html::Image(get($item,'Name' ), get($item,'Icon'), ["aria-hidden"=>'true']);
                    if(isValid($item,'Title' ))
                        yield Html::Span($item['Title' ],null,["class"=>'title' ]);
                    if(isValid($item,'Description' ))
                        yield Html::Paragraph($item['Description' ],null,["class"=>'description' ]);
                    if(isValid($item,'Content' )) yield $item['Content' ];
                    if(isValid($item,'Link')) yield Html::Link(get($item,'Value' ), $item['Link'], ["target"=>'_blank', "class"=>'btn block btn outline button']);
                    yield "</div>";
					if($i%$this->ColumnsCount == 0) yield "</div>";
                }
            }
        })()));
	}
}
?>
