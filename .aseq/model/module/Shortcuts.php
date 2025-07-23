<?php
namespace MiMFa\Module;
use \MiMFa\Library\Html;
use \MiMFa\Library\Convert;
class Shortcuts extends Module{
	public $AllowTitle = false;
	public $AllowIcon = true;
	public $AllowImage = false;
	public $Items = null;
    public $Printable = false;

	public function GetStyle(){
		return parent::GetStyle().Html::Style("
			.{$this->Name}{
				text-align: center;
			}

			.{$this->Name} .item{
				font-size:  var(--size-2);
			}
			.{$this->Name} .item.active{
				border: var(--border-1) var(--fore-color-1);
				font-size:  var(--size-2);
			}
		");
	}

	public function Get(){
		return parent::Get().Convert::ToString(function(){
			if(!isEmpty($this->Items)){
				$count = count($this->Items);
				component("Icons");
				yield \MiMFa\Component\Icons::Render(".".$this->Name);
				for($i = 0; $i < $count; $i++){
					$item = $this->Items[$i];
					yield Html::Link(
						$this->AllowTitle?(getBetween($item,'Title','Name' )??""):"",
						$link = getBetween($item,'Path','Link'),
						["class"=>"item".(endsWith(\Req::$Url,$link)?' active':'').(($this->AllowIcon && isValid($item,'Icon'))?' '.$item['Icon']:'')],
						get($item,"Attributes")
					);
				}
            }
        });
	}
}
?>