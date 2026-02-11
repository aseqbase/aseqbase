<?php
namespace MiMFa\Module;
use \MiMFa\Library\Struct;
use \MiMFa\Library\Convert;
class Shortcuts extends Module{
	public $AllowTitle = false;
	public $AllowIcon = true;
	public $AllowImage = false;
	public $Items = null;
    public $Printable = false;

	public function GetStyle(){
		return parent::GetStyle().Struct::Style("
			.{$this->Name}{
				text-align: center;
			}

			.{$this->Name} .item{
				font-size:  var(--size-2);
			}
			.{$this->Name} .item.active{
				border: var(--border-1) var(--fore-color-input);
				font-size:  var(--size-2);
			}
		");
	}

	public function Get(){
		return parent::Get().Convert::ToString(function(){
			if(!isEmpty($this->Items)){
				$count = count($this->Items);
				yield \MiMFa\Component\Icons::Render(".".$this->Name);
				for($i = 0; $i < $count; $i++){
					$item = $this->Items[$i];
					yield Struct::Link(
						($this->AllowIcon && ($v = get($item,'Icon'))?Struct::Icon($v):'').
						($this->AllowTitle?(getBetween($item,'Title','Name' )??""):""),
						$link = getBetween($item,'Path'),
						["class"=>"item".(endsWith(\_::$Address->Url,$link)?' active':'')],
						get($item,"Attributes")
					);
				}
            }
        });
	}
}