<?php
namespace MiMFa\Module;
use \MiMFa\Library\HTML;
use \MiMFa\Library\Convert;
class Shortcuts extends Module{
	public $Capturable = true;
	public $AllowTitle = false;
	public $AllowIcon = true;
	public $AllowImage = false;
	public $Items = null;

	public function GetStyle(){
		return parent::GetStyle().HTML::Style("
			.{$this->Name}{
				text-align: center;
			}

			.{$this->Name} .item{
				font-size:  var(--Size-2);
			}
			.{$this->Name} .item.active{
				border: var(--Border-1) var(--ForeColor-1);
				font-size:  var(--Size-2);
			}
		");
	}

	public function Get(){
		return parent::Get().Convert::ToString(function(){
			$count = count($this->Items);
			if($count > 0){
				COMPONENT("Icons");
				$comp = new \MiMFa\Component\Icons();
				$comp->EchoStyle(".".$this->Name);
				$comp->EchoTechnologyStyle(".".$this->Name);
				for($i = 0; $i < $count; $i++){
					$item = $this->Items[$i];
					yield HTML::Link(
						$this->AllowTitle?(getValid($item,'Title')??getValid($item,'Name')??""):"",
						$link = getValid($item,'Path')??getValid($item,'Link'),
						["class"=>"item".(endsWith(\_::$URL,$link)?' active':'').(($this->AllowIcon && isValid($item,'Icon'))?' '.$item['Icon']:'')],
						getValid($item,"Attributes")
					);
				}
            }
        });
	}
}
?>